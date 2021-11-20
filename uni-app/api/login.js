import {baseUrl} from './api.js'
import {getToken} from '@/utils/auth.js'
export function getCode(data) {
	return new Promise((resolve, reject)=>{
		uni.request({
			url: `${baseUrl}/api/mp/user/code`,
			method: 'POST',
			data,
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
export function wechat() {
	return new Promise((resolve, reject)=>{
		uni.request({
			url: `${baseUrl}/api/mp/wechat?pageSize=100`,
			method: 'GET',
			header: {
				'Authorization': `Bearer ` + getToken()
			},
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
export function getRoles() {
	return new Promise((resolve, reject)=>{
		uni.request({
			url: `${baseUrl}/api/mp/wechat/roles`,
			method: 'GET',
			header: {
				'Authorization': `Bearer ` + getToken()
			},
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

export function getInfo(data) {
	return new Promise((resolve, reject)=>{
		uni.request({
			url: `${baseUrl}/api/mp/user/info`,
			method: 'POST',
			data,
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

/**
 * @param {Object} data  用户的open_id 和基础信息
 * 新的用户登陆方式下 保存用户信息
 */
export function store(data) {
	return new Promise((resolve, reject)=>{
		uni.request({
			url: `${baseUrl}/api/mp/user/store`,
			method: 'POST',
			data,
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


export function getRun(data) {
	return new Promise((resolve, reject)=>{
		uni.request({
			url: `${baseUrl}/api/mp/user/run`,
			method: 'POST',
			header: {
				'Authorization': `Bearer ` + getToken()
			},
			data,
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



export function scan_login(data) {
 return new Promise((resolve, reject)=>{
  uni.request({
   url: `${baseUrl}/api/mp/scan_login`,
   method: 'POST',
   header: {
    'Authorization': `Bearer ` + getToken()
   },
   data,
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

export function reg_code(data) {
 return new Promise((resolve, reject)=>{
  uni.request({
   url: `${baseUrl}/api/mp/reg_code`,
   method: 'POST',
   header: {
    'Authorization': `Bearer ` + getToken()
   },
   data,
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

