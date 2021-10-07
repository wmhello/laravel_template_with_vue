import Vue from "vue";
import Router from "vue-router";

Vue.use(Router);

/* Layout */
import Layout from "@/layout";

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
    path: "/login",
    component: () => import("@/views/login/index"),
    hidden: true
  },
  {
    path: "/oauth/github/callback",
    component: () => import("@/views/oauth/github"),
    hidden: true
  },
  {
    path: "/oauth/qq/callback",
    component: () => import("@/views/oauth/qq"),
    hidden: true
  },
  {
    path: "/oauth/gitee/callback",
    component: () => import("@/views/oauth/gitee"),
    hidden: true
  },
  {
    path: "/404",
    component: () => import("@/views/404"),
    hidden: true
  },
  {
    path: "/",
    component: Layout,
    redirect: "/dashboard",
    children: [
      {
        path: "dashboard",
        name: "Dashboard",
        component: () => import("@/views/dashboard/index"),
        meta: { title: "面板", icon: "dashboard", affix: true }
      }
    ]
  },
  {
    path: "/personal",
    component: Layout,
    redirect: "/personal/index",
    hidden: true,
    children: [
      {
        path: "index",
        name: "personal_index",
        component: () => import("@/views/info/index"),
        hidden: true,
        meta: {
          title: "个人信息修改",
          icon: "dashboard"
        }
      }
    ]
  }
];

/**
 * asyncRoutes
 * the routes that need to be dynamically loaded based on user roles
 */
export const asyncRoutes = [
  {
    path: "/admin",
    component: Layout,
    meta: {
      title: "系统管理",
      icon: "category",
      roles: ["admin.index", "roles.index", "modules.index"]
    },
    redirect: "/admin/index",
    alwaysShow: true,
    children: [
      {
        path: "index",
        name: "admin_index",
        component: () => import("@/views/system/admin/index"),
        meta: {
          title: "用户管理",
          roles: ["admin.index"],
          icon: "dashboard"
        }
      },
      {
        path: "role",
        name: "role_index",
        component: () => import("@/views/system/role/index"),
        meta: {
          title: "角色管理",
          roles: ["roles.index"],
          icon: "form"
        }
      },
      {
        path: "permission",
        name: "permission_index",
        component: () => import("@/views/system/module/index"),
        meta: {
          title: "模块与功能管理",
          roles: ["modules.index"],
          icon: "lock"
        }
      }
    ]
  },
  {
    path: "/contents",
    component: Layout,
    meta: {
      title: "内容管理",
      icon: "pdf",
      roles: ["carousels.menu", "articles.menu", "article_categories.menu"]
    },
    redirect: "/content/carousel",
    alwaysShow: true,
    children: [
      {
        path: "carousel",
        name: "carousel_index",
        component: () => import("@/views/content/carousel/index"),
        meta: {
          title: "轮播图管理",
          roles: ["carousels.menu"],
          icon: "list"
        }
      },
      {
        path: "article_category",
        name: "article_category_index",
        component: () => import("@/views/content/article_category/index"),
        meta: {
          title: "文章类型管理",
          roles: ["article_categories.menu"],
          icon: "form"
        }
      },
      {
        path: "article",
        name: "article_index",
        component: () => import("@/views/content/article/index"),
        meta: {
          title: "文章管理",
          roles: ["articles.menu"],
          icon: "article"
        }
      }
    ]
  },
  {
    path: "/app",
    component: Layout,
    redirect: "/app/chat",
    name: "app",
    alwaysShow: true,
    meta: {
      role: ["admin"],
      icon: "edit",
      title: "应用事例",
      roles: ["chat.menu", "kefu.menu"]
    },
    children: [
      {
        path: "chat",
        name: "chat_index",
        component: () => import("@/views/chat/index"),
        meta: {
          roles: ["chat.menu"],
          title: "聊天室",
          icon: "tab"
        }
      },
      {
        path: "kefu",
        name: "chat_kefu",
        component: () => import("@/views/chat/kefu"),
        meta: {
          roles: ["kefu.menu"],
          title: "客服",
          icon: "tab"
        }
      }
    ]
  },

  // 404 page must be placed at the end !!!
  { path: "*", redirect: "/404", hidden: true }
];

const createRouter = () =>
  new Router({
    mode: "history", // require service support
    scrollBehavior: () => ({ y: 0 }),
    routes: constantRoutes
  });

const router = createRouter();

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter();
  router.matcher = newRouter.matcher; // reset router
}

export default router;
