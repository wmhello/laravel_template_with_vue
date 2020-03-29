<template>
  <div class="login-container">
    <div class="warp">
      <div class="imgButtons">
        <h1 style="color:#fff">
          <a target="_blank" href="https://github.com/wmhello/laravel_template_with_vue" alt="跳转到开源项目">
            <svg-icon icon-class="github" />
            <span>开源项目</span>
          </a>
        </h1>
        <h1 style="color:#fff">
          <a :href="docAddress" target="_blank">
            <svg-icon icon-class="article" />
            <span>文档</span>
          </a>
        </h1>
      </div>
      <div class="login">
        <h1 class="title">系统登录</h1>
        <ValidationObserver v-slot="{ invalid }">
          <el-form class="login-form">
            <ul class="login-list">
              <ValidationProvider name="用户名" rules="required"  mode="eager" v-slot="{ classes, errors, valid}">
                <li>
                  <img src="@/assets/login/user2.png" alt="" />
                  <input name="username" v-model="loginForm.username" type="text" placeholder="用户名" autocomplete="off" />
                </li>
                <span :class="`is-${valid}`">{{ errors[0] }}</span>
              </ValidationProvider>
              <ValidationProvider name="密码" rules="required|min:6"  mode="eager" v-slot="{ classes, errors, valid}">
                <li>
                  <img src="@/assets/login/password2.png" alt="" />
                  <input v-model="loginForm.password" name="password" :type="pwdType" placeholder="输入密码" autocomplete="off" />
                  <img src="@/assets/login/show2.png" v-if="pwdType === 'password'" alt="" style="margin-right: 10px;" @click="changeType" />
                  <img src="@/assets/login/no-show.png" v-if="pwdType === 'text'" alt="" style="margin-right: 10px;" @click="changeType" />
                </li>
                <span :class="`is-${valid}`">{{ errors[0] }}</span>
              </ValidationProvider>
            </ul>
            <div class="remember">
              <input v-model="loginForm.isAutoLogin" type="checkbox" class="e-selfecheckbox" id="place1" />
              <label class="selfecheckbox_label" for="place1">自动登陆</label>
            </div>
            <el-button
              :loading="loading"
              :disabled="invalid"
              type="primary"
              style="width:100%;margin-bottom:20px;margin-top: 20px;border-radius:8px; background-color: rgb(102,216,116);border: none;"
              @click.native.prevent="handleLogin"
            >
              登录
            </el-button>
          </el-form>
        </ValidationObserver>
      </div>
    </div>
  </div>
</template>

<script>
import { ValidationProvider, extend,  ValidationObserver } from 'vee-validate';
import {setIsAutoLogin} from '@/utils/auth'
export default {
  name: 'Login',
  data() {
    return {
      loginForm: {
        username: '',
        password: '',
        isAutoLogin: 0
      },
      pwdType: 'password',
      loginRules: {
        username: [{ required: true, trigger: 'blur', message: '登陆名必须填写' }, { min: 3, trigger: 'blur', message: '登陆名不少于三个字符' }],
        password: [{ required: true, trigger: 'blur', message: '登陆密码必须填写' }, { min: 3, trigger: 'blur', message: '登陆密码不少于6个字符' }]
      },
      loading: false,
      passwordType: 'password',
      redirect: undefined
    };
  },
  components: {
    ValidationProvider,
    ValidationObserver
  },
  watch: {
    $route: {
      handler: function(route) {
        this.redirect = route.query && route.query.redirect;
      },
      immediate: true
    }
  },
  created() {
    setIsAutoLogin(0)
  },
  methods: {
    changeType() {
      this.pwdType = this.pwdType === 'text' ? 'password' : 'text';
    },
    showPwd() {
      if (this.passwordType === 'password') {
        this.passwordType = '';
      } else {
        this.passwordType = 'password';
      }
      this.$nextTick(() => {
        this.$refs.password.focus();
      });
    },
    handleLogin() {
      let status = this.loginForm.isAutoLogin? 1: 0;
      setIsAutoLogin(status)
      this.loading = true;
      this.$store
        .dispatch('user/login', this.loginForm)
        .then(() => {
          this.$router.push({ path: this.redirect || '/' });
          this.loading = false;
        })
        .catch(error => {
          let result = error.response.data;
          this.$message.error(result.info);
          this.loading = false;
          this.loginForm = {
            username: '',
            password: ''
          };
        });
    }
  },
  computed: {
    docAddress() {
      let hostURL = process.env.VUE_APP_BASE_API;
      return hostURL + 'showdoc/web/#/1';
    }
  }
};
</script>

<style lang="scss" scoped>
.login-container {
  overflow: hidden;
  background: url(../../assets/login/bg.jpg);
  position: relative;
  width: 100vw;
  height: 100vh;
  & .imgButtons {
    position: fixed;
    right: 15px;
    & h1 {
      display: inline-block;
      margin-right: 20px;
      position: relative;
      width: 60px;
      & a:link span {
        display: none;
      }
      & a:hover span {
        display: inline;
        font-weight: normal;
        position: absolute;
        top: 50px;
        left: 1px;
        font-size: 12px;
      }
    }
  }
  .login {
    position: absolute;
    top: 40%;
    left: 50%;
    width: 620px;
    transform: translate(-50%, -50%);
    border: 1px solid rgb(216, 216, 216);
    border-bottom: 1px solid rgb(142, 142, 142);
    min-height: 340px;
    h1 {
      height: 60px;
      line-height: 60px;
      text-align: center;
      color: rgb(255, 255, 255);
      margin-bottom: 20px;
      letter-spacing: 5px;
    }
    .login-form {
      width: 400px;
      margin: 0 auto;
      .login-list {
        padding-left: 0;
        li {
          display: flex;
          padding-bottom: 3px;
          border-bottom: 1px solid rgb(243, 243, 243);
          margin-top: 10px;
          &:first-child {
            margin-top: 30px;
          }
          & img {
            width: 32px;
            height: 32px;
          }
          & input {
            padding: 0 10px;
            border: none;
            flex: 1;
            -webkit-appearance: none;
            background-color: transparent;
            color: rgb(233, 233, 233);
            &:focus {
              outline: none;
            }
            &::-webkit-input-placeholder {
              color: rgb(233, 233, 233);
              font-size: 16px;
            }
            &::-moz-placeholder {
              color: rgb(233, 233, 233);
              font-size: 16px;
            }
          }
        }
      }
    }
  }
}
.warp {
  position: absolute;
  top: 0px;
  left: 0px;
  right: 0px;
  bottom: 0px;
  background-color: rgba(133, 133, 133, 0.6);
}

.remember {
  display: flex;
  height: 30px;
  line-height: 30px;
  margin-top: 5px;
  margin-bottom: 10px;
}

.e-selfecheckbox {
  display: none;
}
.selfecheckbox_label {
  font-size: 16px;
  font-weight: 100;
  color: #eee;
}
.selfecheckbox_label:before {
  content: '';
  display: inline-block;
  vertical-align: middle;
  width: 13px;
  height: 13px;
  background-image: url('../../assets/login2/no-select.png');
  background-size: 100%;
  margin-right: 3px;
}

.e-selfecheckbox:checked + .selfecheckbox_label:before {
  background-image: url('../../assets/login2/select.png');
}

span.is-false{
  color:#ef0307;
  font-size: 12px;
  line-height: 20px;
  height: 20px;
}
</style>
