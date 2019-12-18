<template>
  <div :class="classObj" class="app-wrapper">
    <div v-if="device==='mobile'&&sidebar.opened" class="drawer-bg" @click="handleClickOutside" />
    <sidebar class="sidebar-container" />
    <div class="main-container">
      <div :class="{'fixed-header':fixedHeader}">
        <navbar />
       <tags-view  />
      </div>
      <app-main />
    </div>
  </div>
</template>

<script>
import { Navbar, Sidebar, AppMain, TagsView} from './components'
import ResizeMixin from './mixin/ResizeHandler'
import Echo from 'laravel-echo'
import { getToken } from '@/utils/auth'

export default {
  name: 'Layout',
  components: {
    Navbar,
    Sidebar,
    AppMain,
    TagsView
  },
  mixins: [ResizeMixin],
  computed: {
    sidebar() {
      return this.$store.state.app.sidebar
    },
    device() {
      return this.$store.state.app.device
    },
    fixedHeader() {
      return this.$store.state.settings.fixedHeader
    },
    classObj() {
      return {
        hideSidebar: !this.sidebar.opened,
        openSidebar: this.sidebar.opened,
        withoutAnimation: this.sidebar.withoutAnimation,
        mobile: this.device === 'mobile'
      }
    },
    name(){
      return this.$store.state.user.name
    }
  },
    created(){
    // 获取网站域名
    let hostURL = process.env.VUE_APP_BASE_API;
    let start = hostURL.indexOf('//')
    let end = hostURL.lastIndexOf('/')
    let host = hostURL.substring(start + 2, end)   // 获得域名
   
    let token = getToken()

    window.io = require('socket.io-client')
    window.Echo = new Echo({
      auth: {
        headers: {
          Authorization: `Bearer ${token}`
        }
      },
      broadcaster: 'socket.io',
      host: host + ':6001'
    });
    window.Echo.channel('leave.'+ this.name)
      .listen('UserLogin', (e) => {
          this.$alert('当前用户在其它地方已经登录，现在即将退出', '登录警告', {
          confirmButtonText: '确定',
          callback: action => {
            this.$store.dispatch('user/resetToken').then(() => {
              window.location.reload();
            })
          }
        })
      })
  },
  methods: {
    handleClickOutside() {
      this.$store.dispatch('app/closeSideBar', { withoutAnimation: false })
    }
  }
}
</script>

<style lang="scss" scoped>
  @import "~@/styles/mixin.scss";
  @import "~@/styles/variables.scss";

  .app-wrapper {
    @include clearfix;
    position: relative;
    height: 100%;
    width: 100%;
    &.mobile.openSidebar{
      position: fixed;
      top: 0;
    }
  }
  .drawer-bg {
    background: #000;
    opacity: 0.3;
    width: 100%;
    top: 0;
    height: 100%;
    position: absolute;
    z-index: 999;
  }

  .fixed-header {
    position: fixed;
    top: 0;
    right: 0;
    z-index: 9;
    width: calc(100% - #{$sideBarWidth});
    transition: width 0.28s;
  }

  .hideSidebar .fixed-header {
    width: calc(100% - 54px)
  }

  .mobile .fixed-header {
    width: 100%;
  }
</style>
