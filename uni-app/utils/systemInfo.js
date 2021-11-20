module.exports = {
	info : function () {
		try {
		    var res = uni.getSystemInfoSync();
			var iPhoneXBottom = 0;
			res.model = res.model.replace(' ', '');
			res.model = res.model.toLowerCase();
			if(res.model.indexOf('iphonex') != -1 || res.model.indexOf('iphone11') != -1){
				res.iPhoneXBottomHeightRpx = 50;
				res.iPhoneXBottomHeightPx = uni.upx2px(50);
			}else{
				res.iPhoneXBottomHeightRpx = 0;
				res.iPhoneXBottomHeightPx  = 0;
			}
			return res;
		} catch (e){
		    return null;
		}
	}
}