<template>
  <v-view :toolbar="toolbar" :columns="columns" :search-config="searchConfig">
  </v-view>
</template>

<script>
import View from "@/views/components/View";
import handle from "@/minix/handle";

export default {
  components: {
    "v-view": View
  },
  mixins: [handle],
  data() {
    return {
      toolbar: ["add", "upload", "download"],
      module: "order",
      searchConfig: [
       {
          label: "订单编号",
          type: "text",
          hasTitle: true,
          prop: "order_number"
        },
        {
          label: "商户编号",
          type: "text",
          hasTitle: false,
          prop: "merchant_number"
        }
      ],
      columns: [
        {
          label: "标识",
          prop: "id",
          width: "100"
        },
        {
          label: "订单编号",
          prop: "order_number",
          width: "180"
        },
        {
          label: "商户编号",
          prop: "merchant_number",
          width: "180"
        },
        {
          label: "商户名称",
          prop: "merchant_name",
          width: "180"
        }
      ]
    };
  }
};
</script>
