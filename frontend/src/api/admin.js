import fetch from '@/utils/fetch'

export function getInfo (searchObj = {}, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/admin',
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
    url: '/api/admin',
    method: 'get',
    params: {
      page: current_page,
    },
  })
}

export function getInfoById(id) {
  return fetch({
    url: '/api/admin/' + id,
    method: 'get',
  })
}

export function resetAdminByPsw(id, password) {
  return fetch({
    url: '/api/admin/' + id + '/reset',
    method: 'post',
    data: {
      password
    }
  })
}

export function uploadAdminByImg(data) {
  return fetch({
    url: '/api/admin/uploadAvatar',
    method: 'post',
    data,
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
}

export function updateInfo(id, data) {
  return fetch({
    url: '/api/admin/' + id,
    method: 'put',
    params: {
      name: data.name,
      role: data.role,
      avatar: data.avatar
    },
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    }
  })
}

export function deleteInfoById(id) {
  return fetch({
    url: '/api/admin/' + id,
    method: 'delete',
  })
}

export function addInfo(data) {
  console.log(data)
  return fetch({
    url: '/api/admin',
    method: 'post',
    data
  })
}

export function uploadFile (data) {
  return fetch({
    url: '/api/admin/upload',
    method: 'post',
    data,
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
}

export function exportCurrentPage (pageSize = 10, page = 1, searchObj = {}) {
  return fetch({
    url: '/api/admin/export',
    method: 'post',
    data: {
      page,
      pageSize,
      name: searchObj.name,
      email: searchObj.email
    }
  })
}

export function exportAll (searchObj = {}) {
  return fetch({
    url: '/api/admin/exportAll',
    method: 'post',
    data: {
      name: searchObj.name,
      email: searchObj.email
    }
  })
}

export function deleteAll( params) {
  return fetch({
     url: '/api/admin/deleteAll',
     method: 'post',
     data: {
       ids: params
     }
  })

}

export function Model (name = '', email = '', role = [], avatar = '', password = '', password_confirmation='') {
  this.name = name
  this.email = email
  this.role = role
  this.avatar = avatar
  this.password = password
  this.password_confirmation = password_confirmation
}

export function SearchModel(name = '', email = " ") {
this.name = name
this.email = email
}
