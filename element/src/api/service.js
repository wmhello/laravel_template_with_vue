import request from "@/utils/request";
const url = "/services";

// 列表
export function index(page = 1, pageSize = 100, searchObj = {}) {
  let params = Object.assign({}, { page, pageSize }, searchObj)
  return request({
    url: `${url}`,
    method: "get",
    params
  });
}

// 详情
export function show(id) {
  return request({
    url: `${url}/${id}`,
    method: "get"
  });
}
export function getCustomer() {
  return request({
    url: `${url}/customer`,
    method: "get"
  });
}

// 新增
export function checkCustomer(data) {
  return request({
    url: `${url}/check`,
    method: "post",
    data
  });
}
export function customerRegister(data) {
  return request({
    url: `${url}/register`,
    method: "post",
    data
  });
}
export function unRegister(data) {
  return request({
    url: `${url}/un_register`,
    method: "post",
    data
  });
}
export function userLeave() {
  return request({
    url: `${url}/user_leave`,
    method: "post",
  });
}
export function sendDataToCustomer(data) {
  return request({
    url: `${url}/send_data_to_customer`,
    method: "post",
    data
  });
}
export function sendDataToUser(data) {
  return request({
    url: `${url}/send_data_to_user`,
    method: "post",
    data
  });
}

// 修改
export function update(data) {
  return request({
    url: `${url}/${data.id}`,
    method: "patch",
    data
  });
}

//删除
export function destroy(id) {
  return request({
    url: `${url}/${id}`,
    method: "delete"
  });
}

// 导入文件之前先下载模板
export function download() {
  return request({
    url: `${url}/import`,
    method: "post",
    data: {
      action: "download"
    },
    responseType: "blob"
  });
}
// 导入文件
export function upload(data) {
  return request({
    url: `${url}/import`,
    method: "post",
    headers: {
      "Content-Type": "multipart/form-data"
    },
    data
  });
}

// 导出文件和数据
export function exportData(data) {
  return request({
    url: `${url}/export`,
    method: "post",
    data,
    responseType: "blob"
  });
}
