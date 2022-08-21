/*
 * @Author: wmhello 871228582@qq.com
 * @Date: 2021-10-12 22:49:35
 * @LastEditors: wmhello 871228582@qq.com
 * @LastEditTime: 2022-08-21 11:37:39
 * @FilePath: \element\src\api\chat.js
 * @Description: 这是默认设置,请设置`customMade`, 打开koroFileHeader查看配置 进行设置: https://github.com/OBKoro1/koro1FileHeader/wiki/%E9%85%8D%E7%BD%AE
 */
import request from '@/utils/request'
const url = "/chats";

export function register(data) {
  return request({
    url: `${url}/register`,
    method: 'post',
    data
  })
}

export function unRegister() {
  return request({
    url: `${url}/un_register`,
    method: 'post'
  })
}

export function sendDataToUser(data) {
  return request({
    url: `${url}/send_data_to_user`,
    method: 'post',
    data
  })
}



