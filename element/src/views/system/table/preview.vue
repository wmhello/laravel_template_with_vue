<!--
 * @Author: wmhello 871228582@qq.com
 * @Date: 2022-08-16 21:01:45
 * @LastEditors: wmhello 871228582@qq.com
 * @LastEditTime: 2022-08-19 11:05:19
 * @FilePath: \element\src\views\system\snippet\index.vue
 * @Description: 这是默认设置,请设置`customMade`, 打开koroFileHeader查看配置 进行设置: https://github.com/OBKoro1/koro1FileHeader/wiki/%E9%85%8D%E7%BD%AE-->
<template>
  <div class="warpper">
    <div class="table">
      <el-tabs
        style="margin-top: 10px"
        v-model="activeName"
        type="card"
        @tab-click="handleClick"
      >
        <el-tab-pane label="后端控制器" name="back_api"></el-tab-pane>
        <el-tab-pane label="后端模型" name="back_model"></el-tab-pane>
        <el-tab-pane label="后端资源" name="back_resource"></el-tab-pane>
        <el-tab-pane label="后端路由" name="back_routes"></el-tab-pane>
        <el-tab-pane label="前端api" name="front_api"></el-tab-pane>
        <el-tab-pane label="前端模型" name="front_model"></el-tab-pane>
        <el-tab-pane label="前端页面" name="front_page"></el-tab-pane>
      </el-tabs>
      <div class="discode">
        <codemirror
          v-show="
            ['front_page', 'front_model', 'front_api'].includes(activeName)
          "
          ref="front"
          v-model="formData[activeName]"
          class="code"
          :options="cmOptions"
        ></codemirror>
      </div>
      <div class="discode">
        <codemirror
          ref="back"
          v-show="
            ['back_api', 'back_model', 'back_resource', 'back_routes'].includes(
              activeName
            )
          "
          :options="backOptions"
          v-model="formData[activeName]"
          class="code"
        ></codemirror>
      </div>
    </div>
  </div>
</template>

