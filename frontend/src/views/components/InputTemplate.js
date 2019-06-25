export default {
  name: 'InputTemplate',
  data() {
    return {
      content: this.text
    }
  },
  props: {
    text: {
      type: String,
      default: ''
    }
  },
  render: function(h) {
    var that = this
    return h('div',[
      h('input', {
        domProps: {
          value: that.content
        },
        on: {
          input: function(event) {
            that.content = event.target.value
          }
        }
      }),
    h('p', that.content)
    ])
  }
}
