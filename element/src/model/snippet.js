
export const rules = {
  name: [{ required: true, message: "请输入名称", trigger: "blur" }],
};

export function Model(
) {
  this.name = null;
  this.desc = null;
  this.back_api = null
  this.back_model = null
  this.back_resource = null
  this.back_routes = null
  this.front_api = null
  this.front_model = null
  this.front_page = null
}

export function SearchModel(id = null) {
  this.id = id
}
