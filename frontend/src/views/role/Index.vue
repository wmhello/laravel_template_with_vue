<template>
  <div id="role">
    <div id="datagrid">
      <div class="toolbar">
           <el-button plain icon="el-icon-plus" @click="add()">添加</el-button>
      </div>
    <!-- 角色列表 -->
    <el-table :data="tableData" border style="width: 100%">
      <el-table-column prop="id" label="序号" width="70">
      </el-table-column>
      <el-table-column prop="name" label="角色" width="180">
      </el-table-column>
      <el-table-column prop="explain" label="描述" width="200">
      </el-table-column>
      <el-table-column prop="remark" label="备注" width="500">
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
           <el-tooltip content="设置功能" placement="right-end" v-if="!(scope.row.name == 'admin')">
            <el-button plain icon="el-icon-edit-outline" size="small" @click="setPermission(scope.row)"></el-button>
          </el-tooltip>
          <el-tooltip content="编辑" placement="right-end" v-if="!(scope.row.name == 'admin')">
            <el-button plain icon="el-icon-edit" type="primary" size="small" @click="edit(scope.row)"></el-button>
          </el-tooltip>
          <el-tooltip content="删除" placement="right-end" v-if="!(scope.row.name == 'admin')">
            <el-button plain icon="el-icon-delete" type="danger" size="small" @click="del(scope.row)"></el-button>
          </el-tooltip>
        </template>
      </el-table-column>
    </el-table>

    <!-- 修改角色信息 -->
    <el-dialog title="修改角色信息" :visible.sync="editDialogFormVisible" @close="cancel()">
      <el-form :model="form" label-position="top" label-width="80px">
        <el-row class="first-row">
        <el-col :span="12" class="first-column">
        <el-form-item label="角色">
          <el-input v-model="form.name" placeholder=''></el-input>
        </el-form-item>
        </el-col>
        <el-col :span="12">
        <el-form-item label="描述">
           <el-input v-model="form.explain" placeholder=''></el-input>
        </el-form-item>
        </el-col>

        </el-row>
        <el-row class="last-row">
        <el-col :span="24" class="first-column">
        <el-form-item label="备注">
          <el-input type="textarea" v-model="form.remark"></el-input>
        </el-form-item>
        </el-col>
        </el-row>
        </el-row>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="cancel()">取 消</el-button>
        <el-button type="primary" @click="save()">确 定</el-button>
      </span>
    </el-dialog>

    <el-dialog title="设置角色功能" :visible.sync="isPermission" @close="cancelPermission()" width="30%" @open="setPermissionSelect">
      <el-tree
            :data="treeData"
            show-checkbox
            node-key="id"
            ref="tree"
            default-expand-all
            highlight-current
           :default-checked-keys="permissions"
           :props="defaultProps">
      </el-tree>
      <span slot="footer" class="dialog-footer">
        <el-button @click="cancelPermission()">取 消</el-button>
        <el-button type="primary" @click="savePermission()">确 定</el-button>
      </span>
    </el-dialog>
    </div>
  </div>
 </template>

<script>
import {
  getInfo,
  getInfoById,
  addInfo,
  updateInfo,
  deleteInfoById,
  Model
} from "@/api/role";

import { getPermission } from "@/api/permission";

