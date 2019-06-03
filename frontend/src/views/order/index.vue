<template>
<div id="config">
  <el-form id="toolbar" :inline="true" :model="searchForm" class="demo-form-inline">
    <el-form-item >
      <el-input v-model="searchForm.order_number"  @keyup.enter.native="find" placeholder="请输入订单编号">
      </el-input>
    </el-form-item>
    <el-form-item >
      <el-input type="text" v-model="searchForm.merchant_number"  @keyup.enter.native="find" placeholder="请输入商户编号">
      </el-input>
    </el-form-item>
    <el-form-item >
      <el-input type="text" v-model="searchForm.merchant_name"  @keyup.enter.native="find" placeholder="请输入商户名称">
      </el-input>
    </el-form-item>
    <el-form-item >
      <el-select  v-model="searchForm.status" clearable placeholder="请选择订单状态">
        <el-option label="未发" value="1"></el-option>
        <el-option label="已发未完" value="2"></el-option>
        <el-option label="已发完" value="3"></el-option>
      </el-select>

    </el-form-item>
    <el-form-item>
      <el-button @click="find()" plain>查询</el-button>
      <el-button type="info" @click="findReset()" plain>重置</el-button>
    </el-form-item>
  </el-form>
  <div id="datagrid">
    <div class="toolbar" v-permission="['orders.store']">
      <el-row>
        <el-col :span="6">
          <el-button plain icon="el-icon-plus" @click="add()" v-permission="['orders.store']">添加订单信息</el-button>
       </el-col>
        <el-col :span="18" >
          <p class="tips" v-html="summary"></p>
        </el-col>
    </el-row>
    <el-alert
      title="通过v-permission指令，在普通用户角色下，订单的删除按钮被删除，因为普通用户没有删除操作的权限，请注意查看前端代码的书写格式"
      type="error">
    </el-alert>
    </div>
    <!-- 信息表 -->
    <el-table v-contextmenu:contextmenu size='mini' @row-contextmenu='contextHandle' :data="tableData" :row-class-name="tableRowClassName" :row-style="selectedHighlight" border @row-dblclick="dblHandle" @row-click="rowHandle" @select-all="selectChange"
      @selection-change="selectChange" v-loading="loading">
      <el-table-column type="selection" width="55">
      </el-table-column>
      <el-table-column label="订单编号" prop="order_number" width="150">
      </el-table-column>
      <el-table-column label="商户编号" prop="merchant_number" width="150">
      </el-table-column>
      <el-table-column label="商户名称" prop="merchant_name" width="200">
      </el-table-column>
      <el-table-column label="购买时间" width="130">
        <template slot-scope="scope">
          <span>{{scope.row.order_time|filterOrderTime}}</span>
        </template>
      </el-table-column>
      <el-table-column label="订单状态" width="100">
        <template slot-scope="scope">
          <el-tag type="success" v-if="scope.row.status===3">{{scope.row.status|filterStatus}}</el-tag>
          <el-tag type="danger" v-if="scope.row.status===1">{{scope.row.status|filterStatus}}</el-tag>
          <el-tag type="info" v-if="scope.row.status===2">{{scope.row.status|filterStatus}}</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="备注" prop="remark" width="100">
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-tooltip content="编辑" placement="right-end" effect="light">
            <el-button plain v-permission="['orders.show']" icon="el-icon-edit" type="primary" size="small" @click="edit(scope.row)"></el-button>
          </el-tooltip>
          <el-tooltip content="编辑" placement="right-end" effect="light">
            <el-button v-permission="['orders.destory']" plain icon="el-icon-delete"   type="danger" size="small"   @click="del(scope.row,'删除订单，将删除订单对应的所有产品和包裹信息，是否继续')"></el-button>
          </el-tooltip>
        </template>
      </el-table-column>
    </el-table>
    <!-- 分页 -->
    <el-row class="page">
      <el-col :span="2" :offset="1">
        <!-- <el-button type="danger" plain @click="delAll()">删除选择</el-button> -->
      </el-col>
      <el-col :span="20">
        <el-pagination background @current-change="pagination" @size-change="sizeChange" :current-page.sync="current_page" :page-sizes="[10,20,25,50]" layout="total,sizes,prev, pager, next" :page-size.sync="pageSize" :total="total">
        </el-pagination>
      </el-col>
    </el-row>
  </div>

  <el-dialog title="订单管理" center  :visible.sync="editDialogFormVisible" :close-on-click-modal="false">
     <el-form :model="form" :rules="rules" ref="ruleForm" label-width="100px">
        <el-row>
           <el-col :span="12">
              <el-form-item label="订单编号" prop="order_number">
                 <el-input v-model="form.order_number" :disabled="isEdit===true" placeholder="请输入订单编号"></el-input>
              </el-form-item>
           </el-col>
           <el-col :span="12">
             <el-form-item label="商户编号" prop="merchant_number" >
                <el-input v-model="form.merchant_number" placeholder="请输入商户编号"></el-input>
             </el-form-item>
           </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item label="商户名称" >
               <el-input v-model="form.merchant_name" placeholder="请输入商户名称"></el-input>
            </el-form-item>
          </el-col>
           <el-col :span="12">
              <el-form-item label="订单日期" >
                <el-date-picker
                  v-model="form.order_time"
                  type="datetime"
                  placeholder="选择日期时间">
                </el-date-picker>
              </el-form-item>
           </el-col>
        </el-row>
        <el-row>
           <el-col :span="24">
              <el-form-item label="备注" prop="remark">
                <el-input v-model="form.remark" placeholder="请输入备注"></el-input>
              </el-form-item>
           </el-col>
        </el-row>
     </el-form>
     <div slot="footer" class="dialog-footer">
        <el-button type="primary"  @click="save()">确 定</el-button>
        <el-button @click="cancel()">取 消</el-button>
     </div>
  </el-dialog>

  <v-contextmenu ref="contextmenu">
    <v-contextmenu-item @click="add">新增订单</v-contextmenu-item>
    <v-contextmenu-item :divider='true'></v-contextmenu-item>
    <v-contextmenu-item @click="editMenuHandle">修改订单</v-contextmenu-item>
    <v-contextmenu-item @click="deleteHandle">删除定单</v-contextmenu-item>
    <v-contextmenu-item :divider='true' ></v-contextmenu-item>
  </v-contextmenu>

