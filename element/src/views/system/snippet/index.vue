<!--
 * @Author: wmhello 871228582@qq.com
 * @Date: 2022-08-16 21:01:45
 * @LastEditors: wmhello 871228582@qq.com
 * @LastEditTime: 2022-08-16 23:56:38
 * @FilePath: \element\src\views\system\snippet\index.vue
 * @Description: 这是默认设置,请设置`customMade`, 打开koroFileHeader查看配置 进行设置: https://github.com/OBKoro1/koro1FileHeader/wiki/%E9%85%8D%E7%BD%AE-->
<template>
  <div class="warpper">
    <div class="toolbar">
      <el-form
        :inline="true"
        ref="ruleForm"
        :model="formData"
        class="demo-form-inline"
      >
        <el-form-item label="方案标识">
          <el-input v-model="formData.name" placeholder="英文标识"></el-input>
        </el-form-item>
        <el-form-item label="方案说明">
          <el-input v-model="formData.desc" placeholder="中文说明"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="success" @click="saveHandle">保存片段</el-button>
          <el-button type="primary" @click="onNew">新建方案</el-button>
          <el-button type="info">选择已经存在编辑</el-button>
        </el-form-item>
      </el-form>
    </div>
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
export default {
  name: "SnippetIndex",
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
  // 监听屏幕
  mounted() {
    let that = this;
    that.clientHeight = `${document.documentElement.clientHeight}`; //获取浏览器可视区域高度
    // // 获取codemirror对象
    that.editor = this.$refs.front.codemirror;
    that.editor2 = this.$refs.back.codemirror;
    // // 设置codemirror高度
    that.editor.display.wrapper.style.height = this.clientHeight - 250 + "px";
    that.editor2.display.wrapper.style.height = this.clientHeight - 250 + "px";
    // // 监听屏幕
    window.onresize = function () {
      that.clientHeight = `${document.documentElement.clientHeight}`;
      // 设置代码区域高度
      that.editor.display.wrapper.style.height = this.clientHeight - 250 + "px";
      that.editor2.display.wrapper.style.height =
        this.clientHeight - 250 + "px";
    };
  },
  methods: {
    transfromData(data) {
      if (data.length > 0) {
        this.formData = data[0];
      } else {
        this.formData = new Model();
      }
      return data;
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
// 这里不能加 scoped 不然就没有效果了
// .discode {
//   overflow-y: scroll !important;
//   font-size: 13px; // 下面两行是控制行间距的
//   line-height: 150%;
//   .CodeMirror {
//     overflow-y: scroll !important; // 这里控制高度自适应
//     height: auto !important;
//     .CodeMirror-scroll {
//       min-height: 300px; // 设置最小高度
//     }
//   }
// }
</style>
