<template>
  <div
    class="wapper"
    v-loading="loading"
    :element-loading-text="loadTips"
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.8)"
  >
    <div class="title">页面测试(普通用户)</div>
    <div class="content">
      <div class="sidebar">
        <div class="main">
          <div class="dialog-box">
            <div v-for="(item, index) in msgList" :key="index">
              <chat-item :item="item" :name="name"></chat-item>
            </div>
          </div>
        </div>
        <div class="msg">
          <!-- <el-input type="textarea" v-model="msg"></el-input> -->
          <div class="msg-box" style="" contenteditable v-model="msg"></div>
          <div class="cmd">
            <el-button type="primary" plain size="small" @click="sendMsg"
              >发送</el-button
            >
          </div>
        </div>
      </div>
      <nav>
        <h4>常见问题</h4>
        <ol @click="selectList">
          <li>1.技术栈</li>
          <li>2.如何联系作者</li>
        </ol>
      </nav>
    </div>
  </div>
</template>

<script>
import moment from "moment";
import ChatItem from "@/components/Chat/item.vue";
import { mapGetters } from "vuex";
import setting from "@/settings";
import {
  checkCustomer,
  getCustomer,
  userLeave,
  sendDataToCustomer,
} from "@/api/service";
export default {
  components: {
    ChatItem,
  },
  name: "ServiceIndex",
  computed: {
    ...mapGetters(["name", "avatar"]),
  },
  data() {
    return {
      msg: "",
      msgList: [],
      loading: false,
      loadTips: "身份不对，不能使用该功能",
    };
  },
  beforeRouteEnter(to, from, next) {
    next(async (vm) => {
      // 通过 `vm` 访问组件实例
      let { data } = await getCustomer();
      let { customer } = data;
      if (vm.name === customer) {
        vm.$alert(
          `${customer}用户被默认设置为客服，该页面是普通的咨询用户页面`,
          "友情提示",
          {
            confirmButtonText: "确定",
            callback: (action) => {
              vm.loading = true;
            },
          }
        );
      } else {
        try {
          await checkCustomer({
            uuid: window.localStorage.getItem("uuid"),
          });
        } catch (error) {
          vm.loading = true;
          let res = error.response.data;
          // 如果有从后台返回的错误信息，则直接使用
          if ("info" in res) {
            vm.loadTips = res.info;
          } else {
            vm.loadTips = "客服不在线,无法使用该功能";
          }
        }
      }
    });
  },
  async beforeRouteLeave(to, from, next) {
    // 正常时候退出才会去发送退出消息给客服
    if (!this.loading) {
      try {
        await userLeave();
        delete window.websocketHandle.customerLogin;
        // // 客户退出咨询
        delete window.websocketHandle.customerLogout;
        // 接收到用户发送来的数据执行的代码
        delete window.websocketHandle.customerSay;
      } catch (error) {}
      next();
    } else {
      next();
    }
  },
  created() {
    if (setting.isWebsocket && !this.loading) {
      // 开启websocket
      if (!window.websocketHandle) {
        window.websocketHandle = {};
      }
      // // 收到客服发送来的消息，处理的方法
      window.websocketHandle.customerLogin = this.onCustomerLogin;
      // // 客户退出咨询
      window.websocketHandle.customerLogout = this.onCustomerLogout;
      // 接收到用户发送来的数据执行的代码
      window.websocketHandle.customerSay = this.onCustomerSay;
    }
  },
  mounted() {
    let height = document.documentElement.clientHeight;
    let app = document.querySelector(".app-main");
    app.style.height = height - 84 + "px";
  },
  methods: {
    // 用户先登录，然后客服登录之后的处理
    async onCustomerLogin(item, res) {
      await checkCustomer({
        uuid: window.localStorage.getItem("uuid"),
      });
      this.$notify({
        title: "友情提示",
        message: `客服${res.name}已经登录，可以咨询。`,
        offset: 100,
      });
      this.loading = false;
    },
    // 客户退出之后的处理
    async onCustomerLogout(item, res) {
      this.loading = true;
      this.loadTips = "客服已经退出，无法通讯";
    },
    // 接收到客服发送来的信息的处理方式
    onCustomerSay(item, res) {
      this.msgList.push(res);
    },
    async sendMsg() {
      this.msg = document.querySelector(".msg-box").innerText;
      document.querySelector(".msg-box").innerText = "";
      let data = {
        name: this.name,
        content: this.msg,
        avatar: this.avatar,
        time: moment(new Date()).format("HH:mm"),
      };
      this.msgList.push(data);
      await sendDataToCustomer(data);
    },
    selectList(e) {
      let item = parseInt(e.srcElement.innerText);
      let data = {};
      switch (item) {
        case 1:
          data = {
            name: "自动回复",
            content: "后端laravel、前端vue.js、通讯使用了workerman",
            avatar: null,
            time: moment(new Date()).format("HH:mm"),
          };
          this.msgList.push(data);
          break;
        case 2:
          data = {
            name: "自动回复",
            content: "作者的微信是xpyzwm,需要技术支持和辅导可以联系",
            avatar: null,
            time: moment(new Date()).format("HH:mm"),
          };
          this.msgList.push(data);
          break;
        default:
          break;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.wapper {
  min-height: 600px;
  min-width: 1000px;
  background-color: #fbfbfb;
  border: 1px solid #dbdbdb;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  box-sizing: border-box;
  & .title {
    padding-left: 10px;
    height: 60px;
    line-height: 60px;
    width: 100%;
    border-bottom: 1px solid #dadada;
  }
}
.content {
  height: 540px;
  width: 100%;
  display: flex;
  flex-direction: row;
}
.content > .sidebar {
  width: 72%;
  height: 100%;
  display: flex;
  flex-direction: column;
}
.content > .sidebar .main {
  flex: 2;
}
.content > .sidebar .msg {
  flex: 1;
  border-top: 1px solid #d6d6d6;
  display: flex;
  padding: 5px;
  flex-direction: column;
  & .msg-box {
    flex: 5;
  }
  & .cmd {
    flex: 1;
    display: flex;
    flex-direction: row-reverse;
  }
}
.content > nav {
  width: 28%;
  border-left: 1px solid #dadada;
  padding: 10px;
  h4 {
    font-weight: normal;
    line-height: 35px;
    height: 35px;
    border-bottom: 1px solid #eee;
    font-size: 18px;
  }
  ol {
    list-style: none;
    padding: 0;
  }

  & ol > li {
    height: 28px;
    line-height: 28px;
    font-size: 16px;
    cursor: pointer;
    border: 1px solid #888;
    border-radius: 5px;
    margin-bottom: 5px;
  }
}
</style>
