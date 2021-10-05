export const rules = {
  name: [{ required: true, message: "请输入类型，英文标识", trigger: "blur" }],
  note: [{ required: true, message: "请输入说明，中文说明", trigger: "blur" }]
};

export function Model(note = "", name = "", status = true) {
  this.name = name;
  this.note = note;
  this.status = status;
}
