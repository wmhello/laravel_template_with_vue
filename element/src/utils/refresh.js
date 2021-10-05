import store from "@/store";
import { refreshTokenFn } from "@/api/user";
import { setIsAutoLogin } from "@/utils/auth";
import { Message } from "element-ui";
import router from "@/router";

export function refreshToken() {
  return new Promise(async (resolve, reject) => {
    const tokenResult = await refreshTokenFn();
    if (tokenResult.status === 200) {
      const { access_token, expires_in } = tokenResult.data;
      const tokenInfo = {
        accessToken: access_token,
        expiresIn: expires_in
      };
      store.dispatch("user/resetToken", tokenInfo);
      resolve(tokenInfo);
    } else {
      // 令牌无法刷新，后端返回401 则重新开始
      if (tokenResult.status === 401) {
        setIsAutoLogin(0);
        reject();
      }
    }
  });
}

export function handleRefreshFail() {
  store.dispatch("user/resetToken", {
    accessToken: "",
    expiresIn: ""
  });
  Message({
    message: "令牌过期，请重新登陆使用",
    type: "error",
    duration: 5 * 1000
  });
  router.push({
    path: "/login"
  });
}
