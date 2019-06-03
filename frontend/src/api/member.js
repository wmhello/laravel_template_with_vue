import fetch from '@/utils/fetch'
import {order_baseUrl as baseUrl} from './api'

export function getInfo(searchObj = {}, page = 1, pageSize = 10, id) {
  return fetch({
    url: baseUrl,
    method: 'get',
    params: {
        id,
        page: page,
        pageSize: pageSize,
        order_number: searchObj.order_number,
        merchant_number: searchObj.merchant_number,
        merchant_name: searchObj.merchant_name,
        status: searchObj.status,
    }
  })
}

export function getInfoById (id) {
  return fetch({
    url: baseUrl + '/' + id,
    method: 'get',
  })
}

export function updateInfo (id, data) {

    if (typeof data.order_time === 'string') {
      data.order_time = Math.ceil(Date.parse(data.order_time)/1000)
    } else {
      data.order_time =Math.ceil(((data.order_time).getTime())/1000)
    }
    return fetch({
      url: baseUrl +'/' + id,
      method: 'PATCH',
      data,
    })
}

export function deleteInfoById (id) {
  return fetch({
    url: baseUrl +'/' + id,
    method: 'delete',
  })
}

export function addInfo (data) {
  data.order_time =Math.ceil(((data.order_time).getTime())/1000)
  return fetch({
    url: baseUrl,
    method: 'post',
    data,
  })
}

export function deleteAll( params) {
  return fetch({
     url: baseUrl + '/deleteAll',
     method: 'post',
     data: {
       ids: params
     }
  })
}


// 获取订单对应的产品信息

export function getProductsById (id) {
  return fetch({
    url: baseUrl + '/' + id + '/products',
    method: 'get',
  })
}

export function getEnableProductsById (id) {
  return fetch({
    url: baseUrl + '/' + id + '/enable',
    method: 'get',
  })
}

export function getSummaryById (id) {
  return fetch({
    url: baseUrl + '/' + id + '/summary',
    method: 'get',
  })
}
