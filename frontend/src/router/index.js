import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

/* Layout */
import Layout from '@/layout'

/**
 * Note: sub-menu only appear when route children.length >= 1
 * Detail see: https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
 *
 * hidden: true                   if set true, item will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu
 *                                if not set alwaysShow, when item has more than one children route,
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noRedirect           if set noRedirect will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    roles: ['admin','editor']    control the page roles (you can set multiple roles)
    title: 'title'               the name show in sidebar and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar
    breadcrumb: false            if set false, the item will hidden in breadcrumb(default is true)
    activeMenu: '/example/list'  if set path, the sidebar will highlight the path you set
  }
 */

/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all roles can be accessed
 */
export const constantRoutes = [
  {
    path: '/login',
    component: () => import('@/views/login/index'),
    hidden: true
  },

  {
    path: '/404',
    component: () => import('@/views/404'),
    hidden: true
  },


  {
    path: '/',
    component: Layout,
    redirect: '/dashboard',
    children: [{
      path: 'dashboard',
      name: 'Dashboard',
      component: () => import('@/views/dashboard/index'),
      meta: { title: '首页', icon: 'dashboard'}
    }]
  },
  {
  path: '/modify',
  component: Layout,
  hidden: true,
  redirect: '/modify/index',
  children: [{
    path: 'index',
    component: () => import('@/views/admin/modify/index'),
    name: 'modify',
    meta: { title: '修改个人密码', icon: 'user', noCache: true }
  },
  {
    path: 'view',
    component: () => import('@/views/table/view'),
    hidden: true,
    name: 'view',
    meta: { title: '组件测试', icon: 'user' }
  },
  {
    path: 'template',
    component: () => import('@/views/table/template'),
    hidden: true,
    name: 'desc-template',
    meta: { title: '组件和渲染函数', icon: 'user' }
  }
  ]
 }
]

/**
 * asyncRoutes
 * the routes that need to be dynamically loaded based on user roles
 */
export const asyncRoutes = [
  {
  menuId: 1,
  path: '/admin',
  component: Layout,
  name: 'user_manage',
  hidden: false,
  redirect: '/admin/index', // == /admin/user
  alwaysShow: true,
  meta: {
    title: '用户管理',
    icon: 'people'
  },
  children: [
    {
      menuId: 2,
      path: 'index',
      component: () => import('@/views/admin/Index'),
      name: 'user_list',
      meta: {
        title: '用户列表',
        icon: 'user'
      }
    }
  ]
},
{
  menuId: 3,
  path: '/role',
  component: Layout,
  name: 'permission',
  hidden: false,
  redirect: '/role/index',
  alwaysShow: true,
  meta: {
    title: '权限管理',
    icon: 'people'
  },
  children: [
    {
      menuId: 4,
      path: 'index',
      component: () => import('@/views/role/Index'),
      name: 'role_index',
      meta: {
        title: '角色管理',
        icon: 'user'
      }
    },
    {
      menuId: 5,
      path: 'permission',
      component: () => import('@/views/permission/Index'),
      name: 'permission_index',
      meta: {
        title: '功能管理',
        icon: 'user'
      }
    }
  ]
},
{
  path: '/log',
  component: Layout,
  name: 'log',
  hidden: false,
  redirect: '/log/login',
  alwaysShow: true,
  meta: {
    title: '日志管理',
    icon: 'edit'
  },
  children: [
    {
      path: 'login',
      component: () => import('@/views/log/Login'),
      name: 'log_login',
      meta: {
        title: '系统日志',
        icon: 'user'
      }
    },
    {
      path: 'work',
      component: () => import('@/views/log/Work'),
      name: 'log_work',
      meta: {
        title: '操作日志',
        icon: 'user'
      }
    }
  ]
},
{
  path: '/order',
  component: Layout,
  redirect: '/order/index',
  name: 'order',
  alwaysShow: true,
  meta: {
    role: ['admin'],
    icon: 'edit',
    title: '业务事例',
    roles: ['orders.index']
  },
  children: [
    {
      path: 'index',
      name: 'order_index',
      component:  () => import('@/views/order/index'),
      meta: {
        roles: ['orders.index'],
        title: '订单列表',
        icon: 'tab'
      }
    }]
  },
{
  path: '/app',
  component: Layout,
  redirect: '/app/chat',
  name: 'app',
  alwaysShow: true,
  meta: {
    role: ['admin'],
    icon: 'edit',
    title: '应用事例',
    roles: ['chat.index', 'chat.kefu']
  },
  children: [
    {
      path: 'chat',
      name: 'chat_index',
      component:  () => import('@/views/chat/index'),
      meta: {
        roles: ['chat.index'],
        title: '聊天室',
        icon: 'tab'
      }
    },
    {
      path: 'kefu',
      name: 'chat_kefu',
      component:  () => import('@/views/chat/kefu'),
      meta: {
        roles: ['chat.kefu'],
        title: '客服',
        icon: 'tab'
      }
    },
  ]
  },
  // 404 page must be placed at the end !!!
  { path: '*', redirect: '/404', hidden: true }
]

const createRouter = () => new Router({
  mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRoutes
})

const router = createRouter()

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router
