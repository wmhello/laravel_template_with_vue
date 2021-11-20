<template>
	<view class="content">
		<view class="logo" v-if="!showModal"><image src="../../static/logo.jpg" mode="" style="height: 135rpx; width: 135rpx;"></image></view>

		<view class="toolbars" v-if="!showModal">
			<button v-if="canUseGetUserProfile" type="default" @click="login_new">微信用户一键登陆</button>
			<button v-else class="active" open-type="getUserInfo" @getuserinfo="login">微信用户一键登陆</button>
			<view class="close" @click="nav('/pages/index/index')">暂不登陆</view>
		</view>

		<view class="modal-mask" v-if="showModal"></view>
		<view class="modal-dialog" v-if="showModal">
			<view class="modal-content">
				<view><image src="../../static/logo.jpg" class="show"></image></view>
				<view>绑定手机号</view>
				<view>请先绑定手机号在进行此操作</view>
				<button open-type="getPhoneNumber" @getphonenumber="getPhone">
					<!-- <image src="../images/showWx.png" class="iconWx"></image> -->
					微信用户一键绑定
				</button>
			</view>
		</view>
	</view>
</template>

<script>
import { mapState } from 'vuex';
var session_key = null,
	_self = null,
	pageOptions = null;
import { setToken } from '@/utils/auth.js';
import { setRoles } from '@/utils/roles.js';
import { setPhone } from '@/utils/phone.js';
import { getCode, getInfo, getRun, store } from '@/api/login.js';
import { getStatus } from '@/api/info.js';
export default {
	data() {
		return {
			showModal: false,
			canUseGetUserProfile: false
		};
	},
	computed: {
		...mapState(['isLogin'])
	},
	onShow() {
		if (wx.getUserProfile) {
			this.canUseGetUserProfile = true;
		}
	},
	onLoad() {},
	methods: {
		//小程序授权api替换 getUserInfo 替换
		async getPhone(e) {
			let data = {
				session_key: session_key,
				iv: e.detail.iv,
				encryptedData: e.detail.encryptedData,
				uid: ''
			};
			try {
				let res = await getRun(data);
				setPhone(res.phoneNumber);
				this.$store.commit('SET_PHONE', res.phoneNumber);
				this.navBack()
			} catch (e) {
				//TODO handle the exception
				this.$tip('为了操作方便，建议绑定电话号码');
			}
		},
		navBack(){  // 导航回到业务页面
			let backtype = uni.getStorageSync('backtype');
			let backpage = uni.getStorageSync('backpage');
			if (backtype == 1) {
				uni.redirectTo({
					url: backpage
				});
			} else {
				uni.switchTab({
					url: backpage
				});
			}
		},
		login_new(e) {
			_self = this;
			wx.getUserProfile({
				desc: '用于完善会员资料',
				success: response => {
					uni.login({
						success: res => {
							let data = {
								code: res.code
							};
							getCode(data).then(res => {
								session_key = res.session_key;
								setToken(res.openid);
								_self.$store.commit('setLoginState', true);
								_self.$store.commit('SET_TOKEN', res.openid);
								uni.setStorageSync('avatar', response.userInfo.avatarUrl);
								_self.$store.commit('SET_AVATAR', response.userInfo.avatarUrl);
								uni.setStorageSync('nickname', response.userInfo.nickName);
								_self.$store.commit('SET_NICKNAME', response.userInfo.nickName);
								let obj = {
									open_id: res.openid,
									avatar: response.userInfo.avatarUrl,
									nickname: response.userInfo.nickName,
									gender: response.userInfo.gender,
									country: response.userInfo.country,
									city: response.userInfo.city,
									province: response.userInfo.province
								};
								store(obj)
									.then(res => {
										_self.showModal = true;
									})
									.catch(err => {
										console.log('保存数据出错');
									});
							});
						}
					});
				}
			});
		},
		login() {
			_self = this;
			uni.login({
				success: res => {
					let data = {
						code: res.code
					};
					getCode(data).then(res => {
						session_key = res.session_key;
						wx.getUserProfile({
							success: e => {
								console.log(e);
							}
						});
						uni.getUserInfo({
							lang: 'zh_CN',
							success: response => {
								let data = {
									session_key: session_key,
									iv: response.iv,
									encryptedData: response.encryptedData
								};
								getInfo(data).then(async res => {
									let result = res;
									setToken(result.token);
									_self.saveUserData(result);
									_self.$store.commit('setLoginState', true);
									_self.$store.commit('SET_TOKEN', result.token);
									_self.showModal = true;
								});
							}
						});
					});
				}
			});
		},
		closeLogin() {
			uni.switchTab({
				url: '/pages/home/home'
			});
		},
		saveUserData(result) {
			uni.setStorageSync('avatar', result.data.avatar);
			_self.$store.commit('SET_AVATAR', result.data.avatar);
			uni.setStorageSync('nickname', result.data.nickname);
			_self.$store.commit('SET_NICKNAME', result.data.nickname);
		},
		nav(url) {
			uni.switchTab({
				url
			});
		}
	},
	onLoad: function(options) {
		_self = this;
		//微信
		// #ifdef MP-WEIXIN
		uni.login({
			success: res => {
				let data = {
					code: res.code
				};
				getCode(data).then(res => {
					session_key = res.session_key;
				});
			}
		});

		// #endif
	}
};
</script>
<style>
.logo {
	margin-top: 300rpx;
	margin-bottom: 50rpx;
	display: flex;
}

