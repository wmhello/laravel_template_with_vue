import Mock from 'mockjs'
import loginAPI from './login'
import userAPI from './user'
import roleAPI from './role'
import deptAPI from './dept'
// Mock.setup({
//   timeout: '350-600'
// })

// 登录相关
Mock.mock(/\/auth\/oauth\/token/, 'post', loginAPI.loginByUsername)
Mock.mock(/\/auth\/removeToken/, 'post', loginAPI.logout)
Mock.mock(/\/admin\/api\/info\.*/, 'get', loginAPI.getUserInfo)
Mock.mock(/\/admin\/api\/userTree/, 'get', loginAPI.getUserTree)

// 用户模块
Mock.mock(/\/admin\/user\/list\.*/, 'get', userAPI.fetchUserList)
Mock.mock(/\/admin\/user\/del\/1/, 'post', userAPI.delByUserId)
Mock.mock(/\/admin\/user\/del\/2/, 'post', userAPI.delByUserId2)
Mock.mock(/\/admin\/user\/find\/4/, 'get', userAPI.fetchUserByUserId)
Mock.mock(/\/admin\/user\/addUser/, 'post', userAPI.addUser)
Mock.mock(/\/admin\/user\/updateUser/, 'post', userAPI.updateUser)

// 角色部门相关
Mock.mock(/\/admin\/role\/listByDeptId\/1/, 'get', roleAPI.listByDeptId)
Mock.mock(/\/admin\/role\/listByDeptId\/2/, 'get', roleAPI.listByDeptId)
Mock.mock(/\/admin\/role\/listByDeptId\/3/, 'get', roleAPI.listByDeptId)
Mock.mock(/\/admin\/role\/listByDeptId\/4/, 'get', roleAPI.listByDeptId)
Mock.mock(/\/admin\/role\/listByDeptId\/5/, 'get', roleAPI.listByDeptId)
Mock.mock(/\/admin\/role\/list/, 'get', roleAPI.fetchRoleList)

// 部门相关
Mock.mock(/\/admin\/dept\/tree/, 'get', deptAPI.deptTree)

export default Mock
