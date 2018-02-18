<template>
  <div id="permission">
       <el-form id="toolbar" :inline="true" :model="searchForm" class="demo-form-inline">
          <el-form-item label="功能名称">
            <el-input v-model="searchForm.name" placeholder="请输入功能">
            </el-input>
          </el-form-item>
         <el-form-item label="功能类型">
             <el-select v-model=" searchForm.type" clearable placeholder="功能类型">
                 <el-option label="功能组" value="1"></el-option>
                 <el-option label="功能路由" value="2"></el-option>
             </el-select>
         </el-form-item>
           <el-form-item label="功能组">
               <el-select v-model="searchForm.pid" clearable placeholder="功能组">
                   <el-option v-for="item in permissions" :label="item.name" :value="item.id" :key="item.id"></el-option>
               </el-select>
           </el-form-item>
           <el-form-item>
               <el-button  @click="find()" plain>查询</el-button>
               <el-button type="info" @click="findReset()" plain>重置</el-button>
           </el-form-item>
     </el-form>
  <div id="datagrid">
    <div class="toolbar">
      <el-button  plain icon="el-icon-plus" @click="add()">添加功能</el-button>
      <el-button  plain icon="el-icon-plus" @click="addGroup()">添加组</el-button>
    </div>
    <!-- 学校行政列表 -->
    <el-table :data="tableData" border style="width: 100%" @select-all="selectChange" @selection-change="selectChange" v-loading="loading">
      <el-table-column type="selection" width="55">
      </el-table-column>
      <el-table-column prop="id" label="序号" width="50">
      </el-table-column>
      <el-table-column label="功能名称" >
           <template slot-scope="scope">
               <span v-if="scope.row.type ===1">{{scope.row.name}}</span>
               <span v-else>&nbsp&nbsp&nbsp&nbsp|-{{scope.row.name}}</span>
           </template>
      </el-table-column>
      <el-table-column label="功能类型" width="100">
          <template slot-scope="scope">
          <el-tag
          :type="scope.row.type === 1 ? 'primary' : 'success'"
          close-transition>{{scope.row.type === 1 ? '功能分组' : '功能节点' }}</el-tag>
          </template>
      </el-table-column>
      <el-table-column label="所属组" width="100">
          <template slot-scope="scope">
          <span>{{scope.row.pid|featureGroup(permissions) }}</span>
          </template>
      </el-table-column>

      <el-table-column prop="method" label="访问方法" width="120">
      </el-table-column>
      <el-table-column prop="route_name" label="后端路由" width="180">
      </el-table-column>
      <el-table-column prop="remark" label="备注" >
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-tooltip content="编辑功能" v-if="scope.row.type === 2" placement="right-end"  effect="light">
            <el-button plain icon="el-icon-edit" type="primary" size="small" @click="edit(scope.row)"></el-button>
          </el-tooltip>
          <el-tooltip content="编辑组" v-if="scope.row.type === 1" placement="right-end"  effect="light">
            <el-button plain icon="el-icon-edit" type="primary" size="small" @click="edit(scope.row)"></el-button>
          </el-tooltip>
          <el-tooltip content="删除" placement="right-end"  effect="light">
            <el-button plain icon="el-icon-delete" type="danger" size="small" @click="del(scope.row)"></el-button>
          </el-tooltip>
        </template>
      </el-table-column>
    </el-table>
    <!-- 分页 -->
    <el-row class="page">
      <el-col :span="2" :offset="1">
        <el-button type="danger" plain @click="delAll()">删除选择</el-button>
      </el-col>
      <el-col :span="20">
    <el-pagination
      background
      @current-change="pagination"
      @size-change="sizeChange"
      :current-page.sync="current_page"
      :page-sizes="[10,20,25,50]"
      layout="total,sizes,prev, pager, next"
      :page-size.sync="pageSize"
      :total="total">
    </el-pagination>
      </el-col>
    </el-row>
    </div>

        <!-- 编辑列表 -->
  <el-dialog title="功能管理" center :rules="rules" ref="permission" :visible.sync="editDialogFormVisible" :close-on-click-modal="false">
      <el-form :model="form" label-width="100px">
        <el-row>
          <el-col :span="12">
        <el-form-item label="功能名称" prop="name">
            <el-input v-model="form.name"  placeholder="请输入名称">
            </el-input>
        </el-form-item>
          </el-col>
          <el-col :span="12">
        <el-form-item label="功能组" prop="pid">
          <el-select v-model="form.pid" placeholder="请选择所属功能组">
              <el-option v-for="item in permissions" :label="item.name" :value="item.id" :key="item.id"></el-option>
          </el-select>
        </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
        <el-form-item label="访问方式" prop="method">
            <el-select v-model="form.method" placeholder="请选择访问方式">
            <el-option value="GET" >GET</el-option>
            <el-option value="POST" >POST</el-option>
            <el-option value="PATCH" >PATCH</el-option>
            <el-option value="DELETE" >DELETE</el-option>
          </el-select>
        </el-form-item>
          </el-col>
          <el-col :span="12">
        <el-form-item label="后端路由"  prop="route_name">
          <el-input v-model="form.route_name"></el-input>
        </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
           <el-form-item label="前端匹配"  prop="route_match">
             <el-input v-model="form.route_match"></el-input>
           </el-form-item>
          </el-col>
          <el-col :span="12">
        <el-form-item label="备注"  prop="remark">
          <el-input type="textarea" v-model="form.remark"></el-input>
        </el-form-item>
          </el-col>
        </el-row>
        </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="cancel()">取 消</el-button>
        <el-button type="primary" @click="save()">确 定</el-button>
      </div>
    </el-dialog>


        <!-- 编辑组 -->
  <el-dialog title="组管理" center  :visible.sync="isGroup" :close-on-click-modal="false">
      <el-form :model="form" label-width="100px">
        <el-row>
          <el-col :span="12">
        <el-form-item label="组名称" prop="name">
            <el-input v-model="form.name"  placeholder="请输入名称">
            </el-input>
        </el-form-item>
          </el-col>
          <el-col :span="12">
        <el-form-item label="所属功能组" prop="pid">
          <el-select v-model="form.pid" placeholder="请选择所属功能组">
              <el-option label="全局" :value="0" >全局</el-option>
              <el-option v-for="item in permissions" :label="item.name" :value="item.id" :key="item.id"></el-option>
          </el-select>
        </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
        <el-form-item label="组备注"  prop="remark">
          <el-input type="textarea" v-model="form.remark"></el-input>
        </el-form-item>
          </el-col>
        </el-row>
        </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="cancelGroup()">取 消</el-button>
        <el-button type="primary" @click="saveGroup()">确 定</el-button>
      </div>
    </el-dialog>

