
export const rules = {
  name: [{ required: true, message: "请输入名称", trigger: "blur" }],
};

export function Model(
  name = "",
  desc = null,
  status = true
) {
  this.name = name;
  this.desc = desc;
  this.status = status
}

export function SearchModel() {}
