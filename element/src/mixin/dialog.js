/*
 * @Author: wmhello 871228582@qq.com
 * @Date: 2022-05-19 05:36:42
 * @LastEditors: wmhello 871228582@qq.com
 * @LastEditTime: 2022-08-11 14:29:11
 * @FilePath: \erp_frontend\src\mixin\dialog.js
 * @Description: 这是默认设置,请设置`customMade`, 打开koroFileHeader查看配置 进行设置: https://github.com/OBKoro1/koro1FileHeader/wiki/%E9%85%8D%E7%BD%AE
 */
export default {
  props: {
    show: {
      type: Boolean,
      value: false,
    },
  },
  data() {
    return {
      visible: this.show,
    }
  },
  watch: {
    show() {
      this.visible = this.show;
    },
  },
  methods: {
    onCancel(formName = "ruleForm") {
      this.resetForm(formName);
      this.$emit("update:show", false);
    },
    resetForm(formName = "ruleForm") {
      this.$refs[formName].resetFields();
    },
    submit(formName = "ruleForm") {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.$emit("save-data", this.formData);
        } else {
          this.$message.error("数据有误,请按照格式要求重新填写");
          return false;
        }
      });
    },
  },
}
