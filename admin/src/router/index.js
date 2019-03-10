import Vue from 'vue'
import Router from 'vue-router'
const _import = require('./_import_' + process.env.NODE_ENV)
// in development-env not use lazy-loading, because lazy-loading too many pages will cause webpack hot update too slow. so only in production use lazy-loading;
// detail: https://panjiachen.github.io/vue-element-admin-site/#/lazy-loading

Vue.use(Router)

/* Layout */
import Layout from '../views/layout/Layout'

/** note: submenu only apppear when children.length>=1
*   detail see  https://panjiachen.github.io/vue-element-admin-site/#/router-and-nav?id=sidebar
**/

/**
* hidden: true                   if `hidden:true` will not show in the sidebar(default is false)
* alwaysShow: true               if set true, will always show the root menu, whatever its child routes length
*                                if not set alwaysShow, only more than one route under the children
*                                it will becomes nested mode, otherwise not show the root menu
* redirect: noredirect           if `redirect:noredirect` will no redirct in the breadcrumb
* name:'router-name'             the name is used by <keep-alive> (must set!!!)
* meta : {
    roles: ['admin','editor']     will control the page roles (you can set multiple roles)
    title: 'title'               the name show in submenu and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar,
    noCache: true                if true ,the page will no be cached(default is false)
  }
**/
export const constantRouterMap = [
  { path: '/login', component: _import('login/index'), hidden: true },
  { path: '/authredirect', component: _import('login/authredirect'), hidden: true },
  { path: '/404', component: _import('errorPage/404'), hidden: true },
  { path: '/401', component: _import('errorPage/401'), hidden: true },
  {
    path: '',
    component: Layout,
    redirect: 'dashboard',
    children: [{
      path: 'dashboard', component: _import('dashboard/index'), name: 'dashboard',
      meta: { title: 'dashboard', icon: 'dashboard', noCache: true }
    }]
  },
  {
    path: '/modify',
    component: Layout,
    hidden: true,
    redirect: '/modify/index',
    children: [{
      path: 'index', component: _import('admin/modify/index'), name: 'modify',
      meta: { title: 'modify', icon: 'user', noCache: true }
    }]
  }
]

export default new Router({
  // mode: 'history', // require service support
  // base: '/mall/',
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRouterMap
})

export const asyncRouterMap = [
  {
    menuId: 1,
    path: '/admin',
    component: Layout,
    name: 'menu_manage',
    hidden: false,
    redirect: 'noredirect', // == /admin/user
    meta: {
      title: 'menu_manage',
      icon: 'people'
    },
    children: [
      {
        menuId: 2,
        path: 'user',
        component: _import('admin/user/index'),
        name: 'menu_user',
        meta: {
          title: 'menu_user',
          icon: 'user'
        }
      },
      { menuId: 3, hidden: true, path: 'role', component: _import('admin/role/index'), name: 'menu_role', meta: { title: 'menu_role', icon: 'role' }}
    ]
  },
  {
    menuId: 4,
    path: '/example',
    component: Layout,
    name: 'menu_example',
    hidden: false,
    redirect: 'noredirect', // == /admin/user
    meta: {
      title: 'menu_example',
      icon: 'example'
    },
    children: [
      {
        menuId: 5,
        path: 'map',
        component: _import('example/map/index'),
        name: 'menu_map',
        meta: {
          title: 'menu_map',
          icon: 'table'
        }
      },
      {
        menuId: 6,
        path: 'orther',
        component: _import('example/orther/index'),
        name: 'menu_map',
        hidden: true,
        meta: {
          title: 'menu_map',
          icon: 'example'
        }
      }
    ]
  },
  { path: '*', redirect: '/404', hidden: true }
]
