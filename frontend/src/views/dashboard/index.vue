<template>
  <div class="dashboard-container" id="dashboard-container">

    <el-row :gutter='10'>
      <el-col :span="12">
        <el-card class="box-card" id="introduction">
          <div slot="header" class="clearfix">
            <span>介绍</span>
          </div>
          <el-tabs v-model="activeName2">
              <el-tab-pane label="系统描述" name="first">
                 <p class="desc">
                 本系统结合后端代码，主要是让大家熟悉前后端分离开发的过程，快速过度到前后端分离开发的体系。
                 本系统源码主要适合以下场景：
                  <ol>
                    <li>PC端后台系统</li>
                    <li>web管理系统开发</li>
                    <li>各类API开发与扩展</li>
                  </ol>
                 </p>
              </el-tab-pane>
              <el-tab-pane label=" 技术交流" name="third">
                <table class="dataintable browsersupport">
                  <tbody>
                    <tr>
                      <th>QQ群</th>
                      <th>公众号</th>
                      <th>微信</th>
                      <th>博客</th>
                  </tr>
                  <tr>
                      <td class=""><img width="80" height="80" src="https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/qq_qrcode.jpg" alt="QQ群二维码">
                      <p>
                       <h3>106822531</h3>
                      </p>
                      </td>
                      <td class="">
                        <img src="https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/gzh.jpg" height="80" width="80" alt="QQ二维码"></img>
                        <p><h3>computer_life</h3></p>
                      </td>
                      <td class=""><img src="https://github.com/wmhello/laravel_template_with_vue/raw/master/Screenshots/weixin1.png" alt="微信二维码" width="80" height="80">  <p><h3>xpyzwm</h3></p></td>
                      <td class="">
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAARgAAAEYCAIAAAAI7H7bAAAFz0lEQVR4nO3dS44cKBBAwamR73/l9glYYD+cUIrYjqf+T0idAj4/Pz//AX/n/+kXAN9ASBAQEgSEBAEhQUBIEBASBH6t/sPn8/mXr+OPVXOw1fvdffzdx9n9nG+b+1Wv//XfmxUJAkKCgJAgICQICAkCQoKAkCCwnCOtTM0xqjnD7uOcni9NOf16qt/JK783KxIEhAQBIUFASBAQEgSEBAEhQWB7jrRSzSWm5gavPG+13+n0Pq7Tbvu9WZEgICQICAkCQoKAkCAgJAgICQLZHOkVt81Pduc8r5wLd3p+dRsrEgSEBAEhQUBIEBASBIQEASFB4GvnSKfvKdq1+7zVHOZb72W6jRUJAkKCgJAgICQICAkCQoKAkCCQzZFumzNU57xN7Tuq5ktT9zud/j3c9nuzIkFASBAQEgSEBAEhQUBIEBASBLbnSLedn7Zyet4yNY+aev2nX8/KK783KxIEhAQBIUFASBAQEgSEBAEhQeBz276OytT84bbP8/Q85/Qc6RVWJAgICQJCgoCQICAkCAgJAkKCQDZHOn3u2W37Um7bp3Ta1Pt9ZU5lRYKAkCAgJAgICQJCgoCQICAkCCzPtdv9O301N7htPjB1T9HUOXivOP172P2+rEgQEBIEhAQBIUFASBAQEgSEBIHsfqTd+dLKt96388q9TN967txK9XuwIkFASBAQEgSEBAEhQUBIEBASBLbnSFPzkKl50el7hE4/ztR+pNvmfqf30VmRICAkCAgJAkKCgJAgICQICAkCy/uRXjlfbuX1/Tm3nWs3df7ertPzzBUrEgSEBAEhQUBIEBASBIQEASFBYLkf6bZz1XYf/7b9NqfnV6+839vOzatejxUJAkKCgJAgICQICAkCQoKAkCCw3I+0/B+G7hc6beq8vtO+9fXvOv36rUgQEBIEhAQBIUFASBAQEgSEBIHlfqTT84Tb7hE6fR/R7vNWvvX7Ov05O9cOBggJAkKCgJAgICQICAkCQoLA9n6k7Sc4PH+oTL2eV87Bu+37WpnaN2VFgoCQICAkCAgJAkKCgJAgICQILPcjTZnaB7X776u5SjVvue1zq+ZjFfuR4AFCgoCQICAkCAgJAkKCgJAgsD1HOj1POD1/qM5Pu+0eoalz4V55v7t2368VCQJCgoCQICAkCAgJAkKCgJAgsDzX7pVz2257nF2vfM63uW2uZUWCgJAgICQICAkCQoKAkCAgJAhsz5Gm7uGZ2ge1MnWO3Ld+Pq/83uxHgoOEBAEhQUBIEBASBIQEASFBILsfqZobTJ13tzJ1n88r86LK6ec9/fhWJAgICQJCgoCQICAkCAgJAkKCwHI/0vYDDZ3PNvX6T++T2X3eqf08u26ba1XfuxUJAkKCgJAgICQICAkCQoKAkCCwPUe67Zy6lanHn9o3ddu5fFPPO3UunxUJAkKCgJAgICQICAkCQoKAkCCQ3Y/0yvlvr9wLtOuVOdtpU/M0KxIEhAQBIUFASBAQEgSEBAEhQWB5P9Lu39Gn7tWpTO2bqkzNbaY+n6n3u2JFgoCQICAkCAgJAkKCgJAgICQILOdIt/2dfuW2fTi3Pe/Kbd/v1Dl71eNbkSAgJAgICQJCgoCQICAkCAgJAss50sor+21OnyN32/1Lpx9n5bZ9XFP3UFmRICAkCAgJAkKCgJAgICQICAkC23Oklal7fqrHf+VcuNPztNvmb1PcjwQDhAQBIUFASBAQEgSEBAEhQSCbI93m9Nzj9Lzllcev5lQrpz9/59rBRYQEASFBQEgQEBIEhAQBIUHga+dIu26bb9x2n1Ll9Pua+hysSBAQEgSEBAEhQUBIEBASBIQEgWyOdNscY2qeU82Xdv99NZ+p5mm7Tv9+Tn8vViQICAkCQoKAkCAgJAgICQJCgsD2HOmV+20qU3OV0/t2ps6Lm9qXdXpfkxUJAkKCgJAgICQICAkCQoKAkCDwuW0fEbzIigQBIUFASBAQEgSEBAEhQUBIEPgNlwXqRK+Oxz8AAAAASUVORK5CYII=" alt="" width="80" height="80">
                      <p><h3><a href="https://wmhello.github.io">地址</a></h3></p>
                      </td>
                  </tr>
                </tbody>
                </table>
              </el-tab-pane>
              <el-tab-pane label="教学及其他" name="fourth">
                <p class="desc">本人长期从事有偿教学，如果你想系统的学习web程序开发、微信开发和webapp的开发，
                想成为业界最受欢迎的全栈工程师，可以联系我。学习内容涉及PHP、laravel、javascript、vue.js、es6、前端工程化、SEO优化、前后端分离开发等知识
                </p>
              </el-tab-pane>
           </el-tabs>
        </el-card>
      </el-col>
      <el-col :span="12">
        <el-card class="box-card" >
          <div slot="header" class="clearfix">
            <span>功能进度列表</span>
          </div>
              <el-tabs v-model="task">
                <el-tab-pane label="已完成" name="task-completed">
                  <data-table :list="todoList.completed"></data-table>
                </el-tab-pane>
                <el-tab-pane label="进行中" name="task-doing">
                  <data-table :list="todoList.doing"></data-table>
                </el-tab-pane>
                <el-tab-pane label="计划中" name="task-plan">
                  <data-table :list="todoList.plan"></data-table>
                </el-tab-pane>
            </el-tabs>

        </el-card>
      </el-col>
    </el-row>
    <div style="height: 10px"></div>
    <el-row :gutter='10'>
      <el-col :span="12">
        <el-card class="box-card" ref="log">
          <div slot="header" class="clearfix">
            <span>系统日志</span>
          </div>
          <table class="dataintable log-info">
            <tbody>
              <tr>
                <th>标识</th>
                <th>用户</th>
                <th>操作</th>
                <th>描述</th>
            </tr>
            <tr v-for="item in logs" :key="item.id">
                <td>{{item.id}}</td>
                <td>{{item.user_name}}</td>
                <td>{{item.type}}</td>
                <td>{{item.desc}}</td>
            </tr>
            </tbody>
          </table>
          <div class="page">
            <el-pagination
                background
                @current-change="pagination"
                @size-change="sizeChange"
                :current-page.sync="current_page"
                layout="total,prev, pager, next"
                :page-size.sync="pageSize"
                :total="total">
              </el-pagination>
          </div>
        </el-card>
      </el-col>
      <el-col :span="12">
        <el-card class="box-card">
          <div slot="header" class="clearfix">
            <span>赞助人员列表</span>
          </div>
          <p class="desc">
            程序开源后得到了许多好心人的赞助，有的甚至没有留下名字，所以无法做到每个不漏的列出。感谢你们，你们的认可是我一直进行改进的动力。
          </p>
          <div id="sponsor">
             <el-tag v-for="item in sponsor" :key="item" v-html="item"></el-tag>
          </div>


        </el-card>
      </el-col>
    </el-row>


  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { getInfo as getInfoBySession } from "@/api/session";
