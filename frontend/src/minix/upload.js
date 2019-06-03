let UPLOAD = {
  data() {
    return {
     isShowUpload: false,
    };
  },
  methods: {
    upload() {
      this.isShowUpload = true
    },
    closeUpload() {
     this.isShowUpload = false
    },
  },
  mounted() {
  },
  created() {
  },
};


export default UPLOAD
