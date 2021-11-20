import router from '@/router'
import { saveToken,saveOpenId } from '@/utils/cache'
import store from '@/store'
import qs from 'qs'

router.beforeEach((to, from, next) => {
	const loginStatus = Number(store.getters.loginStatus)
	const token = store.getters.token
	if (loginStatus === 0) {  // 未登陆
	    // 微信未授权登录跳转到授权登录页面
	    const url = window.location.href
	    // 解决重复登录url添加重复的code与state问题
	    const parseUrl = qs.parse(url.split('?')[1])
	    // 无论拒绝还是授权都设置成1
	    store.dispatch('user/setLoginStatus', 1)
	    // 跳转到微信授权页面
	    window.location.href = process.env.VUE_APP_REDIRECT +`?end_url=${url}`
	  } else if (loginStatus === 1) {   // 正在登陆
	    const url = window.location.href
	    // 解决重复登录url添加重复的code与state问题
	    const parseUrl = qs.parse(url.split('?')[1])
	    saveToken(parseUrl.token)
		saveOpenId(parseUrl.openid)
	    store.dispatch('user/setLoginStatus', 2)
	    delete to.query.token
	    delete to.query.openid
	    next()
	  } else {  // 登陆
	   // 已登录直接进入
	    next()
	  }
	})