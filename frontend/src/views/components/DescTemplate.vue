<template lang="html">
  <div class="">
      <p>{{content}}</p>
  </div>
</template>

<script>
export default {
  name: 'desc-template',
  data() {
    return {
    }
  },
  props: {
    content: {
      type: String,
      default: 'Test!!'
    }
  }
}
</script>

<style lang="css" scoped>
</style>
