import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'home',
    component: function() {
		return import(/* webpackChunkName: "about" */ '../views/Home.vue')
	} 
  },
  {
    path: '/about',
    name: 'about',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: function() {
		return import(/* webpackChunkName: "about" */ '../views/About.vue')
	} 
  },
  {
	  path: '/img',
	  name: 'img',
	  component: function() {
		  return import(/* webpackChunkName: "img" */ '../views/Img.vue')
	  } 
  },
  {
	  path: '/pay/index',
	  name: 'pay',
	  component: function () {
		return import(/* webpackChunkName: "img" */ '../views/Pay.vue')
		  
	  },
	}  
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
