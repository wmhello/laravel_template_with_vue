let CURD = {
  data() {
    return {
      tableData: [],
      editDialogFormVisible: false,
      uploadId: "",
      teachers: [],
      loading: false,
      isNew: false,
      isEdit: false,
      current_page: 1,
      total: 0,
      pageSize: 10,
    };
  },
  methods: {
    //按回车查询
    search(event){
      let node = document.querySelector('.el-input__inner');
      // this.searchForm.good_number = node.value
      this.fetchData()
    },
    // 搜索相关
    find() {
      this.fetchData();
    },
    findReset() {
      let obj = this.searchModel;
      this.searchForm = new obj();
      this.fetchData();
    },
    fetchData(
      searchObj = this.searchForm,
      page = this.current_page,
      pageSize = this.pageSize
    ) {
      this.loading = true;
      this.curd.getInfo(searchObj, page, pageSize)
        .then(response => {
          //成功执行内容
          if(typeof this.listHandle === "function") { //是函数    其中 FunName 为函数名称
               this.listHandle(response)
           } else { //不是函数
             let result = response.data;
             this.tableData = result;
         }
          this.total = response.meta.total;
          this.loading = false;
        })
        .catch(err => {
          this.tools.error(this, err.response.data);
          this.loading = false;
        });
    },
    // 查询数据 获取信息列表
    add() {
      let obj = this.model
      this.form = new obj();
      this.editDialogFormVisible = true;
      this.isNew = true;
    },
    save() {
      this.$refs['ruleForm'].validate((valid) => {
          if (valid) {
            this.editDialogFormVisible = false;
            if (this.isNew) {
              this.isNew = false;
              this.newData();
            }
            if (this.isEdit) {
              this.isEdit = false;
              this.updateData();
            }
          } else {
              return false;
            }
          });
    },
    cancel() {
      this.editDialogFormVisible = false;
      this.isNew = false;
      this.isEdit = false;
    },
    edit(row) {  // 获取某个信息
      let id = row.id;
      this.uploadId = id;
      this.curd.getInfoById(id).then(response => {
          // 构建前端使用的数据 如果存在数据处理的函数，则调用函数
          if(typeof this.editHandle === "function") { //是函数    其中 FunName 为函数名称
               this.editHandle(response)
           } else { //不是函数
             let result = response.data
             this.form = result
         }
        this.isEdit = true;
        this.editDialogFormVisible = true;
      });
    },
    // edit(row) {
    //   let id = row.id;
    //   this.uploadId = id;
    //   this.curd.getInfoById(id).then(response => {
    //     let result = response.data;
    //     this.form = result;
    //     this.isEdit = true;
    //     this.editDialogFormVisible = true;
    //   });
    // },
    updateData() {
      this.curd.updateInfo(this.uploadId, this.form)
        .then(response => {
          //成功执行内容
          let result = response.status_code;
          if (result == 200) {
            this.fetchData();
            this.tools.success(this, "信息更新成功");
          }
        })
        .catch(err => {
          this.tools.error(this, err.response.data);
        });
    },
    newData() {
      this.curd.addInfo(this.form)
        .then(response => {
          this.tools.success(this, "功能信息添加成功");
          this.fetchData();
        })
        .catch(err => {
          this.tools.error(this, err.response.data);
        });
    },
    // 删除  删除单个 批量删除
    del(row,tips="此操作将永久删除该信息, 是否继续?") {

      this.$confirm(tips, "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      }).then(() => {
        let id = row.id;
        let record = null;
        // record = this.tableData.findIndex(val => val.id == id);
        this.curd.deleteInfoById(id)
          .then(response => {
            let result = response.status_code;
            if (result == 200) {
              this.$message({
                type: "success",
                message: "删除成功!"
              });
              this.fetchData();
              // this.tableData.splice(record, 1);
            }
          })
          .catch(err => {
            //失败执行内容
            this.tools.error(this, err.response.data);
          });
      });
    },
    selectChange(selection) {
      this.multiSelect = selection;
    },
    delAll(tips="此操作将永久删除该功能, 是否继续?") {
      // 删除所有的数据
      this.$confirm(tips, "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          let arr = [];
          this.multiSelect.forEach(item => {
            arr.push(item.id);
          });
          this.curd.deleteAll(arr)
            .then(response => {
              this.fetchData();
            })
            .catch(err => {
              this.tools.error(err.response.data);
            });
        })
        .catch(() => {
          this.tools.errorTips(this, "删除操作已经取消");
        });
    },
    // 分页相关
    pagination(val) {
      this.current_page = val;
      this.fetchData();
    },
    sizeChange(val) {
      this.pageSize = val;
      this.fetchData();
    }
  },
  mounted() {
  },
  created() {
  },
};


export default CURD
