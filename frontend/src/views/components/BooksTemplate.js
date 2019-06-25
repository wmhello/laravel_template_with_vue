// ul
//   li v-for

export default {
  name: 'BooksTemplate',
  props: {
    lists: {
      type: Array,
      default: function() {
        return []
      }
    }
  },
  render: function(h) {
    return h('ul',{
        style: {
          color: '#f00'
        }
    }, this.lists.map(item => {
      return h('li', item)
    }))
  }
}
