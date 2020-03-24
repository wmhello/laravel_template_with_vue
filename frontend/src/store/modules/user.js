import { login, logout, getInfo } from '@/api/login'
import {
  getToken,
  setToken,
  removeToken,
  getRefreshToken,
  setRefreshToken,
  removeRefreshToken,
  getTokenExpiresIn,
  setTokenExpiresIn,
  removeTokenExpiresIn,
  getIsAutoLogin,
  expiresDayVar,
  setIsAutoLogin,
  getExpiresTime
 } from '@/utils/auth'
import { resetRouter } from '@/router'


const getDefaultState = () => {
  return {
    token: getToken(),
    name: '',
    avatar: '',
    roles: [],
    refreshToken: getRefreshToken(),
    tokenExpiresIn: getTokenExpiresIn(),
    permissions: []
  }
}

const state = getDefaultState()

const mutations = {
  RESET_STATE: (state) => {
    Object.assign(state, getDefaultState())
  },
  SET_TOKEN: (state, token) => {
    state.token = token
  },
  SET_NAME: (state, name) => {
    state.name = name
  },
  SET_AVATAR: (state, avatar) => {
    state.avatar = avatar
  },
  SET_ROLES: (state, roles) => {
    state.roles = roles
  },
  SET_PERMISSIONS: (state, permissions) => {
    state.permissions = permissions
  }
}

const actions = {
  // user login
  login({ commit }, userInfo) {
    const { username, password } = userInfo
    return new Promise((resolve, reject) => {
      login({ username: username.trim(), password: password }).then(response => {
         let data = response
         let expiresDay = null
         if (Number(getIsAutoLogin()) === 1) {
           expiresDay = expiresDayVar
         }
        const { token, express_in, refresh_token } = data
        commit('SET_TOKEN', token)
        setToken(token, expiresDay)
        setRefreshToken(refresh_token, expiresDay)
        const expiresTime = getExpiresTime(express_in)
        setTokenExpiresIn(expiresTime, expiresDay)
        resolve()
      }).catch(error => {
        reject(error)
      })
    })
  },

  // get user info
  getInfo({ commit, state }) {
    return new Promise((resolve, reject) => {
      getInfo(state.token).then(response => {
        const data  = response
        if (!data) {
          reject('Verification failed, please Login again.')
        }

        const { roles, name, avatar, permissions} = data
        // roles must be a non-empty array
        if (!roles || roles.length <= 0) {
          reject('getInfo: roles must be a non-null array!')
        }
        commit('SET_ROLES', roles)
        commit('SET_NAME', name)
        commit('SET_AVATAR', avatar)
        commit('SET_PERMISSIONS', permissions)
        resolve(data)
      }).catch(error => {
        reject(error)
      })
    })
  },

  // user logout
  logout({ commit, state }) {
    return new Promise((resolve, reject) => {
      logout(state.token).then(() => {
        window.Echo.leave('leave.' + state.name);
        commit('SET_TOKEN', '')
        commit('SET_ROLES', [])
        commit('SET_PERMISSIONS', [])
        commit('SET_NAME', '')
        commit('SET_AVATAR', '')
        removeToken()
        removeRefreshToken()
        removeTokenExpiresIn()
        setIsAutoLogin(0)
        resetRouter()
        resolve()
      }).catch(error => {
        reject(error)
      })
    })
  },

  // remove token

  resetToken({ commit }, logInfo) {
    const { accessToken, refreshToken, expiresIn } = logInfo
    return new Promise((resolve, reject) => {
      if (!accessToken || !refreshToken) {
        commit('SET_TOKEN', '')
        removeToken()
        removeRefreshToken()
        removeTokenExpiresIn()
      } else {
        let expiresDay = null
        if (Number(getIsAutoLogin()) === 1) {
          expiresDay = expiresDayVar
        }
        commit('SET_TOKEN', accessToken)
        setToken(accessToken, expiresDay)
        setRefreshToken(refreshToken, expiresDay)
        const expiresTime = getExpiresTime(expiresIn)
        setTokenExpiresIn(expiresTime, expiresDay)
      }
      resolve()
    })
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
