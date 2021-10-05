<template>
  <div class="warpper">
    <div class="toolbar">
      <el-button type="primary" plain @click="add">添加用户</el-button>
      <el-button type="primary" plain @click="uploadData">用户导入</el-button>
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
        <el-table-column
          prop="nickname"
          label="昵称"
          width="150"
          align="center"
        />
        <el-table-column prop="email" label="登陆名" width="200" />
        <el-table-column prop="phone" label="电话号码" width="200" />
        <el-table-column label="角色" min-width="100">
          <template v-slot="scope">
            <el-tag
              v-for="(item, index) in scope.row.roles"
              :key="index"
              style="margin-right: 10px;"
            >
              {{ item.role_name }}</el-tag
            >
          </template>
        </el-table-column>

        <el-table-column label="头像" width="120">
          <template slot-scope="scope">
            <el-avatar
              v-if="scope.row.avatar"
              shape="square"
              size="medium"
              :src="scope.row.avatar"
            />
            <el-avatar v-else shape="square" size="medium" :src="squareUrl" />
          </template>
        </el-table-column>
        <el-table-column label="状态" width="160">
          <template v-slot="scope">
            <span v-if="scope.row.email === 'admin'" />
            <el-switch
              v-else
              v-model="scope.row.status"
              active-text="启用"
              inactive-text="禁用"
              @change="changeStatus(scope.row)"
            />
          </template>
        </el-table-column>
        <el-table-column label="操作" width="300">
          <template slot-scope="scope">
            <el-button
              v-if="scope.row.email !== 'admin'"
              plain
              @click="edit(scope.row.id)"
              >修改</el-button
            >
            <el-button
              v-if="scope.row.email !== 'admin'"
              plain
              @click.prevent="updatePassword(scope.row.id)"
              >重置密码</el-button
            >
            <el-button
              v-if="scope.row.email !== 'admin'"
              plain
              type="danger"
              @click="del(scope.row.id)"
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
    >
      <el-form
        v-if="dialogFormVisible"
        ref="ruleForm"
        :model="formData"
        :rules="rules"
      >
        <!-- 修复表单自动提交 -->
        <el-input type="password" style="position:fixed;bottom: -9999px;" />
        <el-input type="password" style="position:fixed;bottom: -9999px;" />
        <!-- 这里面开始 -->
        <el-row :gutter="20">
          <el-col :span="12">
            <el-form-item label="登陆名" prop="email">
              <el-input v-model="formData.email" :disabled="isEdit" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="昵称" prop="nickname">
              <el-input v-model="formData.nickname" type="text" />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="12">
            <el-form-item v-if="isNew" label="密码" prop="password">
              <el-input v-model="formData.password" type="password" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item
              v-if="isNew"
              label="密码确认"
              type="password"
              prop="password_confirmation"
            >
              <el-input
                v-model="formData.password_confirmation"
                type="password"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="12">
            <el-form-item label="用户角色" prop="roles">
              <el-select
                v-model="formData.roles"
                multiple
                clearable
                placeholder="请选择用户角色"
                style="width: 100%"
              >
                <el-option
                  v-for="item in roles"
                  :key="item.id"
                  :label="item.desc"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="电话号码" prop="phone">
              <el-input v-model="formData.phone" />
            </el-form-item>
          </el-col>
        </el-row>
        <el-form-item label="头像" style="margin-bottom: 0px;" />
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
          <img v-if="formData.avatar" :src="formData.avatar" class="avatar" />
          <i v-else class="el-icon-plus avatar-uploader-icon" />
        </el-upload>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="saveData()">{{ cmdTitle }}</el-button>
      </div>
    </el-dialog>

    <el-dialog
      title="修改密码"
      :visible.sync="dialogFormPasswordVisible"
      width="30%"
    >
      <el-form>
        <el-form-item label="请输入新密码">
          <el-input v-model="password" autocomplete="off" type="password" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormPasswordVisible = false">取 消</el-button>
        <el-button type="primary" @click="savePassword">确 定</el-button>
      </div>
    </el-dialog>
    <UploadXls :show.sync="isShow" :module="module" />
  </div>
</template>

<script>
import CURD from "@/mixin/CURD";
import { update } from "@/api/admin";
import { index as getRoles } from "@/api/role";
import { store as uploadImg } from "@/api/media";
import UploadXls from "@/components/UploadXls";
export default {
  name: "AdminIndex",
  components: {
    UploadXls: UploadXls
  },
  mixins: [CURD],
  data() {
    return {
      module: "admin",
      newTitle: "新建用户信息",
      editTitle: "编辑用户信息",
      imageUrl: "",
      dialogFormPasswordVisible: false,
      password: "",
      id: "",
      roles: [],
      isUploadFile: false,
      importDialogVisible: false,
      fileReader: new FileReader(),
      isShow: false,
      squareUrl:
        "https://cube.elemecdn.com/9/c2/f0ee8a3c7c9638a54940382568c9dpng.png"
    };
  },
  async created() {
    const { data } = await getRoles();
    this.roles = data;
  },

  methods: {
    uploadData() {
      this.isShow = true;
    },
    async edit(id) {
      const { show } = require(`@/api/${this.module}`);
      const { data } = await show(id);
      // eslint-disable-next-line prefer-const
      let result = data;
      let arr = [];
      arr = result.roles.map((item) => {
        return item.role_id;
      });
      result.roles = arr;
      this.formData = result;
      this.isNew = false;
      this.isEdit = true;
      this.setTitle();
      this.dialogFormVisible = true;
    },
    async changeStatus(content) {
      this.$confirm("此操作将修改用户状态, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(async () => {
          const data = {
            id: content.id,
            status: content.status ? 1 : 0,
            action: "status"
          };
          const { info } = await update(data);
          this.$message.success(info);
        })
        .catch(() => {
          // 返回原来的状态
          content.status = !content.status;
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
            this.formData.avatar = data.url;
            this.save("ruleForm");
          })
          .catch((err) => {
            options.onError(err);
          });
      };
    },
    updatePassword(id) {
      this.id = id;
      this.dialogFormPasswordVisible = true;
    },
    async savePassword() {
      if (this.password.length >= 6 && this.password.length <= 20) {
        const data = {
          id: this.id,
          action: "reset",
          password: this.password
        };
        const { info } = await update(data);
        this.$message.success(info);
        this.dialogFormPasswordVisible = false;
      } else {
        this.$message.error("密码长度必须在6-20个字符之间");
      }
    },
    changeAvatar(file, fileList) {
      const reader = new window.FileReader();
      reader.readAsDataURL(new Blob([file.raw]));
      reader.onload = (e) => {
        this.formData.avatar = reader.result;
        this.imageUrl = reader.result;
        this.isUploadFile = true;
      };
    },
    saveData() {
      if (this.isUploadFile) {
        this.$refs.uploadAvatar.submit();
      } else {
        this.save("ruleForm");
      }
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
.el-form table tbody {
  width: 100%;
}
.el-form .header {
  box-sizing: border-box;
  border: 1px solid #ccc;
  background-color: #09f;
  color: #fff;
  height: 44px;
  display: flex;
  flex-direction: row;
}
.el-form .header .title {
  margin: auto;
  font-weight: bold;
}
.el-form .content {
  display: flex;
  box-sizing: border-box;
  border: 1px solid #ccc;
  background-color: #0cf;
  color: #fff;
  height: 44px;
  flex-direction: row;
}
.el-form .content div {
  margin: auto;
  border-radius: 0px;
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
.avatar {
  width: 178px;
  height: 178px;
  display: block;
}
</style>
