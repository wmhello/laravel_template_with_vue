<template>

  <div class="warpper">
    <div class="toolbar">
      <el-button type="primary" plain @click="add">添加店铺</el-button>
    </div>
    <div class="table">
      <el-table :data="tableData"
                size="small"
                stripe
                border
                style="width: 100%">
        <el-table-column prop="id" label="标识" width="50" align="center" />
        <el-table-column prop="seller_name"
                         label="店铺名称"
                         width="150"
                         align="center" />
        <el-table-column prop="client_id" label="店铺Client_id" width="200" />
        <el-table-column prop="api_key" label="店铺api_key" min-width="200" />
        <el-table-column prop="seller_desc" label="店铺说明" width="120">
        </el-table-column>
        <el-table-column label="操作" width="300">
          <template slot-scope="scope">
              <el-button plain @click="edit(scope.row.id)">修改</el-button>
              <el-button plain type="danger" @click="del(scope.row.id)"
                >删除</el-button
              >
            </template>
        </el-table-column>
      </el-table>
    </div>
    <el-dialog :title="title"
               :visible.sync="dialogFormVisible"
               :close-on-click-modal="false"
               width="40%">
      <el-form v-if="dialogFormVisible"
               ref="ruleForm"
               :model="formData"
               :rules="rules">
        <!-- 这里面开始 -->
        <el-row :gutter="20">
          <el-col :span="12">
            <el-form-item label="店铺名称" prop="seller_name">
              <el-input v-model="formData.seller_name" :disabled="isEdit" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="店铺说明" prop="seller_desc">
              <el-input v-model="formData.seller_desc" type="text" />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="12">
            <el-form-item label="店铺Client_Id" prop="client_id">
              <el-input v-model="formData.client_id" type="text" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="店铺Api_Key"
                          type="text"
                          prop="api_key">
              <el-input v-model="formData.api_key" />
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="save()">{{ cmdTitle }}</el-button>
      </div>
    </el-dialog>
  </div>

</template>

<script>

  import CURD from '@/mixin/simple'
  export default {
    name: '##component##',
    mixins: [CURD],
    data() {
      return {
        module: '##name##',
        newTitle: '新增信息',
        editTitle: '编辑信息'
      }
    },
    methods: {}
  }

</script>

<style>

  .table,
  .toolbar,
  .page {
    margin: 20px;
  }

  .toolbar {
    margin-top: 10px;
    border: 1px solid #ccc;
    padding: 5px;
  }
  .table {
    margin-bottom: 10px;
  }

  .el-form table tbody {
    width: 100%;
  }
  .el-form .header {
    box-sizing: border-box;
    border: 1px solid #ccc;
    background-color: #09f;
    color: #fff;
    height: 44px;
    display: flex;
    flex-direction: row;
  }
  .el-form .header .title {
    margin: auto;
    font-weight: bold;
  }
  .el-form .content {
    display: flex;
    box-sizing: border-box;
    border: 1px solid #ccc;
    background-color: #0cf;
    color: #fff;
    height: 44px;
    flex-direction: row;
  }
  .el-form .content div {
    margin: auto;
    border-radius: 0px;
  }
  .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409eff;
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
