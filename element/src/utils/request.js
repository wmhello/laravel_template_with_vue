import axios from "axios";
import { Message } from "element-ui";
import { getToken, getExpiredStatus } from "@/utils/auth";
import { refreshToken, handleRefreshFail } from "@/utils/refresh.js";

// create an axios instance
const service = axios.create({
  baseURL: process.env.VUE_APP_BASE_API, // url = base url + request url
  // withCredentials: true, // send cookies when cross-domain requests
  timeout: 50000 // request timeout
});

// request interceptor

service.interceptors.request.use(
  async (config) => {
    // 判断是否过期 未过期
    let Auth = `Bearer ${getToken()}`;
    const isExpired = getExpiredStatus();
    if (isExpired) {
      // 过期则刷新令牌
      try {
        await refreshToken();
        Auth = `Bearer ${getToken()}`;
      } catch (e) {
        // 刷新不成功，则返回处理
        handleRefreshFail();
      }
    }
    config.headers["Authorization"] = Auth;
    // if(window.Echo)
    //   {
    //     let socketId = window.Echo.socketId()
    //     if (socketId)
    //     {
    //       config.headers['X-Socket-Id'] = socketId
    //     }
    //   }
    return config;
  },
  (error) => {
    // do something with request error
    return Promise.reject(error);
  }
);

// response interceptor
service.interceptors.response.use(
  /**
   * If you want to get http information such as headers or status
   * Please return  response => response
   */

  (response) => {
    const res = response.data;
    return res;
  },
  (error) => {
    // console.log("err" + error); // for debug
    // Message({
    //   message: error.message,
    //   type: 'error',
    //   duration: 5 * 1000
    // })
    return Promise.reject(error);
  }
);

export default service;
