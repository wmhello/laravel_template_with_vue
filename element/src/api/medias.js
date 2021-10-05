import request from "@/utils/request";
const url = "/medias";

export function store(data) {
  return request({
    url: `${url}`,
    method: "post",
    headers: {
      "Content-Type": "multipart/form-data"
    },
    data
  });
}
