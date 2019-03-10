<template>
  <div class="app-container calendar-list-container">
    <div class="filter-container">
      <el-input style="width: 200px;" class="filter-item" placeholder="用户名" v-model="listPageParams.username" @keyup.enter.native="handleSearch"></el-input>
      <el-button class="filter-item" type="primary" v-waves icon="search" @click="handleSearch()">搜索</el-button>
      <el-button v-if="user_add" class="filter-item" style="margin-left: 10px;" type="primary" icon="edit" v-waves @click="handleAdd()">添加</el-button>
    </div>
    <el-table :key='tableKey' :data="list" v-loading="listLoading" element-loading-text="数据加载中..." border fit
              highlight-current-row style="width: 100%" :default-sort = "{ prop: 'userId', order: 'descending'}">
      <el-table-column align="center" label="序号" sortable prop="userId">
        <template slot-scope="scope">{{ scope.row.userId }}</template>
      </el-table-column>
      <el-table-column align="center" label="用户名">
        <template slot-scope="scope">
          <span>
            <img v-if="scope.row.picUrl" class="user-picUrl" style="width: 20px; height: 20px; border-radius: 50%;" :src="scope.row.picUrl">
            {{scope.row.username}}
          </span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="所属角色" show-overflow-tooltip>
        <template slot-scope="scope">
        <span v-for="(role,key) in scope.row.roleList" :key="key">{{role.roleDesc}} </span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="创建时间" sortable prop='createTime'>
        <template slot-scope="scope">
          <span>{{scope.row.createTime | parseTime('{y}-{m}-{d} {h}:{i}')}}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" class-name="status-col" label="状态">
        <template slot-scope="scope">
          <el-tag>{{scope.row.statu | statusFilter}}</el-tag>
        </template>
      </el-table-column>
      <el-table-column align="center" label="操作">
        <template slot-scope="scope">
          <el-button v-if="user_upd" size="small" type="success" @click="formEdit(scope.row)" v-waves>编辑</el-button>
          <el-button v-if="user_del" size="small" type="danger" @click="handleDelete(scope.row)" v-waves>删除</el-button>
        </template>
      </el-table-column>
    </el-table>

    <!-- //  分页 -->
    <div v-show="!listLoading" class="pagination-container">
      <el-pagination background
        @size-change="handlePageNumChange" @current-change="handlePageNoChange"
        :current-page.sync="listPageParams.pageNo + 1"
        :page-sizes="[20,40,60,80,100]" :page-size="listPageParams.pageNum"
        layout="total, sizes, prev, pager, next, jumper" :total="total">
      </el-pagination>
    </div>

    <!-- // from dialog -->
    <el-dialog :title='dialogTitleMap[dialogStatus]' :visible.sync='dialogFormVisible'>
      <el-form :model="editForm" :rules="editFormRules" ref="editForm" label-width="100px">
        <el-form-item label="用户名" prop="username">
          <el-input v-model="editForm.username" placeholder="请输用户名"></el-input>
        </el-form-item>

        <el-form-item v-if="dialogStatus == 'create'" label="密码" prop="password">
          <el-input type="password" v-model="editForm.password" placeholder="请输入密码" ></el-input>
        </el-form-item>

        <el-form-item label="角色" prop="roleId">
          <el-select class="filter-item" v-model="editForm.roleId" placeholder="请选择">
            <el-option v-for="item in editRolesOptions" :key="item.roleId" :label="item.roleDesc" :value="item.roleId" :disabled="editIsDisabled[item.statu]">
              <span style="float: left">{{ item.roleDesc }}</span>
              <span style="float: right; color: #8492a6; font-size: 13px">{{ item.roleCode }}</span>
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="状态" v-if="dialogStatus == 'update' && user_del " prop="statu" >
          <el-select class="filter-item" v-model="editForm.statu" placeholder="请选择" clearable filterable>
            <el-option v-for="(item,index) in statusOptions" :key="index" :label="item | statusFilter" :value="item"></el-option>
          </el-select>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="formCancel('editForm')">取 消</el-button>
        <el-button v-if="dialogStatus=='create'" type="primary" @click="formCreate('editForm')">确 定</el-button>
        <el-button v-else type="primary" @click="formUpdate('editForm')">修 改</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import { fetchUserList, delByUserId, addUser, fetchUserByUserId, updateUser } from '@/api/user'
