import api from '@/config/api.js';

/**
 * tabBarUrl
 */
const tabBarUrl = [
	'/pages/article/index',
	'/pages/user/favorites',
	'/pages/user/index'
];

const objectToUrlParams = function(obj) {
	var str = "";
	for (var key in obj) {
		str += "&" + key + "=" + obj[key];
	}
	return str.substr(1);
}

/**
 * 是否登录
 */
const isLogin = function() {
	if (uni.getStorageSync("isLogin") == "1") {
		return true;
	}
	return false;
}

/**
 * 初始化登录
 */
const initLogin = function() {
	if (!isLogin()) {
		login();
	}
}

/**
 * 登录
 */
const login = function() {
	/*清除登录缓存*/
	uni.removeStorageSync('isLogin');
	uni.removeStorageSync('accessToken');
	uni.removeStorageSync('currentUser');
	uni.removeStorageSync('platform');

	/*储存当前页*/
	let pages = getCurrentPages();
	let currentPage = pages[pages.length - 1];
	let originUrl = '/' + currentPage.route;

	// #ifdef H5
	let urlParam = objectToUrlParams(currentPage.$route.query)
	if (urlParam) {
		originUrl = originUrl + '?' + urlParam;
	}
	// #endif

	uni.setStorageSync('loginOriginUrl', originUrl); //存储跳转前URL

	// #ifdef MP-WEIXIN
	uni.navigateTo({
		url: '/pages/wechat/miniAppLogin'
	})
	// #endif

	// #ifndef MP-WEIXIN
	if (getPlatform() == 'wechatMP') {
		initMPLogin(); //公众号登录
	} else {
		uni.navigateTo({
			url: '/pages/common/login'
		})
	}
	// #endif
}

/*微信小程序登录初始化*/
const wechatAppLoginInit = function() {
	/*检测是否授权*/
	uni.getSetting({
		success: function(res) {
			/* 已经授权直接登录*/
			if (res.authSetting['scope.userInfo']) {
				wechatAppLogin(false); //登录
			} else {
				uni.navigateTo({
					url: '/pages/wechat/miniAppLogin'
				})
			}
		}
	});
}

/*微信小程序登录*/
const wechatAppLogin = function(isBack = false) {
	/*登录提示*/
	uni.showLoading({
		title: "正在登录",
		mask: true
	});

	/*微信登录*/
	uni.login({
		provider: 'weixin',
		success: loginResult => {
			let code = loginResult.code;
			/*获取用户信息*/
			uni.getUserInfo({
				success: result => {
					/*获取分享id*/
					let share_user_id = uni.getStorageSync('share_user_id');
					share_user_id = share_user_id > 0 ? share_user_id : 0;

					/*登录验证*/
					request({
						url: api.wechat.miniAppLogin,
						data: {
							share_user_id: share_user_id,
							code: code,
							user_info: result.rawData,
							encrypted_data: result.encryptedData,
							iv: result.iv,
							signature: result.signature
						},
						method: 'POST',
						dataType: 'json',
						success: res => {
							if (res.code == 0) {

								/*更新登录状态,保存用户数据*/
								let userInfo = res.data;
								uni.setStorageSync("isLogin", '1');
								uni.setStorageSync("accessToken", userInfo.token);
								uni.setStorageSync('currentUser', userInfo);
								uni.setStorageSync('platform', 'wechatMiniApp');
								uni.setStorageSync('source', 'login');
								if (userInfo.is_exist_user == 0) {
									uni.setStorageSync('register', 1);
								}

								/*switchTab刷新*/
								let originUrl = uni.getStorageSync('loginOriginUrl');
								if (originUrl) {
									let originUrlRoute = originUrl.split('?');
									if (tabBarUrl.includes(originUrlRoute[0])) {
										uni.switchTab({
											url: originUrlRoute[0]
										})
									} else {
										uni.navigateBack();
									}
								} else {
									/*登录后跳转*/
									if (isBack) {
										uni.navigateBack();
									}
								}
							} else {
								alert(res.msg, 'warning');
							}
						}
					});
				},
				fail: result => {
					uni.hideLoading();
				}
			});
		}
	});
}

/*微信公众号登录*/
const initMPLogin = function() {
	/*获取登录验证url*/
	let url = location.href.split('/pages/');
	let loginUrl = '';
	if (url.length > 1) {
		loginUrl = url[0] + '/pages/wechat/mpLogin';
	} else {
		loginUrl = url[0] + 'pages/wechat/mpLogin';
	}

	/*获取分享id*/
	let share_user_id = uni.getStorageSync('share_user_id');
	share_user_id = share_user_id > 0 ? share_user_id : 0;

	/*拼装url*/
	location.href = api.wechat.mpLogin + '?url=' + encodeURIComponent(loginUrl) + '&share_user_id=' + share_user_id;
}

/*检查是否有操作权限*/
const checkAuth = function() {
	request({
		url: api.user.checkAuth,
		data: {},
		method: 'POST',
		dataType: 'json',
		success: res => {
		}
	});
}

/*绑定手机号码*/
const bindMobile = function() {
	uni.navigateTo({
		url: '/pages/user/bindMobile'
	})
}

/*获取来源url*/
const getSourcePage = function() {
	let pages = getCurrentPages();
	if (pages.length >= 2) {
		let currentPage = pages[pages.length - 2];
		let originUrl = '/' + currentPage.route;
		return originUrl;
	} else {
		return '';
	}

}


