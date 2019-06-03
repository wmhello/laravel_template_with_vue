import fetch from '@/utils/fetch'

export function getInfo (searchObj = {}, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/users',
    method: 'get',
    params: {
        page,
        pageSize,
        name: searchObj.name,
        email: searchObj.email
    }
  })
}

export function getCurrentPage(current_page) {
  return fetch({
    url: '/api/users',
    method: 'get',
    params: {
      page: current_page,
    },
  })
}

export function getInfoById(id) {
  return fetch({
    url: '/api/users/' + id,
    method: 'get',
  })
}

export function resetAdminByPsw(id, password) {
  return fetch({
    url: '/api/users/' + id + '/reset',
    method: 'post',
    data: {
      password
    }
  })
}

export function uploadAdminByImg(data) {
  return fetch({
    url: '/api/users/uploadAvatar',
    method: 'post',
    data,
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
}

export function updateInfo(id, data) {
  return fetch({
    url: '/api/users/' + id,
    method: 'put',
    params: {
      name: data.name,
      roles: data.roles,
      avatar: data.avatar,
    },
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    }
  })
}

export function deleteInfoById(id) {
  return fetch({
    url: '/api/users/' + id,
    method: 'delete',
  })
}

export function addInfo(data) {
  return fetch({
    url: '/api/users',
    method: 'post',
    data
  })
}

export function uploadFile (data) {
  return fetch({
    url: '/api/users/upload',
    method: 'post',
    data,
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
}

export function exportCurrentPage (pageSize = 10, page = 1, searchObj = {}) {
  return fetch({
    url: '/api/users/export',
    method: 'post',
    data: {
      page,
      pageSize,
      name: searchObj.name,
      email: searchObj.email
    }
  })
}

// 导出所有内容
export function exportAll (searchObj = {}) {
  return fetch({
    url: '/api/users/export',
    method: 'get',
    params: {
      name: searchObj.name,
      email: searchObj.email
    }
  })
}

// 下载模板
export function download (searchObj = {}) {
  return fetch({
    url: '/api/users/download',
    method: 'get',
    responseType: 'blob'
  })
}

export function modifyUser (data) {
  return fetch({
    url: '/api/users/modify',
    method: 'post',
    data: {
      password: data.password,
      oldPassword: data.oldPassword,
      password_confirmation: data.password_confirmation
    }
  })
}

export function deleteAll( params) {
  return fetch({
     url: '/api/users/deleteAll',
     method: 'post',
     data: {
       ids: params
     }
  })

}

export function Model (name = '', email = '', roles = [], avatar = '', password = '', password_confirmation='') {
  this.name = name
  this.email = email
  this.roles = roles
  this.avatar = avatar
  this.password = password
  this.password_confirmation = password_confirmation
}

export function SearchModel(name = '', email = " ") {
this.name = name
this.email = email
}
