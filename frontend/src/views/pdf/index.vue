<template>
  <div>
    <iframe
      src="https://view.officeapps.live.com/op/view.aspx?src=http://backend.ouenyione.com/xls/1559517081.xlsx"
      height="560"
      width="100%"
      frameborder="0"
    ></iframe>
  </div>
</template>

<script>
import { test } from "@/api/pdf.js";
import axios from "axios";
export default {
  name: "xls_preview",
  data() {
    return {};
  },
  computed: {
    pdfFile() {
      return `/static/pdf/web/viewer.html?file=${this.checkUrl}`;
    }
  },
  async created() {
    const res = await axios({
      method: "post",
      url: "http://backend.ouenyione.com/api/test",
      responseType: "blob"
    });
    console.log(res);
  }
};
</script>

<style>
</style>
