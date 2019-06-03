import axios from 'axios'
import { Message } from 'element-ui'
import store from '../store'
import { getToken,setToken } from '@/utils/auth'
import { loginToken } from "@/api/login";

// 创建axios实例
const service = axios.create({
  baseURL: process.env.VUE_APP_BASE_API, // api的base_url
  timeout: 5000,                  // 请求超时时间
  withCredentials: true,
})

// request拦截器
service.interceptors.request.use(config => {
  if (getToken()) {
    config.headers['Authorization'] = "Bearer " + getToken() // 让每个请求携带自定义token 请根据实际情况自行修改
  }
  return config
}, error => {
  // Do something with request error
  //console.log(error) // for debug
  return Promise.reject(error)
})

// respone拦截器
service.interceptors.response.use(
  response => {
    const res = response.data
    return res
  },
  error => {
        console.log(error.response);
       // console.log(error.response)
       // if (error.response.status == 401) {  // 刷新token
       //    loginToken().then(response => {
       //      setToken(response.token)
       //      let url = location.href
       //       window.VM.$router.go(0)
       //    })
       // } else {
       //   if (error.response.status == 500) { // token过期  退出系统
       //     store.dispatch('FedLogOut').then(() => {
       //       window.VM.$router.push({ path: '/login'})
       //    })
       //   }
         return Promise.reject(error)
  }
)

export default service
