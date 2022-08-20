<template>
  <div
    class="wapper"
    v-loading="loading"
    :element-loading-text="loadTips"
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.8)"
  >
    <div class="title">页面测试（客服）</div>
    <div class="content">
      <div class="sidebar">
        <div class="main">
          <div v-for="(item, index) in msgList[currentUser]" :key="index">
            <chat-item :item="item" :name="name"></chat-item>
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
        <div
          v-for="(item, index) in onlineUser"
          :key="index"
          @click="selectUser(index)"
          :class="{ 'user-box': true, active: currentUser === item.name }"
        >
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
import setting from "@/settings";
import ChatItem from "@/components/Chat/item.vue";
import { mapGetters } from "vuex";
import {
  customerRegister,
  unRegister,
  getCustomer,
  sendDataToUser,
} from "@/api/service";
export default {
  components: {
    ChatItem,
  },
  name: "CustomerIndex",
  computed: {
    ...mapGetters(["name", "avatar"]),
  },
  data() {
    return {
      msg: "",
      msgList: {},
      onlineUser: [],
      loading: false,
      loadTips: "身份不对，不能使用该功能",
      currentUser: null,
    };
  },
  // 进入页面，首先去注册为客服
  beforeRouteEnter(to, from, next) {
    next(async (vm) => {
      // 通过 `vm` 访问组件实例
      let { data } = await getCustomer();
      let { customer } = data;
      if (vm.name !== customer) {
        vm.$alert(
          `你不是默认的客服，默认的客服是${customer}用户,无法进入该演示页面`,
          "友情提示",
          {
            confirmButtonText: "确定",
            callback: (action) => {
              vm.loading = true;
            },
          }
        );
      } else {
        // 是默认客户用户，要去系统注册下
        try {
          await customerRegister();
          // webSocket开启后，客服页面注册接收到用户发送的信息时候的处理流程
        } catch (error) {
          vm.loading = true;
          vm.loadTips = "身份注册失败，无法使用客服功能";
        }
      }
    });
  },
  // 离开页面，取消客服注册
  async beforeRouteLeave(to, from, next) {
    if (!this.loading) {
      await unRegister({
        uuid: window.localStorage.getItem("uuid"),
      });
      // 退出客服功能要上传相关的事件处理程序
      // 删除用户登录处理程序
      delete window.websocketHandle.userLogin;
      delete window.websocketHandle.userLogout;
      delete window.websocketHandle.userSay;
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
      // 普通用户进入到客服系统，准备进行咨询时候，客服会受到信息
      window.websocketHandle.userLogin = this.onUserLogin;
      // 客户退出咨询
      window.websocketHandle.userLogout = this.onUserLogout;
      // 接收到用户发送来的数据执行的代码
      window.websocketHandle.userSay = this.onUserSay;
    }
  },
  mounted() {
    let height = document.documentElement.clientHeight;
    let app = document.querySelector(".app-main");
    app.style.height = height - 84 + "px";
  },
  methods: {
    onUserSay(item, res) {
      this.currentUser = res.name;
      let user = this.onlineUser.find((v) => v.name === res.name);
      res.avatar = user.avatar;
      this.msgList[`${this.currentUser}`].push(res);
    },
    // 发信息给某个客户
    sendInfo() {},
    // 接收客户发来的信息
    receiveInfo() {},
    // 咨询用户咨询，客服端会有提示
    onUserLogout(item, res) {
      let client_id = res.client_id;
      let index = this.onlineUser.findIndex((v) => v.client_id === client_id);
      if (index >= 0) {
        let res = this.onlineUser[index];
        this.onlineUser.splice(index, 1);
        this.currentUser = null;
        this.$notify({
          title: "友情提示",
          message: `用户${res.name}已经退出咨询。`,
          type: "success",
          offset: 100,
        });
      }
    },
    // 接收到有用户前来咨询的信息
    onUserLogin(item, res) {
      let index = this.onlineUser.findIndex((v) => v.name === res.name);
      // 之前没有添加为在线用户，则必须添加
      if (index >= 0) {
        this.onlineUser.push({
          name: res.name,
          avatar: res.avatar,
          client_id: res.client_id,
        });
        // 增加某个用户的数据模型
        if (!Object.keys(this.msgList).includes(res.name)) {
          this.$set(this.msgList, res.name, []);
          this.currentUser = res.name;
        }
        this.$notify({
          title: "友情提示",
          message: `用户${res.name}已经进入咨询室。`,
          type: "success",
          offset: 100,
        });
      } else {
        this.onlineUser.splice(index, 1, {
          name: res.name,
          avatar: res.avatar,
          client_id: res.client_id,
        });
      }
    },
    selectUser(index) {
      this.currentUser = this.onlineUser[index].name;
    },
    async sendMsg() {
      this.msg = document.querySelector(".msg-box").innerText;
      document.querySelector(".msg-box").innerText = "";

      if (this.currentUser) {
        let item = this.onlineUser.find((v) => v.name === this.currentUser);
        let data = {
          name: this.name,
          client_id: item.client_id,
          content: this.msg,
          time: moment(new Date()).format("HH:mm"),
          avatar: this.avatar || null,
        };
        this.msgList[`${this.currentUser}`].push(data);
        await sendDataToUser(data);
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
  overflow: auto;
  height: 360px;
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
}

.user-box {
  height: 40px;
  line-height: 40px;
  display: flex;
  padding: 5px 0px;
  cursor: pointer;
}
div.active {
  border: 1px solid rgb(23, 124, 240);
}
.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 10px;
}
</style>
