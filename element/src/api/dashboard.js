import request from "@/utils/request";
const url = "/dashboards";

export function index() {
  return request({
    url: `${url}`,
    method: "get"
  });
}
