import fetch from '@/utils/fetch'

export function getInfo() {
  return fetch({
    url: '/api/roles',
    method: 'get'
  })
}

export function getRoles() {
  return fetch({
    url: '/api/getRoles',
    method: 'get'
  })
}

export function getInfoById (id) {
  return fetch({
    url: '/api/roles/' + id,
    method: 'get',
  })
}

export function updateInfo (id, data) {
  data.permissions = data.permissions.join(',')
  return fetch({
    url: '/api/roles/' + id,
    method: 'PATCH',
    data,
  })
}

export function deleteInfoById (id) {
  return fetch({
    url: '/api/roles/' + id,
    method: 'delete',
  })
}

export function addInfo (data) {
  data.permissions = data.permissions.join(',')
  return fetch({
    url: '/api/roles',
    method: 'post',
    data,
  })
}

