import {baseUrl} from './api.js'
import {getToken} from '@/utils/auth.js'

export function getMsgById(id) {
	return new Promise((resolve, reject)=>{
		uni.request({
			url: `${baseUrl}/api/mp/exchanges/`+id,
			method: 'GET',
			header: {
				'Authorization': `Bearer ` + getToken()
			},
			success: res => {				
               resolve(res.data)
			},
			fail: (error) => {
				reject(error)
			},
			complete: () => {}
		});
	})
}

export function store(data) {
	return new Promise((resolve, reject)=>{
		uni.request({
			url: `${baseUrl}/api/mp/exchanges`,
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


export function revoke(id) {
	return new Promise((resolve, reject)=>{
		uni.request({
			url: `${baseUrl}/api/mp/exchanges/${id}`,
			method: 'DELETE',
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


export function update(data) {
	return new Promise((resolve, reject)=>{
		uni.request({
			url: `${baseUrl}/api/mp/exchanges/${data.id}`,
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
export function add(data) {
	return new Promise((resolve, reject)=>{
		uni.request({
			url: `${baseUrl}/api/mp/exchanges`,
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

