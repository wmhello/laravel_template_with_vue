<template>
  <div class="warpper">
    <div class="toolbar" v-permission="['articles.store']">
      <el-button
        type="primary"
        plain
        @click="add"
        v-permission="['articles.store']"
        >新增文章</el-button
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
        <el-table-column prop="id" label="标识" width="80" align="center" />
        <el-table-column label="顺序" width="200" align="center">
          <template v-slot="scope">
            <el-input
              v-model.number="scope.row.order"
              v-if="scope.row.isEdit"
            ></el-input>
            <span v-else>{{ scope.row.order }}</span>
            <el-button
              size="mini"
              type="success"
              v-if="scope.row.isEdit"
              @click="saveOrder(scope.row)"
              >保存</el-button
            >
            <el-button v-else @click="adjustOrder(scope.row)" size="mini" plain
              >调整</el-button
            >
          </template>
        </el-table-column>
        <el-table-column prop="title" label="文章标题" min-width="200" />
        <el-table-column prop="summary" label="简要描述" width="200" />
        <el-table-column label="文章封面" width="150" height="150">
          <template v-slot="scope">
            <img :src="scope.row.img" class="avatar" />
          </template>
        </el-table-column>
        <el-table-column label="文章状态" width="100">
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
        <el-table-column
          prop="category.note"
          label="文章类型"
          min-width="100"
        />

        <el-table-column label="操作" width="200">
          <template slot-scope="scope">
            <el-button
              plain
              @click="edit(scope.row.id)"
              v-permission="['articles.update']"
              >编辑</el-button
            >
            <el-button
              plain
              type="danger"
              v-permission="['articles.destroy']"
              @click="del(scope.row.id)"
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
          label-width="80px"
        >
          <el-row :gutter="20">
            <el-col :span="11">
              <el-form-item label="文章标题" prop="title">
                <el-input
                  v-model="formData.title"
                  autocomplete="off"
                  placeholder="请输入文章标题"
                />
              </el-form-item>
            </el-col>
            <el-col :span="11">
              <el-form-item label="文章描述" prop="summary">
                <el-input
                  v-model="formData.summary"
                  autocomplete="off"
                  placeholder="请输入文章描述"
                />
              </el-form-item>
            </el-col>
          </el-row>
          <el-row :gutter="20">
            <el-col :span="11">
              <el-form-item label="文章状态" prop="status">
                <el-switch
                  v-model="formData.status"
                  active-color="#13ce66"
                  inactive-color="#ff4949"
                  :disabled="isEdit"
                >
                </el-switch>
              </el-form-item>
            </el-col>
            <el-col :span="11">
              <el-form-item label="文章顺序" prop="order">
                <el-input-number
                  v-model="formData.order"
                  :min="1"
                  label="文章顺序"
                ></el-input-number>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row :gutter="20">
            <el-col :span="11">
              <el-form-item label="文章类型" prop="article_category_id">
                <el-select
                  v-model="formData.article_category_id"
                  placeholder="请选择"
                >
                  <el-option
                    v-for="item in categories"
                    :key="item.id"
                    :label="item.note"
                    :value="item.id"
                  >
                  </el-option>
                </el-select>
              </el-form-item>
            </el-col>
            <el-col :span="11">
              <el-form-item
                label="文章封面"
                style="margin-bottom: 0px;"
                prop="img"
              >
                <el-upload
                  ref="uploadAvatar"
                  class="avatar-uploader"
                  action="#"
                  :show-file-list="false"
                  :on-change="changeAvatar"
                  :auto-upload="false"
                  :http-request="avatarUpload"
                  name="file"
                >
                  <img v-if="formData.img" :src="formData.img" class="avatar" />
                  <i v-else class="el-icon-plus avatar-uploader-icon" />
                </el-upload>
              </el-form-item>
            </el-col>
          </el-row>
          <el-form-item
            label="文章内容"
            style="margin-bottom: 0px;"
            prop="content"
          >
            <div>
              <tinymce v-model="formData.content" :height="200" />
            </div>
          </el-form-item>
        </el-form>

        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false">取 消</el-button>
          <el-button type="primary" @click="saveHandle('ruleForm')">
            {{ cmdTitle }}
          </el-button>
        </div>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import CURD from "@/mixin/CURD";
import { store as uploadImg } from "@/api/medias";
import Tinymce from "@/components/Tinymce";
import { update } from "@/api/article";
import { index } from "@/api/article_category";
export default {
  name: "articleIndex",
  mixins: [CURD],
  components: {
    Tinymce
  },
  data() {
    return {
      module: "article",
      newTitle: "新增文章",
      editTitle: "编辑文章",
      isPaginate: true,
      isUploadFile: false,
      fileReader: new FileReader(),
      isShow: false,
      imageUrl: "",
      categories: []
    };
  },
  async created() {
    let { data } = await index(1, 100);
    this.categories = data;
  },
  methods: {
    adjustOrder(row) {
      row.isEdit = true;
    },
    async saveOrder(row) {
      let data = {
        id: row.id,
        order: row.order,
        action: "order"
      };
      row.isEdit = false;
      let { info } = await update(data);
      this.fetchData();
    },
    transfromData(data) {
      let result = data.map((item) => {
        item.isEdit = false;
        return item;
      });
      return result;
    },
    saveHandle(form) {
      this.$refs[form].validate(async (valid) => {
        if (valid) {
          if (this.isUploadFile) {
            // 更改了图片，则必须先传图片
            this.$refs.uploadAvatar.submit();
          } else {
            this.save(form);
          }
        } else {
          this.$message.error("数据填写错误，请按照要求重新填写");
        }
      });
    },
    async save(form) {
      this.$refs[form].validate(async (valid) => {
        if (valid) {
          try {
            if (this.isEdit) {
              let { update } = require(`@/api/${this.module}`);
              let { info } = await update(this.formData);
              this.$message.success(info);
            }
            if (this.isNew) {
              let { store } = require(`@/api/${this.module}`);
              let { info } = await store(this.formData);
              this.$message.success(info);
            }
            this.fetchData();
            this.isEdit = false;
            this.isNew = false;
            this.dialogFormVisible = false;
          } catch (error) {
            let result = error.response.data;
            this.$message.error(result.info);
          }
        } else {
          this.$message.error("数据检验出错，请注意按照指定的规则输入");
          return false;
        }
      });
    },
    async avatarUpload(options) {
      const file = options.file;
      if (file) {
        this.fileReader.readAsDataURL(file);
      }
      this.fileReader.onload = async () => {
        const formData = new FormData();
        formData.append("file", file);
        uploadImg(formData)
          .then((res) => {
            const { data } = res;
            this.formData.img = data.url;
            this.save("ruleForm");
          })
          .catch((err) => {
            options.onError(err);
          });
      };
    },
    changeAvatar(file, fileList) {
      const reader = new window.FileReader();
      reader.readAsDataURL(new Blob([file.raw]));
      reader.onload = (e) => {
        this.formData.img = reader.result;
        this.imageUrl = reader.result;
        this.isUploadFile = true;
      };
    },
    async changeStatus(row) {
      let tips = "";
      if (row.status) {
        tips = "是否启用该文章?";
      } else {
        tips = "是否禁用该文章?";
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
