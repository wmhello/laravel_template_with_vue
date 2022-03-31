import { getToken } from '@/utils/auth.js'
import '@/styles/uploader.scss'

export default {
  data() {
    return {
      actionUrl: `${process.env.VUE_APP_BASE_API}/medias`,
      headers: {
        Authorization: 'Bearer ' + getToken()
      },
      uploadId: null
    };
  },
  methods: {
    readyUpload(id) {
      this.uploadId = id
    },
    async uploadSuccess(res) {
      let { data } = res
      let img = data.url
      let item = this.tableData.find(v => v.id === this.uploadId)
      if (item) {
        item.img = img
        let { update } = require(`@/api/${this.module}`)
        try {
          await update(item)
          this.$message.succcess('配图上传成功')
          this.fetchData()
        } catch (e) {
        } finally {
        }
      }
      this.uploadId = null
    }
  }
};
