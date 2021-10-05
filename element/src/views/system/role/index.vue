/* eslint-disable prefer-const */
/* eslint-disable prefer-const */
/* eslint-disable no-unused-vars */
<template>
  <div class="warpper">
    <div class="toolbar">
      <el-button type="primary" plain @click="add">添加角色</el-button>
    </div>
    <div class="table">
      <el-table
        :data="tableData"
        size="small"
        stripe
        border
        style="width: 100%"
      >
        <el-table-column prop="id" label="标识" width="50" align="center" />
        <el-table-column type="expand" label="功能权限">
          <template slot-scope="scope">
            <el-form label-position="left" inline class="demo-table-expand">
              <el-col v-for="(item, index) in scope.row.permissions" :key="index" :span="12">
                <el-form-item :label="item.module_desc + ':'">
                  <el-tag v-for="(v1, i1) in item.permissions" :key="i1" style="margin-right: 10px;">{{ v1 }}</el-tag>
                </el-form-item>
              </el-col>
            </el-form>
          </template>
        </el-table-column>
        <el-table-column
          prop="name"
          label="角色名称"
          width="150"
          align="center"
        />
        <el-table-column prop="desc" label="角色说明" min-width="200" />
        <el-table-column label="创建时间" min-width="200">
          <template v-slot="scope">
            <span>{{ scope.row.created_at|showDateTime }}</span>
          </template>
        </el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">

            <el-button
              v-if="scope.row.name !== 'admin'"
              plain
              @click="edit(scope.row.id)"
            >编辑</el-button>
            <el-button
              v-if="scope.row.name !== 'admin'"
              plain
              type="danger"
              @click="del(scope.row.id)"
            >删除</el-button>
          </template>
        </el-table-column>
      </el-table>
      </el-table-column></div>
    <div class="page">
      <el-pagination
        v-if="isPaginate"
        :current-page="page.current_page"
        :page-sizes="page.sizes"
        :page-size="page.per_page"
        layout="total, sizes, prev, pager, next"
        :total="page.total"
        @size-change="sizeChange"
        @current-change="currentChange"
      />
    </div>
    <div class="form">
      <el-dialog
        :title="title"
        :visible.sync="dialogFormVisible"
        :close-on-click-modal="false"
      >
        <el-form v-if="dialogFormVisible" ref="ruleForm" :model="formData" :rules="rules">
          <el-form-item label="角色名称" prop="name">
            <el-input
              v-model="formData.name"
              :disabled="isEdit"
              autocomplete="off"
            />
          </el-form-item>
          <el-form-item label="角色说明" prop="explain">
            <el-input v-model="formData.desc" autocomplete="off" />
          </el-form-item>
          <el-form-item v-if="formData.permissions" label="权限" style="margin-bottom: 0px;" />
          <el-card v-if="formData.permissions" class="box-card">
            <!-- <div slot="header" class="clearfix" style="padding: 5px 20px;"><span>权限</span></div> -->
            <el-form-item v-for="(value, name) in formData.permissions" :key="name" style="margin-bottom: 0px;" class="permission-mark" label-width="150px" :label="value.desc+':'">
              <div style="display: inline-flex">
                <el-checkbox v-model="value.isAll" :indeterminate="value.isIndeterminate" style="margin-right: 20px;" @change="handleCheckAllChange(value)">全选</el-checkbox>
                <el-checkbox-group v-model="value.permissions" @change="handleCheckActionsChange(value)">
                  <!--modules[index] -->
                  <el-checkbox v-if="value.enabled.includes('menu')" label="menu">菜单</el-checkbox>
                  <el-checkbox v-if="value.enabled.includes('index')" label="index">列表</el-checkbox>
                  <el-checkbox v-if="value.enabled.includes('show')" label="show">详情</el-checkbox>
                  <el-checkbox v-if="value.enabled.includes('store')" label="store">新增</el-checkbox>
                  <el-checkbox v-if="value.enabled.includes('update')" label="update">修改</el-checkbox>
                  <el-checkbox v-if="value.enabled.includes('destroy')" label="destroy">删除</el-checkbox>
                  <el-checkbox v-if="value.enabled.includes('import')" label="import">导入</el-checkbox>
                  <el-checkbox v-if="value.enabled.includes('export')" label="export">导出</el-checkbox>
                </el-checkbox-group>
              </div>
            </el-form-item>
          </el-card>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false">取 消</el-button>
          <el-button type="primary" @click="save('ruleForm')">{{
            cmdTitle
          }}</el-button>
        </div>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import CURD from "@/mixin/CURD";
import moment from "moment";
import { index as getModules } from "@/api/module";

