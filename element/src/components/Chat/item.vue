<template lang="html">
    <div v-if="item.name === name" class="clearfix">
      <div class="chat-content" style="float:right" >
         <p class="title">{{item.name}}  {{item.time}}</p>
         <p class="content">{{item.content}}</p>
      </div>
    </div>
    <div v-else class="clearfix">
      <div class="chat-content" style="float:left" >
         <p class="title">{{item.name}}  {{item.time}}</p>
         <p class="content">{{item.content}}</p>
      </div>
    </div>
</template>

<script>

export default {
  name: 'ChatItem',
  props: {
    item: {
      type: Object,
      default: {}
    },
    name: {
      type: String,
      default: ''
    }
  }
}
</script>

<style lang="scss" scoped>
.clearfix:after{
  content: '';
  display: block;
  clear: both;
  zoom: 1;
}
.chat-content {
    margin: 10px;
    padding: 5px;
    & .content {
        margin-left: 10px;
        width: 100%;
        background-color: #888;
        color: #fff;
        height: 35px;
        line-height: 35px;
        border-radius: 5px;
        padding-left: 10px;
    }
    & .title {
        }
}
</style>
