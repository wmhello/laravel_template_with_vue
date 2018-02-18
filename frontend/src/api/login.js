import fetch from '@/utils/fetch'

export function login(username, password) {
  return fetch({
    url: '/api/login',
    method: 'post',
    data: {
      email:username,
      password
    }
  })
}

export function getInfo() {
  return fetch({
    url: '/api/user',
    method: 'get',
  })
}

export function logout() {
  return fetch({
    url: '/api/logout',
    method: 'post'
  })
}
export function loginToken() {
  return fetch({
    url: '/api/token/refresh',
    method: 'post'
  })
}
