<template>
  <div class="container-callback">
    github 登陆成功
  </div>
</template>

<script>
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
    this.$bus.emit("getGithubToken", token);
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
