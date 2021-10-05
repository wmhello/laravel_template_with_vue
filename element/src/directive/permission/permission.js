import store from '@/store'
import permission from '.'

export default {
  inserted(el, binding, vnode) {
    const { value } = binding
    const roles = store.getters && store.getters.roles
    const permissions = store.state.user.permissions
    
    if (value && value instanceof Array && value.length > 0) {
      const permissionRoles = value 
      let hasPermission = false
     
      if (roles.includes('admin')){
         hasPermission = true
      } else {
        hasPermission = permissions.some(role => {
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
