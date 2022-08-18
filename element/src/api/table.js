/*
 * @Author: wmhello 871228582@qq.com
 * @Date: 2022-08-16 16:04:50
 * @LastEditors: wmhello 871228582@qq.com
 * @LastEditTime: 2022-08-18 13:38:55
 * @FilePath: \element\src\api\table.js
 * @Description: 这是默认设置,请设置`customMade`, 打开koroFileHeader查看配置 进行设置: https://github.com/OBKoro1/koro1FileHeader/wiki/%E9%85%8D%E7%BD%AE
 */
import request from "@/utils/request";
const url = "/tables";

export function index(page = 1, pageSize = 100, searchObj = {}) {
  let params = Object.assign({}, { page, pageSize }, searchObj)
  return request({
    url: `${url}`,
    method: "get",
    params
  });
}
export function list() {
  return request({
    url: `${url}/list`,
    method: "get"
  });
}

export function show(id, searchObj = {}) {
  let params = Object.assign({}, searchObj)
  return request({
    url: `${url}/${id}`,
    method: "get",
    params
  });
}

export function store(data) {
  return request({
    url: `${url}`,
    method: "post",
    data
  });
}

export function update(data) {
  return request({
    url: `${url}/${data.id}`,
    method: "patch",
    data
  });
}

export function destroy(id) {
  return request({
    url: `${url}/${id}`,
    method: "delete"
  });
}

export function download(data) {
  return request({
    url: `${url}/export`,
    method: "post",
    data,
    responseType: "blob"
  });
}
