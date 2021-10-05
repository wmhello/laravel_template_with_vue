<template>
  <div class="warpper">
    <div class="toolbar" v-permission="['article_categories.store']">
      <el-button
        type="primary"
        plain
        @click="add"
        v-permission="['article_categories.store']"
        >新增文章类型</el-button
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
        <el-table-column
          prop="id"
          label="标识"
          min-width="100"
          align="center"
        />
        <el-table-column
          prop="name"
          label="英文标题"
          min-width="200"
          align="center"
        />
        <el-table-column
          prop="note"
          label="中文说明"
          min-width="200"
          align="center"
        />
        <el-table-column label="状态" min-width="200" align="center">
          <template v-slot="scope">
            <el-switch
              v-model="scope.row.status"
              active-color="#13ce66"
              inactive-color="#ff4949"
              @change="changeStatus(scope.row)"
            >
            </el-switch>
          </template>
        </el-table-column>
        <el-table-column label="操作" width="200">
          <template slot-scope="scope">
            <el-button
              plain
              @click="edit(scope.row.id)"
              v-permission="['article_categories.update']"
              >编辑</el-button
            >
            <el-button
              plain
              type="danger"
              @click="del(scope.row.id)"
              v-permission="['article_categories.destroy']"
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
        width="40%"
      >
        <el-form
          v-if="dialogFormVisible"
          ref="ruleForm"
          :model="formData"
          :rules="rules"
          label-position="right"
          label-width="100px"
        >
          <el-form-item label="类型" prop="name">
            <el-input
              v-model="formData.name"
              placeholder="请输入英文类型"
            ></el-input>
          </el-form-item>
          <el-form-item label="说明" prop="note">
            <el-input
              v-model="formData.note"
              placeholder="请输入中文说明"
            ></el-input>
          </el-form-item>
          <el-form-item label="状态" prop="status">
            <el-switch
              v-model="formData.status"
              :disabled="isEdit"
              active-color="#13ce66"
              inactive-color="#ff4949"
            >
            </el-switch>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false">取 消</el-button>
          <el-button
            type="primary"
            v-if="isNew"
            v-permission="['article_categories.store']"
            @click="save('ruleForm')"
            >{{ cmdTitle }}</el-button
          >
          <el-button
            type="primary"
            v-if="isEdit"
            v-permission="['article_categories.update']"
            @click="save('ruleForm')"
            >{{ cmdTitle }}</el-button
          >
        </div>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import CURD from "@/mixin/CURD";
import { update } from "@/api/article_category";
export default {
  mixins: [CURD],
  name: "article_category_index",
  data() {
    return {
      module: "article_category",
      newTitle: "新增文章类型",
      editTitle: "编辑文章类型",
      isPaginate: true
    };
  },
  methods: {
    async changeStatus(row) {
      let tips = "";
      if (row.status) {
        tips = "是否启用该文章类型?";
      } else {
        tips = "是否禁用该文章类型?";
      }
      this.$confirm(tips, "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(async () => {
          let data = {
            id: row.id,
            status: row.status,
            action: "status"
          };
          let { info } = await update(data);
          this.$message.success(info);
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消删除"
          });
        });
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
  text-align: center;
}
.avatar {
  width: 50%;
  height: 50%;
  display: block;
}
.avatar-uploader .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}
.avatar-uploader .el-upload:hover {
  border-color: #409eff;
}
.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 178px;
  height: 178px;
  line-height: 178px;
  text-align: center;
}
</style>
