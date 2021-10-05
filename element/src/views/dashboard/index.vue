<template>
  <div class="dashboard-container">
    <component :is="currentRole" />
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import adminDashboard from "./admin";
import systemDashboard from "./system";

export default {
  name: "Dashboard",
  components: {
    adminDashboard,
    systemDashboard
  },
  data() {
    return {
      currentRole: "adminDashboard"
    };
  },
  computed: {
    ...mapGetters(["roles"])
  },
  created() {
    // 根据角色选择不同的面板， 系统面板
    if (
      this.roles.includes("admin") ||
      this.roles.includes("super") ||
      this.roles.includes("system")
    ) {
      this.currentRole = "systemDashboard";
    }
  }
};
</script>
