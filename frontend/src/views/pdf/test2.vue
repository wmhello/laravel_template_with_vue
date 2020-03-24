<template>
  <div>
    pdf测试
    <pdf ref="pdf" style="border: 1px solid red" src="/static/pdf/web/demo.pdf"></pdf>
  </div>
</template>

<script>
import pdf from "vue-pdf";
import axios from "axios";

export default {
  name: "pdf_test2",
  components: {
    pdf
  },
  data() {
    return {
      src: null
    };
  },
  async created() {
    let { data } = await axios.get("http://backend.ouenyione.com/api/test");
    console.log(data.data);

    this.src = data.data;
  }
};
