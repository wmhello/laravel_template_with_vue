<template>
<div id="config">
  <el-form id="toolbar" :inline="true" :model="searchForm" class="demo-form-inline">
    <el-form-item >
      <el-input v-model="searchForm.user_name"  @keyup.enter.native="find" placeholder="请输入登录名">
      </el-input>
    </el-form-item>
    <el-form-item >
      <el-select type="text" v-model="searchForm.type"  clearable placeholder="请选择时间类型">
        <el-option label="登录" value="login"></el-option>
        <el-option label="退出" value="logout"></el-option>
      </el-select>
    </el-form-item>
    <el-form-item >
      <el-date-picker
        v-model="searchForm.created_at"
        type="date"
        placeholder="选择日期">
      </el-date-picker>
    </el-form-item>
    <el-form-item>
      <el-button @click="find()" plain>查询</el-button>
      <el-button type="info" @click="findReset()" plain>重置</el-button>
    </el-form-item>
  </el-form>
  <div id="datagrid">
    <!-- 信息表 -->
    <el-table size='mini'  :data="tableData"   border @selection-change="selectChange" v-loading="loading">
      <el-table-column label="标识" prop="id" width="100">
      </el-table-column>
      <el-table-column label="用户昵称" prop="user_name" width="150">
      </el-table-column>
      <el-table-column label="操作地址" prop="ip" width="200">
      </el-table-column>
      <el-table-column label="时间" prop="created_at" width="200">
      </el-table-column>
      <el-table-column label="操作类型" width="130">
        <template slot-scope="scope">
          <span>{{scope.row.type|filterType}}</span>
        </template>
      </el-table-column>
      <el-table-column label="说明" prop="desc">
      </el-table-column>
    </el-table>
    <!-- 分页 -->
    <el-row class="page">
      <el-col :span="2" :offset="1">
      </el-col>
      <el-col :span="20">
        <el-pagination background @current-change="pagination" @size-change="sizeChange" :current-page.sync="current_page" :page-sizes="[10,20,25,50]" layout="total,sizes,prev, pager, next" :page-size.sync="pageSize" :total="total">
        </el-pagination>
      </el-col>
    </el-row>
  </div>
</div>
</template>

<script>
import CURD from '@/minix/curd'
import UPLOAD from '@/minix/upload'
import {
  getInfo,
} from "@/api/log";

import {
  SearchModel,
} from '@/model/log'
import {
  config
} from "@/config/index"
import {
  Tools
} from "@/views/utils/Tools"


export default {
  name: 'log_login',
  mixins: [CURD],
  data() {
    return {
      searchForm: new SearchModel(),
      form: [],
      rules: [],
      searchModel: SearchModel,
      model: [],
      tools: Tools,
      showMenu: false,
      curd: {
        getInfo: getInfo || function() {},
        getInfoById: function() {},
        updateInfo: function() {},
        addInfo: function() {},
        deleteInfoById: function() {},
        deleteAll: function() {},
      }
    }
  },

  created() {
    this.fetchData()
  },
  filters: {
    filterType(value) {
      let status = {
        'login': '登录',
        'logout': '退出'
      }
      return status[value]
    }
  },
  methods: {
    find(event) {
      this.$nextTick()
        .then(() => {
          this.fetchData();
        })
    },
  }
}
</script>

<style lang="scss">
@import "@/styles/app-main.scss";
#config .el-input {
    // width: 200px;
    // margin-left: 10px;
}
#datagrid {
    .toolbar {
        margin-bottom: 10px;
        .tips{
           line-height: 40px;
           height: 40px;
           margin:0px;
           padding:0px 10px;
        }
    }
    .page {
        margin-top: 10px;
    }
}
.el-button--mini.is-round,
.toolbar-config .el-button--mini {
    padding: 10px;
}
.toolbar-config .el-button+.el-button {
    margin-left: 5px;
}
.tips span{
  color: #f44;
}

</style>
