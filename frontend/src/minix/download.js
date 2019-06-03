let DOWNLOAD = {
  data() {
    return {
     isShowDownload: false,
    };
  },
  methods: {
    download() {
      this.isShowDownload = true
    },
    closeDownload() {
      this.isShowDownload = false
    },
  },
  mounted() {
  },
  created() {
  },
};


export default DOWNLOAD
