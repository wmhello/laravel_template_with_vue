import Vue from 'vue'
import App from './App'

Vue.config.productionTip = false

App.mpType = 'app'
// 引入全局uView
import uView from 'uview-ui';
Vue.use(uView);
Vue.prototype.checkLogin = function(backpage, backtype) {
	var token = uni.getStorageSync('token');
	var nickname = uni.getStorageSync('nickname');
	var avatar = uni.getStorageSync('avatar');
	if (token == '' || avatar == '' || nickname == '') {
		uni.redirectTo({
			url: "../login/login?backpage=" + backpage + "&backtype=" + backtype
		});
		return false;
	}
	return [token, avatar, nickname];
}
import {
	alert,
	confirm,
	tip,
	sheet
} from '@/utils/msg.js'
import store from './store'
Vue.prototype.$store = store
Vue.prototype.$debug = true

Vue.prototype.$alert = alert
Vue.prototype.$confirm = confirm
Vue.prototype.$tip = tip
Vue.prototype.$sheet = sheet

Vue.prototype.$regdate = new Date("2021-08-31");

const app = new Vue({
	store,
	...App
})

// http拦截器，此为需要加入的内容，如果不是写在common目录，请自行修改引入路径
import httpInterceptor from '@/common/http.interceptor'
// 这里需要写在最后，是为了等Vue创建对象完成，引入"app"对象(也即页面的"this"实例)
Vue.use(httpInterceptor, app)

// import httpApi from '@/common/http.api.js'
// Vue.use(httpApi, app)

app.$mount()
