
import store from '@/store'

export default{
  inserted(el, binding, vnode) {
    const { value } = binding
    const roles = store.getters && store.getters.roles
    let routeName = store.state.user.permissions
    let  hasPermission = false

    //   // 对admin角色自动显示所有按钮

    if (value && value instanceof Array && value.length > 0) {
      const permissionRoles = value
        if (roles.indexOf('admin') !== -1) {
          hasPermission = true
        } else {
        hasPermission = routeName.some(role => {
            return permissionRoles.includes(role)
          })
        }
      if (!hasPermission) {
        el.parentNode && el.parentNode.removeChild(el)
      }
    } else {
      throw new Error(`need roles! Like v-permission="['admin','editor']"`)
    }
  }
}