import { getLogInfo } from "@/api/dashboard";
import {Tools} from "@/views/utils/Tools";
import DataTable from '@/views/components/DataTable'

export default {
  name: 'dashboard',
  components: {
   DataTable
  },
  data() {
    return {
      sessionInfo: [],
      leaderInfo: [],
      activeName2:'first',
      task:'task-completed',
      tableData: [
       {
          name: '基本构架',
          desc: '完成SPA前端页面搭建',
          date: '2018.2',
          isSuccess:true
       },
       {
          name: '权限控制集成',
          desc: '控制权限到按钮级别',
          date: '2018.3',
          isSuccess: true
       },
       {
          name: '第三方用户登录',
          desc: '集成第三方用户登录功能',
          date: '2018.6',
          isSuccess: true
       },
       {
          name: '日志',
          desc: '集成登录和业务日志业务',
          date: '2018.6',
          isSuccess: true
       },
       {
          name: '首页优化',
          desc: '首页面板的优化',
          date: '2018.6',
          isSuccess: false
       }
      ],
      logs: [],
      current_page: 1,
      total: 0,
      pageSize: 5,
      todoList:{
        completed:[
          {
             id:1,
             name: '基础',
             desc: '后端API，前端SPA结构成型',
             date: '2018.2'
          },
          {
            id:2,
            name: '权限控制',
            desc: '权限集成，权限控制到按钮',
            date: '2018.2'
          },
          {
            id:3,
            name: '短信',
            desc: '系统集成短信通知API',
            date:'2018.6'
          },
          {
            id: 4,
            name: '第三方登录',
            desc: '第三方登录集成到项目',
            date: '2018.6'
          },
          {
            id: 5,
            name: '日志',
            desc: '集成日志功能，能显示日志',
            date: '2018.6'
          }
        ],
        doing:[
          {
            id: 6,
            name: '首页',
            desc: '优化首页面板',
            date: '进行中'
          }
        ],
        plan: [
          {
            id: 7,
            name: 'QQ登录',
            desc: '集成QQ登录到项目',
            date: '计划中'
          },
          {
            id: 8,
            name: '客服',
            desc: '集成客服功能，实现IM通讯',
            date: '计划中'
          },
          {
            id: 9,
            name: '微信',
            desc: '集成微信公众号的基本管理功能',
            date: '计划中'
          }
        ]
      },
      sponsor:['rcyboom','hello','Baoming_Wong', '灯火阑珊',
      '无骑士','Mr.king','河豚','杨威利de红茶', '风--自由','李晓峰',
      '往事如风','黑白', 'A You', '曾欧文', '梦', 'rough',
      'breath呼哈卢','laravel_vue', '刘天承','姜维',
      '王小涛','A.T.', '枫叶', '刘洋','天空的颜色', 'ComeZc',
       'LGY']
    }
  },
  computed: {
    ...mapGetters([
      'name',
      'roles'
    ])
  },
  methods: {
    // 分页相关
    pagination(val) {
      this.current_page = val;
      this.fetchData()
    },
    sizeChange(val) {
       this.pageSize = val;
       this.fetchData()
    },
    fetchData() {
      getLogInfo(this.current_page, this.pageSize).then(res => {
           this.logs = res.data;
           this.total = res.total;
       }).catch(err => {
           console.log(error)
       })
    },
    adjustDom() {
      let doms = document.documentElement.querySelectorAll('.el-card__body');
      for(let i =0, len = doms.length; i<len; i++){
         doms[i].style.cssText="padding-top: 0;"
      }
      let other = document.documentElement.querySelectorAll('.el-card__header');
      for(var t =0, len = other.length; t<len; t++){
         other[t].style.cssText="border-bottom: 1px solid rgba(20, 132, 210, 0.71);"
      }
      var tags = document.documentElement.querySelectorAll('#sponsor .el-tag');
      for(t =0, len = tags.length; t<len; t++){
         tags[t].style.cssText="display: inline-block; margin-right: 5px; margin-bottom: 5px;"
      }
    },
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
  },
  created() {
    let n = this.tableData.length
    if (n>4) {
       for (let i=0;i<n-4;i++){
        this.tableData.shift();
       }
    }
    this.fetchData();
  },
  mounted() {
    this.adjustDom();
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


  .clearfix:before,
  .clearfix:after {
    display: table;
    content: "";
  }
  .clearfix:after {
    clear: both
  }

  .el-card{
    height: 350px;
    border: 1px solid #1e4db95e;
  }
  #dashboard-container .el-card .el-card__header {
     padding-top:10px;
     padding-bottom: 10px;
     border-bottom: 1px solid #0c172fb0 !important;
  }

  .el-table_body-wrapper{
    overflow-y:scroll;
  }
  #introduction .el-card__body{
    padding-top: 10px;
  }

  [data-v-0bfbb486].el-card__body {
    padding: 10px !important;
}
  .desc{
    text-indent: 2em;
    line-height: 1.5;

  }

  table.log-info{
    margin-top: 15px;
    border-collapse: collapse;
  }


  table.browsersupport {
    margin-top: 15px;
    border-collapse: collapse;
  }

  table.dataintable {
    border: 1px solid #aaa;
    width: 100%;
  }

   table.browsersupport th, table.log-info th {
    padding: 0;
    height: 36px;
    vertical-align: middle;
    text-align: center;
    background-color: #F5F5F5;
    border: 1px solid #ddd;
}
   table.dataintable tr:nth-child(even) {
      background-color: #fff;
   }

  table.browsersupport td {
      padding: 0;
      height: 86px;
      width: 86px;
      vertical-align: middle;
      background: #fdfcf8 no-repeat center;
      border: 1px solid #ddd;
  }

  table.log-info td{
      padding: 0;
      height: 36px;
      text-align: center;
      vertical-align: middle;
      background: #fdfcf8 no-repeat center;
      border: 1px solid #ddd;
  }

  table.browsersupport td a:hover{
    color:#00f;
    text-decoration: underline;
  }

  table.browsersupport td {
      text-align: center ;
  }

  .el-tabs__header{
    margin: 0 0 5px;
  }

  .page{
     margin-top: 5px;
     text-align: center;
  }

  .desc .el-tag{
   display: inline-block;
   margin-right: 5px;
   margin-bottom: 5px;
  }
  .el-table .cell{
    line-height: 20px;
  }

</style>
