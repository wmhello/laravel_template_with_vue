<template>
  <div class="data-tabs">
    <el-row :span="24">
      <el-col v-for="(item, index) in data" :key="index" :span="span">
        <div class="item" :style="{ background: item.color }">
          <div class="item-header">
            <p>{{ item.title }}</p>
            <span>{{ item.subtitle }}</span>
          </div>
          <div class="item-body">
            <h2>
              <count-to
                :start-val="0"
                :end-val="item.count"
                :duration="3600"
                class="card-panel-num"
              />
            </h2>
          </div>
          <div class="item-footer">
            <span>{{ item.allcount }}</span>
            <p>{{ item.text }}</p>
          </div>
          <p class="item-tip">{{ item.key }}</p>
        </div>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import CountTo from "vue-count-to";
export default {
  name: "NxDataTabs",
  components: {
    CountTo
  },
  props: {
    option: {
      type: Object,
      default: () => {}
    }
  },
  data() {
    return {
      span: this.option.span || 6,
      data: this.option.data || []
    };
  }
};
</script>

<style lang="scss" scoped></style>
