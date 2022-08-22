<template>
  <div class="login-container">
    <el-form
      ref="loginForm"
      :model="loginForm"
      :rules="loginRules"
      class="login-form"
      auto-complete="on"
      label-position="left"
    >
      <div class="title-container">
        <h3 class="title">信息管理系统</h3>
      </div>

      <el-form-item prop="username">
        <span class="svg-container">
          <svg-icon icon-class="user" />
        </span>
        <el-input
          ref="username"
          v-model="loginForm.username"
          placeholder="请输入用户名或者手机号码 默认为admin"
          name="username"
          type="text"
          tabindex="1"
          auto-complete="on"
        />
      </el-form-item>

      <el-form-item prop="password">
        <span class="svg-container">
          <svg-icon icon-class="password" />
        </span>
        <el-input
          :key="passwordType"
          ref="password"
          v-model="loginForm.password"
          :type="passwordType"
          placeholder="密码默认为123456"
          name="password"
          tabindex="2"
          auto-complete="on"
          @keyup.enter.native="handleLogin"
        />
        <span class="show-pwd" @click="showPwd">
          <svg-icon
            :icon-class="passwordType === 'password' ? 'eye' : 'eye-open'"
          />
        </span>
      </el-form-item>

      <el-button
        :loading="loading"
        type="primary"
        style="width: 100%; margin-bottom: 30px"
        @click.native.prevent="handleLogin"
        >{{ cmdTitle }}</el-button
      >
      <el-row>
        <span class="oath-item" @click="oauth('gitee')" v-if="isThreeLogin">
          <svg-icon
            icon-class="gitee"
            style="width: 40px; height: 40px; color: #f80"
          />
        </span>
      </el-row>
    </el-form>
  </div>
</template>

<script>
import { setIsAutoLogin } from "@/utils/auth";
import { openWindow } from "@/utils/tools";
import { uuid } from "@/utils/tools.js";
import web from "@/utils/socket";
import setting from "@/settings";
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
      loading: false,
      cmdTitle: "登录",
      uuid: uuid(), // 进行身份验证，编译websocket发送数据到指定的用户页面
      oauthURL: {
        github:
          process.env.VUE_APP_BASE_API +
          `/login/github?address=${process.env.VUE_APP_GITHUB_CALLBACK}`,
        qq: "",
        gitee:
          process.env.VUE_APP_BASE_API +
          `/login/gitee?address=${process.env.VUE_APP_GITEE_CALLBACK}`,
      },
      loginForm: {
        username: "",
        password: "",
      },
      loginWindow: null,
      loginRules: {
        username: [
          {
            required: true,
            trigger: "blur",
          },
        ],
        password: [
          {
            required: true,
            trigger: "blur",
            validator: validatePassword,
          },
        ],
      },
      loading: false,
      passwordType: "password",
      redirect: undefined,
      timer: {},
      websock: null,
    };
  },
  computed: {
    isThreeLogin() {
      return process.env.VUE_APP_THREE_LOGIN === "ON";
    },
  },
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      if ("WebSocket" in window && setting.isWebsocket) {
        vm.loading = true;
        vm.cmdTitle = "服务器连接中...";
        vm.loginWebsocket();
      }
    });
  },
  created() {
    setIsAutoLogin(0);
    // web.initWebpack.bind(this)();
  },
  methods: {
    websocketonmessage(e) {
      //数据接收
      var res = JSON.parse(e.data);
      if (res && res.type === "init") {
        // 初始化,保留client_id,准备去做绑定数据  开发阶段显示提示
        if (process.env.NODE_ENV === "development") {
          console.log("初始化并返回client_id");
        }
        // 如果已经存在定时器，则说明说明之前的websocket连接成功了
        if (this.$bus.timer1) {
          return false;
        }
        window.localStorage.setItem("uuid", res.client_id);
        this.loading = false;
        this.cmdTitle = "登录";
        // 清除所有的等待连接的定时器任务
        for (let i = 1; i <= 5; i++) {
          clearTimeout(this.timer[`websocket${i}`]);
        }
        let that = this;
        this.$bus.timer1 = setInterval(() => {
          that.websock.send(
            JSON.stringify({
              type: "ping",
            })
          );
        }, 30 * 1000);
      } else {
        // 存在特定业务的处理逻辑，则调用
        let uuid = window.localStorage.getItem("uuid");
        if (window.websocketHandle && window.websocketHandle[res.type]) {
          // 后端放回的数据类型，进行识别
          // 如果用户标识为all 或者说没有uuid，则发送给全部
          if (res.select === "all" || !res.hasOwnProperty("uuid")) {
            window.websocketHandle[res.type](res.content, res);
          } else {
            // 发送到指定的设备
            if (
              res.select === "one" &&
              typeof res.uuid === "string" &&
              res.uuid === uuid
            ) {
              window.websocketHandle[res.type](res.content, res);
            }
            // 发送到组，包含多个设备
            if (
              res.select === "includes" &&
              Array.isArray(res.uuid) &&
              res.uuid.includes(uuid)
            ) {
              window.websocketHandle[res.type](res.content, res);
            }
            // 除了本设备以外
            if (
              res.select === "except" &&
              typeof res.uuid === "string" &&
              res.uuid !== uuid
            ) {
              window.websocketHandle[res.type](res.content, res);
            }
            // 排除本分组内的设备
            if (
              res.select === "except" &&
              Array.isArray(res.uuid) &&
              !res.uuid.includes(uuid)
            ) {
              window.websocketHandle[res.type](res.content, res);
            }
          }
        } else {
          if (process.env.NODE_ENV === "development") {
            console.log("没有注册处理程序");
            console.log(res);
          }
        }
      }
    },
    websocketclose() {
      //关闭
      console.log("WebSocket关闭");
      // initWebpack(this.token)
    },
    websocketerror() {
      //失败
      console.log("WebSocket连接失败");
    },
    websocketopen() {
      //打开
    },
    loginWebsocket() {
      // 连续5次发送新建websocket等待请求，2秒钟一次，这样的话，
      // 只要有一次完成就把所有定时器都清除，然后设置为允许登录

      for (let i = 1; i <= 5; i++) {
        this.timer[`websocket${i}`] = null;
        this.timer[`websocket${i}`] = setTimeout(() => {
          let url = setting.websocketUrl || "ws://127.0.0.1:1800";
          this.websock = new WebSocket(url); //这里面的this都指向vue
          this.websock.onopen = this.websocketopen;
          this.websock.onmessage = this.websocketonmessage;
          this.websock.onclose = this.websocketclose;
          this.websock.onerror = this.websocketerror;
        }, 2000 * i);
      }
    },
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
      this.$refs.loginForm.validate(async (valid) => {
        if (valid) {
          this.loading = true;
          try {
            await this.$store.dispatch("user/login", this.loginForm);
            this.loading = false;
            this.$router.push({
              path: "/",
            });
          } catch (error) {
            console.log(error);
            this.loading = false;
          }
        } else {
          console.log("error submit!!");
          return false;
        }
      });
    },
  },
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
