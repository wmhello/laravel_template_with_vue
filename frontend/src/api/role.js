import fetch from '@/utils/fetch'

export function getInfo() {
  return fetch({
    url: '/api/role',
    method: 'get',
  })
}

export function getRoles() {
  return fetch({
    url: '/api/getRoles',
    method: 'get',
  })
}

export function getInfoById (id) {
  return fetch({
    url: '/api/role/' + id,
    method: 'get',
  })
}

export function updateInfo (id, data) {
  return fetch({
    url: '/api/role/' + id,
    method: 'PATCH',
    data,
  })
}

export function deleteInfoById (id) {
  return fetch({
    url: '/api/role/' + id,
    method: 'delete',
  })
}

export function addInfo (data) {
  return fetch({
    url: '/api/role',
    method: 'post',
    data,
  })
}

export function Model (name='', explain='', remark='', permission = []) {
    this.name = name
    this.explain = explain
    this.remark = remark
    this.permission = permission
}
