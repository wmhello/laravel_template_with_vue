<template>
  <div class="warpper">
    <div class="toolbar">
      <el-button type="primary" plain @click="handleAdd">添加模块</el-button>
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
        <el-table-column prop="name" label="模块名称" width="150" />
        <el-table-column prop="desc" label="模块说明" min-width="200" />
        <el-table-column label="功能权限" min-width="200">
          <template v-slot="scope">
            <el-tag
              v-for="(item, index) in scope.row.permissions"
              :key="index"
              style="margin-right:20px"
            >
              {{ item.desc }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column label="操作" width="200">
          <template slot-scope="scope">
            <el-button plain @click="edit(scope.row.id)">编辑</el-button>
            <el-button plain type="danger" @click="del(scope.row.id)"
              >删除</el-button
            >
          </template>
        </el-table-column>
      </el-table>
    </div>
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
        <el-form
          v-if="dialogFormVisible"
          ref="ruleForm"
          :model="formData"
          :rules="rules"
          label-position="right"
        >
          <el-form-item label="模块名称" prop="name">
            <el-input
              v-model="formData.name"
              autocomplete="off"
              placeholder="请输入模块名称，必须英文，要求输入的内容必须与后端的RESTful路由名称一致, 如articles"
            />
          </el-form-item>

          <el-form-item label="模块说明" prop="remark">
            <el-input
              v-model="formData.desc"
              autocomplete="off"
              placeholder="请输入模块说明，中文名称"
            />
          </el-form-item>

          <el-form-item label="赋予权限" prop="name">
            <el-select
              v-model="formData.permissions"
              multiple
              clearable
              placeholder="请选择权限"
              style="width: 100%"
            >
              <el-option
                v-for="(item, index) in permissionList"
                :key="index"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="cancel">取 消</el-button>
          <el-button type="primary" @click="savePermission">{{
            cmdTitle
          }}</el-button>
        </div>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import CURD from "@/mixin/CURD";
import { permissionList } from "@/model/module";

export default {
  name: "ModuleIndex",
  mixins: [CURD],
  data() {
    return {
      module: "module",
      newTitle: "新建功能信息",
      editTitle: "编辑功能信息",
      permissionList
    };
  },
  async created() {},
  methods: {
    handleAdd() {
      this.add();
    },
    async edit(id) {
      const { show } = require(`@/api/module`);
      const { data } = await show(id);
      const { permissions } = data;
      const result = [];
      permissions.forEach((item) => {
        result.push(item.name);
      });
      const formData = {
        id: data.id,
        name: data.name,
        desc: data.desc,
        permissions: result
      };
      this.formData = formData;
      console.log(this.formData);
      this.isNew = false;
      this.isEdit = true;
      this.setTitle();
      this.dialogFormVisible = true;
    },
    savePermission() {
      this.save("ruleForm");
    },
    cancel() {
      this.dialogFormVisible = false;
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
</style>
