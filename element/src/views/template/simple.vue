<!--
 * @Author: wmhello 871228582@qq.com
 * @Date: 2021-11-27 17:30:20
 * @LastEditors: wmhello 871228582@qq.com
 * @LastEditTime: 2022-08-17 11:40:33
 * @FilePath: \element\src\views\template\simple.vue
 * @Description: 这是默认设置,请设置`customMade`, 打开koroFileHeader查看配置 进行设置: https://github.com/OBKoro1/koro1FileHeader/wiki/%E9%85%8D%E7%BD%AE
-->
<template>
  <div class="warpper">
    <div class="toolbar">
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
        <el-table-column prop="id" label="标识" width="50" align="center" />
        <el-table-column prop="name" label="名称" width="150" align="center" />
        <el-table-column prop="desc" label="说明" width="200">
        </el-table-column>
        <el-table-column label="状态" width="200">
          <template v-slot="{ row }">
            <span v-if="row.status">是</span>
            <span v-else>否</span>
          </template>
        </el-table-column>
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
import CURD from "@/mixin/simple";
export default {
  name: "##component##",
  mixins: [CURD],
  data() {
    return {
      module: "##name##",
      newTitle: "新增信息",
      editTitle: "编辑信息",
    };
  },
  methods: {},
};
</script>

<style scoped>
</style>
