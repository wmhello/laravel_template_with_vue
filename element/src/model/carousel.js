export const rules = {
  title: [{ required: true, message: "请输入标题", trigger: "blur" }],
  img: [{ required: true, message: "请添加图片", trigger: "blur" }]
};

export function Model(
  title = "",
  img = "",
  url = "",
  opentype = "navigate",
  carousel_note = ""
) {
  this.title = title;
  this.img = img;
  this.url = url;
  this.carousel_note = carousel_note;
  this.opentype = opentype;
}
