<template>
	<view>
		  小程序首页
		  <button type="default" @click="cmdLogin">点击登陆</button>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				
			}
		},
		methods: {
			cmdLogin(){
				uni.redirectTo({
					url: '/pages/login/login'
				})
			}
		}
	}
</script>

<style>

</style>
