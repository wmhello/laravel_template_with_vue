export const rules = {
  name: [{ required: true, message: "请输入模块名称", trigger: "blur" }]
};

export function Model(name = "", desc = "", permissions = []) {
  this.name = name;
  this.desc = desc;
  this.permissions = permissions;
}

export function SearchModel() {}

export const permissionList = [
  {
    label: "菜单",
    value: "menu"
  },
  {
    label: "列表",
    value: "index"
  },
  {
    label: "详情",
    value: "show"
  },
  {
    label: "新增",
    value: "store"
  },
  {
    label: "修改",
    value: "update"
  },
  {
    label: "删除",
    value: "destroy"
  },
  {
    label: "导入",
    value: "import"
  },
  {
    label: "导出",
    value: "export"
  }
];
