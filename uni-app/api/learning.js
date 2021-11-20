import {baseUrl} from './api.js'
import {getToken} from '@/utils/auth.js'

export function index() {
	return new Promise((resolve, reject)=>{
		uni.request({
			url: `${baseUrl}/api/mp/learnings/info`,
			method: 'GET',
			header: {
				'Authorization': `Bearer ` + getToken()
			},
			success: res => {
				if (res.statusCode >=500 && res.statusCode <= 600) {					
					reject(res)
				}
				if (res.statusCode >= 200 && res.statusCode < 500) {					
					resolve(res)
				}
			},
			fail: (error) => {
				reject()
			},
			complete: () => {}
		});
	})
}


export function newdata(data) {
	return new Promise((resolve, reject)=>{
		uni.request({
			url: `${baseUrl}/api/mp/learnings`,
			method: 'POST',
			data,
			header: {
				'Authorization': `Bearer ` + getToken()
			},
			success: res => {
				if (res.statusCode >=500 && res.statusCode <= 600) {					
					reject(res)
				}
				if (res.statusCode >= 200 && res.statusCode < 500) {					
					resolve(res)
				}
			},
			fail: (error) => {
				reject()
			},
			complete: () => {}
		});
	})
}
export function updata(data,id) {
	return new Promise((resolve, reject)=>{
		uni.request({
			url: `${baseUrl}/api/mp/learnings/`+id,
			method: 'PUT',
			data,
			header: {
				'Authorization': `Bearer ` + getToken()
			},
			success: res => {
				if (res.statusCode >=500 && res.statusCode <= 600) {					
					reject(res)
				}
				if (res.statusCode >= 200 && res.statusCode < 500) {					
					resolve(res)
				}
			},
			fail: (error) => {
				reject()
			},
			complete: () => {}
		});
	})
}


export function getcourse() {
	return new Promise((resolve, reject)=>{
		uni.request({
			url: `${baseUrl}/api/mp/courses`,
			method: 'GET',
			header: {
				'Authorization': `Bearer ` + getToken()
			},
			success: res => {
				if (res.statusCode >=500 && res.statusCode <= 600) {					
					reject(res)
				}
				if (res.statusCode >= 200 && res.statusCode < 500) {					
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