.logo image {
	margin: auto;
}

.toolbars {
	margin: 0 30rpx;
}

.toolbars .active {
	background-color: rgb(64, 173, 75);
	color: #fff;
	margin-bottom: 20rpx;
}

.toolbars .close {
	color: #eb6524;
	font-size: 30rpx;
	text-align: center;
}

/* ---弹窗css--- */
.modal-mask {
	width: 100%;
	height: 100%;
	position: fixed;
	top: 0;
	left: 0;
	background: #000;
	opacity: 0.5;
	overflow: hidden;
	color: #fff;
}

.modal-dialog {
	width: 72%;
	position: absolute;
	top: 30%;
	left: 14%;
	background: #fff;
	border-radius: 12rpx;
}

.modal-content {
	text-align: center;
}

.modal-content .show {
	/* 	width: 450rpx;
	height: 323rpx; */
	display: block;
	margin: 0 auto;
	/* margin-top: -118rpx; */
	z-index: 10000;
	height: 135rpx;
	width: 135rpx;
	margin-top: 20rpx;
	margin-bottom: 20rpx;
}

.iconWx {
	width: 52rpx;
	height: 41rpx;
	padding-right: 20rpx;
}

.iconPhone {
	width: 56rpx;
	height: 56rpx;
	padding-right: 15rpx;
}

.modal-content view:nth-of-type(2) {
	font-size: 38rpx;
	color: #333333;
	line-height: 1;
}

.modal-content view:nth-of-type(3) {
	font-size: 26rpx;
	color: #9c9c9c;
	margin: 18rpx 0 29rpx;
	line-height: 1;
}

.modal-content button:nth-of-type(1) {
	width: 80%;
	height: 80rpx;
	border-radius: 60rpx;
	margin: 0 auto 80rpx;
	font-size: 30rpx;
	color: #fff;
	background: #31cc32;
	display: flex;
	flex-direction: row;
	align-items: center;
	justify-content: center;
	padding: 0;
	box-sizing: border-box;
}

.modal-content button:nth-of-type(1)::after {
	border: none;
}

.modal-content .wxLogin {
	font-size: 26rpx;
	color: #424242;
	display: flex;
	flex-direction: row;
	align-items: center;
	justify-content: center;
	margin: 38rpx 0 80rpx;
}

button::after {
	border: none;
}
</style>
