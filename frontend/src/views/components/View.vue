<template>
  <div class="view">
    <div class="search" v-if="hasSearch">

    </div>
    <div class="datalist">
      <div class="toolbar">
        <slot name="toolbar">
          <el-button
            v-if="toolbar.includes('add')"
            plain
            icon="el-icon-plus"
            @click="$parent.add"
          >添加</el-button>
          <el-button
            v-if="toolbar.includes('upload')"
            plain
            icon="el-icon-upload"
            @click="$parent.upload"
          >导入</el-button>
          <el-button
            v-if="toolbar.includes('download')"
            plain
            icon="el-icon-download"
            @click="$parent.download"
          >导出</el-button>
        </slot>
      </div>
      <el-table
        v-bind="$attrs"
        v-on="$listeners"
        :data="$parent.tableData"
        stripe
        border=""
        style="width: 100%"
      >
        <slot>
          <el-table-column
            v-for="(item, index) in columns"
            :key="index"
            :label="item.label"
            :prop="item.prop"
            :width="item.width"
          ></el-table-column>
          <el-table-column label="操作">
            <template slot-scope="scope">
              <el-tooltip content="编辑" placement="right-end">
                <el-button
                  size="small"
                  plain
                  icon="el-icon-edit-outline"
                  @click="$parent.edit(scope.row)"
                ></el-button>
              </el-tooltip>
              <el-tooltip content="删除" placement="right-end">
                <el-button
                  plain
                  icon="el-icon-delete"
                  type="danger"
                  size="small"
                  @click="$parent.del(scope.row)"
                ></el-button>
              </el-tooltip>
            </template>
          </el-table-column>
        </slot>
      </el-table>
      <el-row class="footer" v-if="hasFooter">
        <el-col :span="4">
          <el-button v-if="hasPatchDelete" type="danger" plain>批量删除</el-button>
        </el-col>
        <el-col :span="18" :offset="caclOffset">
          <el-pagination
            v-if="hasPager"
            @size-change="$parent.sizeChange"
            @current-change="$parent.pagination"
            :current-page.sync="$parent.current_page"
            :page-sizes="[10, 20, 25, 50]"
            :page-size="$parent.pageSize"
            background=""
            layout="total, sizes,  pager"
            :total="$parent.total"
          ></el-pagination>
        </el-col>
      </el-row>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    hasSearch: {
      type: Boolean,
      default: true
    },
    hasFooter: {
      type: Boolean,
      default: true
    },
    hasPatchDelete: {
      type: Boolean,
      default: true
    },
    hasPager: {
      type: Boolean,
      default: true
    },
    toolbar: {
      type: [Array],
      default: ["add", "upload", "download"]
    },
    columns: {
      type: [Array],
      required: true
    },
    searchConfig: {
      type: [Object],
      required: true
    }
  },
    data () {
    return {
      searchForm: {}
    }
  },
  computed: {
    caclOffset() {
      return this.hasPatchDelete === false ? 4 : 0;
    }
  },
     created() {
      // Vue.set(this.searchForm, 'order_number', '')
      // Vue.set(this.searchForm, 'merchant_number', '')
      this.searchForm = this.$parent.searchForm
  },
  methods: {
    test(){
      console.log(this.searchForm);
      console.log(this.$parent.searchForm);
    }
  }
}
</script>

<style lang="sass" scoped>

</style>