export default {
  data () {
    return {
      tableData: [],  //数据表格内容
      editDialogFormVisible: false, // 编辑和添加对话框开关
      uploadId: "",   // 更新的Id号
      form: new Model(), //  编辑和添加数据对应的模型
      isNew: false,    // 添加状态
      isEdit: false,   // 编辑状态
      isPermission: false,
      permissions:[],
      treeData: [],
      defaultProps: {
          children: 'children',
          label: 'label'
      }
    };
  },
  methods: {

    fetchData () {  // 获取数据列表
      getInfo().then(response => {

        let result = response.data;
        this.tableData = result;
      })
        .catch(() => {
        });
    },
    add () {  // 显示添加页面
      this.form = new Model();
      this.isNew = true;
      this.editDialogFormVisible = true;
    },
    setPermission(row){
       let id = row.id;
       this.uploadId = id;
       getPermission().then(res => {
         this.treeData = res.data
         getInfoById(id).then(response => {
           let result = response.data;
           let yearTime = new Date(result.year, 1, 1);
           result.year = yearTime
           this.form = result;
           this.permissions = this.form.permission
           this.isPermission = true;
           // this.$refs.tree.setCheckedKeys(this.permissions,true)
        });
       })
    },
    setPermissionSelect() {
        if (this.$refs.tree !== undefined) {
          this.$refs.tree.setCheckedKeys(this.permissions,true)
        }
    },
    edit (row) {  // 显示编辑页面
       let id = row.id;
       this.uploadId = id;
       getInfoById(id).then(response => {
         let result = response.data;
         let yearTime = new Date(result.year, 1, 1);
         result.year = yearTime
         this.form = result;
         this.isEdit = true;
        this.editDialogFormVisible = true;
      });
    },
    newData(){  // 保存新建数据
      this.isNew =false;
      addInfo(this.form).then(response => {
        //成功执行内容
        let result = response.status_code;
        if (result == 200) {
          this.$message({
            type: 'success',
             message: '创建新的角色信息成功',
          })
          this.fetchData()
        }
      }).catch(err => {
          let result = err.response.data
          this.$message({
            type: 'error',
             message: result.message,
          })
      })
    },
    updateData() {  // 保存更新数据
      this.isEdit =false;
      updateInfo(this.uploadId, this.form).then(response => {
        //成功执行内容
        let result = response.status_code;
        if (result == 200) {
          let currentId = this.form.id;
          let record = 0;
          record = this.tableData.findIndex((val, index) => {
            return val.id == currentId;
          });
          this.tableData.splice(record, 1, this.form);
          this.$message({
            type: 'success',
             message: '角色信息更改成功',
          })
        }
      })
        .catch((err) => {
          console.log(err.response.data)
        });
    },
    cancel() { // 对话框中取消保存
      this.isEdit =false;
      this.isNew =false;
      this.editDialogFormVisible = false;
    },
    cancelPermission(){
       this.isPermission = false
    },
    save() { // 对话框中保存数据
      this.editDialogFormVisible = false;
      if (this.isEdit && !this.isNew) {
        this.updateData();
      }
      if (!this.isEdit && this.isNew) {
        this.newData();
      }
    },
    savePermission() {
      this.isPermission = false
      this.form.permission = this.$refs.tree.getCheckedKeys()
      updateInfo(this.uploadId, this.form).then(response => {
        //成功执行内容
        let result = response.status_code;
        if (result == 200) {
          let currentId = this.form.id;
          let record = 0;
          record = this.tableData.findIndex((val, index) => {
            return val.id == currentId;
          });
          this.tableData.splice(record, 1, this.form);
          this.$message({
            type: 'success',
             message: '角色信息更改成功',
          })
        }
      })
      .catch((err) => {
          console.log(err.response.data)
        });

    },
    del (row) { // 删除指定的数据
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
          })
            .catch(() => {
            });
        })
    }
  },
  mounted () {
    this.fetchData()
  },
};
</script>

<style lang="scss">
@import './../../styles/app-main.scss';
#role .el-form--label-top .el-form-item__label {
  width: 100%;
  text-align:center;
}
#role .el-form-item__content{
  text-align: center;
}

#role .first-row .el-col {
border:1px solid $border-color;
border-left: 0px;
}
#role .first-row .first-column {
border-left:1px solid $border-color;
}

#role .last-row .el-col {
border:1px solid $border-color;
border-top: 0px;
border-left: 0px;
}
#role .last-row .first-column {
border-left:1px solid $border-color;
}

</style>
