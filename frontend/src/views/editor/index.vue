<template>
  <div class="default-tinymce">
    <textarea class="editor"></textarea>
  </div>
</template>

<script>
import Tinymce from "tinymce";

export default {
  name: "editor_index",
  mounted() {
    Tinymce.init({
      selector: ".editor",
      language: "zh_CN",
      // 编辑器的皮肤，有 oxide oxide-dark
      // skin: 'oxide',
      // 编辑器宽高
      height: 600,
      // plugins: ['link'],  
    //会自动引入silver主题
    // https://fe.120yibao.com/common/tinymce/5.0.13/themes/silver/theme.min.js
      theme: 'silver',
    //会自动引入oxide皮肤
    // https://fe.120yibao.com/common/tinymce/5.0.13/skins/ui/oxide/skin.min.css
      skin: "oxide",
      menubar: true,
      statusbar: false,
      // 用到的插件
      plugins: 'image',
      async images_upload_handler (blobInfo, success, fail) {
          const file = blobInfo.blob()
          console.log(file);
          
          // try {
          //   const url = self.YB.businessURL(await self.upload.post(file))
          //   success(url)
          // } catch (e) {
          //   fail(e.message || '上传失败，请重试')
          // }
      },
        autosave_interval: '30s',
        // 自动保存的数据存储的最大时间
        autosave_retention: '30m',
    //   // 编辑区域内容样式
    //   content_css: "css/content.css",
      // 工具栏的配置项
      toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent  | link image | print preview media fullpage | forecolor backcolor emoticons",
     // toolbar: "code",
      // plugins: "numlist bullist",
      // toolbar: "lists",
    });
  }
};
</script>

<style lang="sass" scoped>

</style>
