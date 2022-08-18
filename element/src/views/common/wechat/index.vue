<template>
  <div class="warpper">
    <div class="toolbar">
      <el-form :inline="true" :model="searchForm" class="demo-form-inline">
        <el-form-item label="APP_ID">
          <el-input v-model="searchForm.app_id" placeholder="请输入APP_ID">
          </el-input>
        </el-form-item>
        <el-form-item label="APP_Secret">
          <el-input
            v-model="searchForm.app_secret"
            placeholder="请输入APP_Secret"
          >
          </el-input>
        </el-form-item>

        <el-form-item>
          <el-button @click="find()" plain>查询</el-button>
          <el-button type="info" @click="findReset()" plain>重置</el-button>
        </el-form-item>
      </el-form>
      <el-button type="primary" plain @click="add">添加</el-button>
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
          prop="app_id"
          label="APP_ID"
          width="100"
          align="center"
        />
        <el-table-column
          prop="app_secret"
          label="APP_Secret"
          width="100"
          align="center"
        />
        <el-table-column
          prop="type"
          label="应用类型"
          width="100"
          align="center"
        />
        <el-table-column
          prop="status"
          label="是否启用"
          width="100"
          align="center"
        />

        <el-table-column label="操作" min-width="300">
          <template slot-scope="scope">
            <el-button plain @click="edit(scope.row.id)">修改</el-button>
            <el-button plain type="danger" @click="del(scope.row.id)"
              >删除</el-button
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
      :title="title"
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
            <el-form-item label="APP_ID" prop="app_id">
              <el-input v-model="formData.app_id" type="text" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="APP_Secret" prop="app_secret">
              <el-input v-model="formData.app_secret" type="text" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="应用类型" prop="type">
              <el-select v-model="formData.type" clearable placeholder="请选择">
                <el-option label="内容1" value="内容1"> </el-option>
              </el-select>
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="save('ruleForm')">{{
          cmdTitle
        }}</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import "@/styles/view.scss";
import CURD from "@/mixin/CURD";
export default {
  name: "WechatIndex",
  mixins: [CURD],
  data() {
    return {
      module: "wechat",
      newTitle: "新增信息",
      editTitle: "编辑信息",
    };
  },
  methods: {},
};
</script>

<style scoped>
</style>
