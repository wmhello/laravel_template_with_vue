<template>
  <div class="warpper">
    <div class="toolbar" v-permission="['carousels.store']">
      <el-button
        type="primary"
        plain
        @click="add"
        v-permission="['carousels.store']"
        >新增轮播图</el-button
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
          prop="title"
          label="标题"
          min-width="200"
          align="center"
        />
        <el-table-column label="轮播图" width="150" height="150">
          <template v-slot="scope">
            <img :src="scope.row.img" class="avatar" />
          </template>
        </el-table-column>
        <el-table-column
          prop="url"
          label="轮播地址"
          min-width="200"
          align="center"
        />
        <el-table-column
          prop="opentype"
          label="打开方式"
          min-width="200"
          align="center"
        />
        <el-table-column
          prop="carousel_note"
          label="轮播图说明"
          min-width="200"
          align="center"
        />
        <el-table-column label="操作" width="200">
          <template slot-scope="scope">
            <el-button
              plain
              @click="edit(scope.row.id)"
              v-permission="['carousels.update']"
              >编辑</el-button
            >
            <el-button
              plain
              type="danger"
              @click="del(scope.row.id)"
              v-permission="['carousels.destroy']"
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
          <el-form-item label="轮播图标题" prop="title">
            <el-input v-model="formData.title"></el-input>
          </el-form-item>
          <el-form-item label="轮播图说明" prop="carousel_note">
            <el-input v-model="formData.carousel_note"></el-input>
          </el-form-item>
          <el-form-item label="跳转地址" prop="url">
            <el-input v-model="formData.url"></el-input>
          </el-form-item>
          <el-form-item label="跳转方式" prop="opentype">
            <el-select v-model="formData.opentype">
              <el-option v-for="item in types" :key="item" :value="item">{{
                item
              }}</el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="轮播图" style="margin-bottom: 0px;" prop="img">
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
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false">取 消</el-button>
          <el-button
            type="primary"
            v-if="isNew"
            v-permission="['carousels.store']"
            @click="saveHandle('ruleForm')"
            >{{ cmdTitle }}</el-button
          >
          <el-button
            type="primary"
            v-if="isEdit"
            v-permission="['carousels.update']"
            @click="saveHandle('ruleForm')"
            >{{ cmdTitle }}</el-button
          >
        </div>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import CURD from "@/mixin/CURD";
import { store as uploadImg } from "@/api/medias";
import UploadXls from "@/components/UploadXls";

export default {
  mixins: [CURD],
  components: {
    UploadXls: UploadXls
  },
  data() {
    return {
      module: "carousel",
      newTitle: "新增轮播图",
      editTitle: "编辑轮播图",
      isPaginate: true,
      isUploadFile: false,
      fileReader: new FileReader(),
      isShow: false,
      imageUrl: "",
      types: ["navigate", "switchTab"]
    };
  },
  methods: {
    exportFile(fileName, res) {
      const content = res;
      const blob = new Blob([content], {
        type: "application/vnd.ms-excel;charset=utf-8"
      });

      // 非IE下载
      const elink = document.createElement("a");
      elink.download = fileName;
      elink.style.display = "none";
      elink.href = window.URL.createObjectURL(blob);
      document.body.appendChild(elink);
      elink.click();
      window.URL.revokeObjectURL(elink.href); // 释放 URL对象
      document.body.removeChild(elink);
    },
    uploadData() {
      this.isShow = true;
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
          console.log("图片上传成功 ");
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
