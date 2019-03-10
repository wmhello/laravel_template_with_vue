export default {
  listByDeptId: config => {
    return { 'msg': 'success', 'code': 0, 'data': [{ 'roleId': 1, 'roleName': 'admin', 'roleCode': 'ROLE_ADMIN', 'roleDesc': '超级管理员', 'createTime': 1509263151000, 'updateTime': 1517987558000, 'statu': '0' }] }
  },
  fetchRoleList: config => {
    return { 'msg': 'success', 'code': 0, 'data': [{ 'roleId': 1, 'roleName': '超级管理员', 'roleCode': 'ROLE_ADMIN', 'roleDesc': '超级管理员', 'createTime': 1520524560000, 'updateTime': 1520524560000, 'statu': 0 }, { 'roleId': 2, 'roleName': '测试Test', 'roleCode': 'ROLE_TEST', 'roleDesc': '测试', 'createTime': 1520524560000, 'updateTime': 1520524560000, 'statu': 0 }] }
  }
}