</div>
</template>

<script>
import {
  getInfo,
  getInfoById,
  addInfo,
  updateInfo,
  deleteInfoById,
  deleteAll,
  Model,
  SearchModel,
  getGroup
} from "@/api/permission";
import {config} from "./../../config/index"
import {Tools} from "@/views/utils/Tools";

export default {
  data() {
    return {
      searchForm: new SearchModel(),
      tableData: [],
      editDialogFormVisible: false,
      uploadId: "",
      teachers: [],
      permissions: [],
      form: new Model(),
      ruleForm: new Model(),
      loading: false,
      isNew: false,
      isEdit: false,
      isGroup: false,
      current_page: 1,
      total: 0,
      pageSize: 10,
      session_id: null,
      multiSelect: [],
      rules:{
          name: [
            {required: true ,message: '请输入功能名称', trigger: 'blur'}
          ]
      }
    };
  },
  methods: {
    // 搜索相关
    find() {
       this.fetchData()
    },
    findReset() {
      this. searchForm = new SearchModel(this.session_id, null,null)
      this.fetchData()
    },
    // 查询数据 获取信息列表
    fetchData(searchObj = this.searchForm , page = this.current_page, pageSize = this.pageSize) {
      this.loading = true
      getInfo(searchObj, page, pageSize)
        .then(response => {
          //成功执行内容
          let result = response.data;
           this.tableData = result;
           this.total = response.meta.total;
           this.loading = false
        })
        .catch(err => {
           Tools.error(this, err.response.data)
            this.loading = false
        });
    },
    addGroup() {
       this.isNew = true
       this.isGroup = true
       this.form = new Model(null, 0, 1)
    },
    add() {
      this.editDialogFormVisible = true;
      this.isNew = true;
      this.form = new Model(null,null,2);
    },
    // 模型的新建、编辑与更新
    edit(row) {
      let id = row.id;
      this.uploadId = id;
      getInfoById(id).then(response => {
        let result = response.data;
        this.form = result;
        this.isEdit = true;
        if (row.type === 1) {
            this.isGroup = true
        } else {
            this.editDialogFormVisible = true;
        }

      });
    },
    save() {
      this.editDialogFormVisible = false;
      if (this.isNew) {
        this.isNew = false
        this.newData()
      }
      if (this.isEdit) {
        this.isEdit = false
        this.updateData()
      }
    },
   saveGroup() {
      this.isGroup = false;
      if (this.isNew) {
        this.isNew = false
        this.newData()
      }
      if (this.isEdit) {
        this.isEdit = false
        this.updateData()
      }
    },
    cancel(){
      this.editDialogFormVisible = false
      this.isNew = false
      this.isEdit = false
    },
    cancelGroup() {
      this.isGroup = false
      this.isNew = false
      this.isEdit = false
    },
    updateData() {
      updateInfo(this.uploadId, this.form)
        .then(response => {
          //成功执行内容
          let result = response.status_code;
          if (result == 200) {
            // let currentId = this.form.id;
            // let record = 0;
            // record = this.tableData.findIndex((val, index) => {
            //   return val.id == currentId;
            // });
            // this.tableData.splice(record, 1, this.form);
            this.fetchData();
            Tools.success(this, "信息更新成功");
          }
        })
        .catch(err => {
          Tools.error(this, err.response.data)
        });
    },
    newData() {
      addInfo(this.form)
        .then(response => {
            Tools.success(this, "功能信息添加成功");
            this.fetchData();
        })
        .catch(err => {
          Tools.error(this, err.response.data)
        });
    },

    // 删除  删除单个 批量删除
    del(row) {
      this.$confirm("此操作将永久删除该信息, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      }).then(() => {
          let id = row.id;
          let record = null;
          record = this.tableData.findIndex(val => val.id == id);
          deleteInfoById(id).then(response => {
              let result = response.status_code;
              if (result == 200) {
                this.$message({
                  type: "success",
                  message: "删除成功!"
                });
                this.tableData.splice(record, 1);
              }
            }).catch(err => {
              //失败执行内容
              Tools.error(this, err.response.data)
        })
      })
    },

    selectChange(selection) {
       this.multiSelect = selection;
    },
    delAll() { // 删除所有的数据
      this.$confirm("此操作将永久删除该功能, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      }).then(() => {
        let arr = []
        this.multiSelect.forEach(item=> {
          arr.push(item.id);
        })
       deleteAll(arr).then(response=>{
         this.fetchData()
       }).catch(err => {
         Tools.error(err.response.data)
       })
      }).catch(() => {
          Tools.errorTips(this, '删除操作已经取消')
      })
    },

    getGroup(){
      return new Promise((resovle, reject) => {
         getGroup().then(res=> {
              this.permissions = res.data;
              resovle('调用信息功能组信息正确')
         }).catch(error => {
              reject('调用信息功能组信息出错')
         })
      })
    },
    // 分页相关
    pagination(val) {
      this.current_page = val;
      this.fetchData()
    },
    sizeChange(val) {
       this.pageSize = val;
       this.fetchData()
    },

  },
  mounted() { // 操作相关的DOM

  },
  created() { // 获取数据，无法操作节点
      Promise.all([this.getGroup()]).then(res => {
      this.fetchData();
    });
  },
  filters: {
     featureGroup(value, group){
        if (value === 0) {
          return '顶层'
        } else {
           let gp = group.find(item => item.id === value)
           return gp.name
        }
     }
  }
};
</script>

<style lang="scss">
 @import './../../styles/app-main.scss';
#leader .el-input {
  width: 200px;
  margin-left: 10px;
}


</style>
