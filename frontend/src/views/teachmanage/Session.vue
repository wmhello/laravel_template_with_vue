<template>
  <div id="session">
    <div id="datagrid">
      <div class="toolbar">
           <el-button plain icon="el-icon-plus" v-has="'session.store'" @click="add()">添加</el-button>
      </div>
    <el-table :data="tableData" :border="true" style="width: 80%" scope="scope">
      <el-table-column prop="id" label="序号" width="80">
      </el-table-column>
      <el-table-column prop="year" label="学年" :formatter="formatYear">
      </el-table-column>
      <el-table-column prop="team" label="学期"  :formatter="formatTeam">
      </el-table-column>
      <el-table-column prop="one" label="高一班级数">
      </el-table-column>
      <el-table-column prop="two" label="高二班级数">
      </el-table-column>
      <el-table-column prop="three" label="高三班级数">
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-tooltip content="编辑" placement="right-end" effect="light">
            <el-button plain icon="el-icon-edit" type="primary" size="small" v-has="'session.show'" @click="edit(scope.row)"></el-button>
          </el-tooltip>
          <el-tooltip content="删除" placement="right-end" effect="light">
            <el-button plain icon="el-icon-delete" type="danger" size="small" v-has="'session.delete'" @click="del(scope.row)"></el-button>
          </el-tooltip>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog title="学期信息" :visible.sync="editDialogFormVisible" :close-on-click-modal="false">
      <el-form :model="form" label-position="top" label-width="100px">
        <el-row class="first-row">
          <el-col :span="16" class="first-column">
        <el-form-item label="学年"  prop="year">
         <el-date-picker v-model="form.year" align="right" type="year" placeholder="选择年">
      </el-date-picker>
        </el-form-item>
        </el-col>
        <el-col :span="8">
          <el-form-item label="学期" prop="team">
          <el-select v-model="form.team" placeholder="请选择学期">
            <el-option label="上学期" :value="1">上学期</el-option>
            <el-option label="下学期" :value="2">下学期</el-option>
          </el-select>
        </el-form-item>
        </el-col>
        </el-row>
        <el-row class="last-row">
        <el-col :span="8" class="first-column">
        <el-form-item label="高一班数" prop="one">
          <el-input-number v-model="form.one"  :min="1" :max="50" label="描述文字"></el-input-number>
          </el-form-item>
        </el-col>
          <el-col :span="8">
          <el-form-item label="高二班数" prop="two">
                    <el-input-number v-model="form.two"  :min="1" :max="50" label="描述文字"></el-input-number>
          </el-form-item>
          </el-col>
          <el-col :span="8">
          <el-form-item label="高三班数" prop="three">
                    <el-input-number v-model="form.three"  :min="1" :max="50" label="描述文字"></el-input-number>
          </el-form-item>
          </el-col>
      </el-row>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="cancel()">取 消</el-button>
        <el-button type="primary" v-if="($_has('session.update') && isEdit) || ($_has('session.store') && isNew)" @click="save()">确 定</el-button>
      </div>
    </el-dialog>

    </div>
  </div>
</template>



<script>
import {
  getInfo,
  getInfoById,
  addInfo,
  updateInfo,
  deleteInfoById,
  Model
} from "@/api/session";

export default {
  data () {
    return {
      tableData: [],  //数据表格内容
      editDialogFormVisible: false, // 编辑和添加对话框开关
      uploadId: "",   // 更新的Id号
      form: new Model(), //  编辑和添加数据对应的模型
      isNew: false,    // 添加状态
      isEdit: false   // 编辑状态
    };
  },
  methods: {
    formatTeam(row, column){ // 格式化数据
       return row.team ==1 ?'上学期': '下学期'
    },
    formatYear(row, column) {   // 格式化数据
      let nextYear = parseInt(row.year)+1
      return row.year + '-' + nextYear
    },
    fetchData () {  // 获取数据列表
      getInfo().then(response => {

        let result = response.data;
        this.tableData = result;
      })
        .catch(() => {
        });
    },
    add () {  // 显示添加页面
      this.form = new Model();
      this.isNew = true;
      this.editDialogFormVisible = true;
    },
    edit (row) {  // 显示编辑页面
       let id = row.id;
       this.uploadId = id;
       getInfoById(id).then(response => {
         let result = response.data;
         let yearTime = new Date(result.year, 1, 1);
         result.year = yearTime
         this.form = result;
        this.isEdit = true;
        this.editDialogFormVisible = true;
      });
    },
    newData(){  // 保存新建数据
      this.isNew =false;
      addInfo(this.form).then(response => {
        //成功执行内容
        let result = response.status_code;
        if (result == 200) {
          this.$message({
            type: 'success',
             message: '创建新的学期信息成功',
          })
          this.fetchData()
        }
      }).catch(err => {
          let result = err.response.data
          this.$message({
            type: 'error',
             message: result.message,
          })
      })
    },
    updateData() {  // 保存更新数据
      this.isEdit =false;
      updateInfo(this.uploadId, this.form).then(response => {
        //成功执行内容
        let result = response.status_code;
        if (result == 200) {
          let currentId = this.form.id;
          let record = 0;
          record = this.tableData.findIndex((val, index) => {
            return val.id == currentId;
          });
          this.tableData.splice(record, 1, this.form);
          this.$message({
            type: 'success',
             message: '学期信息更改成功',
          })
        }
      })
        .catch((err) => {
          console.log(err.response.data)
        });
    },
    cancel() { // 对话框中取消保存
      this.isEdit =false;
      this.isNew =false;
      this.editDialogFormVisible = false;
    },
    save() { // 对话框中保存数据
      this.editDialogFormVisible = false;
      if (this.isEdit && !this.isNew) {
        this.updateData();
      }
      if (!this.isEdit && this.isNew) {
        this.newData();
      }
    },
    del (row) { // 删除指定的数据
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
          })
            .catch(() => {
            });
        })
    }
  },
  mounted () {
    this.fetchData()
  },
};
</script>

<style lang="scss">
@import './../../styles/app-main.scss';
#session .el-form--label-top .el-form-item__label {
  width: 100%;
  text-align:center;
}
#session .el-form-item__content{
  text-align: center;
}

#session .first-row .el-col {
border:1px solid $border-color;
border-left: 0px;
}
#session #datagrid .first-column {
border-left:1px solid $border-color;
}

#session .last-row .el-col {
border:1px solid $border-color;
border-top: 0px;
border-left: 0px;
}

</style>

