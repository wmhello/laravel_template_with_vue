import axios from 'axios'
import store from '@/store'
import { Toast } from 'vant'
import {loadToken} from '@/utils/cache.js'

// create an axios instance
const service = axios.create({
  baseURL: process.env.VUE_APP_BASE_URL, // 'http://api.halian.net', // url = base url + request url
  withCredentials: false, // send cookies when cross-domain requests
  timeout: 5000 // request timeout
})

// request拦截器 request interceptor
service.interceptors.request.use(
  config => {
    let token = loadToken()
    if (token) {
	  console.log(token)
      config.headers['Authorization'] = 'Bearer ' + token
    }
    return config
  },
  error => {
    // do something with request error
    console.log(error) // for debug
    return Promise.reject(error)
  }
)
// respone拦截器
service.interceptors.response.use(
  response => {
    Toast.clear()
    const res = response.data
	return Promise.resolve(res)
    // // 这里注意修改成你访问的服务端接口规则
    // if (res.status_code && res.status_code !== 200) {
    //   Toast({
    //     message: res.info
    //   })
    //   // 登录超时,重新登录
    //   if (res.status_code === 401) {
    //     store.dispatch('user/fedLogOut').then(() => {
    //       location.reload()
    //     })
    //   }
    //   return Promise.reject(res || 'error')
    // } else {
    //   return Promise.resolve(res)
    // }
  },
  error => {
    Toast.clear()
    console.log('err' + error) // for debug
    return Promise.reject(error)
  }
)

export default service
