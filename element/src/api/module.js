import request from '@/utils/request'
const url = '/modules'

export function index(page = 1, pageSize = 100) {
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
  return request({
    url: `${url}`,
    method: 'post',
    data
  })
}

export function update(data) {
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