/**
 * 网络请求
 * @param {Object} req
 */
const request = function(req) {
	let accessToken = uni.getStorageSync("accessToken");
	let platform = getPlatform();
	let header = {
		'platform': platform,
		'token': accessToken,
		'Content-type': 'application/x-www-form-urlencoded'
	};
	if (req.header) {
		header = Object.assign(header, req.header);
	}
	uni.request({
		url: req.url,
		data: req.data || {},
		header: header,
		method: req.method || "GET",
		dataType: req.dataType || "json",
		success: function(res) {
			if (res.data.code == '1000') {
				login(); //登录
			} else if (res.data.code == '1003') {
				bindMobile(); //绑定手机号码
			} else {
				if (req.success) {
					req.success(res.data);
				}
			}
		},
		fail: function(res) {
			uni.showToast({
				title: '网络异常~',
				icon: 'none'
			});
			if (req.fail) {
				req.fail(res);
			}
		},
		complete: function(res) {
			if (res.statusCode != 200) {
				if (res.code == '1000') {
					login();
				}
			}
			if (req.complete) {
				req.complete(res);
			}
		}
	});
}

/*上传文件*/
const uploadFile = function(req) {
	let accessToken = uni.getStorageSync("accessToken");
	let platform = getPlatform();
	let header = {
		'platform': platform,
		'token': accessToken
	};
	if (req.header) {
		header = Object.assign(header, req.header);
	}
	uni.uploadFile({
		url: req.url,
		filePath: req.filePath,
		header: header,
		name: req.name || 'file',
		formData: req.formData || {},
		success: (res) => {
			if (res.data.code == '1000') {
				login(); //登录
			} else if (res.data.code == '1003') {
				bindMobile(); //绑定手机号码
			} else {
				if (req.success) {
					req.success(JSON.parse(res.data));
				}
			}
		},
		fail: (res) => {
			console.warn('--- request fail >>>');
			console.warn(res);
			console.warn('<<< request fail ---');
			uni.showToast({
				title: '网络异常~',
				icon: 'none'
			});
			if (req.fail) {
				req.fail(res);
			}
		},
		complete: (res) => {
			if (res.statusCode != 200) {
				if (res.code == '1000') {
					login();
				}
			}
			if (req.complete) {
				req.complete(res);
			}
		}
	});
}

/*获取平台类型 */
const getPlatform = function() {
	let platform = uni.getStorageSync('platform');

	// #ifdef MP-WEIXIN
	platform = 'wechatMiniApp'; //微信小程序
	// #endif

	// #ifdef H5
	if (!platform) {
		platform = 'h5'; //H5
	}
	// #endif

	// #ifdef APP-PLUS
	if (uni.getSystemInfoSync().platform == 'ios') {
		platform = 'ios';
	} else {
		platform = 'android';
	}
	// #endif

	return platform;
}

/*无状态提示信息*/
const alert = function(msg = '', icon = 'none', url = '', openType = 'navigate') {
	/*消息强制转字符串*/
	if (typeof(msg) != 'string') {
		msg = msg.toString();
	}

	if (msg.length > 7) {
		//长度超过7个字符，用示模态弹窗展示
		uni.showModal({
			title: '提示',
			content: msg,
			showCancel: false
		});
	} else {
		if (icon == 'warning') {
			uni.showToast({
				title: msg,
				image: "/static/images/icon-warning.png"
			});
		} else {
			uni.showToast({
				title: msg,
				icon: icon
			})
		}
	}
	if (url || openType == 'back') {
		setTimeout(() => {
			if (openType == 'redirect') {
				uni.redirectTo({
					url: url
				});
			} else if (openType == 'switchTab') {
				uni.switchTab({
					url: url
				});
			} else if (openType == 'reLaunch') {
				uni.reLaunch({
					url: url
				});
			} else if (openType == 'back') {
				uni.navigateBack();
			} else {
				uni.navigateTo({
					url: url
				});
			}
		}, 1500)
	}
};

/*弹出加载框*/
const loading = function(msg = '', mask = true) {
	/*消息强制转字符串*/
	if (typeof(msg) != 'string') {
		msg = msg.toString();
	}
	uni.showLoading({
		title: msg,
		mask: mask
	})
};

/*是否微信浏览器*/
const isWechat = function() {
	// #ifndef H5
	return false;
	// #endif

	// #ifdef H5
	let ua = window.navigator.userAgent.toLowerCase();
	if (ua.match(/micromessenger/i) == 'micromessenger') {
		return true;
	} else {
		return false;
	}
	// #endif
};

/*获取平台类型 */
const getNaviBarHeight = function() {
	let height = '90rpx';
	// #ifdef MP
	let device = uni.getSystemInfoSync();
	if (device.system.indexOf('iOS') > -1) {
		if (device.model.indexOf('iPhone X') > -1) {
			height = '44px;margin-top: 25px;padding-bottom: 5px;';
		} else {
			height = '44px';
		}
	} else {
		height = '48px';
	}
	// #endif
	return height;
}

export default {
	tabBarUrl,
	isLogin,
	initLogin,
	login,
	wechatAppLogin,
	initMPLogin,
	request,
	uploadFile,
	alert,
	loading,
	isWechat,
	getNaviBarHeight,
	getPlatform,
	getSourcePage,
	checkAuth,
	bindMobile
};
