const userList = { 'msg': 'success', 'code': 0, 'data': { 'total': 4, 'pageNo': 0, 'pageNum': 20, 'list': [{ 'userId': 4, 'username': 'test4', 'password': '$2a$10$10ntdT66NtRvsw1A0b3veu1g/JE0XGwlVHhS3i2FztgHNmOa/j/oi', 'openId': null, 'mobile': null, 'picUrl': null, 'statu': 0, 'createTime': 1520577723000, 'updateTime': 1520577723000, 'roleList': [{ 'roleId': 2, 'roleName': '测试Test', 'roleCode': 'ROLE_TEST', 'roleDesc': '测试', 'createTime': 1520524560000, 'updateTime': 1520524560000, 'statu': 0 }] }, { 'userId': 3, 'username': 'test2', 'password': '$2a$10$1QLEolaGWQmXGf7woa8G1.UYT17YV3TWPG/WK9Xlc8xP70prErpsC', 'openId': null, 'mobile': null, 'picUrl': null, 'statu': 0, 'createTime': 1520417559000, 'updateTime': 1520430320000, 'roleList': [{ 'roleId': 1, 'roleName': '超级管理员', 'roleCode': 'ROLE_ADMIN', 'roleDesc': '超级管理员', 'createTime': 1520524560000, 'updateTime': 1520524560000, 'statu': 0 }] }, { 'userId': 2, 'username': 'test', 'password': '$2a$10$bvIjvNMsFP0d.wkF2yb9puXn00.q086DInosQsCjXIA9zDINbvIBq', 'openId': null, 'mobile': null, 'picUrl': 'https://avatars0.githubusercontent.com/u/21272196?s=40&v=4', 'statu': 0, 'createTime': 1519727098000, 'updateTime': 1520430360000, 'roleList': [{ 'roleId': 2, 'roleName': '测试Test', 'roleCode': 'ROLE_TEST', 'roleDesc': '测试', 'createTime': 1520524560000, 'updateTime': 1520524560000, 'statu': 0 }] }, { 'userId': 1, 'username': 'admin', 'password': '$2a$10$yfpKd9hRpeloL3MTZH4e9ugrsS/rQp0KoVGpXWIBOR4UniBJFj9Sy', 'openId': null, 'mobile': '', 'picUrl': 'https://avatars0.githubusercontent.com/u/21272196?s=40&v=4', 'statu': 0, 'createTime': 1509263113000, 'updateTime': 1520477219000, 'roleList': [{ 'roleId': 1, 'roleName': '超级管理员', 'roleCode': 'ROLE_ADMIN', 'roleDesc': '超级管理员', 'createTime': 1520524560000, 'updateTime': 1520524560000, 'statu': 0 }] }] }}

export default {
  fetchUserList: config => {
    return userList
  },
  delByUserId: config => {
    return { 'msg': 'success', 'code': 0, 'data': true }
  },
  delByUserId2: config => {
    return { 'msg': 'success', 'code': 0, 'data': false }
  },
  addUser: config => {
    return { 'msg': 'success', 'code': 0, 'data': true }
  },
  fetchUserByUserId: config => {
    return { 'userId': 1, 'username': 'admin', 'password': '$2a$10$yfpKd9hRpeloL3MTZH4e9ugrsS/rQp0KoVGpXWIBOR4UniBJFj9Sy', 'statu': 0, 'picUrl': 'https://avatars0.githubusercontent.com/u/21272196?s=40&v=4', 'roleList': [{ 'roleId': 1, 'roleName': '超级管理员', 'roleCode': 'ROLE_ADMIN', 'roleDesc': '超级管理员', 'statu': 0 }] }
  },
  updateUser: config => {
    return { 'msg': 'success', 'code': 0, 'data': true }
  }
}
