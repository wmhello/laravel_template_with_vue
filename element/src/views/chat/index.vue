<template lang="html">
  <div id="chat">
     <el-row>
        <el-col :span="16">
          <div class="chat-box">
            <div v-for="item in messages" :key="item.timezone">
              <chat-item :item="item" :name="name"></chat-item>
            </div>
          </div>
        </el-col>
        <el-col :span="8" class="">
          <el-card class="box-card">
            <div slot="header" class="clearfix">
              <span>在线用户</span>
            </div>
            <div v-for="item in users" :key="item" class="text item">
              {{item}}
            </div>
            <br>
            <el-form class="chat-msg" :inline="true" :model="formInline" @submit.native.prevent>
              <el-form-item label="消息">
                <el-input v-model="formInline.msg" @keyup.enter.native="send"></el-input>
              </el-form-item>
              <el-form-item>
                <el-button type="success" plain @click.native.prevent="send">发送</el-button>
              </el-form-item>
            </el-form>
          </el-card>
          <el-card class="box-card">
            <div slot="header" class="clearfix">
              <span>使用说明</span>
            </div>
            <p style="color: #f50;line-height: 1.4" class="tips">
              本事例使用laravel + laravel-echo-server,利用广播推送功能完成。
              为了模拟演示聊天室效果，你可以同时使用多个不同的浏览器分别用不同的系统管理员用户来登录，就可以在聊天室相互发送消息了。
            </p>
          </el-card>
        </el-col>
     </el-row>
  </div>
</template>

<script>
import {
  sendChat
} from '@/api/chat'
import {
  mapGetters
} from 'vuex'
import {
  parseTime
} from '@/utils/index'

import ChatItem from '@/components/Chat/item'

export default {
  name: 'chat_index',
  components: {
    ChatItem
  },
  data() {
    return {
      users: [],
      formInline: {
        msg: ''
      },
      messages: []
    }
  },
  computed: {
    ...mapGetters(['name']),
  },
  beforeRouteLeave(to, from, next) {
    window.Echo.leave('chat')
    next()
  },
  beforeRouteEnter(to, from, next) {
    next(vm => {
      // 通过 `vm` 访问组件实例
      window.Echo.join('chat')
        .here((users) => {
          vm.users = users
        }).joining((user) => {
          vm.users.push(user);
          vm.$notify({
            title: '提示',
            message: `${user}轻轻的加入了聊天室`,
            type: 'success'
          });
        }).leaving((user) => {
          let index = vm.users.findIndex(item => item === user)
          vm.users.splice(index, 1)
          vm.$notify({
            title: '提示',
            message: `${user}悄悄的离开了聊天室`,
            type: 'default'
          });
        }).listen('Chat', (e) => {
          let msg = e.msg
          if (msg.name !== vm.name) {
            vm.messages.push(msg)
          }
        })
    })
  },
  created(){
    var _self = this;
    document.onkeydown = function(e){
      if(window.event == undefined){
        var key = e.keyCode;
      }else{
        var key = window.event.keyCode;
      }
      if(key === 13){
        _self.send();
      }
    }
  },
  methods: {
    send() {
      if (!this.formInline.msg){
        return false
      }
      let msg = {
        name: this.name,
        timezone: Date.now(),
        time: parseTime(Date.now()),
        content: this.formInline.msg
      }
      this.formInline.msg = ''
      this.messages.push(msg)
      sendChat(msg).then(() => {})
    }
  }
}
</script>

<style scoped lang="scss">
#chat {
    background-color: #eee;
    & .chat-box {
        border: 1px solid rgba(0, 0, 0, 0.4);
        border-radius: 5px;
        width: 90%;
        height: 80vh;
        background-color: rgba(255,128,0, 0.2);
        margin: 20px;
        overflow: auto
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
        content: "";
    }
    .clearfix:after {
        clear: both;
    }
    .box-card {
        margin-top: 20px;
        width: 90%;
    }
    .tips{
      text-indent: 2em;
    }
}
</style>
