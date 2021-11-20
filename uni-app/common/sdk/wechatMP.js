// #ifdef H5

let jweixin = require('jweixin-module')

/*初始化分享*/
function initShare(page, share) {
	page.$app.request({
		url: page.$api.wechat.mpConfig,
		data: {
			url: window.location.href
		},
		method: 'POST',
		dataType: 'json',
		success: res => {
			if (res.code == 0) {
				let config = res.data;
				/*授权api*/
				let jsApiList = [
					'updateAppMessageShareData',
					'updateTimelineShareData',
					'hideOptionMenu',
					'showOptionMenu',
					'chooseWXPay'
				];
				jweixin.config({
					debug: false,
					appId: config.appId, // 必填，公众号的唯一标识  
					timestamp: config.timestamp, // 必填，生成签名的时间戳  
					nonceStr: config.nonceStr, // 必填，生成签名的随机串  
					signature: config.signature, // 必填，签名  
					jsApiList: jsApiList // 必填，需要使用的JS接口列表          
				});
				jweixin.ready(() => {
					/*初始化公众号配置，获取js授权签名*/
					let shareUrl = share.link; //通过中转方法解决微信分享会截断#号路径问题
					share.link = shareUrl.replace('?from=singlemessage', '');
					/*分享到朋友圈*/
					jweixin.updateAppMessageShareData({
						title: share.title, // 分享标题  
						desc: share.desc, // 分享描述  
						link: share.link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致  
						imgUrl: share.imgUrl, // 分享图标  
						success: () => {
							share.success();
						},
						cancel: () => {
						}
					});
					/*分享给朋友*/
					jweixin.updateTimelineShareData({
						title: share.title, // 分享标题  
						link: share.link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致  
						imgUrl: share.imgUrl, // 分享图标  
						success: () => {
							share.success();
						},
						cancel: () => {
						}
					});
				})
			} else {
			}
		},
		fail: res => {
		},
	});
}
module.exports = {
	initShare: initShare
}

// #endif
