<template>
  <div id="teaching">
       <el-form id="toolbar" :inline="true" :model="searchForm" class="demo-form-inline">
          <el-form-item label="姓名">
            <el-select v-model="searchForm.teacher_id" clearable filterable placeholder="请查找或选择">
                  <el-option v-for="item in teachers" :key="item.id" :label="item.name" :value="item.id">
                  </el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="年级">
             <el-select v-model=" searchForm.grade" clearable placeholder="年级">
                 <el-option label="高一" value="1"></el-option>
                 <el-option label="高二" value="2"></el-option>
                 <el-option label="高三" value="3"></el-option>
             </el-select>
         </el-form-item>
          <el-form-item label="科目">
                 <el-select v-model="searchForm.teach_id" clearable filterable placeholder="请查找或选择">
                  <el-option v-for="item in teach" :key="item.id" :label="item.name" :value="item.id">
                  </el-option>
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
    <!-- 学校教研组长列表 -->
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
      <el-table-column label="年级" width="120">
          <template slot-scope="scope">
          <el-tag
          type='primary'
          close-transition>{{scope.row.grade|getGradeType(gradeType)}}</el-tag>
          </template>
      </el-table-column>
            <el-table-column label="班级" width="100">
        <template slot-scope="scope">
          <el-tag
          type="info"
          close-transition>{{scope.row.class_id + '班'}}</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="科目" width="100">
        <template slot-scope="scope">
          <el-tag
          type='success'
          close-transition>{{scope.row.teach_id|getTeach(teach)}}</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="课时" prop="hour">
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
  <el-dialog title="教学过程管理" center :visible.sync="editDialogFormVisible" :close-on-click-modal="false" @close="cancel()">
      <el-form :model="form" label-width="100px" label-position="top">
        <el-row class="first-row">
          <el-col :span="10" class="first-column" :offset="2">
              <el-form-item label="科目">
                <template v-if="isEdit">
                 <el-select v-model="form.teach_id" clearable disabled filterable placeholder="请查找或选择">
                  <el-option v-for="item in teach" :key="item.id" :label="item.name" :value="item.id">
                  </el-option>
                </el-select>
                </template>
                <template v-if="isNew">
                 <el-select v-model="form.teach_id" clearable filterable placeholder="请查找或选择">
                  <el-option v-for="item in teach" :key="item.id" :label="item.name" :value="item.id">
                  </el-option>
                </el-select>
                </template>
              </el-form-item>
          </el-col>
          <el-col :span="10">
        <el-form-item label="年级" prop="grade">
          <template v-if="isEdit">
          <el-select v-model="form.grade"  disabled clearable placeholder="请选择年级" required>
            <el-option label="高一" :value="1">高一</el-option>
            <el-option label="高二" :value="2">高二</el-option>
            <el-option label="高三" :value="3">高三</el-option>
          </el-select>
          </template>
          <template v-if="isNew">
          <el-select v-model="form.grade" :disabled="! form.teach_id" clearable placeholder="请选择年级" required>
            <el-option label="高一" :value="1">高一</el-option>
            <el-option label="高二" :value="2">高二</el-option>
            <el-option label="高三" :value="3">高三</el-option>
          </el-select>
          </template>
        </el-form-item>
          </el-col>
        </el-row>

        <el-row class="normal-row">
          <el-col :span="20" class="first-column teacher_id" :offset="2">
             <el-form-item label="姓名">
               <template v-if="isEdit">
               <el-radio-group v-model="form.teacher_id" disabled size="mini">
                <el-radio v-for="item in teachingTeacher" :key="item.id" :label="item.id" border>{{item.name}}</el-radio>
               </el-radio-group>
               </template>
              <template v-if="isNew">
               <el-radio-group v-model="form.teacher_id"  size="mini">
                <el-radio v-for="item in teachingTeacher" :key="item.id" :label="item.id" border>{{item.name}}</el-radio>
               </el-radio-group>
              </template>

          </el-form-item>
          </el-col>
        </el-row>

        <el-row class="normal-row">
          <el-col :span="20" class="first-column class_id" :offset="2">
            <el-form-item label="班级">
               <el-checkbox-group v-model="form.classAll" size="small">
              <el-checkbox  v-for="item in maxClass" :key="item.label" :label="item.label" border :disabled="item.disable">{{item.label + '班'}}</el-checkbox>
               </el-checkbox-group>
            </el-form-item>
          </el-col>
        </el-row>

        <el-row class="last-row">
        <el-col :span="10" class="first-column" :offset="2">
          <el-form-item label="备注" prop="remark">
            <el-input type="textarea" v-model="form.remark"></el-input>
          </el-form-item>
          </el-col>
           <el-col :span="10">
          <el-form-item label="周课时" prop="hour">
            <el-input-number v-model="form.hour" @change="handleChange" :min="1" :max="80" label="描述文字"></el-input-number>
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
              module="teaching"
  @close-upload="closeUpload"></upload-xls>

  <download-xls :show="isShowDownload"
              :template-file="downloadFile"
              module="teaching"
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
} from "@/api/teaching";
import {
  getSession,
  getTeacher,
  getDefaultSession,
  getTeach,
  getClassByGrade,
  getTeacherByTeachingId,
  getSelectClassByGrade,
   getClassByTeachingId
} from "@/api/other";
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
      teach: [],
      form: new Model(),
      loading: false,
      isNew: false,
      isEdit: false,
      isShowUpload: false,
      isShowDownload: false,
      gradeType: ["无", "高一", "高二", "高三"],
      current_page: 1,
      total: 0,
      pageSize: 10,
      session_id: null,
      templateFile: config.site + '/xls/teaching.xls',
      downloadFile: config.site + '/xls/教学过程管理.xls',
      multiSelect: [],
      maxClass: [],
      teachingTeacher: []
    };
  },
  methods: {
    // 搜索相关
    find() {
       this.fetchData()
    },
    findReset() {
      this. searchForm = new SearchModel(this.session_id)
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
      this.maxClass = [];
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
         getClassByTeachingId(this.form.id).then(res => {
              this.maxClass = res.data
         }).catch(err => {
           console.log(err.response)
         })
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
            // let currentId = this.form.id;
            // let record = 0;
            // record = this.tableData.findIndex((val, index) => {
            //   return val.id == currentId;
            // });
            // this.tableData.splice(record, 1, this.form);
            this.fetchData();
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
    getTeach() {
      return new Promise((resolve, reject) => {
        getTeach()
          .then(response => {
            this.teach = response.data;
            resolve("teach ok");
          })
          .catch(err => {
            reject("教学科目信息出错");
          });
      });
    },

  },
  mounted() { // 操作相关的DOM

  },
  created() { // 获取数据，无法操作节点
      Promise.all([this.getSessions(), this.getTeachers(), this.getTeach()]).then(res => {
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
    getTeach(value, teach) {
      let item = teach.find(val => val.id == value);
      return item.name;
    },
    getGradeType(value, leaderType) {
      return leaderType[value];
    },
    getLeader(value) {
        return value?'教研组长':'备课组长';
    },
    adjustSessionMark(value, item){
      let year = item.year + 1
      let teamNote = item.team==1? '上学期': '下学期'
      return item.year+'-'+year+'学年'+teamNote
    }
  },
  watch: {
    'form.grade'(val, oldval){
      if (val && this.form.teach_id) {
        if (this.isNew) {
           getClassByGrade(val, this.form.teach_id).then(res=>{
              this.maxClass = res.data
           }).catch(err=>{
              Tools.error(this, err.response.data)
           })
        }
      } else {
        this.maxClass = []
      }
    },
    'form.teach_id'(val, oldval){
       if (val){
        getTeacherByTeachingId(val).then(res=>{
          this.teachingTeacher = res.data
        }).catch(err=>{
          Tools.error(this, err.response.data)
        })
       } else {
          this.teachingTeacher = []
          this.maxClass = []
       }
    }
  }
};
</script>

<style lang="scss">
 @import './../../styles/app-main.scss';
#department .el-input {
  width: 200px;
  margin-left: 10px;
}

#department .el-form--label-top .el-form-item__label {
  width: 100%;
  text-align:center;
}
#department .el-form-item__content{
  text-align: center;
}
#department .el-col-10>.el-form-item>.el-form-item__content>.el-input{
  width: 80%;
}

#department .first-row .el-col {
border:1px solid $border-color;
border-left: 0px;
}
#department .first-row .first-column {
border-left:1px solid $border-color;
}
#department .normal-row .el-col {
border:1px solid $border-color;
border-left: 0px;
border-top: 0px;
}
#department .normal-row .first-column {
border-left:1px solid $border-color;
}

#department .last-row .el-col {
border:1px solid $border-color;
border-top: 0px;
border-left: 0px;
}
#department .last-row .first-column {
border-left:1px solid $border-color;
}
#department .last-row .first-column .el-upload-dragger{
  width: auto;
  height: auto;
}

.teacher_id .el-radio--mini{
   width: 90px;
   margin-bottom: 5px;
}
.teacher_id .el-radio--mini:nth-child(5n+1){
   margin-left: 0px !important;
}


.class_id .el-checkbox-group .el-checkbox{
  width: 65px;
}

.class_id .el-checkbox-group .el-checkbox:nth-child(7n+1){
  margin-left: 0px !important;
}

.el-col-10>.el-form-item>.el-form-item__content>.el-textarea{
width: 80%
}

</style>
