import {
	getCode,
	getInfo,
	getRun
} from '@/api/login.js';

function loginStatus() {
	return new Promise((resolve, reject) => {
		uni.login({
			success: res => {
				resolve({
					code: res.code
				});
			},
			fail: () => {
				reject();
			}
		});
	});
}

function wxRun(session_key) {
	return new Promise((resolve, reject) => {
		wx.getWeRunData({
			//解密微信运动
			success: response => {
				resolve({
					session_key: session_key,
					iv: response.iv,
					encryptedData: response.encryptedData
				});
			},
			fail: () => {
				reject();
			}
		});
	});
}

export async function getCurrentRunInfo() {
	let session_key = null
	let data = await loginStatus();
	let res = await getCode(data);
	session_key = res.session_key;
	let run = await wxRun(session_key);
	let result = await getRun(run);
	let { stepInfoList, watermark } = result;
	let currentData = stepInfoList[stepInfoList.length - 1];
	let todayStep = currentData.step;
    return todayStep;
}

export async function getMonthRunInfo() {
	let session_key = null
	let data = await loginStatus();
	let res = await getCode(data);
	session_key = res.session_key;
	let run = await wxRun(session_key);
	let result = await getRun(run);
	let { stepInfoList, watermark } = result;
    return stepInfoList;
}
