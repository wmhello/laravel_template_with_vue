import fetch from '@/utils/fetch'
import {log_baseUrl} from './api'

export function getInfo (searchObj = {}, page = 1, pageSize = 10) {
  if (typeof searchObj['created_at'] === 'object') {
    searchObj['time'] = searchObj['created_at'].getTime()/1000
  }
  return fetch({
    url: '/api/logs/show',
    method: 'get',
    params: {
        page,
        pageSize,
        user_name: searchObj.user_name,
        type: searchObj.type,
        created_at: typeof searchObj.created_at === 'object'?searchObj['time'] : ''  // 修复bug，日期选择器会跳转到1970年
    }
  })
}
