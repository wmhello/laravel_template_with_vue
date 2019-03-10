import axios from 'axios'
import { Message } from 'element-ui'
import store from '@/store'
import { getToken } from '@/utils/auth'

// create an axios instance
const service = axios.create({
  // baseURL: process.env.BASE_API, // api的base_url
  timeout: 30000 // request timeout
})

// request interceptor
service.interceptors.request.use(config => {
  // Do something before request is sent
  if (store.getters.token) {
    config.headers['Authorization'] = 'Bearer ' + getToken() // 让每个请求携带token-- ['X-Token']为自定义key 请根据实际情况自行修改
  }
  return config
}, error => {
  // Do something with request error
  console.log(error) // for debug
  Promise.reject(error)
})

// respone interceptor
service.interceptors.response.use(
  response => {
    const res = response.data
    if (res.code === -1) {
      message(res.msg, 'error')
      return Promise.reject(res)
    }
    return res
  },
  error => {
    const res = error.response
    console.log(res.status)
    if (res.status === 401) {
      store.dispatch('FedLogOut').then(() => { location.reload() })
    } else if (res.status === 403) {
      message(res.status + '： ' + res.data.msg, 'error')
    } else if (res.status === 400) {
      message(res.status + '： ' + res.data.error_description, 'error')
    } else if (res.status === 202) {
      this.$router.push({ path: '/' })
    } else if (res.status === 503) { // 服务异常
      message(res.status + '： ' + res.data, 'error')
    } else {
      message(res.status + '： ' + res.data.message, 'error')
    }
    return Promise.reject(error)
  })

export function message(text, type) {
  Message({
    message: text,
    type: type,
    duration: 5 * 1000
  })
}

export default service
