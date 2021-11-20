<template>
	<div class="pay">
		<van-nav-bar title="支付" left-text="返回" left-arrow @click-left="onClickLeft" />
		<van-grid>
			<van-grid-item icon="gold-coin-o" text="1元" @click="handlePay(100)" />
			<van-grid-item icon="gold-coin-o" text="清除登陆标记" @click="clear()" />
			<van-grid-item icon="gold-coin-o" text="10元" @click="handlePay(1000)" />
			<van-grid-item icon="gold-coin-o" text="100元" @click="handlePay(10000)" />
		</van-grid>
		<van-button type="primary" block>{{token}}</van-button>
		<van-button type="primary" block>{{openId}}</van-button>
	</div>
</template>

<script>
import wx from 'weixin-js-sdk'
import operate from '@/minix/operate.js';
import { pay, getId } from '@/api/jssdk.js';
// import axios from 'axios';
import { loadToken, loadOpenId, saveLoginStatus} from '@/utils/cache.js';

export default {
	name: 'pay',
	mixins: [operate],
	data() {
		return {
			openId: loadOpenId(),
			type: '类型',
			token: loadToken()
		};
	},
	methods: {
		clear: function() {
			saveLoginStatus(0)
			this.$toast.success(`清除成功！！`);
		},
		 handlePay: async function(price) {
			var that = this;
			/**
			 * 另一种方法，无法调起支付，应该是wx-jssdk的版本问题
			 *
			 */
			//      var onBridgeReady=function(){
			//          WeixinJSBridge.invoke(
			//                  'getBrandWCPayRequest',
			// 		`{
			// 		     "appId": ${_this.params.appId},
			// 		     "timeStamp": ${_this.params.timeStamp},
			// 		     "nonceStr": ${_this.params.nonceStr},
			// 		     "package": ${_this.params.package},
			// 		     "signType":${_this.params.signType},
			// 		     "paySign": ${_this.params.paySign}
			// 		 }`,
			//                  (res)=>{
			//                      if (res.err_msg == "get_brand_wcpay_request:ok") {
			//                          _this.alert('支付成功');
			//                          window.location.reload();
			//                      }
			//                      if (res.err_msg == "get_brand_wcpay_request:cancel") {
			//                          _this.alert('取消支付');
			//                          window.location.reload();
			//                      }
			//                  }
			//          );
			//      }
			//      let callpay=function(){
			// console.log('callplay')
			//          if (typeof WeixinJSBridge == "undefined") {
			//              if (document.addEventListener) {
			//                  document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
			//              } else if (document.attachEvent) {
			//                  document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
			//                  document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
			//              }
			//          } else {
			//              onBridgeReady();
			//          }
			//      }

			// pay(price, this.openId).then(data=>{
			// 	result = data
			// 	this.params = data
			// 	callpay();
			// })
			//请求支付数据
			 //没有获取数据
			// axios({
			// 	url: 'http://wmhello.mynatapp.cc/api/spa-pay',
			// 	method: 'get',
			// 	params: {
			// 		price,
			// 		open_id: this.openId
			// 	},
			// 	withCredentials: true,
			// 	responseType: 'json'
			// })
			this.openId = loadOpenId();
			var res = await pay(price, this.openId)
				wx.chooseWXPay({
					timestamp: res.timestamp,
					nonceStr: res.nonceStr,
					package: res.package,
					signType: res.signType,
					paySign: res.paySign, // 支付签名
					success: function(res) {
						// 支付成功后的回调函数
					}
				});
		}
	}
};
</script>

<style></style>
