<template>
  <div id="role">
    <div id="datagrid">
      <div class="toolbar">
           <el-button plain icon="el-icon-plus" @click="add()" >添加</el-button>
           <el-alert
             title="为了演示方便，前端屏蔽了序号为1、2号角色的内容编辑操作，具体可以查看详细代码"
             type="warning">
           </el-alert>
      </div>
       <!-- 角色列表 -->
      <el-table :data="tableData" border style="width: 100%">
        <el-table-column prop="id" label="序号" width="70">
        </el-table-column>
        <el-table-column prop="name" label="角色" width="180">
        </el-table-column>
        <el-table-column prop="explain" label="描述" width="200">
        </el-table-column>
        <el-table-column prop="remark" label="备注" width="200">
        </el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope" v-if="scope.row.id > 2">
              <el-tooltip content="设置功能" placement="right-end">
               <el-button plain icon="el-icon-edit-outline" size="small" @click="setPermission(scope.row)"></el-button>
             </el-tooltip>
             <el-tooltip content="编辑" placement="right-end" >
               <el-button plain icon="el-icon-edit" type="primary" size="small" @click="edit(scope.row)"></el-button>
             </el-tooltip>
             <el-tooltip content="删除" placement="right-end" >
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
          <el-button type="primary" @click="save()">确 定</el-button>
          <el-button @click="cancel()">取 消</el-button>
        </span>
      </el-dialog>

      <el-dialog class="setPermission" title="设置角色功能" :visible.sync="isPermission" width="60%">
        <el-form :model="form" label-width="120px">
          <el-checkbox-group v-model="form.permissions" size="mini">
            <template v-for="parent in permissions">
              <el-form-item :label="parent.name">
                  <el-checkbox border v-for="item in parent.children" :label="item.id" :key="item.id">{{item.name}}</el-checkbox>
              </el-form-item>
            </template>
          </el-checkbox-group>
        </el-form>
        <span slot="footer" class="dialog-footer">
          <el-button type="primary" @click="savePermission()">确 定</el-button>
          <el-button @click="cancelPermission()">取 消</el-button>
        </span>
      </el-dialog>
    </div>
  </div>
 </template>

<script>
import {
  getInfoById,
  updateInfo,
} from "@/api/role";
import handle from "@/minix/handle";
import { getPermission } from "@/api/permission";

export default {
  name: 'role_index',
  mixins: [handle],
  data () {
    return {
      module: 'role',
      isPermission: false,
      permissions:[],
      checkPermissions: []// 选择的节点
    };
  },
  methods: {
    async setPermission(row){
       let  result = await getPermission()
       this.permissions = result.data
       result = await getInfoById(row.id)
       this.form = result.data
       this.isPermission = true;
    },
    cancelPermission(){
       this.isPermission = false
    },
    savePermission() {
      this.isPermission = false
      updateInfo(this.form.id, this.form).then(() => {
        //成功执行内容
        this.success( '角色信息更改成功')
      }).catch((err) => {
         console.log(err);
          this.error(err.response.info)
      });
    }
  }
};
</script>

<style lang="scss">
@import '@/styles/app-main.scss';
#role .el-form--label-top .el-form-item__label {
  width: 100%;
  text-align:center;
}
#role .el-form-item__content{
  text-align: center;
}
#role .setPermission .el-form-item__content{
  text-align: left;
  .el-checkbox{
    margin-right: 10px;
    margin-left: 0px;
  }
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
