import request from "@/utils/request";
const url = "/wechats";

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

// 新增
export function store(data) {
  return request({
    url: `${url}`,
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