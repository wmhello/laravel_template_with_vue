import { isArray } from "@/utils/validate";
 
function checkArray(rule, value, callback) {
  if (!isArray(value)) {
    callback(new Error("请选择角色"));
  } else {
    callback();
  }
}

export const rules = {
  email: [{ required: true, message: "请输入登陆名", trigger: "blur" }],
  password: [
    { required: true, message: "请输入密码", trigger: "blur" },
    {
      min: 6,
      max: 20,
      message: "长度在 6 到 20 个字符",
      trigger: "blur"
    }
  ],
  password_confirmation: [
    { required: true, message: "请输入密码", trigger: "blur" },
    {
      min: 6,
      max: 20,
      message: "长度在 6 到 20 个字符",
      trigger: "blur"
    }
  ],
  roles: [{ required: true, validator: checkArray }]
};

export function Model(
  nickname = "",
  email = "",
  password = "",
  password_confirmation = "",
  roles = [],
  avatar = "",
  status = 1
) {
  this.nickname = nickname;
  this.email = email;
  this.password = password;
  this.password_confirmation = password_confirmation;
  this.roles = roles;
  this.avatar = avatar;
  this.status = status;
}

export function SearchModel() {}
