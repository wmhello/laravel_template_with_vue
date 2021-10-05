export const rules = {
  title: [{ required: true, message: "请输入文章标题", trigger: "blur" }],
  summary: [{ required: true, message: "请输入简要描述", trigger: "blur" }],
  img: [{ required: true, message: "请选择文章封面", trigger: "change" }],
  conetent: [{ required: true, message: "文章内容不能为空", trigger: "blur" }],
  article_category_id: [
    { required: true, message: "请选择文章类型", trigger: "blur" }
  ]
};

export function Model(
  title = "",
  summary = "",
  status = true,
  img = "",
  content = "",
  order = 1,
  article_category_id = null
) {
  this.summary = summary;
  this.title = title;
  this.status = status;
  this.img = img;
  this.content = content;
  this.order = order;
  this.article_category_id = article_category_id;
}
