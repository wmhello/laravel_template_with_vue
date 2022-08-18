export default {
  data() {
    return {
      tableData: [],
      page: {
        total: null,
        per_page: 10,
        sizes: [10, 20, 25, 50],
        current_page: 1
      },
      rules: {},
      dialogFormVisible: false,
      isNew: false,
      isEdit: false,
      title: "",
      cmdTitle: "",
      formData: {},
      uploadVisible: false,
      searchForm: {},
      isPaginate: false
    };
  }
}
