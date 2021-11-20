const TokenKey = 'phone'

export function getPhone() {
  return uni.getStorageSync(TokenKey)
}

export function setPhone(token) {
  return uni.setStorageSync(TokenKey, token)
}

export function removePhone() {
  return uni.removeStorageSync(TokenKey)
}