</div>
</template>

<script>
import CURD from '@/minix/curd'
import UPLOAD from '@/minix/upload'
import {
  getInfo,
  getInfoById,
  updateInfo,
  addInfo,
  deleteInfoById,
  deleteAll,
  getSummaryById
} from "@/api/order";

import {
  Model,
  SearchModel,
  Rules
} from '@/model/order'
import {
  config
} from "@/config/index"
import {
  Tools
} from "@/views/utils/Tools"


export default {
  name: 'order_index',
  mixins: [CURD],
  data() {
    return {
      searchForm: new SearchModel(),
      form: new Model(),
      rules: Rules,
      searchModel: SearchModel,
      model: Model,
      tools: Tools,
      showMenu: false,
      curd: {
        getInfo: getInfo || function() {},
        getInfoById: getInfoById || function() {},
        updateInfo: updateInfo || function() {},
        addInfo: addInfo || function() {},
        deleteInfoById: deleteInfoById || function() {},
        deleteAll: deleteAll || function() {},
      },
      searchSelect: 'order_number',
      searchContent: '',
      types: ['无'],
      templateFile: config.site + '/xls/config.xlsx',
      goods: [],
      getIndex: null,
      editDialogFormVisible: false,
      currentRecord: null,
        product_count: 0,
        sum_amount: 0,
        sum_remain: 0
      // item: new Single()
    }
  },

  created() {
    this.fetchData()
  },
  computed: {
    summary(){
      if (this.product_count){
        return `当前订单有产品 <span>${this.product_count} </span>类,  合计 <span>${this.sum_amount} </span>件商品,  已经发送 <span>${this.sum_amount-this.sum_remain} </span>件,  还剩<span>${this.sum_remain}</span>件未发送`
      }  else {
        return ''
      }
    }
  },
  filters: {
    filterOrderTime(value) {
      let tempDate = new Date(value);
      return tempDate.getFullYear() + '-' + (tempDate.getMonth() + 1) + '-' + tempDate.getDate()+ ' ' + tempDate.getHours() + ':' + tempDate.getMinutes() + ':' + tempDate.getSeconds();
    },
    filterStatus(value) {
      let status = ['', '未发', '已发未完', '已发完']
      return status[value]
    }
  },
  methods: {
    showContent(id){
      this.$router.push({path: '/order/'+id+'/products'})
    },
    showPackage(id){
      this.$router.push({path: '/order/'+id+'/package'})
    },
    editMenuHandle(id){
      this.edit({id: this.currentRecord})
    },
    deleteHandle(){
      this.del({id: this.currentRecord},'删除订单，将删除订单对应的所有产品和包裹信息，是否继续')
    },
    find(event) {
      this.$nextTick()
        .then(() => {
          this.fetchData();
        })
    },
    dblHandle(row, column, event) { // 双击编辑
      this.edit(row)
    },
    rowHandle(row, column, event) {  // 单击行
      this.getIndex = row.index // index
      this.getSummary(row.id)
    },
    getSummary(id){
      getSummaryById(id).then(response=>{
        let {product_count,sum_amount,sum_remain} = response.data
        this.product_count = product_count
        this.sum_amount = sum_amount
        this.sum_remain = sum_remain
      })
    },
    contextHandle(row, column, event) {  // 右键单击
      this.getIndex = row.index
      this.currentRecord = row.id;
      this.getSummary(row.id)
    },
    tableRowClassName({
      row,
      rowIndex
    }) {
      //把每一行的索引放进row
      row.index = rowIndex // row.id rowIndex;
    },
    selectedHighlight({
      row,
      rowIndex
    }) {

      if ((this.getIndex) === (rowIndex)) {
        return {
          "background-color": "#CAE1ff"
        };
      }
    },
    testMenu() {
      console.log('test');
    }
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