export default {
  name: "RoleIndex",
  filters: {
    showDateTime(val) {
      const dateTime = moment(val * 1000);
      return dateTime.format("YYYY-MM-DD HH:mm:ss");
    }
  },
  mixins: [CURD],
  data() {
    return {
      isPaginate: false,
      module: "role",
      newTitle: "新建角色信息",
      editTitle: "编辑角色信息",
      id: null,
      permissions: [],
      permissionList: [],
      enabledPermissions: [],
      hasEnablePermission: false
    };
  },
  methods: {
    // 列表数据转换
    transfromData(data) {
      let result = JSON.parse(JSON.stringify(data));
      result = result.map(item => {
        item.permissions = [];
        item.role_permissions.forEach(v => {
          const index = item.permissions.findIndex(v2 => v2.module_name === v.module_name);
          if (index >= 0) { // 存在则添加权限名称
            item.permissions[index].permissions.push(v.permission_desc);
          } else { // 不存在模块则全部添加
            item.permissions.push({
              "module_name": v.module_name,
              "module_desc": v.module_desc,
              "permissions": [v.permission_desc]
            });
          }
        });
        return item;
      });
      return result;
    },

    // 获取可以使用的所有模块以及相关的权限
    async getEnablePermissions() {
      const { data } = await getModules();
      const result = [];
      data.forEach(item => {
        let tmp = [];
        tmp = item.permissions.map(v => {
          return v.name;
        });
        result.push({
          "name": item.name,
          "desc": item.desc,
          "enabled": tmp
        });
      });
      this.enabledPermissions = result;
    },
    // 全选状态
    handleCheckAllChange(value) {
      (value.permissions = value.isAll ? value.enabled : []), (value.isIndeterminate = false);
    },
    // 选项框组的状态
    handleCheckActionsChange(value) {
      const checkedCount = value.permissions.length;
      value.isAll = checkedCount === value.enabled.length;
      value.isIndeterminate = checkedCount > 0 && checkedCount < value.enabled.length;
    },

    async add() {
      await this.getEnablePermissions();
      const { Model } = require(`@/model/${this.module}`);
      let data = JSON.parse(JSON.stringify(this.enabledPermissions));
      data = data.map(item => {
        item.permissions = [];
        item.isAll = false;
        item.isIndeterminate = false;
        return item;
      });
      this.formData = new Model("", "", data);
      this.isNew = true;
      this.isEdit = false;
      this.setTitle();
      this.dialogFormVisible = true;
    },
    /**
     * 首先获取所有可以使用的模块和权限，用于显示在权限列表
     * 其次获得这个角色的权限
     * 最后把可以用的模块和权限与这个角色的权限合并在一起，
     * 模块里面enabled表示可以用，permissions表示角色已经有的
     */
    async edit(id) {
      if (!this.hasEnablePermission) {
        await this.getEnablePermissions();
        this.hasEnablePermission = true;
      }
      const { show } = require(`@/api/${this.module}`);
      const result = await show(id); //
      const { name, desc, role_permissions } = result.data;
      const tmp = [];
      role_permissions.forEach(item => {
        const r = {
          name: item.module_name,
          desc: item.module_desc,
          permissions: [item.permission_name]
        };
        // 查看是否存在当前的模块，存在则添加权限，不存在则直接新建
        const index = tmp.findIndex(v => v.name === r.name);
        if (index >= 0) {
          const arr = tmp[index].permissions;
          arr.push(item.permission_name);
          tmp[index].permissions = arr;
        } else {
          tmp.push(r);
        }
      });
      const role = {
        id,
        name,
        desc,
        permissions: tmp
      };
      // 该角色选择的模块及其权限
      const arr1 = role.permissions;
      // 所有可以设置的模块权限
      let arr2 = this.enabledPermissions;
      arr2 = arr2.map(item => {
        const index = arr1.findIndex(v => v.name === item.name);

        if (index >= 0) {
          item.permissions = arr1[index].permissions;
        } else {
          item.permissions = [];
        }
        if (item.permissions.length === item.enabled.length) {
          item.isAll = true;
        } else {
          item.isAll = false;
        }
        if (item.permissions.length === 0 || item.permissions.length === item.enabled.length) {
          item.isIndeterminate = false;
        } else {
          item.isIndeterminate = true;
        }
        return item;
      });
      role.permissions = arr2;
      this.formData = JSON.parse(JSON.stringify(role));
      this.isNew = false;
      this.isEdit = true;
      this.setTitle();
      this.dialogFormVisible = true;
    }
  }
};
</script>

<style>
.table,
.toolbar,
.page {
  margin: 20px;
}

.toolbar {
  margin-top: 10px;
  border: 1px solid #ccc;
  padding: 5px;
}
.table {
  margin-bottom: 10px;
}
.page {
  margin-top: 10px;
}
.page {
  text-align: center;
}
#permission .el-row {
  margin-bottom: 10px;
}
</style>
