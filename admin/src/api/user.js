import request from '@/utils/request'

export function fetchUserList(_params) {
  return request({
    url: '/admin/user/list',
    method: 'get',
    params: _params
  })
}

export function fetchUserByUserId(userId) {
  return request({
    url: '/admin/user/find/' + userId,
    method: 'get'
  })
}

export function delByUserId(userId) {
  return request({
    url: '/admin/user/del/' + userId,
    method: 'post'
  })
}

export function addUser(_from) {
  return request({
    url: '/admin/user/addUser',
    method: 'post',
    data: _from
  })
}

export function updateUser(_from) {
  return request({
    url: '/admin/user/updateUser',
    method: 'post',
    data: _from
  })
}

export function modifyUser(_from) {
  return request({
    url: '/admin/user/modifyUser',
    method: 'post',
    data: _from
  })
}

