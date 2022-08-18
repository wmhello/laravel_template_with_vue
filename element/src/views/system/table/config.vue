<template>
  <div class="warpper">
    <div class="toolbar">
      <!-- <el-form :inline="true" :model="searchForm" class="demo-form-inline">
        <el-form-item label="名称">
          <el-input
            v-model="searchForm.name"
            @keyup.enter.native="search"
            placeholder="请输入名称"
          >
          </el-input>
        </el-form-item>
        <el-form-item label="状态">
          <el-select v-model="searchForm.status" placeholder="请选择状态">
            <el-option :value="true" label="是"></el-option>
            <el-option :value="false" label="否"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button @click="find()" plain>查询</el-button>
          <el-button type="info" @click="findReset()" plain>重置</el-button>
        </el-form-item>
      </el-form> -->
      <el-button type="primary" plain size="small" @click="saveHandle"
        >保存设置</el-button
      >
      <el-button type="success" plain size="small" @click="asyncHandle"
        >同步数据表</el-button
      >
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
          prop="column_name"
          label="字段名称"
          width="150"
          align="center"
        />

        <el-table-column prop="data_type" label="数据类型" width="100">
        </el-table-column>
        <el-table-column label="字段描述" width="200">
          <template v-slot="{ row }">
            <el-input v-model="row.column_comment"></el-input>
          </template>
        </el-table-column>
        <el-table-column label="必填" width="60">
          <template v-slot="{ row }">
            <el-checkbox v-model="row.is_required"></el-checkbox>
          </template>
        </el-table-column>
        <el-table-column label="列表" width="60">
          <template v-slot="{ row }">
            <el-checkbox v-model="row.is_list"></el-checkbox>
          </template>
        </el-table-column>
        <el-table-column label="表单" width="60">
          <template v-slot="{ row }">
            <el-checkbox v-model="row.is_form"></el-checkbox>
          </template>
        </el-table-column>
        <el-table-column label="表单顺序" width="100">
          <template v-slot="{ row }">
            <el-input v-model.number="row.form_order"></el-input>
          </template>
        </el-table-column>
        <el-table-column label="表单类型" width="160">
          <template v-slot="{ row }">
            <el-select v-model="row.form_type" clearable>
              <el-option value="text" label="文本框"></el-option>
              <el-option value="textarea" label="文本域"></el-option>
              <el-option value="radio" label="单选框"></el-option>
              <el-option value="select" label="下拉框"></el-option>
              <el-option value="date" label="日期框"></el-option>
              <el-option value="datetime" label="日期时间框"></el-option>
            </el-select>
          </template>
        </el-table-column>
        <el-table-column label="查询方式" width="160">
          <template v-slot="{ row }">
            <el-select v-model="row.query_type" clearable>
              <el-option value="=" label="相等"></el-option>
              <el-option value="like" label="包含"></el-option>
              <el-option value="<>" label="不等"></el-option>
              <el-option value="null" label="为空"></el-option>
              <el-option value="notnull" label="不为空"></el-option>
            </el-select>
          </template>
        </el-table-column>
        <el-table-column label="关联字典" width="160">
          <template v-slot="{ row }">
            <el-select v-model="row.assoc_table" clearable>
              <el-option
                v-for="(item, index) in tables"
                :key="index"
                :value="item"
                :label="item"
              ></el-option>
            </el-select>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <!-- <div class="page">
      <el-pagination
        :current-page="page.current_page"
        :page-sizes="page.sizes"
        :page-size="page.per_page"
        layout="total, sizes, prev, pager, next"
        :total="page.total"
        @size-change="sizeChange"
        @current-change="currentChange"
      />
    </div> -->
    <el-dialog
      :title="title"
      :visible.sync="dialogFormVisible"
      :close-on-click-modal="false"
      width="40%"
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
            <el-form-item label="名称" prop="name">
              <el-input v-model="formData.name" :disabled="isEdit" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="说明">
              <el-input v-model="formData.desc" type="text" />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="12">
            <el-form-item label="状态">
              <el-select v-model="formData.status" placeholder="请选择状态">
                <el-option :value="true" label="是"></el-option>
                <el-option :value="false" label="否"></el-option>
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
import variable from "@/mixin/variable";
import { index, update } from "@/api/table_config";
import { list } from "@/api/table.js";
export default {
  name: "TableConfigIndex",
  mixins: [variable],
  data() {
    return {
      module: "table_config",
      newTitle: "新增信息",
      editTitle: "编辑信息",
      table: null,
      tables: [],
    };
  },
  beforeRouteEnter(to, from, next) {
    next(async (vm) => {
      // 通过 `vm` 访问组件实例
      let table = to.query.table;
      vm.table = table;
      let { data } = await index(1, 100, { table });
      vm.tableData = data;
    });
  },
  async created() {
    let { data } = await list();
    this.tables = data;
  },
  methods: {
    async saveHandle() {
      let tableData = JSON.stringify(this.tableData);
      let data = {
        action: "modify",
        data: tableData,
      };

      try {
        await update(data);
        this.$message.success("详细配置保存完成");
        let res = await index(1, 100, { table: this.table });
        this.tableData = res.data;
      } catch (error) {
        let { info } = error.response.data;
        this.$message.error(info);
      }
    },
    async asyncHandle() {
      let data = {
        action: "sync",
        table: this.table,
      };
      try {
        await update(data);
        this.$message.success("同步数据表完成");
        let res = await index(1, 100, { table: this.table });
        this.tableData = res.data;
      } catch (error) {
        let { info } = error.response.data;
        this.$message.error(info);
      }
    },
  },
};
</script>

<style scoped>
</style>
