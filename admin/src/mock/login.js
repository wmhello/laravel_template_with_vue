const userMap = {
  admin: {
    access_token: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE1MjA2MDM2NDMsInVzZXJfbmFtZSI6ImFkbWluIiwiYXV0aG9yaXRpZXMiOlsiUk9MRV9BRE1JTiJdLCJqdGkiOiJkODBjNGVjMC0yZWE0LTRhYTktYTMwNS1hMWQzZTk0NzBhZjUiLCJjbGllbnRfaWQiOiJjb20uZ2l0aHViLmxpdXdlaWp3Iiwic2NvcGUiOlsic2VydmVyIl19.z8dc3gXmJsKI1zUriRDLUnjIbW1j8UX9R1m_S-YsRlw',
    refresh_token: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJhZG1pbiIsInNjb3BlIjpbInNlcnZlciJdLCJhdGkiOiJkODBjNGVjMC0yZWE0LTRhYTktYTMwNS1hMWQzZTk0NzBhZjUiLCJleHAiOjE1MjMxNTI0NDMsImF1dGhvcml0aWVzIjpbIlJPTEVfQURNSU4iXSwianRpIjoiZWEzZTRkOWYtNzg1Yy00ZDQ0LWExM2QtYjY1MjBjNmFhMTY1IiwiY2xpZW50X2lkIjoiY29tLmdpdGh1Yi5saXV3ZWlqdyJ9.s3X7yKk575bJwxLf7w603TD9EBLF8GB8HVqh44ED1Oc'
  }
}

const userInfoMap = { 'msg': 'success', 'code': 0, 'data': { 'user': { 'userId': 1, 'username': 'admin', 'password': '', 'openId': null, 'mobile': '', 'picUrl': 'https://avatars0.githubusercontent.com/u/21272196?s=40&v=4', 'statu': 0, 'createTime': null, 'updateTime': null, 'roleList': [] }, 'permissions': ['user_view', 'user_del', 'user_upd', 'user_add'], 'roles': ['ROLE_ADMIN'] }}

const userTree = [2, 8, 7, 10, 9, 14, 1, 5, 3, 11, 4, 13, 6, 12]

export default {
  loginByUsername: config => {
    return userMap['admin']
  },
  getUserInfo: config => {
    return userInfoMap
  },
  logout: config => {
    // const { accesstoken, refreshToken } = JSON.parse(config.body)
    return { 'msg': 'success', 'code': 0, 'data': true }
  },
  getUserTree: config => {
    return userTree
  }
}
