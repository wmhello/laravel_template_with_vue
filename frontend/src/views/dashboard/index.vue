<template>
  <div class="dashboard-container">
    <div class='dashboard-text'>name:{{name}}</div>
    <div class='dashboard-text'>role:<span v-for='role in roles' :key='role'>{{role}}</span></div>
    <el-button @click="getSession()">发送请求学期列表的数据</el-button>
    <el-button @click="getLeader()" v-has="'leader.index'">发送请求行政管理的数据</el-button>

  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { getInfo as getInfoBySession } from "@/api/session";
import { getInfo as getInfoByLeader } from "@/api/leader";
import {Tools} from "@/views/utils/Tools";

export default {
  name: 'dashboard',
  data() {
    return {
      sessionInfo: [],
      leaderInfo: []
    }
  },
  computed: {
    ...mapGetters([
      'name',
      'roles'
    ])
  },
  methods: {
     getSession() {
       getInfoBySession().then(res => {
           this.sessionInfo = res.data;
       }).catch(err => {
           console.log(error)
       })

     },
     getLeader() {
       getInfoByLeader().then(res => {
           this.leaderInfo = res.data;
       }).catch(err => {
            this.$message({
              type: 'error',
              message: err.response.data.message
            })
       })
     }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.dashboard {
  &-container {
    margin: 30px;
  }
  &-text {
    font-size: 30px;
    line-height: 46px;
  }
}
</style>
