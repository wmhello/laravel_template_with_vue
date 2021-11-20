import {baseUrl} from './api.js'
import {getToken} from '@/utils/auth.js'
export function store(data) {
	return new Promise((resolve, reject)=>{
		uni.request({
			url: `${baseUrl}/api/mp/members`,
			method: 'POST',
			data,
			header: {
				'Authorization': `Bearer ` + getToken()
			},
			success: res => {
				if (res.statusCode >=400 && res.statusCode <= 600) {
					reject(res)
				}
				if (res.statusCode >= 200 && res.statusCode <= 300) {
					resolve(res.data)
				}
			},
			fail: () => {
				reject()
			},
			complete: () => {}
		});
	})
}


export function getStatus() {
	return new Promise((resolve, reject)=>{
		uni.request({
			url: `${baseUrl}/api/mp/members`,
			method: 'GET',
			header: {
				'Authorization': `Bearer ` + getToken()
			},
			success: res => {				
                if (res.statusCode >= 400 && res.statusCode <= 600) {
					reject(res)
				} 
				if (res.statusCode >= 200 && res.statusCode < 300) {
					resolve(res.data)
				}
			},
			fail: (error) => {
				reject()
			},
			complete: () => {}
		});
	})
}


export function sendCode(phone) {
	return new Promise((resolve, reject)=>{
		uni.request({
			url: `${baseUrl}/api/admin/sms/send`,
			method: 'POST',
			data: {
				phone
			},
			header: {
				'Authorization': `Bearer ` + getToken()
			},
			success: res => {
                if (res.statusCode === 404) {
					reject(res)
				} 
				if (res.statusCode === 200) {
					resolve(res.data)
				}
			},
			fail: () => {
				reject()
			},
			complete: () => {}
		});
	})
}




