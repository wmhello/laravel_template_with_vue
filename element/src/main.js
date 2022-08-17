/*
 * @Author: wmhello 871228582@qq.com
 * @Date: 2021-10-12 22:49:35
 * @LastEditors: wmhello 871228582@qq.com
 * @LastEditTime: 2022-08-17 11:41:20
 * @FilePath: \element\src\main.js
 * @Description: 这是默认设置,请设置`customMade`, 打开koroFileHeader查看配置 进行设置: https://github.com/OBKoro1/koro1FileHeader/wiki/%E9%85%8D%E7%BD%AE
 */
import Vue from "vue";

import "normalize.css/normalize.css"; // A modern alternative to CSS resets

import ElementUI from "element-ui";
import "element-ui/lib/theme-chalk/index.css";
import locale from "element-ui/lib/locale/lang/zh-CN"; // lang i18n

import "@/styles/index.scss"; // global css

import App from "./App";
import store from "./store";
import router from "./router";

import "@/icons"; // icon
import "@/permission"; // permission control
import permission from "@/directive/permission/index.js"; // 权限指令
Vue.use(permission);

import elDragDialog from '@/directive/el-drag-dialog/index.js'  // 可以拖动的对话框
Vue.use(elDragDialog)

/**
 * If you don't want to use mock-server
 * you want to use MockJs for mock api
 * you can execute: mockXHR()
 *
 * Currently MockJs will be used in the production environment,
 * please remove it before going online! ! !
 */
// import { mockXHR } from '../mock'
// if (process.env.NODE_ENV === 'production') {
//   mockXHR()
// }

// set ElementUI lang to EN
Vue.use(ElementUI, { locale });

Vue.config.productionTip = false;
//代码编辑器

import { codemirror } from 'vue-codemirror'
import 'codemirror/lib/codemirror.css'
Vue.use(codemirror)



import '@/utils/websocket.js'

new Vue({
  el: "#app",
  router,
  store,
  render: (h) => h(App)
});
