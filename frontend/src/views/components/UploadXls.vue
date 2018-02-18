<template>
      <!-- 数据导入对话框 -->
    <el-dialog title="文件导入" center :visible="uploadDialogFormVisible" :close-on-click-modal="false" @close="cancelUpload()">
      <div style="margin-bottom:10px">
           <el-button size="small" type="text" @click="downloadTemplate()">下载模板</el-button>
      </div>
      <el-upload
          class="upload-demo"
          action="123"
          accept=".xls"
          ref="upload"
         :auto-upload="false"
         :before-upload="beforeUpload">
  <el-button slot="trigger" size="small" type="primary">选择文件</el-button>
  <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">上传到服务器</el-button>
  <div slot="tip" class="el-upload__tip">只能上传xls文件</div>
</el-upload>
      <div slot="footer" class="dialog-footer">
        <el-button type="primary" @click="saveUpload()">确 定</el-button>
        <el-button @click="cancelUpload()">取 消</el-button>
      </div>
    </el-dialog>
</template>

<script>
import { Tools } from "@/views/utils/Tools";
export default {
  name: "UploadXls",
  props: {
    show: Boolean,
    templateFile: String,
    module: String
  },
  data() {
    return {};
  },
  computed: {
    uploadDialogFormVisible() {
      return this.show;
    }
  },
  methods: {
    cancelUpload() {
      this.$emit("close-upload");
    },
    saveUpload() {
      this.$emit("close-upload");
    },
    downloadTemplate() {
      location.href = this.templateFile;
    },
    submitUpload() {
      this.$confirm('是否上传指定的内容', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then((response) => {
            this.$refs.upload.submit()
        }).catch(()=>{
          Tools.errorTips(this, '上传操作取消');
        })
    },
    beforeUpload(file) {
      if (file.type !== "application/vnd.ms-excel") {
        Tools.errorTips(this, '文件格式不正确，无法上传');
        return false
      }
      let fd = new FormData();
      fd.append("file", file);
      import(`./../../api/${this.module}`).then(
        ({uploadFile}) => {
          uploadFile(fd).then(res => {
            Tools.success(this, '文件信息上传成功');
            try
            {
             if(typeof(eval(this.$parent.fetchData))=="function")
             {
               this.$parent.fetchData();
             }
              }catch(e)
            {
               console.log('没有相关函数')
            }

           });
        return true;
        })
    },
  }
};
</script>


<style lang="sass" scoped>

</style>
