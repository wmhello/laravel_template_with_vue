<template>
  <div>pdf读取实例</div>
</template>

<script>
import { test } from "@/api/pdf.js";
import axios from "axios";
export default {
  name: "PdfIndex",
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
