<script>
		import systemInfo from '@/utils/systemInfo.js'
		import store from '@/store'
		import {
			getToken
		} from '@/utils/auth.js'
		import {
			setRoles
		} from '@/utils/roles.js'
				
	export default {
		async onLaunch() {
			let info = systemInfo.info()
			this.$store.commit('SET_WIDTH', info.windowWidth)
			this.$store.commit('SET_HEIGHT', info.windowHeight)
			if (getToken()) {
				store.commit("SET_TOKEN", uni.getStorageSync('token'))
				store.commit("SET_AVATAR", uni.getStorageSync('avatar')) 
				store.commit("SET_NICKNAME", uni.getStorageSync('nickname')) 
				store.commit("SET_PHONE", uni.getStorageSync('phone')) 
			}
		},
		onShow: function() {
		},
		onHide: function() {
		}
	}
</script>

<style lang="scss">
	@import "uview-ui/index.scss";
	// @import url("static/icon/iconfont.css");
	/*每个页面公共css */
</style>