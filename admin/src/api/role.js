import request from '@/utils/request'

export function fetchRoleListByDeptId(deptId) {
  return request({
    url: '/admin/role/listByDeptId/' + deptId,
    method: 'get'
  })
}

export function fetchRoleList() {
  return request({
    url: '/admin/role/list',
    method: 'get'
  })
}
