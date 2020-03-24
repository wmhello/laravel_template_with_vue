import store from '@/store'
import { refreshTokenFn } from '@/api/user'
import { setIsAutoLogin } from '@/utils/auth'
import { Message } from 'element-ui'
import router from '@/router'

export function refreshToken() {
  return new Promise(async(resolve, reject) => {
    const tokenResult = await refreshTokenFn()
    if (tokenResult.status === 200) {
      const {
        token,
        express_in,
        refresh_token
      } = tokenResult.data.data
      const tokenInfo = {
        accessToken: token,
        refreshToken: refresh_token,
        expiresIn: express_in
      }
      store.dispatch('user/resetToken', tokenInfo)
      resolve(tokenInfo)
    } else {
      if (tokenResult.status === 401) {
        setIsAutoLogin(0)
        reject()
      }
    }
  })
}

export function handleRefreshFail() {
  Message({
    message: '请登录',
    type: 'error',
    duration: 5 * 1000
  })
  store.dispatch('user/resetToken', {
    accessToken: '',
    refreshToken: '',
    expiresIn: ''
  })
  router.push({ path: '/login' })
}
