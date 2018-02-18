<template>
  <div id="leader">
       <el-form id="toolbar" :inline="true" :model="searchForm" class="demo-form-inline">
          <el-form-item label="姓名">
            <el-select v-model="searchForm.teacher_id" clearable filterable placeholder="请查找或选择">
                  <el-option v-for="item in teachers" :key="item.id" :label="item.name" :value="item.id">
                  </el-option>
            </el-select>
          </el-form-item>
         <el-form-item label="行政类型">
             <el-select v-model=" searchForm.leader_type" clearable placeholder="行政类型">
                 <el-option label="中层领导" value="1"></el-option>
                 <el-option label="学校领导" value="2"></el-option>
             </el-select>
         </el-form-item>
           <el-form-item label="学期">
               <el-select v-model="searchForm.session_id" clearable placeholder="学期">
                   <el-option v-for="item in sessions" :label="item.remark |adjustSessionMark(item)" :value="item.id" :key="item.id"></el-option>
               </el-select>
           </el-form-item>
           <el-form-item>
               <el-button  @click="find()" plain>查询</el-button>
               <el-button type="info" @click="findReset()" plain>重置</el-button>
           </el-form-item>
     </el-form>
  <div id="datagrid">
    <div class="toolbar">
      <el-button  plain icon="el-icon-plus" @click="add()">添加</el-button>
      <el-button  plain icon="el-icon-upload" @click="upload()">导入</el-button>
      <el-button  plain icon="el-icon-download" @click="download()">导出</el-button>
    </div>
    <!-- 学校行政列表 -->
    <el-table :data="tableData" border style="width: 100%" @select-all="selectChange" @selection-change="selectChange" v-loading="loading">
      <el-table-column type="selection" width="55">
      </el-table-column>
      <el-table-column prop="id" label="序号" width="70">
      </el-table-column>
      <el-table-column label="教师" width="120">
          <template slot-scope="scope">
              <span>{{scope.row.teacher_id|getTeacherName(teachers)}}</span>
          </template>
      </el-table-column>
      <el-table-column label="行政类型" width="120">
          <template slot-scope="scope">
          <el-tag
          :type="scope.row.leader_type === 1 ? 'primary' : 'success'"
          close-transition>{{scope.row.leader_type|getLeaderType(leaderType)}}</el-tag>
          </template>
      </el-table-column>
      <el-table-column prop="job" label="职务描述" width="180">
      </el-table-column>
      <el-table-column prop="remark" label="备注" width="300">
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-tooltip content="编辑" placement="right-end"  effect="light">
            <el-button plain icon="el-icon-edit" type="primary" size="small" @click="edit(scope.row)"></el-button>
          </el-tooltip>
          <el-tooltip content="删除" placement="right-end"  effect="light">
            <el-button plain icon="el-icon-delete" type="danger" size="small" @click="del(scope.row)"></el-button>
          </el-tooltip>
        </template>
      </el-table-column>
    </el-table>
    <!-- 分页 -->
    <el-row class="page">
      <el-col :span="2" :offset="1">
        <el-button type="danger" plain @click="delAll()">删除选择</el-button>
      </el-col>
      <el-col :span="20">
    <el-pagination
      background
      @current-change="pagination"
      @size-change="sizeChange"
      :current-page.sync="current_page"
      :page-sizes="[10,20,25,50]"
      layout="total,sizes,prev, pager, next"
      :page-size.sync="pageSize"
      :total="total">
    </el-pagination>
      </el-col>
    </el-row>
    </div>

        <!-- 编辑列表 -->
  <el-dialog title="行政信息" center :visible.sync="editDialogFormVisible" :close-on-click-modal="false">
      <el-form :model="form" label-width="100px">
        <el-row>
          <el-col :span="12">
        <el-form-item label="(*)教师" ref="tip">
            <el-select v-model="form.teacher_id" filterable placeholder="请选择或者输入姓名">
                  <el-option v-for="item in teachers" :key="item.id" :label="item.name" :value="item.id">
                  </el-option>
            </el-select>
        </el-form-item>
          </el-col>
          <el-col :span="12">
        <el-form-item label="(*)行政类型" prop="leader_type">
          <el-select v-model="form.leader_type" placeholder="请选择行政类型">
            <el-option label="中层" :value="1">中层</el-option>
            <el-option label="学校" :value="2">学校</el-option>
          </el-select>
        </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
        <el-form-item label="职务描述"  prop="job">
          <el-input v-model="form.job"></el-input>
        </el-form-item>
          </el-col>
          <el-col :span="12">
        <el-form-item label="备注"  prop="remark">
          <el-input v-model="form.remark"></el-input>
        </el-form-item>
          </el-col>
        </el-row>
        </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="cancel()">取 消</el-button>
        <el-button type="primary" @click="save()">确 定</el-button>
      </div>
    </el-dialog>

  <upload-xls :show="isShowUpload"
              :template-file="templateFile"
              module="leader"
  @close-upload="closeUpload"></upload-xls>

  <download-xls :show="isShowDownload"
              :template-file="downloadFile"
              module="leader"
              :pageSize="pageSize"
              :page="current_page"
              :search="searchForm"
  @close-download="closeDownload"></download-xls>

</div>
</template>

<script>
import {
  getInfo,
  getInfoById,
  addInfo,
  updateInfo,
  deleteInfoById,
  deleteAll,
  Model,
  SearchModel
} from "@/api/leader";
import { getSession, getTeacher, getDefaultSession} from "@/api/other";
import {config} from "./../../config/index"
import {Tools} from "@/views/utils/Tools";
import UploadXls from "@/views/components/UploadXls";
import DownloadXls from "@/views/components/DownloadXls";

