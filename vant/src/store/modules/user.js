import {
  saveToken,
  saveLoginStatus,
  removeToken,
  removeLoginStatus,
  loadLoginStatus,
  loadToken,
} from '@/utils/cache'
const state = {
  loginStatus: loadLoginStatus(), // 登录状态
  token: loadToken(), // token
}

const mutations = {
 SET_LOGIN_STATUS: (state, loginStatus) => {
    state.loginStatus = loginStatus
  },
  SET_TOKEN: (state, token) => {
    state.token = token
  }
}

const actions = {
  // 登录相关，通过code获取token和用户信息

  // 设置状态
  setLoginStatus({ commit }, query) {
    if (query === 0 || query === 1) {
      // 上线打开注释，本地调试注释掉，保持信息最新
      removeToken()
    }
    // 设置不同的登录状态
    commit('SET_LOGIN_STATUS', saveLoginStatus(query))
  },
  // 登出
  fedLogOut() {
    // 删除token，用户信息，登陆状态
    removeToken()
    removeLoginStatus()
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
