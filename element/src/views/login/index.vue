<template>
  <div class="login-container">
    <el-form ref="loginForm" :model="loginForm" :rules="loginRules" class="login-form" auto-complete="on"
      label-position="left">
      <div class="title-container">
        <h3 class="title">信息管理系统</h3>
      </div>

      <el-form-item prop="username">
        <span class="svg-container">
          <svg-icon icon-class="user" />
        </span>
        <el-input ref="username" v-model="loginForm.username" placeholder="请输入用户名或者手机号码 默认为admin" name="username"
          type="text" tabindex="1" auto-complete="on" />
      </el-form-item>

      <el-form-item prop="password">
        <span class="svg-container">
          <svg-icon icon-class="password" />
        </span>
        <el-input :key="passwordType" ref="password" v-model="loginForm.password" :type="passwordType"
          placeholder="密码默认为123456" name="password" tabindex="2" auto-complete="on" @keyup.enter.native="handleLogin" />
        <span class="show-pwd" @click="showPwd">
          <svg-icon :icon-class="passwordType === 'password' ? 'eye' : 'eye-open'" />
        </span>
      </el-form-item>

      <el-button :loading="loading" type="primary" style="width:100%;margin-bottom:30px;"
        @click.native.prevent="handleLogin">登录</el-button>
      <el-row>
        <!-- <span class="oath-item" @click="oauth('github')">
          <svg-icon
            icon-class="github"
            style="width:40px;height:40px;color:#f80;"
          />
        </span>
        <span class="oath-item" @click="oauth('qq')">
          <svg-icon
            icon-class="qq"
            style="width:40px;height:40px;color:#f80;"
          />
        </span> -->
        <span class="oath-item" @click="oauth('gitee')"  v-if = "isThreeLogin" >
          <svg-icon icon-class="gitee" style="width:40px;height:40px;color:#f80;" />
        </span>
      </el-row>
    </el-form>
  </div>
</template>

<script>
  import {
    setIsAutoLogin
  } from "@/utils/auth";
  import {
    openWindow
  } from "@/utils/tools";
  import Echo from "laravel-echo";
  import {
    uuid
  } from "@/utils/tools.js";

  export default {
    name: "Login",
    data() {
      const validatePassword = (rule, value, callback) => {
        if (value.length < 6) {
          callback(new Error("密码长度不能少于6位"));
        } else {
          callback();
        }
      };
      return {
        uuid: uuid(), // 进行身份验证，编译websocket发送数据到指定的用户页面
        oauthURL: {
          github: process.env.VUE_APP_BASE_API + `/login/github?address=${
          process.env.VUE_APP_GITHUB_CALLBACK
        }`,
          qq: "",
          gitee: process.env.VUE_APP_BASE_API + `/login/gitee?address=${
          process.env.VUE_APP_GITEE_CALLBACK
        }`
        },
        loginForm: {
          username: "",
          password: ""
        },
        loginWindow: null,
        loginRules: {
          username: [{
            required: true,
            trigger: "blur"
          }],
          password: [{
            required: true,
            trigger: "blur",
            validator: validatePassword
          }]
        },
        loading: false,
        passwordType: "password",
        redirect: undefined
      };
    },
    computed:{
      isThreeLogin() {
        return process.env.VUE_APP_THREE_LOGIN === 'ON'
      }
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
      setIsAutoLogin(0);
    },
    mounted() {
      // if (process.env.VUE_APP_WEBSOCKET === "ON") {
      //   window.io = require("socket.io-client");
      //   const hostURL = process.env.VUE_APP_BASE_API;
      //   let host = "";
      //   if (hostURL.indexOf("/api/admin") > 0) {
      //     const end = hostURL.indexOf("/api/admin");
      //     host = hostURL.substring(0, end);
      //   } else {
      //     host = hostURL;
      //   }
      //   const data = {
      //     auth: {},
      //     broadcaster: "socket.io",
      //     host: host + ":6001"
      //   };
      //   window.Echo = new Echo(data);
      //   window.Echo.channel(`success.${this.uuid}`).listen("ThreeLogin", (e) => {
      //     window.threeLogin.close();
      //   });
      // }
    },
    methods: {
      oauth(url) {
        const webURL = this.oauthURL[url] + "&uuid=" + this.uuid;
        openWindow(webURL, "第三方登陆", 400, 250);
      },
      showPwd() {
        if (this.passwordType === "password") {
          this.passwordType = "";
        } else {
          this.passwordType = "password";
        }
        this.$nextTick(() => {
          this.$refs.password.focus();
        });
      },
      handleLogin() {
        this.$refs.loginForm.validate((valid) => {
          if (valid) {
            this.loading = true;
            this.$store
              .dispatch("user/login", this.loginForm)
              .then(() => {
                this.$router.push({
                  path: this.redirect || "/"
                });
                this.loading = false;
              })
              .catch((error) => {
                const result = error.response.data;
                this.$message({
                  message: result.info,
                  type: "error",
                  duration: 5 * 1000
                });
                this.loading = false;
              });
          } else {
            console.log("error submit!!");
            return false;
          }
        });
      }
    }
  };
</script>

<style lang="scss">
  /* 修复input 背景不协调 和光标变色 */
  /* Detail see https://github.com/PanJiaChen/vue-element-admin/pull/927 */

  $bg: #283443;
  $light_gray: #fff;
  $cursor: #fff;

  @supports (-webkit-mask: none) and (not (cater-color: $cursor)) {
    .login-container .el-input input {
      color: $cursor;
    }
  }

  .oath-item {
    margin-right: 40px;
    cursor: pointer;
  }

  /* reset element-ui css */
  .login-container {
    .el-input {
      display: inline-block;
      height: 47px;
      width: 85%;

      input {
        background: transparent;
        border: 0px;
        -webkit-appearance: none;
        border-radius: 0px;
        padding: 12px 5px 12px 15px;
        color: $light_gray;
        height: 47px;
        caret-color: $cursor;

        &:-webkit-autofill {
          box-shadow: 0 0 0px 1000px $bg inset !important;
          -webkit-text-fill-color: $cursor !important;
        }
      }
    }

    .el-form-item {
      border: 1px solid rgba(255, 255, 255, 0.1);
      background: rgba(0, 0, 0, 0.1);
      border-radius: 5px;
      color: #454545;
    }
  }
</style>

<style lang="scss" scoped>
  $bg: #2d3a4b;
  $dark_gray: #889aa4;
  $light_gray: #eee;

  .login-container {
    min-height: 100%;
    width: 100%;
    background-color: $bg;
    overflow: hidden;

    .login-form {
      position: relative;
      width: 520px;
      max-width: 100%;
      padding: 160px 35px 0;
      margin: 0 auto;
      overflow: hidden;
    }

    .tips {
      font-size: 14px;
      color: #fff;
      margin-bottom: 10px;

      span {
        &:first-of-type {
          margin-right: 16px;
        }
      }
    }

    .svg-container {
      padding: 6px 5px 6px 15px;
      color: $dark_gray;
      vertical-align: middle;
      width: 30px;
      display: inline-block;
    }

    .title-container {
      position: relative;

      .title {
        font-size: 26px;
        color: $light_gray;
        margin: 0px auto 40px auto;
        text-align: center;
        font-weight: bold;
      }
    }

    .show-pwd {
      position: absolute;
      right: 10px;
      top: 7px;
      font-size: 16px;
      color: $dark_gray;
      cursor: pointer;
      user-select: none;
    }
  }
</style>
