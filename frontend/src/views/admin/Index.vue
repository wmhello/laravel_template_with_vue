<template>
  <div id="admin">
 <el-form id="toolbar" :inline="true" :model="searchForm" class="demo-form-inline">
          <el-form-item label="姓名">
              <el-input v-model="searchForm.name" placeholder="用户姓名"></el-input>
          </el-form-item>
         <el-form-item label="登录名">
              <el-input v-model="searchForm.email" placeholder="系统登录名"></el-input>
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
    <el-table :data="tableData" :border="true" style="width: 100%"
    @select-all="selectChange" @selection-change="selectChange"  v-loading="loading">
      <el-table-column type="selection" width="55">
      </el-table-column>
      <el-table-column prop="id" label="序号" width="70">
      </el-table-column>
      <el-table-column prop="name" label="姓名" width="100">
      </el-table-column>
      <el-table-column prop="email" label="登录名">
      </el-table-column>
      <el-table-column  label="角色" class="box">
      <template slot-scope="scope" >
        <el-tag style="margin-right: 5px" v-for="item in scope.row.role" :key="item" size="medium">{{item|roleFilter(roles)}}</el-tag>
      </template>
      </el-table-column>
      <el-table-column  label="头像" width="80">
        <template slot-scope="scope">
           <img v-if="scope.row.avatar" :src="scope.row.avatar|avatarFilter" alt="" width="50" height="50">
        </template>
      </el-table-column>

      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-tooltip content="编辑" placement="right-end">
            <el-button size="small" plain icon="el-icon-edit-outline" @click="edit(scope.row)"></el-button>
          </el-tooltip>
          <el-tooltip content="修改密码" placement="right-end">
            <el-button plain icon="el-icon-setting" size="small" @click="reset(scope.row)"></el-button>
          </el-tooltip>
          <el-tooltip content="删除" placement="right-end">
            <el-button plain icon="el-icon-delete" type="danger" size="small" @click="del(scope.row)"></el-button>
          </el-tooltip>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog title="用户信息" :visible.sync="editDialogFormVisible" :close-on-click-modal="false" @close="cancel();return true;">
      <el-form :model="form" label-width="80px" label-position="top">
        <el-row class="first-row">
          <el-col :span="10" class="first-column" :offset="2">
        <el-form-item label="姓名">
          <el-input v-model="form.name"></el-input>
        </el-form-item>
          </el-col>
          <el-col :span="10" >
            <el-form-item label="登录名">
              <el-input v-model="form.email"></el-input>
            </el-form-item>
          </el-col>
        </el-row>

        <el-row class="normal-row" v-if="isNew">
          <el-col :span="10" class="first-column" :offset="2">
              <el-form-item label="密码" prop="password" >
                <el-input  type="password" v-model="form.password"></el-input>
              </el-form-item>
          </el-col>
          <el-col :span="10" >
              <el-form-item label="确认密码" prop="checkPass">
                <el-input type="password" v-model="form.password_confirmation" auto-complete="off"></el-input>
              </el-form-item>
          </el-col>
        </el-row>

        <el-row class="last-row" style="height: 290px; overflow: hidden">
        <el-col :span="10" class="first-column" :offset="2" style="height: 100%">
            <el-form-item label="用户头像">
           <el-upload class="avatar-uploader" drag ref="upload" action="123" accept=".jpg,.png" :before-upload="beforeUpload">
            <img v-if="form.avatar" :src="imageUrl" class="avatar">
            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
             <div slot="tip" class="el-upload__tip">上传头像，只能传jpg/png文件</div>
          </el-upload>
            </el-form-item>
        </el-col>
        <el-col :span="10" style="height: 100%">
          <el-form-item label="用户角色">
            <el-select v-model="form.role" multiple placeholder="用户权限">
              <el-option v-for="item in roles"  :key="item.name" :label="item.explain" :value="item.name">
                {{item.explain}}</el-option>
            </el-select>
          </el-form-item>
        </el-col>
      </el-row>
      </el-form>

      <div slot="footer" class="dialog-footer">
        <el-button @click="cancel()">取 消</el-button>
        <el-button type="primary" @click="save()">确 定</el-button>
      </div>
    </el-dialog>

    <el-dialog title="密码重置" :visible.sync="resetDialogFormVisible" :close-on-click-modal="false">
      <el-form :model="form2" label-width="100px">
        <el-form-item label="请输入新密码">
          <el-input v-model="form2.psw" type="password"></el-input>
        </el-form-item>
        <el-form-item label="再次确认密码">
          <el-input v-model="form2.newpsw" type="password"></el-input>
        </el-form-item>
      </el-form>

      <div slot="footer" class="dialog-footer">
        <el-button @click="cancelPassword()">取 消</el-button>
        <el-button type="primary" @click="savePassword()">确 定</el-button>
      </div>
    </el-dialog>
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

  <upload-xls :show="isShowUpload"
              :template-file="templateFile"
              module="admin"
  @close-upload="closeUpload"></upload-xls>

  <download-xls :show="isShowDownload"
              :template-file="downloadFile"
              module="admin"
              :pageSize="pageSize"
              :page="current_page"
              :search="searchForm"
  @close-download="closeDownload"></download-xls>
  </div>
</template>

<script>
import { getToken } from "@/utils/auth";
import {
  getInfo,
  getInfoById,
  resetAdminByPsw,
  uploadAdminByImg,
  updateInfo,
  addInfo,
  uploadFile,
  deleteInfoById,
  deleteAll,
  getCurrentPage,
  SearchModel,
  Model
} from "@/api/admin";
import {getRoles }  from "@/api/role";

