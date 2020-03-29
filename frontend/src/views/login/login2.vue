<template>
  <div class="warp">
    <ul class="imgButtons">
      <li class="item">
        <a target="_blank" data-msg="开源项目" href="https://github.com/wmhello/laravel_template_with_vue" alt="跳转到开源项目"><svg-icon icon-class="github" /></a>
      </li>
      <li class="item">
        <a :href="docAddress" data-msg="使用文档" target="_blank"><svg-icon icon-class="article" /></a>
      </li>
    </ul>
    <div class="login-container">
      <h2>欢迎登陆管理系统</h2>
      <ValidationObserver v-slot="{ invalid }">
        <form action="" class="login-form">
          <ul>
            <ValidationProvider name="username" mode="eager" rules="required" v-slot="{ classes, errors, valid}">
              <li>
                <img src="@/assets/login2/user.png" alt="" />
                <input type="text" v-model="loginForm.username" class="username" placeholder="用户名(871228582@qq.com)" autocomplete="off" />
              </li>
              <span :class="`is-${valid}`">{{ errors[0] }}</span>
            </ValidationProvider>
            <ValidationProvider name="password" mode="eager" rules="required|min:6" v-slot="{ classes, errors, valid}">
            <li>
              <img src="@/assets/login2/password.png" alt="" />
              <input type="password" v-model="loginForm.password" class="password" placeholder="密码(123456)" autocomplete="off" />
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
              style="width:100%;margin-bottom:20px;border-radius:8px; background-color: rgb(66, 139, 202);border: none;"
              @click.native.prevent="handleLogin"
            >
              登录
            </el-button>
        </form>
      </ValidationObserver>
    </div>
  </div>
</template>

<script>
import { ValidationProvider, ValidationObserver} from 'vee-validate';
import {setIsAutoLogin} from '@/utils/auth.js'

export default {
  data() {
    return {
      loginForm:{
        username: '',
        password: '',
        isAutoLogin: 0
      },
      loading: false
    };
  },
  components: {
    ValidationProvider,
    ValidationObserver
  },
  computed: {
    docAddress() {
      let hostURL = process.env.VUE_APP_BASE_API;
      return hostURL + 'showdoc/web/#/1';
    }
  },
  created() {
    setIsAutoLogin(0)
  },
  methods: {
    handleLogin() {
      let status = this.loginForm.isAutoLogin?1: 0;
      this.loading = true;
      setIsAutoLogin(status)
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
  }
};
</script>

<style scoped="scss">
.warp {
  width: 100vw;
  height: 100vh;
  position: relative;
  background-image: url(../../assets/login2/warp.jpg);
  background-size: 100% 100%;
}
.imgButtons {
  position: absolute;
  right: 25px;
  top: 0;
}
.imgButtons .item {
  display: inline-block;
  margin-left: 20px;
}
.imgButtons .item a {
  color: #fff;
  display: block;
  width: 32px;
  height: 32px;
  position: relative;
}
.imgButtons .item a::after {
  position: absolute;
  left: -16px;
  top: 42px;
  width: 200%;
  height: 100%;
  text-align: center;
  font-size: 12px;
  content: attr(data-msg);
  transition: all 500ms;
  color: #fff;
  opacity: 0;
}
.imgButtons .item a:hover::after {
  opacity: 1;
}
.imgButtons .item a svg {
  width: 100%;
  height: 100%;
}

.login-container {
  position: absolute;
  border: 1px solid #08f;
  min-height: 360px;
  overflow: hidden;
  border-radius: 5px;
  width: 20%;
  right: 19.5%;
  top: 26%;
  bottom: 31%;
  background-color: rgb(242, 247, 253);
  padding: 0;
  margin: 0;
}

.login-container h2 {
  text-align: center;
  color: #eee;
  height: 40px;
  line-height: 40px;
  background-color: rgb(56, 126, 187);
  letter-spacing: 5px;
  font-family: 'Microsoft YaHei';
  font-size: 18px;
  margin: 0;
}

.login-form {
  margin: 20px;
  margin-top: 10%;
}

.login-form ul {
  border-radius: 5px;
  background-color: #fff;
  padding: 5px 20px;
}

.login-form ul li {
  display: flex;
  border-bottom: 1px solid rgb(235, 245, 254);
  height: 40px;
  line-height: 40px;
  margin-bottom: 10px;
}

.login-form ul li.is-true{
  border-bottom: 1px solid #2d8672;
}

.login-form ul li.is-false{
  border-bottom: 1px solid #ef0307;
}

span.is-false{
  color:#ef0307;
  font-size: 12px;
  line-height: 20px;
  height: 20px;
}
.login-form ul li:last-child {
  border-bottom: none;
  margin-bottom: 0px;
}

.login-form ul li img {
  width: 32px;
  height: 32px;
}

.login-form ul li input {
  border: none;
  padding-left: 20px;
  -webkit-appearance: none;
  flex: 1;
}

.login-form ul li input:focus {
  outline: none;
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
.submit {
  padding: 0;
  margin: 0;
}
.submit a {
  display: block;
  text-align: center;
  height: 40px;
  line-height: 40px;
  background-color: rgb(66, 139, 202);
  color: #fff;
  letter-spacing: 2px;
  border-radius: 5px;
}

.submit a:hover {
  text-decoration: none;
}
</style>
