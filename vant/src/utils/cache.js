import cookies from 'js-cookie'
import storage from 'good-storage'
const LoginStatusKey = 'Login-Status' // 登录态 0未授权未登录 1授权未登录 2 登陆成功
const TokenKey = 'Access-Token' // token
const OpenId = 'Open-Id'
// 获取登录状态
export function loadLoginStatus() {
  return cookies.get(LoginStatusKey) || 0
}
// 保持登录状态
export function saveLoginStatus(status) {
  cookies.set(LoginStatusKey, status, { expires: 7 })
  return status
}
// 删除登录状态
export function removeLoginStatus() {
  cookies.remove(LoginStatusKey)
  return ''
}
// 获取token
export function loadToken() {
  return storage.get(TokenKey, '')
}
// 保存token
export function saveToken(token) {
  storage.set(TokenKey, token)
  return token
}
// 删除token
export function removeToken() {
  storage.remove(TokenKey)
  return ''
}

export function loadOpenId() {
  return storage.get(OpenId, '')
}
// 保存token
export function saveOpenId(token) {
  storage.set(OpenId, token)
  return token
}
// 删除token
export function removeOpenId() {
  storage.remove(OpenId)
  return ''
}
