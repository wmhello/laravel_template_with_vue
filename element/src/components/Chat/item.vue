<!--
 * @Author: wmhello 871228582@qq.com
 * @Date: 2021-10-12 22:49:35
 * @LastEditors: wmhello 871228582@qq.com
 * @LastEditTime: 2022-08-20 21:04:08
 * @FilePath: \element\src\components\Chat\item.vue
 * @Description: 这是默认设置,请设置`customMade`, 打开koroFileHeader查看配置 进行设置: https://github.com/OBKoro1/koro1FileHeader/wiki/%E9%85%8D%E7%BD%AE
-->
<template >
  <div v-if="item.name === name" class="chat-item">
    <div class="content_box">
      <img
        v-if="item.avatar"
        :src="item.avatar + '?imageView2/1/w/40/h/40'"
        class="user-avatar"
      />
      <img v-else src="@/assets/avatar.png" class="user-avatar" alt="" />
      <div class="content">{{ item.content }}</div>
    </div>
  </div>
  <div v-else class="chat-item">
    <div class="content_box_left">
      <img
        v-if="item.avatar"
        :src="item.avatar + '?imageView2/1/w/40/h/40'"
        class="user-avatar"
      />
      <img v-else src="@/assets/avatar.png" class="user-avatar" alt="" />
      <div class="content_left">{{ item.content }}</div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  name: "ChatItem",
  props: {
    item: {
      type: Object,
      default: {},
    },
    name: {
      type: String,
      default: "",
    },
  },
  created() {},
  computed: {
    ...mapGetters(["avatar"]),
  },
};
</script>

<style lang="scss" scoped>
.clearfix:after {
  content: "";
  display: block;
  clear: both;
  zoom: 1;
}

.chat-item {
  margin: 10px 5px;
}
.content_box {
  height: 40px;
  display: flex;
  flex-direction: row-reverse;
}
.content_box_left {
  height: 40px;
  display: flex;
}
.content_left {
  height: 40px;
  line-height: 40px;
  background-color: #f2f6f1;
  color: rgb(22, 21, 21);
  border: 1px solid #888;
  padding: 0px 10px;
  margin-right: 10px;
  border-radius: 5px;
}
.content {
  height: 40px;
  line-height: 40px;
  background-color: #95eb6c;
  color: rgb(22, 21, 21);
  padding: 0px 10px;
  margin-right: 10px;
  border-radius: 5px;
}
.user-avatar {
  cursor: pointer;
  width: 40px;
  height: 40px;
  border-radius: 10px;
}
</style>
