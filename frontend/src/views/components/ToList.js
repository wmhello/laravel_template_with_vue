export default {
  name: 'ToList',
  props: {
    lists: {
      type: Array,
      default: function() {
        return []
      }
    },
    isShow: {
      type: Boolean,
      default: true
    }
  },
  data() {
    return {
      bookLists: this.lists,
      content: '',
    }
  },
  render: function(h){
    var that = this
    if (this.isShow) {
      return h('div',[
         h('ul',that.bookLists.map(item=>{
          return h('li',item)
        })),
        h('input',{
          domProps: {
            value: that.content
          },
          on:{
            input:function(event) {
              that.content = event.target.value
            },
            keypress: function(event){
              if (event.keyCode === 13) {
                that.bookLists.push(that.content)
                that.content = ''
              }
            }
          }
        })
      ])
    } else {
      return h('ul',that.bookLists.map(item =>{
          return h('li',item)
        }))
    }
  }
}
