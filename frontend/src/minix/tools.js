export default {
  methods: {
    success(info) {
      this.$message({
        type: 'success',
        message: info
      })
    },
    error(info) {
      this.$message({
        type: 'error',
        message: info
      })
    }
  }
}
