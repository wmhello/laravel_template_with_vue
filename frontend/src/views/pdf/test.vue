<template>
  <div class="page-one">
    <p>pdf.js demo</p>
    <!--本地-->
    <div style="margin-top: 10px">
      <p>本地</p>
      <button @click="check">点击</button>
      <button @click="checkLocal">点击一下</button>
    </div>
    <!--服务器-->
    <div style="margin-top: 30px">
      <p>服务器</p>
      <button @click="checkError">查看错误</button>
      <button @click="checkNormal">查看有效</button>
      <button @click="checkSuccess">查看服务器跨域返回流</button>
    </div>
    <!--上传文件-->
    <div style="margin-top: 40px">
      <input type="file" name="myfile" id="myfile" @change="preview($event)" />
      <!--<button @click="changeLocal">点击预览本地pdf</button>-->
      <iframe v-if="showPdf" id="previewPdf" :src="fileUrl" height="560" width="100%"></iframe>
    </div>
    <!--canvas-->
    <div style="margin-top: 30px;color: #70DB55">
      <span style="font-weight: 600" @click="checkContract">点我试试</span>
    </div>
    <!-- <md-contract :visible="contractVisable" :showBtns="true" @handleModal="close"></md-contract> -->
  </div>
</template>

<script>
// import mdContract from "./contract.md.vue";
import axios from "axios";

export default {
  name: "pdf_test",
  components: {
    // mdContract
  },
  data() {
    return {
      fileUrl: "",
      showPdf: false,
      contractVisable: false
    };
  },
  methods: {
    check() {
      window.open("/static/pdf/web/viewer.html");
    },
    checkLocal() {
      //          let url = '/static/pdf/web/demo.pdf'
      let url = "demo.pdf";
      window.open("/static/pdf/web/viewer.html?file=" + url);
    },
    checkError() {
      let url = "http://somedomain/doc/manuals/R-intro.pdf"; // 报错跨域
      window.open("/static/pdf/web/viewer.html?file=" + url);
    },
    checkNormal() {
      let url = "http://backend.ouenyione.com/uploads/1.pdf"; // 有效 服务器配置跨域处理
      window.open("/static/pdf/web/viewer.html?file=" + url);
    },
    async checkSuccess() {
      // 后台返回流的形式，也是我本人项目的使用
      // let url = "http://backend.ouenyione.com/api/test?name=lx1.pdf";
      // // 当然上面的是可以的，但是此access_token 是有时效性的，只是放在这边当作个例子，至于最后我为什么加了个测试.pdf 是可以在浏览器标签叶上显示
      // // url = this.getObjectURL(url);
      // let { data } = await axios.get(url);
      // console.log(data);
      let { data } = await axios.get("http://backend.ouenyione.com/api/test");
      let result = data.data;
      var blob = new Blob([result], { type: "appliaction/pdf" });
      var reader = new FileReader();
      reader.readAsDataURL(blob);
      var that = this;
      reader.onload = function() {
        that.fileUrl = this.result;
        that.showPdf = true;
        console.log("结束");

        //window.open("/static/pdf/web/viewer.html?file=" + this.result);
      };
    },
    // 这是打开本地文件进行预览
    preview(event) {
      let files = document.getElementById("myfile").files[0];
      if (files.type !== "application/pdf") {
        alert("只能上传一份pdf文件哦～");
        return;
      }
      this.showPdf = true;
      this.fileUrl = this.getObjectURL(files);
    },
    getObjectURL(file) {
      let url = null;
      if (window.createObjectURL != undefined) {
        // basic
        url = window.createObjectURL(file);
      } else if (window.webkitURL != undefined) {
        // webkit or chrome
        url = window.webkitURL.createObjectURL(file);
      } else if (window.URL != undefined) {
        // mozilla(firefox)
        url = window.URL.createObjectURL(file);
      }
      console.log(url);

      return url;
    },
    checkContract() {
      this.contractVisable = !this.contractVisable;
    },
    close() {
      this.contractVisable = false;
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.page-one button {
  margin-left: 10px;
}
</style>
