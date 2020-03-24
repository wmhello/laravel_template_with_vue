import axios from 'axios'
import { Message } from 'element-ui'
import store from '../store'
import { loginToken } from '@/api/login'
import {
  setToken,
  getToken,
  getExpiredStatus
} from '@/utils/auth'
import { refreshToken, handleRefreshFail } from '@/utils/refresh.js'

// 创建axios实例
const service = axios.create({
  baseURL: process.env.VUE_APP_BASE_API, // api的base_url
  timeout: 5000, // 请求超时时间
  withCredentials: true
})


// request拦截器
// service.interceptors.request.use(config => {
//   if (getToken()) {
//     config.headers['Authorization'] = 'Bearer ' + getToken() // 让每个请求携带自定义token 请根据实际情况自行修改
//   }
//   return config
// }, error => {
//   // Do something with request error
//   // console.log(error) // for debug
//   return Promise.reject(error)
// })

// request interceptor
service.interceptors.request.use(
  async config => {
    // 判断是否过期 未过期
    let Auth = `Bearer ${getToken()}`
    const isExpired = getExpiredStatus()
    if (isExpired) {
      // 过期则刷新令牌
      try {
        await refreshToken()
        Auth = `Bearer ${getToken()}`
      } catch (e) {
        // 刷新不成功，则返回处理
        handleRefreshFail()
      }
    }
    config.headers['Authorization'] = Auth
    return config
  },
  error => {
    // do something with request error
    return Promise.reject(error)
  }
)

// respone拦截器
service.interceptors.response.use(
  response => {
    const headers = response.headers
    if (headers['content-type'] === 'application/octet-stream;charset=utf-8') {
      return response.data
    }
    const res = response.data
    return res
  },
  error => {
    // 401 并且有 token 表示 令牌过期
    // 可以在这里调用刷新
    return Promise.reject(error)
  }
)

export default service
