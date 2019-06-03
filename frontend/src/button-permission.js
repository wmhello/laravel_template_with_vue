import Vue from 'vue'

Vue.prototype.$_has = function (feature) {
  let resources = [];
  let permission = true;
  let routeNamer = store.state.user.permissions
  let roles = store.state.user.roles // 当前用户角色
  // 对admin角色自动显示所有按钮
  if (Array.indexOf(roles, 'admin') !== -1) {
    return true
  }
  //提取权限数组
  if (Array.isArray(feature)) {
  } else {
    // 判断是否有指定的功能权限
    // permission = Array.indexOf(routeName,feature) === -1 ? false : true
    permission = routeName.findIndex(item => item === feature)>=0 ? true : false
  }
  return permission;
}

Vue.directive('has', {
  bind: function (el, binding) {
    if (!Vue.prototype.$_has(binding.value)) {
      el.parentNode.removeChild(el);
    }
  }
});
