<script>
export default {
  name: 'RenderTemplate',
  props: {
    content: {
      type: String,
      default: 'Test!!'
    },
    isShow: {
      type: Boolean,
      default: true
    }
  },
  render: function(h){
    if (this.isShow) {
      return h('div',
      [
        h('p',[h('span', '子元素'), h('', '测试')]),  // jsx react.js
        h('p', this.content)
      ]
    )
  } else {
    return h('div',
    [
      h('p',[h('span', '子元素')])
    ]
  )
  }
    // h(元素、元素属性说明、元素的子元素、元素的值)
  }
}
</script>

<style lang="css" scoped>
  .tips{
    border: 1px solid #ccc;
  }
</style>
