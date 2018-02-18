<template>
  <scroll-bar>
    <el-menu mode="vertical" unique-opened :default-active="$route.path" :collapse="isCollapse"  background-color="#304156" text-color="#fff" active-text-color="#409EFF">
      <sidebar-item :routes="routes"></sidebar-item>
    </el-menu>
  </scroll-bar>
</template>

<script>
import { mapGetters } from 'vuex'
import SidebarItem from './SidebarItem'
import ScrollBar from '@/components/ScrollBar'

export default {
  components: { SidebarItem, ScrollBar },
  computed: {
    ...mapGetters([
      'sidebar'
    ]),
    routes() {
      // return this.$router.options.routes
      return this.$store.state.permission.routers
    },
    isCollapse() {
      //return false;
     //return !this.sidebar.opened
     return ! this.$store.state.app.sidebar.opened
    }
  }
}
</script>
