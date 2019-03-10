<template>
  <div class="app-container calendar-list-container">
    <el-row>
      <el-col :span="12">
        <div class="grid-content bg-purple">
          <el-form :model="editForm" :rules="editFormRules" ref="editForm" label-width="100px" class="demo-ruleForm">
            <el-form-item label="用户名" prop="username">
              <el-input type="text" v-model="editForm.username" disabled></el-input>
            </el-form-item>
            <el-form-item label="原密码" prop="password">
              <el-input type="password" v-model="editForm.password" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="密码" prop="newpassword">
              <el-input type="password" v-model="editForm.newpassword" auto-complete="off"></el-input>
            </el-form-item>
            <el-form-item label="确认密码" prop="newpassword1">
              <el-input type="password" v-model="editForm.newpassword1" auto-complete="off"></el-input>
            </el-form-item>
            <!-- <el-form-item label="头像">
              <my-upload field="file"
                         @crop-upload-success="formUpdateSuccess"
                         v-model="editUpdateShow"
                         :width="300"
                         :height="300"
                         url="/admin/user/upload"
                         :headers="headers"
                         img-format="png">
              </my-upload>
              <img :src="picUrl">
              <el-button type="primary" @click="updateShow" size="mini">选择<i class="el-icon-upload el-icon--right"></i></el-button>
            </el-form-item> -->
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
  import { modifyUser } from '@/api/user'

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
        } else if (value !== this.editForm.newpassword) {
          callback(new Error('两次输入密码不一致!'))
        } else {
          callback()
        }
      }
      return {
        fileList: [],
        editUpdateShow: false,
        editForm: {
          username: '',
          password: '',
          newpassword: '',
          newpassword1: '',
          picUrl: ''
        },
        editFormRules: {
          password: [{ required: true, message: '请输入密码', trigger: 'blur' }, { min: 5, max: 10, message: '长度在 5 到 10 个字符', trigger: 'blur' }],
          newpassword: [{ required: true, validator: validatePassword, trigger: 'blur' }, { min: 5, max: 10, message: '长度在 5 到 10 个字符', trigger: 'blur' }],
          newpassword1: [{ required: true, validator: validateNewPassword, trigger: 'blur' }, { min: 5, max: 10, message: '长度在 5 到 10 个字符', trigger: 'blur' }]
        }
      }
    },
    computed: {
      ...mapGetters([
        'name', 'picUrl'
      ])
    },
    created() {
      this.editForm.username = this.name
    },
    methods: {
      formSubmit(_from) {
        const f = this.$refs
        f[_from].validate(r => {
          if (!r) return r
          modifyUser(this.editForm).then(res => {
            if (!res.data) {
              this.$notify({ title: '失败', message: '修改失败', type: 'error', duration: 2000 })
            } else {
              this.dialogFormVisible = false
              this.formReset(_from)
              this.$notify({ title: '成功', message: '修改成功', type: 'success', duration: 2000 })
            }
          })
          return r
        })
      },
      formReset(_from) {
        this.$refs[_from].resetFields()
      },
      updateShow() {
        this.show = !this.show
      },
      formUpdateSuccess(data, field) {
        this.$store.commit('SET_PICURL', data.picUrl)
      }
    }
  }
</script>
