import axios from 'axios'
import { Message } from 'element-ui'
import store from '../store'
import { getToken,setToken } from '@/utils/auth'
import { loginToken } from "@/api/login";

// 创建axios实例
const service = axios.create({
  baseURL: process.env.BASE_API, // api的base_url
  timeout: 150000,                  // 请求超时时间
  withCredentials: true,
})

// request拦截器
service.interceptors.request.use(config => {
  if (getToken()) {
    config.headers['Authorization'] = "Bearer "+getToken() // 让每个请求携带自定义token 请根据实际情况自行修改
  }

//   axios请求格式
//   { adapter: ƒ, transformRequest: { … }, transformResponse: { … }, timeout: 150000, xsrfCookieName: "XSRF-TOKEN",  … } {
//   {
//   adapter: ƒ xhrAdapter(config)
//   baseURL: "http://manger.test"
//   data: undefinedheaders: { Accept: "application/json, text/plain, */*", Authorization: "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI…MKBIWaqQ78k-f6y-WupXefy4HmXhSa-MnqY33BozaWcJOVsXQ" }
//   maxContentLength: -1
//   method: "get"
//   timeout: 150000
//   transformRequest: { 0: ƒ }
//   transformResponse: { 0: ƒ }
//   url: "http://manger.test/api/user"
//   validateStatus: ƒ validateStatus(status)
//   withCredentials: true
//   xsrfCookieName: "XSRF-TOKEN"
//   xsrfHeaderName: "X-XSRF-TOKEN"
//   __proto__: Object
// }
  let url = config.url  // 当前访问的地址
  // 对指定的白名单API自动放行，一般用于登录和退出
  let white_list = ['/api/login', '/api/user', '/api/logout']
  if (Array.indexOf(white_list, url) !== -1) {
    return config
  }
  let permissions = store.state.user.permissions // 后台获取的当前用户能访问的地址
  let roles = store.state.user.roles // 当前用户角色
  // 对admin角色自动放行
  if (Array.indexOf(roles, 'admin') !== -1) {
    return config
  }
  // 进行权限判断， 如果没有权限的话，就直接在前端拦截，不访问后台了
  let isPermission = false // 权限表示，用户是否有权限
  let i = 0,max =0
  // 利用正则表达式，查看当前用户是否有访问指定地址的权限，没有则无需访问，直接提示，从而拦截请求
  for(max=permissions.length; i<max; i++) {
     let feature = permissions[i]
     if (feature.route_match === ''){
       continue
     } else {
       let regx = eval(feature.route_match) // 字符串转为正则表达式，用来做验证
       if (url.search(regx) == -1) {
         // 正则不匹配， 无权限，继续下一个判断
       } else {
         // 正则匹配，有权限，直接跳出循环
         isPermission = true
         break;
       }
     }
    }
  // 根据判断的结果，来决定是否有访问内容
  if (!isPermission) {
   let error = {
      response: {
         data: {
           message: '前端拦截，当前用户没有权限访问指定的内容',
           status: 'error',
           status_code: 403
         },
         status: 403
     }
    }
    return Promise.reject(error)
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
  /**
  * code为非20000是抛错 可结合自己业务进行修改
  */
    const res = response.data
    // if (res.code !== 20000) {
    //   Message({
    //     message: res.data,
    //     type: 'error',
    //     duration: 5 * 1000
    //   })

      // 50008:非法的token; 50012:其他客户端登录了;  50014:Token 过期了;
    //   if (res.code === 50008 || res.code === 50012 || res.code === 50014) {
    //     MessageBox.confirm('你已被登出，可以取消继续留在该页面，或者重新登录', '确定登出', {
    //       confirmButtonText: '重新登录',
    //       cancelButtonText: '取消',
    //       type: 'warning'
    //     }).then(() => {
    //       store.dispatch('FedLogOut').then(() => {
    //         location.reload()// 为了重新实例化vue-router对象 避免bug
    //       })
    //     })
    //   }
    //   return Promise.reject('error')
    // } else {
    //   return response.data
    // }
    return res
  },
  error => {

       // console.log(error.response)
       if (error.response.status == 401) {  // 刷新token
          loginToken().then(response => {
            setToken(response.token)
            let url = location.href
             window.VM.$router.go(0)
          })
       } else {
         if (error.response.status == 500) { // token过期  退出系统
           store.dispatch('FedLogOut').then(() => {
             window.VM.$router.push({ path: '/login'})
          })
         }
         return Promise.reject(error)
       }
  }
)

export default service