export default {
  components: {
    UploadXls,
    DownloadXls
  },
  data() {
    return {
      searchForm: new SearchModel(),
      tableData: [],
      editDialogFormVisible: false,
      uploadId: "",
      teachers: [],
      sessions: [],
      form: new Model(),
      loading: false,
      isNew: false,
      isEdit: false,
      isShowUpload: false,
      isShowDownload: false,
      leaderType: ["无", "中层", "学校"],
      current_page: 1,
      total: 0,
      pageSize: 10,
      session_id: null,
      templateFile: config.site + '/xls/leader.xls',
      downloadFile: config.site + '/xls/行政管理.xls',
      multiSelect: []
    };
  },
  methods: {
    // 搜索相关
    find() {
       this.fetchData()
    },
    findReset() {
      this. searchForm = new SearchModel(this.session_id, null,null)
      this.fetchData()
    },
    // 查询数据 获取信息列表
    fetchData(searchObj = this.searchForm , page = this.current_page, pageSize = this.pageSize) {
      this.loading = true
      getInfo(searchObj, page, pageSize)
        .then(response => {
          //成功执行内容
          let result = response.data;
           this.tableData = result;
           this.total = response.meta.total;
           this.pageCount = response.meta.last_page
           this.loading = false
        })
        .catch(err => {
           Tools.error(this, err.response.data)
            this.loading = false
        });
    },
    add() {
      this.editDialogFormVisible = true;
      this.isNew = true;
      this.form = new Model();
    },
    // 导入、导出窗口的开启与关闭
    upload() {
       this.isShowUpload = true
    },
    closeUpload() {
      this.isShowUpload = false
    },
    download() {
      this.isShowDownload = true
    },
    closeDownload() {
      this.isShowDownload = false
    },
    // 模型的新建、编辑与更新
    edit(row) {
      let id = row.id;
      this.uploadId = id;
      getInfoById(id).then(response => {
        let result = response.data;
        this.form = result;
        this.isEdit = true;
        this.editDialogFormVisible = true;
      });
    },
    save() {
      this.editDialogFormVisible = false;
      if (this.isNew) {
        this.isNew = false
        this.newData()
      }
      if (this.isEdit) {
        this.isEdit = false
        this.updateData()
      }
    },
    cancel(){
      this.editDialogFormVisible = false
      this.isNew = false
      this.isEdit = false
    },
    updateData() {
      updateInfo(this.uploadId, this.form)
        .then(response => {
          //成功执行内容
          let result = response.status_code;
          if (result == 200) {
            let currentId = this.form.id;
            let record = 0;
            record = this.tableData.findIndex((val, index) => {
              return val.id == currentId;
            });
            this.tableData.splice(record, 1, this.form);
            Tools.success(this, "用户信息更新成功");
          }
        })
        .catch(err => {
          Tools.error(this, err.response.data)
        });
    },
    newData() {
      addInfo(this.form)
        .then(response => {
            Tools.success(this, "用户信息添加成功");
            this.fetchData();
        })
        .catch(err => {
          Tools.error(this, err.response.data)
        });
    },

    // 删除  删除单个 批量删除
    del(row) {
      this.$confirm("此操作将永久删除该信息, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      }).then(() => {
          let id = row.id;
          let record = null;
          record = this.tableData.findIndex(val => val.id == id);
          deleteInfoById(id).then(response => {
              let result = response.status_code;
              if (result == 200) {
                this.$message({
                  type: "success",
                  message: "删除成功!"
                });
                this.tableData.splice(record, 1);
              }
            }).catch(err => {
              //失败执行内容
              Tools.error(this, err.response.data)
        })
      })
    },

    selectChange(selection) {
       this.multiSelect = selection;
    },
    delAll() { // 删除所有的数据
      this.$confirm("此操作将永久删除用户, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      }).then(() => {
        let arr = []
        this.multiSelect.forEach(item=> {
          arr.push(item.id);
        })
       deleteAll(arr).then(response=>{
         this.fetchData()
       }).catch(err => {
         Tools.error(err.response.data)
       })
      }).catch(() => {
          Tools.errorTips(this, '删除操作已经取消')
      })
    },

    // 分页相关
    pagination(val) {
      this.current_page = val;
      this.fetchData()
    },
    sizeChange(val) {
       this.pageSize = val;
       this.fetchData()
    },
    // 获取各种所需数据
    getSessions() {
      return new Promise((resolve, reject) => {
        getSession()
          .then(response => {
            this.sessions = response.data;
            resolve("sessions ok");
          })
          .catch(err => {
            reject("学期信息调用出错");
          });
      });
    },
    // 获取教师姓名和id信息
    getTeachers() {
      return new Promise((resolve, reject) => {
        getTeacher()
          .then(response => {
            this.teachers = response.data;
            resolve("teachers ok");
          })
          .catch(err => {
            reject("教师信息调用出错");
          });
      });
    },

  },
  mounted() { // 操作相关的DOM

  },
  created() { // 获取数据，无法操作节点
      Promise.all([this.getSessions(), this.getTeachers()]).then(res => {
      this.fetchData();
      getDefaultSession().then(response => {
        let result = response.data;
        this.session_id = result.id;
        this. searchForm = new SearchModel(this.session_id, null,null)
      })
    });
  },
  filters: {
    getTeacherName(value, teachers) {
      let item = teachers.find(val => val.id == value);
      return item.name;
    },
    getLeaderType(value, leaderType) {
      return leaderType[value];
    },
    adjustSessionMark(value, item){
      let year = item.year + 1
      let teamNote = item.team==1? '上学期': '下学期'
      return item.year+'-'+year+'学年'+teamNote
    }
  }
};
</script>

<style lang="scss">
 @import './../../styles/app-main.scss';
#leader .el-input {
  width: 200px;
  margin-left: 10px;
}


</style>
