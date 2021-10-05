import request from "@/utils/request";
const url = "/admins";

export function index(page = 1, pageSize = 10) {
  return request({
    url: `${url}`,
    method: "get",
    params: {
      page,
      pageSize
    }
  });
}

export function show(id) {
  return request({
    url: `${url}/${id}`,
    method: "get"
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

export function modify(data) {
  return request({
    url: `${url}/modify`,
    method: "post",
    headers: {
      "Content-Type": "multipart/form-data"
    },
    data
  });
}
