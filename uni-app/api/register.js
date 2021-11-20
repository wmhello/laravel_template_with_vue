import {baseUrl} from './api.js'
import {getToken} from '@/utils/auth.js'

export function download(name) {
	return new Promise((resolve, reject)=>{
		uni.downloadFile({
			url: `${baseUrl}/api/mp/register/download_file?name=${name}`,
			method: 'POST',
			data:{
				name
			},
			header: {
				'Authorization': `Bearer ` + getToken()
			},
			success: res => {
				if (res.statusCode >=400 && res.statusCode <= 600) {
					reject(res)
				}
				if (res.statusCode >= 200 && res.statusCode <= 300) {
					resolve(res)
				}				
			},
			fail: () => {
				reject()
			},
			complete: () => {}
		});
	})
}