export default {
  data() {
    return {

    }
  },
  methods: {
    async modifyStatus(row) {
      let status_tip = row.status ? '启用' : '禁用'
      let tips = `确定${status_tip}该记录吗？`
      this.$confirm(tips, '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(async () => {
        let {
          update
        } = require(`@/api/${this.module}`)
        try {
          let {
            info
          } = await update(row)
          this.$message.success(info)
        } catch (e) {
          //TODO handle the exception
          console.log(e);
        }
        await this.fetchData();
      }).catch(() => {
        row.status = !row.status
      });
    }
  }
}
