export function alert(
	title = '提示',
	content = '内容',
) {
	return new Promise((resolve, reject) => {
		uni.showModal({
			title,
			content,
			showCancel: false,
			success: res => {
				if (res.confirm) {
					resolve()
				}
			}
		})
	})
}

export function confirm(
	title = '提示',
	content = '内容'
) {
	return new Promise((resolve, reject) => {
		uni.showModal({
			title,
			content,
			showCancel: true,
			success: res => {
				if (res.confirm) {
					resolve()
				}
				if (res.cancel) {
					reject()
				}
			}
		})
	})
}

export function tip(title, icon = "none") {
	return new Promise((resolve, reject) => {
		uni.showToast({
			title,
			icon,
			duration: 4000,
			mark: true,
			success: () => {
				resolve()
			}	
		})
	})

}


export function sheet(itemList = []){
	return new Promise((resolve,reject)=>{
		uni.showActionSheet({
			itemList,
			success: res=>{
				resolve(itemList[res.tapIndex])
			},
			fail: res=>{
				reject(res)
			}
		})
	})
}


