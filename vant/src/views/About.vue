<template>
  <div class="about">
		<van-nav-bar
		  title="jssdk页面分享以及页面操作"
		  left-text="返回"
		  left-arrow
		  @click-left="onClickLeft"
		/>
       <van-button type="primary" size="large" @click="close">关闭页面</van-button>
  </div>
</template>


<script>
import wx from 'weixin-js-sdk'
import operate from '@/minix/operate.js'
export default {
   name:'about',
   mixins:[operate],
   methods: {
	 close(){
		 wx.closeWindow()
	 }
   },
   created(){
	   // 微信分享配置
	   wx.ready(function () {
		   var that = this 
	     wx.onMenuShareAppMessage({
	       title: window.document.title,
	       desc: 'jssdk页面功能',
	       link: window.location.href,
	       success: function() {
	         that.$toast.success('分享成功');
	       }
	     })
	})
   }
}
</script>

<style>

</style>
