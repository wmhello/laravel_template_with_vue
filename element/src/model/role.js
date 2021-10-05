export const rules = {
  name: [{ required: true, message: '请输入角色名称', trigger: 'blur' }]
}

export function Model(name = '', desc = '', permissions = []) {
  this.name = name
  this.desc = desc
  this.permissions = permissions
}

export function SearchModel() {}
