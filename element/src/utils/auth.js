import Cookies from "js-cookie";
const TokenKey = "access_token";
const refreshTokenKey = "refresh_token";
const tokenExpiresInKey = "access_token_expiry_time";
const isLoginKey = "auto_login";
export const expiresDayVar = 15;
// 获取Token

// 获取Token
export function getToken() {
  return Cookies.get(TokenKey);
}

export function setToken(token, expires = null) {
  return Cookies.set(TokenKey, token, {
    expires: expires
  });
}

export function removeToken() {
  return Cookies.remove(TokenKey);
}

// 获取刷新token
export function getRefreshToken() {
  return Cookies.get(refreshTokenKey);
}

export function setRefreshToken(token, expires = null) {
  return Cookies.set(refreshTokenKey, token, {
    expires: expires
  });
}

export function removeRefreshToken() {
  return Cookies.remove(refreshTokenKey);
}

// 获取token到期时间
export function getTokenExpiresIn() {
  return Cookies.get(tokenExpiresInKey);
}

export function setTokenExpiresIn(token, expires = null) {
  return Cookies.set(tokenExpiresInKey, token, {
    expires: expires
  });
}

export function removeTokenExpiresIn() {
  return Cookies.remove(tokenExpiresInKey);
}

// 是否自动登陆
export function getIsAutoLogin() {
  return window.localStorage.getItem(isLoginKey);
}
// 设置自动登陆
export function setIsAutoLogin(isLogin) {
  return window.localStorage.setItem(isLoginKey, isLogin);
}

// 把token失效时间计算成 到期时间的时间戳

export function getExpiresTime(time) {
  var millisecond = Date.now();
  var expiresTime = millisecond + time * 1000;
  return expiresTime;
}

// 判断是否过期

export function getExpiredStatus() {
  var currentTime = Date.now();
  var lastTime = getTokenExpiresIn();
  return currentTime > lastTime;
}