import { fetchRoleList } from '@/api/role'
import { mapGetters } from 'vuex'
import waves from '@/directive/waves/index.js' // 点击按钮时候显示水波纹动画
export default {
  directives: {
    waves
  },
  data() {
    return {
      list: null,
      listLoading: false,
      listPageParams: {
        pageNo: 0,
        pageNum: 20,
        username: ''
      },
      tableKey: 0,
      total: null,
      dialogFormVisible: false, // 表单是否显示
      dialogStatus: '',
      dialogTitleMap: {
        update: '编辑',
        create: '创建'
      },
      editForm: {
        username: '',
        password: '',
        statu: '',
        deptName: '',
        roleId: ''
      },
      editFormRules: {
        username: [{ required: true, message: '请输入用户名', trigger: 'blur' }, { min: 3, max: 20, message: '长度在 3 到 20 个字符', trigger: 'blur' }],
        password: [{ required: true, message: '请输入密码', trigger: 'blur' }, { min: 5, max: 10, message: '长度在 5 到 10 个字符', trigger: 'blur' }],
        roleId: [{ required: true, message: '请选择角色', trigger: 'blur' }],
        statu: [{ required: true, message: '请选择状态', trigger: 'blur' }]
      },
      editRolesOptions: [],
      editIsDisabled: {
        0: false, 1: true
      },
      statusOptions: [0, 1]
    }
  },
  created() {
    this.getUserList()
    this.getRoleList()
    // 设置权限，后续将采用动态方式
    this.user_upd = this.permissions['user_upd']
    this.user_del = this.permissions['user_del']
    this.user_add = this.permissions['user_add']
  },
  methods: {
    getUserList() {
      this.listLoading = true
      fetchUserList(this.listPageParams).then(res => {
        this.list = res.data.list
        this.total = res.data.total
        this.listLoading = false
      })
    },
    getRoleList() {
      fetchRoleList().then(res => {
        this.editRolesOptions = res.data
      })
    },
    handleAdd() { // 添加
      this.formReset()
      this.dialogStatus = 'create'
      this.dialogFormVisible = true
    },
    handleSearch() { // 搜索
      this.listPageParams.pageNo = 0
      this.getUserList()
    },
    handlePageNumChange(_pageNum) { // 每页显示数量变化
      this.listPageParams.pageNum = _pageNum
      this.getUserList()
    },
    handlePageNoChange(_pageNo) { // 页码变化
      this.listPageParams.pageNo = _pageNo
      this.getUserList()
    },
    handleDelete(row) {
      this.$confirm('此操作将永久删除该用户(用户名:' + row.username + '), 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        delByUserId(row.userId).then(res => {
          if (res.data) {
            this.getUserList()
            this.$notify({ title: '成功', message: '删除成功', type: 'success', duration: 2000 })
          } else {
            this.$notify({ title: '失败', message: '删除失败', type: 'error', duration: 2000 })
          }
        })
      }).catch(() => {
        this.$message({ type: 'info', message: '已取消删除' })
      })
    },
    formCreate(_from) {
      const f = this.$refs
      f[_from].validate(r => {
        if (!r) return r
        addUser(this.editForm).then(res => {
          if (!res.data) {
            this.$notify({ title: '失败', message: '添加失败', type: 'error', duration: 2000 })
          } else {
            this.dialogFormVisible = false
            this.getUserList()
            this.$notify({ title: '成功', message: '添加成功', type: 'success', duration: 2000 })
          }
        })
        return r
      })
    },
    formCancel(_from) {
      this.dialogFormVisible = false
      this.$refs[_from].resetFields()
    },
    formEdit(row) { // 编辑
      this.formReset()
      this.dialogStatus = 'update'
      this.dialogFormVisible = true
      fetchUserByUserId(row.userId).then(res => {
        this.editForm.username = res.data.username
        this.editForm.password = ''
        this.editForm.statu = res.data.statu
        this.editForm.userId = res.data.userId
        this.editForm.roleId = res.data.roleList.length > 0 ? res.data.roleList[0].roleId : ''
      })
    },
    formUpdate(_from) {
      const f = this.$refs
      f[_from].validate(r => {
        if (!r) return r
        updateUser(this.editForm).then(res => {
          if (!res.data) {
            this.$notify({ title: '失败', message: '修改失败', type: 'error', duration: 2000 })
          } else {
            this.dialogFormVisible = false
            this.getUserList()
            this.$notify({ title: '成功', message: '修改成功', type: 'success', duration: 2000 })
          }
        })
        return r
      })
    },
    formReset() {
      this.editForm = {
        username: '',
        password: '',
        statu: '',
        roleId: ''
      }
    }
  },
  computed: {
    ...mapGetters([
      'permissions'
    ])
  },
  filters: {
    statusFilter(status) {
      const statusMap = {
        0: '有效',
        1: '无效',
        9: '锁定'
      }
      return statusMap[status]
    }
  }
}
</script>

