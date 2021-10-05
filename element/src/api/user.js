import request from "@/utils/request";
import axios from "axios";
import { getToken } from "@/utils/auth";

export function login(data) {
  return request({
    url: "/login",
    method: "post",
    data
  });
}

export function getInfo(token) {
  return request({
    url: "/me",
    method: "get"
  });
}

export function logout() {
  return request({
    url: "/logout",
    method: "post"
  });
}

export function refreshTokenFn() {
  return new Promise((resolve, reject) => {
    axios({
      url: `${process.env.VUE_APP_BASE_API}/refresh`,
      method: "post",
      headers: {
        Authorization: "Bearer " + getToken()
      }
    })
      .then((response) => {
        // return response;
        resolve(response);
      })
      .catch((error) => {
        resolve(error.response);
      });
  });
}
