<template>
	<view>
		<view class="u-flex user-box u-p-l-30 u-p-r-20 u-p-b-30">
			<view class="u-m-r-10">
				<u-avatar :src="avatar" size="140"></u-avatar>
			</view>
			<view class="u-flex-1">
				<view class="u-font-18 u-p-b-20"></view>
				<view class="u-font-14 u-tips-color">微信号:{{nickname}}</view>
			</view>
			<view class="u-m-l-10 u-p-10">
				<u-icon name="scan" color="#969799" size="28"></u-icon>
			</view>
			<view class="u-m-l-10 u-p-10">
				<u-icon name="arrow-right" color="#969799" size="28"></u-icon>
			</view>
		</view>
		
		<view class="u-m-t-20">
			<u-cell-group>
				<u-cell-item icon="rmb-circle" title="支付"></u-cell-item>
			</u-cell-group>
		</view>
		
		<view class="u-m-t-20">
			<u-cell-group>
				<u-cell-item icon="star" title="收藏"></u-cell-item>
				<u-cell-item icon="photo" title="相册"></u-cell-item>
				<u-cell-item icon="coupon" title="卡券"></u-cell-item>
				<u-cell-item icon="heart" title="关注"></u-cell-item>
			</u-cell-group>
		</view>
		
		<view class="u-m-t-20">
			<u-cell-group>
				<u-cell-item icon="setting" title="设置"></u-cell-item>
			</u-cell-group>
		</view>
	</view>
</template>

<script>
	import {mapGetters} from 'vuex'
	import {getToken} from '@/utils/auth.js'
	export default {
		data() {
			return {
				pic:'https://uviewui.com/common/logo.png',
				show:true
			}
		},
		computed:{
			...mapGetters(['token', 'avatar', 'nickname', 'phone'])
		},
		onReady(){
			if (!this.token){
				// 没有登陆， 首先保存当前页面路径，然后跳转到登陆页面
				uni.setStorageSync('backtype', 2);
				uni.setStorageSync('backpage', '/pages/my/my');
				// 2
				uni.navigateTo({
					url: '/pages/login/login'
				})
			 }			
		},
		methods: {
			
		}
	}
</script>

<style>
page{
	background-color: #ededed;
}
.camera{
	width: 54px;
	height: 44px;
	

}
	.camera:active{
		background-color: #ededed;
	}

.user-box{
	background-color: #fff;
}
</style>
