import {baseUrl} from './api.js'

export function getShareInfo() {	
	return new Promise((resolve,reject)=>{
		uni.request({
			url: `${baseUrl}/api/mp/share/info`,
			method: 'GET',
			success: res => {
				resolve(res.data)
			},
			fail: () => {
				reject()
			},
			complete: () => {}
		});	
	})
}




