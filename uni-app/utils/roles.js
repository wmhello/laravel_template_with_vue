const TokenKey = 'roles'

export function getRoles() {
  return uni.getStorageSync(TokenKey)
}

export function setRoles(token) {
  return uni.setStorageSync(TokenKey, token)
}

export function removeRoles() {
  return uni.removeStorageSync(TokenKey)
}