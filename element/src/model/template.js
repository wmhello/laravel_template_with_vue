
export const rules = {
  name: [{ required: true, message: "请输入名称", trigger: "blur" }],
};

export function Model(
  name = "",
  status = true
) {
  this.name = name;
  this.status = status;
}

export function SearchModel() {}
