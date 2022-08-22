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
        <h4>在线用户</h4>
        <div v-for="(item, index) in onlineUser" :key="index" class="user-box">
          <img
            v-if="item.avatar"
            :src="item.avatar + '?imageView2/1/w/40/h/40'"
            class="user-avatar"
          />
          <img v-else src="@/assets/avatar.png" class="user-avatar" alt="" />
          <div style="margin-left: 10px">{{ item.name }}</div>
        </div>
      </nav>
    </div>
  </div>
</template>

<script>
import moment from "moment";
import ChatItem from "@/components/Chat/item.vue";
import { mapGetters } from "vuex";
import setting from "@/settings";

import { register, unRegister, sendDataToUser } from "@/api/chat";
export default {
  components: {
    ChatItem,
  },
  name: "ChatIndex",
  computed: {
    ...mapGetters(["name", "avatar"]),
  },
  data() {
    return {
      msg: "",
      msgList: [],
      loading: false,
      onlineUser: [],
      loadTips: "",
    };
  },
  beforeRouteEnter(to, from, next) {
    next(async (vm) => {
      // 通过 `vm` 访问组件实例
      // 进入页面，就是自己要显示在活动用户第一个
      let result = {
        name: vm.name,
        avatar: vm.avatar,
        client_id: window.localStorage.getItem("uuid"),
      };
      vm.onlineUser.splice(0);
      vm.onlineUser.push(result);
      let { data } = await register();
      data.forEach((v) => {
        vm.onlineUser.push(v);
      });
    });
  },
  created() {
    if (setting.isWebsocket && !this.loading) {
      // 开启websocket
      if (!window.websocketHandle) {
        window.websocketHandle = {};
      }
      // // 收到客服发送来的消息，处理的方法
      window.websocketHandle.chatUserLogin = this.onChatUserLogin;
      // // 客户退出咨询
      window.websocketHandle.chatUserLogout = this.onChatUserLogout;
      // 接收到用户发送来的数据执行的代码
      window.websocketHandle.chatUserSay = this.onChatUserSay;
    }
  },
  async beforeDestroy() {
    await unRegister();
    delete window.websocketHandle.chatUserLogin;
    // // 客户退出咨询
    delete window.websocketHandle.chatUserLogout;
    // 接收到用户发送来的数据执行的代码
    delete window.websocketHandle.chatUserSay;
  },
  mounted() {
    let height = document.documentElement.clientHeight;
    let app = document.querySelector(".app-main");
    app.style.height = height - 84 + "px";
  },
  methods: {
    // 用户登录聊天室
    async onChatUserLogin(item, res) {
      let index = this.onlineUser.findIndex((v) => v.name === res.name);
      if (index === -1) {
        this.onlineUser.push(res);
        this.$notify({
          title: "友情提示",
          type: "succcess",
          message: `用户${res.name}进入了聊天室`,
          offset: 100,
        });
      } else {
        this.onlineUser.splice(index, 1, res);
      }
    },
    // 用户退出聊天室
    async onChatUserLogout(item, res) {
      let index = this.onlineUser.findIndex((v) => v.name === res.name);
      if (index >= 0) {
        this.onlineUser.splice(index, 1);
        this.$notify({
          title: "友情提示",
          type: "succcess",
          message: `用户${res.name}退出了聊天室`,
          offset: 100,
        });
      }
    },
    // 用户收到其他用户的信息
    onChatUserSay(item, res) {
      if (res.name !== name) {
        this.msgList.push(res);
      }
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
      let mainDiv = document.querySelector(".main");
      mainDiv.scrollTop = mainDiv.scrollHeight - 40;
      await sendDataToUser(data);
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
  height: 360px;
  overflow: auto;
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
  overflow: auto;
  height: 100%;
  h4 {
    font-weight: normal;
    line-height: 35px;
    height: 35px;
    border-bottom: 1px solid #eee;
    font-size: 18px;
  }
}

.user-box {
  height: 40px;
  line-height: 40px;
  display: flex;
  padding: 5px 0px;
  cursor: pointer;
}
.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 10px;
}
</style>
