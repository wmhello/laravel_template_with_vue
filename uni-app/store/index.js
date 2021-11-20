import Vue from 'vue'
import Vuex from 'vuex'

import getters from '@/store/getters.js'
import app from  './modules/app.js'
import user from  './modules/user.js'
import {getToken} from '@/utils/auth.js'
Vue.use(Vuex)
const store = new Vuex.Store({
	modules:{
		app,
		user
	},
	state: {
		isLogin: getToken()?true:false
	},
	getters,
	actions: {
	},
	mutations: {
		setLoginState(state, isLogin) {
			state.isLogin = isLogin
		}
	}
})

export default store
