import fetch from '@/utils/fetch'

export function getInfo (searchObj = {}, page = 1 , pageSize = 10) {

  return fetch({
    url: '/api/permissions',
    method: 'get',
    params: {
      pageSize,
      page,
      name: searchObj.name,
      pid: searchObj.pid,
      type: searchObj.type
    },
  })
}

export function getInfoById (id) {
  return fetch({
    url: '/api/permissions/' + id,
    method: 'get'
  })
}

export function updateInfo (id, data) {
  return fetch({
    url: '/api/permissions/' + id,
    method: 'patch',
    data
  })
}

export function addInfo (data) {
  console.log(data)
  return fetch({
    url: '/api/permissions',
    method: 'post',
    data,
  })
}

export function deleteInfoById (id) {
  return fetch({
    url: '/api/permissions/' + id,
    method: 'delete'
  })
}

export function deleteAll(params) {
  return fetch({
    url: '/api/permissions/deleteAll',
    method: 'post',
    data: {
      ids:params
    }
  })
}

export function getGroup() {
  return fetch({
    url: '/api/permissions/getGroup',
    method: 'post'
  })

}

export function getPermission() {
  return fetch({
    url: '/api/permissions/getPermissionByTree',
    method: 'post'
  })
}

export function Model (name = null, pid = null, type = null, method = null, route_name = null, route_match = null, remark = null) {
  this.name = name;
  this.pid = pid;
  this.type = type;
  this.method = method;
  this.route_name = route_name;
  this.route_match = route_match;
  this.remark = remark;
}

export function SearchModel(name = null, pid = null, type = null) {
  this.name = name;
  this.pid = pid;
  this.type = type;
}


