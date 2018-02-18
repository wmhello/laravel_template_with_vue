<template>
     <!-- 数据导出对话框 -->
    <el-dialog title="数据导出" :visible="exportDialogFormVisible"   :close-on-click-modal="false" @close="cancelDownload()">
    <div>
           <p>请选择导出的数据范围</p>
    </div>
      <div slot="footer" class="dialog-footer">
        <el-button type="primary" @click="exportData(1)">当前页</el-button>
        <el-button type="primary" @click="exportData(2)">全部</el-button>
      </div>
    </el-dialog>
</template>

<script>
import {Tools} from "@/views/utils/Tools";
export default {
  name: "DownloadXls",
  props: {
    show: Boolean,
    templateFile: String,
    module: String,
    pageSize: Number,
    page: Number,
    search: Object
  },
  data() {
    return {};
  },
  computed: {
    exportDialogFormVisible() {
      return this.show;
    }
  },
  methods: {
    cancelDownload() {
      this.$emit("close-download")
    },
    exportData(type) {
      switch (type) {
        case 1:
        import(`./../../api/${this.module}`).then(
        ({exportCurrentPage}) => {
          exportCurrentPage(this.pageSize, this.page, this.search)
            .then(res => {
              location.href = this.templateFile
            })
            .catch(err => {
              Tools.error(this, err.response.data);
            });
        })
          break;
        case 2:
        import(`./../../api/${this.module}`).then(
        ({exportAll}) => {
          exportAll(this.search)
            .then(res => {
              location.href = this.templateFile;
            })
            .catch(err => {
             Tools.error(this, err.response.data);
            });
        })
          break;
        default:
          break;
      }
    }

  }
};
</script>


<style lang="sass" scoped>

</style>