<script>
import "@/styles/view.scss";
import CURD from "@/mixin/simple";
import { codemirror } from "vue-codemirror";
require("codemirror/mode/python/python.js");
require("codemirror/addon/fold/foldcode.js");
require("codemirror/addon/fold/foldgutter.js");
require("codemirror/addon/fold/brace-fold.js");
require("codemirror/addon/fold/xml-fold.js");
require("codemirror/addon/fold/indent-fold.js");
require("codemirror/addon/fold/markdown-fold.js");
require("codemirror/addon/fold/comment-fold.js");
import { Model } from "@/model/snippet";
import { index } from "@/api/table_config";
export default {
  name: "PreviewIndex",
  mixins: [CURD],
  components: {
    codemirror,
  },
  data() {
    return {
      module: "snippet",
      newTitle: "新增信息",
      editTitle: "编辑信息",
      activeName: "back_api",
      content: null,
      snippet: {},
      editor: null,
      editor2: null,
      tableConfig: {},
      columns: [],
      cmOptions: {
        // codemirror options
        tabSize: 4,
        mode: { name: "javascript", json: true },
        // mode: {name:"python",json:true},//代码类型
        theme: "darcula",
        //设置主题
        lineNumbers: true, //是否显示行数
        linewrapping: true,
        extrakeys: { Ctrl: "autocomplete" }, //按键配置
        linewiseCopyCut: true,
        showCursorwhenSelecting: true,
        matchBrackets: true, //括号匹配
        line: true,
      },
      backOptions: {
        // codemirror options
        tabSize: 4,
        mode: { name: "php", json: true },
        // mode: {name:"python",json:true},//代码类型
        theme: "darcula",
        //设置主题
        lineNumbers: true, //是否显示行数
        linewrapping: true,
        extrakeys: { Ctrl: "autocomplete" }, //按键配置
        linewiseCopyCut: true,
        showCursorwhenSelecting: true,
        matchBrackets: true, //括号匹配
        line: true,
      },
    };
  },
  beforeRouteEnter(to, from, next) {
    next(async (vm) => {
      // 通过 `vm` 访问组件实例
      vm.tableConfig = to.query;
      let { data } = await index(1, 100, { table: vm.tableConfig.table_name });
      vm.tableData = data;
      vm.tableData.forEach((v) => {
        vm.columns.push(v.column_name);
      });
    });
  },
  // 监听屏幕
  mounted() {
    let that = this;
    that.clientHeight = `${document.documentElement.clientHeight}`; //获取浏览器可视区域高度
    // // 获取codemirror对象
    that.editor = this.$refs.front.codemirror;
    that.editor2 = this.$refs.back.codemirror;
    // // 设置codemirror高度
    that.editor.display.wrapper.style.height = this.clientHeight - 200 + "px";
    that.editor2.display.wrapper.style.height = this.clientHeight - 200 + "px";
    // // 监听屏幕
    window.onresize = function () {
      that.clientHeight = `${document.documentElement.clientHeight}`;
      // 设置代码区域高度
      that.editor.display.wrapper.style.height = this.clientHeight - 200 + "px";
      that.editor2.display.wrapper.style.height =
        this.clientHeight - 200 + "px";
    };
  },
  methods: {
    transfromData(data) {
      if (data.length > 0) {
        this.formData = data[0];
      } else {
        this.formData = new Model();
      }
      // 处理后端控制器
      this.formData.back_api = this.formData.back_api.replace(
        /##back_model##/g,
        this.tableConfig.back_model
      );

      // 填充字段
      let format = "##fillable##";
      let value = null;
      this.columns.forEach((v) => {
        let content = "";
        content = `'${v}', `;
        value === null ? (value = content) : (value += content);
      });
      value === null
        ? (value = "[]")
        : (value = "[" + value.slice(0, value.length - 2) + "]");
      this.formData.back_api = this.formData.back_api.replace(format, value);

      // 控制器中的查询
      format = `$this->model::paginate($pageSize)`;
      value = null;
      this.tableData.forEach((v) => {
        let content = "";
        if (v.query_type) {
          let title = this.upperToForamt(v.column_name);
          content = `${title}()->`;
          value === null ? (value = content) : (value += content);
        }
      });
      value === null
        ? (value = format)
        : (value = `$this->model::${value}paginate($pageSize)`);
      this.formData.back_api = this.formData.back_api.replace(format, value);

      // 处理后端模型
      this.formData.back_model = this.formData.back_model.replace(
        /##back_model##/g,
        this.tableConfig.back_model
      );
      format = "##scopeItem##";
      value = null;
      this.tableData.forEach((v) => {
        let content = "";
        if (v.query_type) {
          let title = this.upperToForamt(v.column_name);
          switch (v.query_type) {
            case "=":
              content = `
              public function scope${title}($query)
                {
                    $params = request()->input('${v.column_name}');
                    if ($params) {
                        return $query = $query->where('${v.column_name}', $params);
                    } else {
                        return $query;
                    }
                }
              `;
              break;
            case "like":
              content = `
              public function scope${title}($query)
                {
                    $params = request()->input('${v.column_name}');
                    if ($params) {
                        return $query = $query->where('${v.column_name}','like', "%".$params."%");
                    } else {
                        return $query;
                    }
                }
              `;
              break;
            case "<>":
              content = `
              public function scope${title}($query)
                {
                    $params = request()->input('${v.column_name}');
                    if ($params) {
                        return $query = $query->where('${v.column_name}', '<>', $params);
                    } else {
                        return $query;
                    }
                }
              `;
              break;
            case "null":
              content = `
              public function scope${title}($query)
                {
                    $params = request()->input('${v.column_name}');
                    if ($params) {
                        return $query = $query->whereNull('${v.column_name}');
                    } else {
                        return $query;
                    }
                }
              `;
              break;
            case "notnull":
              content = `
              public function scope${title}($query)
                {
                    $params = request()->input('${v.column_name}');
                    if ($params) {
                        return $query = $query->whereNotNull('${v.column_name}');
                    } else {
                        return $query;
                    }
                }
              `;
              break;
          }
          value === null ? (value = content) : (value += content);
        }
      });
      value === null ? (value = "") : (value = content);
      console.log(content);
      // this.formData.back_model = this.formData.back_model.replace(
      //   format,
      //   value
      // );
      // 处理后端资源集合
      this.formData.back_resource = this.formData.back_resource.replace(
        /##back_model##/g,
        this.tableConfig.back_model
      );

      // 处理后端路由
      this.formData.back_routes = this.formData.back_routes.replace(
        /##back_model##/g,
        this.tableConfig.back_model
      );
      this.formData.back_routes = this.formData.back_routes.replace(
        /##back_routes##/g,
        this.tableConfig.back_routes
      );
      this.formData.front_api = this.formData.front_api.replace(
        /##back_routes##/g,
        this.tableConfig.back_routes
      );
      // 前端页面
      this.formData.front_page = this.formData.front_page.replace(
        /##front_model##/g,
        this.tableConfig.front_model
      );
      let content = "";
      this.tableData.forEach((v) => {
        if (v.is_list) {
          content += ` <el-table-column prop="${v.column_name}" label="${v.column_comment}" width="100" align="center" />\n`;
        }
      });
      this.formData.front_page = this.formData.front_page.replace(
        /##front_component_name##/g,
        this.tableConfig.front_component_name
      );
      this.formData.front_page = this.formData.front_page.replace(
        /##columnInfo##/g,
        content
      );
      return data;
    },
    upperToForamt(str) {
      str = str.charAt(0).toUpperCase() + str.slice(1);
      return str;
    },
    handleClick() {},
    async saveHandle() {
      if ("id" in this.snippet) {
        this.isEdit = true;
        this.isNew = false;
      } else {
        this.isEdit = true;
        this.isNew = false;
      }
      await this.save("ruleForm");
    },
    onNew() {
      this.$nextTick(() => {
        this.formData = new Model();
      });
    },
  },
};
</script>

<style lang="scss">
</style>
