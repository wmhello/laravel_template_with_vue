import request from '@/utils/request'

export function getConfig(url, params) {
    return request({
        url: '/api/wx/jssdk/config',
        method: 'post',
        data: {
            api: params,
            url
        }
    })
}


export function pay(price,openId) {
    return request({
        url: '/api/wx/spa-pay',
        method: 'get',
        params: {
           price,
		   open_id:openId
        }
    })
}

export function getId(token) {
	return request({
	    url: '/api/wx/getId',
	    method: 'post',
	    data: {
	       token
	    }
	})
}




