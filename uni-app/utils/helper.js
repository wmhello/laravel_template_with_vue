export function getImageInfo(file) {
	return new Promise((resolve, reject) => {
		uni.getImageInfo({
			src: file, //服务器返回的带参数的小程序码地址
			success: function(res) {
				//res.path是网络图片的本地地址
				resolve(res.path);
			},
			fail: function(res) {
				//失败回调
				reject(res)
			}
		});
	})
}