import {config} from "./../../config/index";
import UploadXls from "@/views/components/UploadXls";
import DownloadXls from "@/views/components/DownloadXls";
import {Tools} from "@/views/utils/Tools";

export default {
  components: {
    UploadXls, DownloadXls
  },
  data() {
    return {
      searchForm: new SearchModel(),
      form: new Model(),
      imageUrl: "",
      tableData: [],
      resetDialogFormVisible: false,
      editDialogFormVisible: false,
      templateFile: config.site + '/xls/user.xls',
      downloadFile: config.site + '/xls/用户管理.xls',
      resetId: "",
      uploadId: "",
      isNew: false,
      isEdit: false,
      isShowUpload: false,
      isShowDownload: false,
      form2: {
        psw: "",
        newpsw: ""
      },
      roles: [],
      loading: false,
      current_page: 1,
      total: 0,
      pageSize: 10,
      multiSelect: []
    };
  },
  methods: {
    find(){
       this.fetchData()
    },
    findReset() {
       this.searchForm = new SearchModel()
       this.fetchData()
    },
    add(){
       this.isNew = true
       this.form = new Model()
       this.editDialogFormVisible = true
    },
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
    // 头像上传
    beforeUpload(file) {
      let fd = new FormData();
      fd.append("photo", file);
      uploadAdminByImg(fd).then(res => {
        let file = res.data.url;
        this.form.avatar = file;
        this.imageUrl = config.site + '/'+file;
      });
      return true;
    },
    fetchData(searchObj = this.searchForm , page = this.current_page, pageSize = this.pageSize) {
       this.loading = true
      getInfo(searchObj, page, pageSize)
        .then(response => {
          let result = response.data;
          this.tableData = result;
          this.total = response.meta.total;
           this.loading = false
        })
        .catch(() => {
           this.loading = false
        });
    },
    edit(row) {
      let id = row.id;
      this.uploadId = id;
      getInfoById(id).then(response => {
        let result = response.data;
        this.form = result;
        // 显示图像
        if (result.avatar) {
          this.imageUrl = config.site + '/' + result.avatar;
        } else {
          this.imageUrl = "";
        }
        this.isEdit = true;
        this.editDialogFormVisible = true;
      });
    },
    cancel() {
       this.isNew = false
       this.isEdit = false
       this.editDialogFormVisible = false;
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
    newData() {
      addInfo(this.form).then(response => {
           Tools.success(this, '用户添加成功')
           this.fetchData();
      }).catch(err => {
         Tools.error(this, err.response.data)
      })
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
            this.editDialogFormVisible = false;
            Tools.success(this,'用户信息修改成功')
          }
        })
        .catch((err) => {
          //失败执行内容
          Tools.error(this, err.response.data)
        });
    },
    reset(row) {
      this.form2 = {
        psw: "",
        newpsw: ""
      };
      this.resetDialogFormVisible = true;
      this.resetId = row.id;
    },
    cancelPassword(){
      this.resetDialogFormVisible = false;
    },
    savePassword() {
      if (this.form2.psw == this.form2.newpsw && this.form2.psw) {
        let password = this.form2.psw;
        resetAdminByPsw(this.resetId, password).then(response => {
          let result = response.status_code;
          if (result == 200) {
            Tools.success(this, "恭喜你，重置密码成功")
            this.resetDialogFormVisible = false;
          }
        });
      } else {
        Tools.errorTips(this, "输入密码为空或两次输入密码不同，请重新输入！");
        this.resetDialogFormVisible = false;
      }
    },
    del(row) {
      this.$confirm("此操作将永久删除该用户, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          let id = row.id;
          let record = null;
          record = this.tableData.findIndex(val => val.id == id);

          deleteInfoById(id)
            .then(response => {
              let result = response.status_code;
              if (result == 200) {
                Tools.success(this, "删除成功!")
                this.tableData.splice(record, 1);
              }
            })
            .catch(() => {
              //失败执行内容
            });
        })
        .catch(() => {});
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
    // 分页功能
    pagination(val){
      this.current_page = val;
      this.fetchData();
    },
    sizeChange(val){
      this.pageSize = val
      this.fetchData();
    },
    getRoleAll() {
       getRoles().then(res => {
         this.roles = res.data
       })
       .catch(err => {

       })
    }
  },
  mounted() {
  },
  beforeCreate() {
        getRoles().then(res => {
         this.roles = res.data
         this.fetchData()
       })
       .catch(err => {
       })
  },
  filters: {
    roleFilter(val, items) {
            let role = items.find(item => item.name == val)
            return  role.explain

    },
    avatarFilter(val) {
      return config.site +'/'+val;
    }
  }
};
</script>

<style lang="scss">
 @import './../../styles/app-main.scss';

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

#admin .el-form--label-top .el-form-item__label {
  width: 100%;
  text-align:center;
}
#admin .el-form-item__content{
  text-align: center;
}
#admin .el-col-10>.el-form-item>.el-form-item__content>.el-input{
  width: 80%;
}

#admin .first-row .el-col {
border:1px solid $border-color;
border-left: 0px;
}
#admin .first-row .first-column {
border-left:1px solid $border-color;
}
#admin .normal-row .el-col {
border:1px solid $border-color;
border-left: 0px;
border-top: 0px;
}
#admin .normal-row .first-column {
border-left:1px solid $border-color;
}

#admin .last-row .el-col {
border:1px solid $border-color;
border-top: 0px;
border-left: 0px;
}
#admin .last-row .first-column {
border-left:1px solid $border-color;
}
#admin .last-row .first-column .el-upload-dragger{
  width: auto;
  height: auto;
}
</style>
