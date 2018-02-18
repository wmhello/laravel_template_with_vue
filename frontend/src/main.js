// import Vue from 'vue'
// import ElementUI from 'element-ui'
// //import 'element-ui/lib/theme-chalk/index.css'
// import 'element-theme-default'
// import locale from 'element-ui/lib/locale/lang/en'
// import App from './App'
// import router from './router'
// import store from './store'
// import '@/icons' // icon
// import '@/permission' // 权限

// Vue.use(ElementUI, { locale })

// Vue.config.productionTip = false

// window.VM = new Vue({
//   el: '#app',
//   router,
//   store,
//   template: '<App/>',
//   components: { App }
// })


import Vue from 'vue'

import 'normalize.css/normalize.css'// A modern alternative to CSS resets

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import locale from 'element-ui/lib/locale/lang/zh-CN'

import '@/styles/index.scss' // global css

import App from './App'
import router from './router'
import store from './store'

import '@/icons' // icon
import '@/permission' // permission control

Vue.use(ElementUI, { locale })

Vue.config.productionTip = false

Vue.prototype.$_has = function (feature) {
  let resources = [];
  let permission = true;
  let routeName = store.state.user.routeName
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

new Vue({
  el: '#app',
  router,
  store,
  template: '<App/>',
  components: { App }
})
