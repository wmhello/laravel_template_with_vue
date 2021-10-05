<template>
  <div class="app-container">
    <div class="item">
      <nx-data-tabs :option="easyDataOption" />
    </div>
  </div>
</template>

<script>
import nxDataTabs from "@/components/nx-data-tabs/nx-data-tabs";

export default {
  name: "Report",
  components: {
    nxDataTabs
  },
  data() {
    return {
      columns: 24,
      activeName: "first",
      easyDataOption: {
        span: 6,
        data: [
          {
            title: "活动统计",
            subtitle: "活动",
            count: 0,
            allcount: 0,
            text: "活动个数",
            color: "rgb(49, 180, 141)",
            key: "个"
          },
          {
            title: "会员统计",
            subtitle: "会员",
            count: 0,
            allcount: 0,
            text: "会员人数",
            color: "rgb(56, 161, 242)",
            key: "人"
          },
          {
            title: "商品统计",
            subtitle: "商品",
            count: 0,
            allcount: 0,
            text: "商品数量",
            color: "rgb(117, 56, 199)",
            key: "种"
          }
        ]
      }
    };
  },
  computed: {},
  watch: {},
  created() {},
  methods: {}
};
</script>

<style scoped>
.item {
  margin-bottom: 16px;
}
/* .box-card {
  height: 400px;
} */
.el-card__body {
  padding-bottom: 5px;
}
</style>
<style lang="scss">
@import "../../../styles/data-card.scss";
@import "../../../styles/data-display.scss";
@import "../../../styles/data-icons.scss";
@import "../../../styles/data-tabs.scss";
</style>
