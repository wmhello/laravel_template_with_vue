<template>
  <div class="container-callback">
    gitee 登陆成功
  </div>
</template>

<script>
import Cookie from "js-cookie";
export default {
  data() {
    return {
      code: null,
      github_token: null
    };
  },
  mounted() {
    const search = window.location.search;
    const token = search.split("=")[1];
    Cookie.set("gitee_token", token);
  },
  async created() {},
  methods: {}
};
</script>

<style>
.container-callback {
  min-width: 100vw;
  min-height: 100vh;
  background-image: url("/static/img/load3.jpg");
  background-repeat: round;
}
</style>
