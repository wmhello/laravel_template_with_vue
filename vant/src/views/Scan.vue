<template>
	<div>
		<van-nav-bar title="二维码扫码处理" left-text="返回" left-arrow @click-left="onClickLeft" />
		<van-button type="primary" size="large" @click="scan">扫码</van-button>
		<div>结果为:{{result}}</div>
	</div>
</template>
<script>
import wx from 'weixin-js-sdk';
import operate from '@/minix/operate.js';
export default {
	name: 'about',
	mixins: [operate],
	data() {
		return {
			imgSrc: null,
			result: null
		};
	},
	methods: {
		scan(){
			var that = this
			wx.scanQRCode({
			  needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
			  scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
			  success: function (res) {
			    this.result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
			  }
			});
		},
		chooseImage() {
			var that = this
			wx.chooseImage({
				count: 1, // 默认9
				sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
				sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
				success: function(res) {
					// this
					var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
					var imgs = [];
					imgs = imgs.concat(localIds);
					var img = imgs[0];
					wx.getLocalImgData({
						localId: img, // 图片的localID
						success: function(res) {
							var localData = res.localData; // localData是图片的base64数据，可以用img标签显示,ios和安卓要重新处理，安卓要加上图片格式
							if (window.__wxjs_is_wkwebview) {
								that.imgSrc = localData;
							} else {
								var baseHeader = 'data:image/jpg;base64,';  // 安卓
								var base = baseHeader + localData;
								that.imgSrc = base;
							}
						}
					});
				}
			});
		}
	}
};
</script>

<style></style>