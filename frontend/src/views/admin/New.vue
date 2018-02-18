<template>
  <div>
    <el-form :model="form" :rules="rules" ref="form" label-width="100px" class="demo-form">
      <el-form-item label="用户昵称" prop="name">
        <el-input v-model="form.name"></el-input>
      </el-form-item>
      <el-form-item label="邮箱" prop="email">
        <el-input v-model="form.email"></el-input>
      </el-form-item>
      <el-form-item label="密码" prop="password">
        <el-input  type="password" v-model="form.password"></el-input>
      </el-form-item>
      <el-form-item label="确认密码" prop="checkPass">
        <el-input type="password" v-model="form.checkPass" auto-complete="off"></el-input>
      </el-form-item>
      <el-form-item label="用户权限" prop="role">
        <el-select v-model="form.role" multiple placeholder="请选择权限">
          <el-option v-for="item in roles" :key="item.name" :label="item.explain" :value="item.name">{{item.explain}}</el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="用户头像">
           <avatar-uploader :image='imageUrl' :show-img='form.avatar'  @save="saveAvatarImg"></avatar-uploader>
      </el-form-item>

      <el-form-item>
        <el-button type="primary" @click="submitForm('form')">立即创建</el-button>
        <el-button @click="resetForm('form')">重置</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { getToken } from "@/utils/auth";
import { addInfo,uploadAdminByImg } from "@/api/admin";
import { getRoles } from "@/api/role";
import avatarUploader from "@/components/avatar";
import adminConfig from "@/../static/config";

function Admin(name='', email = '', role = 'user', password = '', checkPass = '', avatar = '')  {
  this.name = name
  this.email = email
  this.role = role
  this.password = password
  this.checkPass = checkPass
  this.avatar = avatar
}

export default {
  data () {
    var validatePass = (rule, value, callback) => {
      if (value.length < 6) {
        callback(new Error('密码不能小于6位'))
      }
      if (value === '') {
        callback(new Error('请输入密码'));
      } else {
        if (this.form.checkPass !== '') {
          this.$refs.form.validateField('checkPass');
        }
        callback();
      }
    };
    var validatePass2 = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请再次输入密码'));
      } else if (value !== this.form.password) {
        callback(new Error('两次输入密码不一致!'));
      } else {
        callback();
      }
    };

    return {
      imageUrl:'',
      form: new Admin(),
      roles: [],
      rules: {
        name: [
          { required: true, message: '请填写名称', trigger: 'blur' },
          { min: 3, max: 5, message: '长度在 3 到 5 个字符', trigger: 'blur' }
        ],
        email: [
          { required: true, message: '请输入邮箱地址', trigger: 'blur' },
          { type: 'email', message: '请输入正确的邮箱地址', trigger: 'blur,change' }
        ],
        role: [
          { required: true, message: '请选择权限', trigger: 'change' }
        ],
        password: [
          {required: true, validator: validatePass, trigger: 'blur' }
        ],
        checkPass: [
          {required: true, validator: validatePass2, trigger: 'blur' }
        ],
      }

    };
  },
  components:{
    avatarUploader
  },
  methods: {
    submitForm (formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          addInfo(this.form).then(response => {
              this.$alert('新管理员用户建立成功','友情提示', {
                callback: action => {
                   // 清空内容，重新开始建立
                   let administrator = new Admin()
                   this.form = administrator
                }
              })
          }).catch(error => {
              let result = error.response.data
              if (result.status_code == 422) {
              let message = [];
              let obj = result.message
              Object.keys(obj).forEach(item => {
                message.push(obj[item])
              })
              this.$notify.error({
                title: '后台数据校验出错',
                message: message.join('||')
              });
              }
          })
        } else {
          this.$message.error('数据校验不通过，请重新填写')
          return false;
        }
      });
    },
    resetForm (formName) {
      this.$refs[formName].resetFields();
    },
    saveAvatarImg(file) {
      let fd = new FormData();
      fd.append("photo", file);
      uploadAdminByImg(fd).then(res => {
        let url = res.data.url;
        this.imageUrl = adminConfig.site + url
        this.form.avatar = url
      });
      return true;
    }
  },
  mounted () {

  },
  beforeCreate() {
    getRoles().then(res => {
         this.roles = res.data
    })
    .catch(err => {
    })
  }
};
</script>

<style>

</style>
