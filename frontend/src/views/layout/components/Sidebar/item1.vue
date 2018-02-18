<template>
  <div class="menu-wrapper">
    <template v-for="item in routes">
      <router-link v-if="!item.hidden&&item.noDropdown&&item.children.length>0" :to="item.path+'/'+item.children[0].path">
        <el-menu-item :index="item.path+'/'+item.children[0].path">
         <!-- <svg-icon v-if="item.children[0].meta&&item.children[0].meta.icon" :icon-class="item.children[0].meta.icon"></svg-icon> -->
         <svg-icon v-if="item.icon" :icon-class="item.icon"></svg-icon>
          <!-- <icon-svg v-if='item.icon' :icon-class="item.icon" /> {{item.children[0].name}} -->
        </el-menu-item>
      </router-link>
      <el-submenu :index="item.name" v-if="!item.noDropdown&&!item.hidden">
        <template slot="title">
             <svg-icon v-if="item.icon" :icon-class="item.icon"></svg-icon>
            <span>{{item.name}}</span>
            <!-- <span v-if="item.meta&&item.meta.title">{{item.meta.title}}</span> -->
          <!-- <icon-svg v-if='item.icon' :icon-class="item.icon" /> {{item.name}} -->
        </template>
        <template v-for="child in item.children" v-if='!child.hidden'>
          <sidebar-item class='menu-indent' v-if='child.children&&child.children.length>0' :routes='[child]'> </sidebar-item>
          <router-link v-else class="menu-indent" :to="item.path+'/'+child.path">
            <el-menu-item :index="item.path+'/'+child.path">
              {{child.name}}
            </el-menu-item>
          </router-link>
        </template>
      </el-submenu>
    </template>
  </div>
</template>

<script>
export default {
  name: 'SidebarItem',
  props: {
    routes: {
      type: Array
    }
  }
}
</script>