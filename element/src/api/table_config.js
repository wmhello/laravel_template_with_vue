/*
 * @Author: wmhello 871228582@qq.com
 * @Date: 2022-08-18 10:38:55
 * @LastEditors: wmhello 871228582@qq.com
 * @LastEditTime: 2022-08-18 18:56:39
 * @FilePath: \element\src\api\table_config.js
 * @Description: 这是默认设置,请设置`customMade`, 打开koroFileHeader查看配置 进行设置: https://github.com/OBKoro1/koro1FileHeader/wiki/%E9%85%8D%E7%BD%AE
 */
import request from "@/utils/request";
const url = "/table_configs";

// 列表
export function index(page = 1, pageSize = 100, searchObj = {}) {
  let params = Object.assign({}, { page, pageSize }, searchObj)
  return request({
    url: `${url}`,
    method: "get",
    params
  });
}

export function listColumns(table = null) {
  let params = Object.assign({}, { table })
  return request({
    url: `${url}/columns`,
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
