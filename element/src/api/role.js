import request from '@/utils/request'
const url = '/roles'

export function index(page = 1, pageSize = 10) {
  return request({
    url: `${url}`,
    method: 'get',
    params: {
      page,
      pageSize
    }
  })
}

export function show(id) {
  return request({
    url: `${url}/${id}`,
    method: 'get'
  })
}

export function store(data) {
  // 转成后端所需要的格式
  const { name, desc, permissions } = data
  let result = []
  result = permissions.map((item) => {
    return {
      module: item.name,
      permissions: item.permissions
    }
  })
  data = {
    name,
    desc,
    permissions: result
  }
  return request({
    url: `${url}`,
    method: 'post',
    data
  })
}

export function update(data) {
  const { id, name, desc, permissions } = data
  let result = []
  result = permissions.map((item) => {
    return {
      module: item.name,
      permissions: item.permissions
    }
  })
  data = {
    id,
    name,
    desc,
    permissions: result
  }
  return request({
    url: `${url}/${data.id}`,
    method: 'patch',
    data
  })
}

export function destroy(id) {
  return request({
    url: `${url}/${id}`,
    method: 'delete'
  })
}
