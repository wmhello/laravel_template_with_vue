import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import Vant from 'vant';
import 'vant/lib/index.css';
Vue.use(Vant);
import '@/permission.js'
import wx from 'weixin-js-sdk'
import { getConfig } from '@/api/jssdk'

const actions = ['openLocation','getLocation','updateAppMessageShareData', 'updateTimelineShareData', 'chooseImage', 'uploadImage', 'previewImage', 'getLocalImgData', 'downloadImage','closeWindow','hideMenuItems', 'scanQRCode','showMenuItems', 'onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareTimeline', 'chooseWXPay']
console.log(process.env.BASE_URL);
router.afterEach(async function(to, from){
  var _url = window.location.origin + to.fullPath
  // 非ios设备，切换路由时候进行重新签名
  if (window.__wxjs_is_wkwebview !== true) {
   var config = await getConfig(_url, actions)
    wx.config(config)
  }  
})
// ios 设备进入页面则进行js-sdk签名
if (window.__wxjs_is_wkwebview === true) {
  var _url = window.location.href.split('#')[0]
  getConfig(_url, actions).then(function (res) {
    wx.config(res)
  }) 
}

Vue.config.productionTip = false
new Vue({
  router,
  store,
  render: function (h) { return h(App) }
}).$mount('#app')
