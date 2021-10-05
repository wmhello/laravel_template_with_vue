import request from '@/utils/request'

export function sendChat(data) {
  return request({
    url: '/chat',
    method: 'post',
    data
  })
}

export function getCustomer(){
  return new Promise((resolve, reject)=>{
       let data = {
          name: 'admin'
       }
       resolve(data)
  })
}

export function sendKefu(data) {
  return request({
    url: '/kefu',
    method: 'post',
    data
  })
}
