<template>
    <el-upload class="avatar-uploader" drag ref="upload" action="123" accept=".jpg,.png" :before-upload="beforeUpload">
        <img v-if="showImg.length>0" :src="image" class="avatar">
        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
         <div slot="tip" class="el-upload__tip">点击上传头像，只能上传jpg/png格式文件。</div>
      </el-upload>
</template>

<script>


export default {
  data() {
    return {

    }
  },
  props:{
    image: {
       type: String,
       required: true
    },
    showImg: {
      type: String,
      required: true
    }
  },
  name: "avatar-uploader",
  methods: {
    beforeUpload(file) {
        this.$emit('save', file)
    },
  },
  mounted () {
   
  }

};
</script>

<style scoped>
.avatar-uploader .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}

.avatar-uploader .el-upload:hover {
  border-color: #20a0ff;
}

.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 178px;
  height: 178px;
  line-height: 178px;
  text-align: center;
}

.avatar {
  width: 178px;
  height: 178px;
  display: block;
}
</style>



