import tools from "@/minix/tools"
import download from "@/minix/download"
import upload from "@/minix/upload"

export default {
  mixins: [tools, download, upload],
  data() {
    return {
      tableData: [], // 数据表格内容
      form: {}, // 模型
      searchForm: {},
      isNew: false, // 添加状态
      isEdit: false, // 编辑状态
      current_page: 1,
      total: 0,
      pageSize: 10,
      editDialogFormVisible: false // 编辑和添加对话框开关
    }
  },
  created(){
    import(`../model/${this.module}`).then(({ Model, SearchModel}) => {
      this.form = new Model(),
      this.searchForm = new SearchModel()
    })
  },
  mounted() {
    this.fetchData()
  },
  methods: {
    fetchData(
      searchObj = this.searchForm,
      page = this.current_page,
      pageSize = this.pageSize
    ) { // 获取数据列表
      import(`../api/${this.module}`).then(({
        getInfo
      }) => {
        getInfo(searchObj, page, pageSize).then(res => {
          this.tableData = res.data
          this.total = res.meta.total
        })
      })
    },
    add() { // 显示添加页面
       import(`../model/${this.module}`).then(({ Model }) => {
         this.form = new Model()
         this.setStatus(true, false, true)
       })
    },
    edit (row) {  // 显示编辑页面
      import(`../api/${this.module}`).then(({ getInfoById }) => {
        getInfoById(row.id).then(response => {
          this.form = response.data
          this.setStatus(false, true, true)
        });
      })
    },
    save() { // 对话框中保存数据
      if (this.isEdit && !this.isNew) {
        this.setStatus()
        this.updateData()
      }
      if (!this.isEdit && this.isNew) {
        this.setStatus()
        this.newData()
      }
    },
    newData(){  // 保存新建数据
      import(`../api/${this.module}`).then(({ addInfo }) => {
        addInfo(this.form).then(response => {
          this.success('创建角色成功')
          this.fetchData()
        }).catch(err => {
          this.error(err.response.data.info)
        })
      })
    },
    updateData() {  // 保存更新数据
      import(`../api/${this.module}`).then(({ updateInfo }) => {
        updateInfo(this.form.id, this.form).then(response => {
          //成功执行内容
          this.success('角色信息更改成功')
          this.fetchData()
        })
        .catch((err) => {
          this.error(err.response.data.info)
        });
      })
    },
    cancel() { // 对话框中取消保存
      this.setStatus()
    },
    setStatus(isNew = false, isEdit = false, editDialogFormVisible = false){
      this.isEdit = isEdit
      this.isNew = isNew
      this.editDialogFormVisible = editDialogFormVisible
    },
    del (row) { // 删除指定的数据
      this.$confirm("此操作将永久删除该信息, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      }).then(() => {
        import(`../api/${this.module}`).then(({ deleteInfoById }) => {
          deleteInfoById(row.id).then(() => {
            this.success("删除成功!")
            this.fetchData()
          }).catch(() => {
          })
        })
      })
    },
    pagination(val) {
      this.current_page = val;
      this.fetchData();
    },
    sizeChange(val) {
      this.pageSize = val;
      this.fetchData();
    }
  }
}
