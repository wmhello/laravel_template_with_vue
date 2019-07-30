import fetch from '@/utils/fetch'

export function sendChat(data) {
  return fetch({
    url: '/api/chat',
    method: 'post',
    data
  })
}

export function getCustomer(){
  return new Promise((resolve, reject)=>{
       let data = {
          name: 'wmhello'
       }
       resolve(data)
  })
}

export function sendKefu(data) {
  return fetch({
    url: '/api/kefu',
    method: 'post',
    data
  })
}
