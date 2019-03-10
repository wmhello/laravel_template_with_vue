import request from '@/utils/request'

export function fetchDeptTree(_params) {
  return request({
    url: '/admin/dept/tree',
    method: 'get',
    params: _params
  })
}
