<template lang="html">
  <div id="chat">
    <el-row>
      <el-col :span="16">
        <!-- 用户页面 -->
        <div class="chat-box" v-if="kefu !== name || users.length === 0">
          <div v-for="item in messages" :key="item.timezone">
            <chat-item :item="item" :name="name"></chat-item>
          </div>
        </div>
        <!-- 客服页面 -->
        <el-tabs tab-position="left" style="margin-top: 20px;" v-if="kefu === name && users.length >= 1"
          v-model="currentUser">
          <el-tab-pane :label="item" :name="item" v-for="item in users" :key="item" v-if="kefu !== item">
            <div class="chat-box">
              <div v-for="msg in kefuMsg[item]" :key="item.timezone">
                <chat-item :item="msg" :name="name"></chat-item>
              </div>
            </div>
          </el-tab-pane>
        </el-tabs>
        <div class="chat-box warnTips" v-if="users.length === 1 && users[0] === kefu">
          <h1>暂时没有用户接入客服，请耐心等待</h1>
        </div>
      </el-col>

      <!-- 侧边提示栏 -->
      <el-col :span="8" class="">
        <el-card class="box-card">
          <div slot="header" class="clearfix">
            <span v-if="name !== kefu">在线客服</span>
            <span v-if="name === kefu">在线用户</span>
          </div>
          <div v-if="name !== kefu" style="margin-bottom:20px">
            <div class="text item">{{ kefu }}</div>
          </div>
          <div v-if="name === kefu" style="margin-bottom:20px">
            <span v-for="item in users" :key="item">
              <!-- 当前登录的是客服，则不显示客服本身 -->
              <el-radio @change="changeCurrentUser" style="margin-right: 20px;" v-if="item !== kefu"
                v-model="currentUser" :label="item" border>{{ item }}</el-radio>
            </span>
          </div>
          <el-form class="chat-msg" :inline="true" :model="formInline" @submit.native.prevent="test">
            <el-form-item label="消息">
              <el-input v-model="formInline.msg" @keyup.enter.native.prevent="send"></el-input>
            </el-form-item>
            <el-form-item>
              <el-button type="success" plain @click.native.prevent="send">发送</el-button>
            </el-form-item>
          </el-form>
        </el-card>
        <el-card class="box-card">
          <div slot="header" class="clearfix"><span>使用说明</span></div>
          <p style="color: #f50;line-height: 1.4" class="tips">当前客服由前端直接模拟，默认是登录名为admin的用户。</p>
          <p style="color: #f50;line-height: 1.4" class="tips">可以查看api/chat下的getCustomer,实际中你可以根据自己的情况，从后台数据库配置中获取</p>
          <p style="color: #f50;line-height: 1.4" class="tips">
            要完整的查看演示效果，可以用多个浏览器分别使用不同的系统管理员登录，一个是admin，表示客服，另几个是其他的系统管理员（如test和xpyzwm）。
          </p>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import {
    getCustomer,
    sendKefu
  } from '@/api/chat';

  import {
    mapGetters
  } from 'vuex';
  import {
    parseTime
  } from '@/utils/index';

  import ChatItem from '@/components/Chat/item';

  export default {
    name: 'chat_kefu',
    components: {
      ChatItem
    },
    data() {
      return {
        users: [], // 当前登录的用户，包括客服和所有的用户
        currentUser: '', // 客服模式下，当前对话的用户名
        formInline: {
          msg: ''
        },
        kefu: '', // 客服
        messages: [], // 用户界面下，存储用户与客服的对话信息
        kefuMsg: {} // 存储着客服信息，客服界面与所有用户的消息 数据结构为对象下包含着每个和
      };
    },
    computed: {
      ...mapGetters(['name'])
    },
    // beforeRouteLeave(to, from, next) {
    //   window.Echo.leave('kefu');
    //   next();
    // },
    // beforeRouteEnter(to, from, next) {
    //   next(vm => {
    //     // 通过 `vm` 访问组件实例
    //     getCustomer().then(res => {
    //       vm.kefu = res.name;
    //     });
    //     window.Echo.join('kefu')
    //       .here(users => {
    //         vm.users = users;
    //         if (vm.users.length && vm.kefu === vm.name) {
    //           // 客服界面下，显示第一个与用户的对话界面
    //           vm.$nextTick(() => {
    //             vm.currentUser = vm.users[vm.users.length - 1];
    //           });
    //         }
    //       })
    //       .joining(user => {
    //         vm.users.push(user);
    //         if (vm.name === vm.kefu) {
    //           // 客服操作
    //           if (!vm.kefuMsg[user]) {
    //             // 不存在该用户，则定义该用户的信息
    //             vm.$set(vm.kefuMsg, user, []);
    //           }
    //           vm.$nextTick(() => {
    //             vm.currentUser = vm.users[vm.users.length - 1];
    //             vm.$notify({
    //               title: '成功',
    //               message: `用户${vm.currentUser}进入`,
    //               type: 'success'
    //             });
    //           });
    //         }
    //       })
    //       .leaving(user => {
    //         let index = vm.users.findIndex(item => item === user);
    //         vm.users.splice(index, 1);
    //         vm.$notify.info({
    //           title: '消息',
    //           message: `用户${user}离开`,
    //         });
    //         if (vm.name === vm.kefu) {
    //           // 客服界面的操作
    //           if (vm.users[vm.users.length - 1] !== vm.name) {
    //             // 客服状态下，如果还有联系人，则显示相关的联系人界面
    //             vm.currentUser = vm.users[vm.users.length - 1];
    //           } else {
    //             vm.currentUser = ''; // 没有接入的联系人
    //           }
    //         }
    //       })
    //       .listen('CustomerService', e => {
    //         let msg = e.msg;
    //         // 用户模式
    //         if (vm.kefu !== vm.name) {
    //           // 客服发信息给我，我显示
    //           if (msg.receiveName === vm.name) {
    //             vm.messages.push({
    //               name: msg.sendName,
    //               time: msg.time,
    //               timezone: msg.timezone,
    //               content: msg.content
    //             });
    //           }
    //         }
    //         //  客服
    //         if (vm.kefu === vm.name) {
    //           let user = msg.sendName;
    //           if (vm.kefuMsg[user]) {
    //             vm.kefuMsg[user].push({
    //               name: msg.sendName,
    //               time: msg.time,
    //               timezone: msg.timezone,
    //               content: msg.content
    //             });
    //           }
    //         }
    //       });
    //   });
    // },
    async created() {
      var _self = this;
      document.onkeydown = function(e) {
        if (window.event == undefined) {
          var key = e.keyCode;
        } else {
          var key = window.event.keyCode;
        }
        if (key === 13) {
          _self.send();
        }
      };
      let {
        name
      } = await getCustomer()
      this.kefu = name;
      window.Echo.join('kefu')
        .here(users => {
          this.users = users;
          if (this.users.length && this.kefu === this.name) {
            // 客服界面下，显示第一个与用户的对话界面
            this.$nextTick(() => {
              this.currentUser = this.users[this.users.length - 1];
            });
          }
        })
        .joining(user => {
          this.users.push(user);
          if (this.name === this.kefu) {
            // 客服操作
            if (!this.kefuMsg[user]) {
              // 不存在该用户，则定义该用户的信息
              this.$set(this.kefuMsg, user, []);
            }
            this.$nextTick(() => {
              this.currentUser = this.users[this.users.length - 1];
              this.$notify({
                title: '成功',
                message: `用户${this.currentUser}进入`,
                type: 'success'
              });
            });
          }
        })
        .leaving(user => {
          let index = this.users.findIndex(item => item === user);
          this.users.splice(index, 1);
          this.$notify.info({
            title: '消息',
            message: `用户${user}离开`,
          });
          if (this.name === this.kefu) {
            // 客服界面的操作
            if (this.users[this.users.length - 1] !== this.name) {
              // 客服状态下，如果还有联系人，则显示相关的联系人界面
              this.currentUser = this.users[this.users.length - 1];
            } else {
              this.currentUser = ''; // 没有接入的联系人
            }
          }
        })
        .listen('CustomerService', e => {
          let msg = e.msg;
          // 用户模式
          if (this.kefu !== this.name) {
            // 客服发信息给我，我显示
            if (msg.receiveName === this.name) {
              this.messages.push({
                name: msg.sendName,
                time: msg.time,
                timezone: msg.timezone,
                content: msg.content
              });
            }
          }
          //  客服
          if (this.kefu === this.name) {
            let user = msg.sendName;
            if (this.kefuMsg[user]) {
              this.kefuMsg[user].push({
                name: msg.sendName,
                time: msg.time,
                timezone: msg.timezone,
                content: msg.content
              });
            }
          }
        });
    },
    methods: {
      test(e) {
        return false;
      },
      send() {
        // 如果是普通用户发信息给客服
        if (!this.formInline.msg) {
          return false;
        }
        if (this.name !== this.kefu) {
          let msg = {
            sendName: this.name,
            receiveName: this.kefu,
            timezone: Date.now(),
            time: parseTime(Date.now()),
            content: this.formInline.msg
          };
          this.formInline.msg = '';
          // 显示自己本人的信息
          this.messages.push({
            name: msg.sendName,
            time: msg.time,
            timezone: msg.timezone,
            content: msg.content
          });
          // 发送给客服
          sendKefu(msg).then(() => {});
        }
        if (this.name === this.kefu) {
          // 客户状态下发信息
          let msg = {
            sendName: this.name,
            receiveName: this.currentUser,
            timezone: Date.now(),
            time: parseTime(Date.now()),
            content: this.formInline.msg
          };

          if (!Array.isArray(this.kefuMsg[this.currentUser])) {
            this.$set(this.kefuMsg, this.currentUser, []);
          }

          this.kefuMsg[this.currentUser].push({
            // 客户界面下，显示自己的信息到指定用户的对话窗口
            name: msg.sendName,
            time: msg.time,
            timezone: msg.timezone,
            content: msg.content
          });
          this.formInline.msg = '';
          sendKefu(msg).then(() => {});
        }
      },

      changeCurrentUser(user) {
        this.currentUser = user;
      }
    }
  };
</script>

<style scoped lang="scss">
  #chat {
    background-color: #eee;

    & .chat-box {
      border: 1px solid rgba(0, 0, 0, 0.4);
      border-radius: 5px;
      width: 90%;
      height: 80vh;
      background-color: rgba(255, 128, 0, 0.2);
      margin: 20px;
      overflow: auto;
    }

    & .users-list {
      height: 30vh;
      list-style: none;

      & li {
        margin-bottom: 10px;
      }
    }

    .text {
      font-size: 14px;
    }

    .item {
      margin-bottom: 18px;
    }

    .clearfix:after,
    .clearfix:before {
      display: table;
      content: '';
    }

    .clearfix:after {
      clear: both;
    }

    .box-card {
      margin-top: 20px;
      width: 90%;
    }

    p.tips {
      text-indent: 2em;
    }

    .warnTips {
      display: flex;
      justify-content: center;
      align-items: center;
    }
  }
</style>
