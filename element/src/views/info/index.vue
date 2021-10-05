<template>
  <div class="personal">
    <el-tabs v-model="tab" tab-position="left">
      <el-tab-pane label="基本设置" name="base">
        <el-row>
          <el-col :span="12">
            <el-form v-if="dialogFormVisible" ref="ruleForm" :model="formData">
              <!-- 修复表单自动提交 -->
              <el-input
                type="password"
                style="position:fixed;bottom: -9999px;"
              />
              <el-input
                type="password"
                style="position:fixed;bottom: -9999px;"
              />
              <!-- 这里面开始 -->
              <el-form-item label="登陆名" prop="email">
                <el-input v-model="formData.email" :disabled="isEdit" />
              </el-form-item>
              <el-form-item label="昵称" prop="nickname">
                <el-input v-model="formData.nickname" type="text" />
              </el-form-item>
              <el-form-item label="电话号码" prop="phone">
                <el-input v-model="formData.phone" />
              </el-form-item>
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
                <img
                  v-if="formData.avatar"
                  :src="formData.avatar"
                  class="avatar"
                />
                <i v-else class="el-icon-plus avatar-uploader-icon" />
              </el-upload>
            </el-form>
            <div style="margin-top: 10px;">
              <el-button type="primary" @click="saveData()">保 存</el-button>
            </div>
          </el-col>
        </el-row>
      </el-tab-pane>
      <el-tab-pane label="密码修改" name="reset">
        <el-row>
          <el-col :span="12">
            <el-form ref="pwdForm" :model="pwd" :rules="rules">
              <!-- 修复表单自动提交 -->
              <el-input
                type="password"
                style="position:fixed;bottom: -9999px;"
              />
              <el-input
                type="password"
                style="position:fixed;bottom: -9999px;"
              />
              <!-- 这里面开始 -->
              <el-form-item prop="old_password" label="旧密码">
                <el-input v-model="pwd.old_password" type="password" />
              </el-form-item>
              <el-form-item prop="password" label="新密码">
                <el-input v-model="pwd.password" type="password" />
              </el-form-item>
              <el-form-item prop="password_confirmation" label="确认密码">
                <el-input v-model="pwd.password_confirmation" type="password" />
              </el-form-item>
            </el-form>
            <div style="margin-top: 10px;">
              <el-button type="primary" @click="modifyPwd('pwdForm')"
                >修 改</el-button
              >
            </div>
          </el-col>
        </el-row>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import { modify } from "@/api/admin";
import { getInfo } from "@/api/user";
export default {
  name: "PersonalIndex",
  data() {
    const validatePass = (rule, value, callback) => {
      if (value === "") {
        callback(new Error("请输入密码"));
      } else {
        callback();
      }
    };
    var validatePass2 = (rule, value, callback) => {
      if (value === "") {
        callback(new Error("请再次输入密码"));
      } else if (value !== this.pwd.password) {
        callback(new Error("两次输入密码不一致!"));
      } else {
        callback();
      }
    };
    return {
      isEdit: true,
      isNew: false,
      tab: "base",
      dialogFormVisible: true,
      isUploadFile: false,
      fileReader: new FileReader(),
      pwd: {
        old_password: "",
        password: "",
        password_confirmation: ""
      },
      formData: {
        nickname: "",
        phone: "",
        email: "",
        avatar: ""
      },
      rules: {
        old_password: [
          { required: true, message: "请输入旧密码", trigger: "blur" }
        ],
        password: [
          { required: true, message: "请输入旧密码", trigger: "blur" },
          { validator: validatePass, trigger: "blur" }
        ],
        password_confirmation: [
          { required: true, message: "请输入旧密码", trigger: "blur" },
          { validator: validatePass2, trigger: "blur" }
        ]
      }
    };
  },
  async created() {
    const { data } = await getInfo();
    this.formData.nickname = data.nickname;
    this.formData.phone = data.phone;
    this.formData.email = data.email;
    this.formData.avatar = data.avatar;
  },
  methods: {
    modifyPwd(formName) {
      this.$refs[formName].validate(async (valid) => {
        if (valid) {
          this.pwd.action = "reset";
          try {
            const formData = new FormData();
            formData.append("action", this.pwd.action);
            formData.append("old_password", this.pwd.old_password);
            formData.append("password", this.pwd.password);
            formData.append(
              "password_confirmation",
              this.pwd.password_confirmation
            );
            const { info } = await modify(formData);
            this.$message.success(info);
          } catch (error) {
            const info = error.response.data.info;
            this.$message.error(info);
          }
        } else {
          this.$message.error("数据校验通过");
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
        formData.append("action", "update-avatar");
        formData.append("file", file);
        formData.append("nickname", this.formData.nickname);
        formData.append("email", this.formData.email);
        formData.append("phone", this.formData.phone);
        try {
          const { info } = await modify(formData);
          this.$message.success(info);
        } catch (error) {
          const info = error.response.data.info;
          this.$message.error(info);
        }
      };
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
    async saveData() {
      if (this.isUploadFile) {
        // 带图片上传数据和更新
        this.$refs.uploadAvatar.submit();
      } else {
        // 更新数据 不包括图片
        const formData = new FormData();
        formData.append("nickname", this.formData.nickname);
        formData.append("email", this.formData.email);
        formData.append("phone", this.formData.phone);
        try {
          const { info } = await modify(formData);
          this.$message.success(info);
        } catch (error) {
          const info = error.response.data.info;
          this.$message.error(info);
        }
      }
    }
  }
};
</script>

<style scoped>
.personal {
  padding: 20px;
}
.avatar-uploader >>> .el-upload {
  border: 1px dashed #399;
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
