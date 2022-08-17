<template>
  <div class="warpper">
    <div class="toolbar">
      <el-form :inline="true" :model="searchForm" class="demo-form-inline">
        <el-form-item label="数据表">
          <el-input
            v-model="searchForm.table_name"
            @keyup.enter.native="search"
            placeholder="请输入数据表"
          >
          </el-input>
        </el-form-item>
        <el-form-item>
          <el-button @click="find()" plain>查询</el-button>
          <el-button type="info" @click="findReset()" plain>重置</el-button>
        </el-form-item>
      </el-form>
    </div>
    <div class="table">
      <el-table
        :data="tableData"
        size="small"
        stripe
        border
        style="width: 100%"
      >
        <el-table-column type="index" label="标识" width="50" align="center" />
        <el-table-column
          prop="table_name"
          label="表名称"
          width="150"
          align="center"
        />

        <el-table-column prop="table_comment" label="表注释" width="200">
        </el-table-column>
        <el-table-column prop="engine" label="数据库引擎" width="200">
        </el-table-column>
        <el-table-column prop="table_collation" label="字符编码集" width="200">
        </el-table-column>
        <el-table-column prop="create_time" label="创建日期" width="200">
        </el-table-column>
        <el-table-column label="操作" min-width="300">
          <template slot-scope="{ row }">
            <el-button plain size="mini" @click="previewHandle(row)"
              >预览</el-button
            >
            <el-button plain size="mini">详细配置</el-button>
            <el-button plain size="mini" @click="editHandle(row)"
              >基础配置</el-button
            >
            <el-button plain size="mini" @click="downloadCode(row)"
              >下载代码</el-button
            >
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="page">
      <el-pagination
        :current-page="page.current_page"
        :page-sizes="page.sizes"
        :page-size="page.per_page"
        layout="total, sizes, prev, pager, next"
        :total="page.total"
        @size-change="sizeChange"
        @current-change="currentChange"
      />
    </div>
    <el-dialog
      title="代码基础配置(不建议更改)"
      :visible.sync="dialogFormVisible"
      :close-on-click-modal="false"
      width="40%"
      v-el-drag-dialog
    >
      <el-form
        v-if="dialogFormVisible"
        ref="ruleForm"
        :model="formData"
        :rules="rules"
      >
        <!-- 这里面开始 -->
        <el-row :gutter="20">
          <el-col :span="12">
            <el-form-item label="后端模型">
              <el-input v-model="formData.table_config.back_model" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="后端路由">
              <el-input
                v-model="formData.table_config.back_routes"
                type="text"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="12">
            <el-form-item label="前端模型">
              <el-input v-model="formData.table_config.front_model" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="前端组件">
              <el-input
                v-model="formData.table_config.front_component_name"
                type="text"
              />
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="save('ruleForm')">设置</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import CURD from "@/mixin/CURD";
import "@/styles/view.scss";
import { show, update } from "@/api/table.js";
export default {
  name: "TableIndex",
  mixins: [CURD],
  data() {
    return {
      module: "table",
      newTitle: "新增信息",
      editTitle: "编辑信息",
      config: {},
    };
  },
  methods: {
    baseConfigHandle() {},
    async previewHandle(row) {
      let { data } = await show(row.id || 0, row);
      this.config = data.table_config;
      this.$router.push({
        path: `/sys/preview`,
        query: this.config,
      });
    },
    async editHandle(row) {
      this.isEdit = true;
      this.isNew = false;
      let { data } = await show(row.id || 0, row);
      this.formData = data;
      this.dialogFormVisible = true;
    },
    async downloadCode(row) {
      row.action = "download";
      await update(row);
    },
  },
};
</script>

<style>
</style>
