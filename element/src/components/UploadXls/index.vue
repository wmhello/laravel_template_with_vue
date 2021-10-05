<template>
  <el-dialog
    title="用户信息导入"
    width="30%"
    class="importDialog"
    :visible.sync="visible"
    :show="show"
    @close="$emit('update:show', false)"
  >
    <div>
      <p>
        <a href="#" style="color: #f88" @click="downloadTemplate">下载模板</a>
      </p>
    </div>
    <el-upload
      ref="xlsUpload"
      class=""
      action="#"
      :http-request="uploadHandle"
      :auto-upload="false"
    >
      <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
      <el-button
        style="margin-left: 10px;"
        size="small"
        type="success"
        @click="submitUpload"
        >上传到服务器</el-button
      >
      <div slot="tip" class="el-upload__tip">只能上传xlsx文件</div>
    </el-upload>
    <span slot="footer" class="dialog-footer">
      <el-button @click="visible = false">取 消</el-button>
      <el-button type="primary" @click="visible = false">确定</el-button>
    </span>
  </el-dialog>
</template>

<script>
export default {
  name: "UploadXls",
  props: {
    show: {
      type: Boolean,
      default: false
    },
    module: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      visible: this.show,
      fileReader: new FileReader()
    };
  },
  watch: {
    show() {
      this.visible = this.show;
    }
  },
  methods: {
    async uploadHandle(options) {
      const file = options.file;
      if (file) {
        this.fileReader.readAsDataURL(file);
      }
      this.fileReader.onload = () => {
        const formData = new FormData();
        formData.append("file", file);
        const { upload } = require(`@/api/${this.module}`);
        upload(formData)
          .then(res => {
            const { info } = res;
            this.$message.success(info);
            this.$parent.fetchData();
            this.visible = false;
          })
          .catch(err => {
            if (err.response.status === 422) {
              const { info, fileName } = err.response.data;
              this.$message.error(info);
              window.location.href = `${fileName}`;
            }
          });
      };
    },
    submitUpload() {
      this.$refs["xlsUpload"].submit();
    },
    // 下载模板
    async downloadTemplate() {
      const { download } = require(`@/api/${this.module}`);
      const res = await download();
      this.exportFile("template.xlsx", res);
    },
    exportFile(fileName, res) {
      const content = res;
      const blob = new Blob([content], {
        type: "application/vnd.ms-excel;charset=utf-8"
      });
      if ("download" in document.createElement("a")) {
        // 非IE下载
        const elink = document.createElement("a");
        elink.download = fileName;
        elink.style.display = "none";
        elink.href = window.URL.createObjectURL(blob);
        document.body.appendChild(elink);
        elink.click();
        window.URL.revokeObjectURL(elink.href); // 释放 URL对象
        document.body.removeChild(elink);
      } else {
        // IE10+下载
        window.navigator.msSaveBlob(blob, fileName);
      }
    }
  }
};
</script>

<style>
.importDialog >>> .el-dialog__body {
  padding-top: 5px;
}
</style>
