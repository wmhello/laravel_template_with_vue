<template>
  <div class="app-container calendar-list-container">
    <el-row>
      <el-col :span="12">
        <div class="grid-content bg-purple">
          <el-form :model="editForm" :rules="editFormRules" ref="editForm" label-width="100px" class="demo-ruleForm">
            <el-form-item label="昵称" prop="name">
              <el-input type="text" v-model="editForm.name" disabled></el-input>
            </el-form-item>
            <el-form-item label="原密码" prop="oldPassword">
              <el-input type="password" v-model="editForm.oldPassword" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="密码" prop="password">
              <el-input type="password" v-model="editForm.password" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="确认密码" prop="password_confirmation">
              <el-input type="password" v-model="editForm.password_confirmation" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item>
              <el-button type="primary" @click="formSubmit('editForm')">提交</el-button>
              <el-button @click="formReset('editForm')">重置</el-button>
            </el-form-item>
          </el-form>
        </div>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex'
  import myUpload from 'vue-image-crop-upload'
  import { modifyUser } from '@/api/admin'

  export default {
    components: { 'my-upload': myUpload },
    data() {
      var validatePassword = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('请输入密码'))
        } else {
          callback()
        }
      }
      var validateNewPassword = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('请再次输入密码'))
        } else if (value !== this.editForm.password_confirmation) {
          callback(new Error('两次输入密码不一致!'))
        } else {
          callback()
        }
      }
      return {
        fileList: [],
        editUpdateShow: false,
        editForm: {
          name: '',
          password: '',
          oldPassword: '',
          password_confirmation: ''
        },
        editFormRules: {
          password: [{ required: true, message: '请输入密码', trigger: 'blur' }, { min: 5, max: 10, message: '长度在 5 到 10 个字符', trigger: 'blur' }],
          oldPassword: [{ required: true, validator: validatePassword, trigger: 'blur' }, { min: 5, max: 10, message: '长度在 5 到 10 个字符', trigger: 'blur' }],
          password_confirmation: [{ required: true, validator: validateNewPassword, trigger: 'blur' }, { min: 5, max: 10, message: '长度在 5 到 10 个字符', trigger: 'blur' }]
        }
      }
    },
    computed: {
      ...mapGetters([
        'name'
      ])
    },
    created() {
      this.editForm.name = this.name
    },
    methods: {
      formSubmit(_from) {
        const f = this.$refs
        f[_from].validate(r => {
          if (!r) return r
          modifyUser(this.editForm).then(res => {
              this.dialogFormVisible = false
              this.formReset(_from)
              this.$alert('修改成功，之后将重新登录', '成功', {
                confirmButtonText: '确定',
                callback: action => {
                  this.$store.dispatch('user/resetToken').then(() => {
                    this.$router.push({ path: '/login'})
                  })
                }
              });
          }).catch(err=>{
            let result = err.response.data
            this.$message.error(result.info)
            this.editForm['password'] = '',
            this.editForm['oldPassword'] = ''
            this.editForm['password_confirmation'] = ''
          })
          return r
        })
      },
      formReset(_from) {
        this.$refs[_from].resetFields()
      },
      updateShow() {
        this.show = !this.show
      }
    }
  }
</script>
