<template>
	<view class="container">
		<u-form :model="form" :rules="rules" ref="uForm">
			<u-divider color="#fa3534" half-width="200" border-color="#6d6d6d">片区学生入学对调申请</u-divider>
			<u-form-item label="学生姓名" prop="stu_name" label-width="220" label-align="right" required><u-input v-model="form.stu_name" readonly="true" disabled /></u-form-item>
			<u-form-item required label="户口所在县份" label-width="220" label-align="right" prop="area">
				<u-input v-model="form.area" placeholder="县区" disabled></u-input>
			</u-form-item>
			<u-form-item required label="原片区学校名称" label-width="220" label-align="right" prop="old_school_name">
				<u-input v-if="isSchool && isNew" type="text" v-model="form.old_school_name" disabled="true"></u-input>
				<u-input
					v-if="isNew && !isSchool"
					type="select"
					:select-open="isShowSchool_old"
					v-model="form.old_school_name"
					placeholder="学校"
					@click="showSchool_old()"
				></u-input>
				<u-input v-if="!isNew" type="text" v-model="form.old_school_name" disabled="true"></u-input>
			</u-form-item>
			<u-form-item v-if="isshowchange" required label="申请对调学校名称" label-width="220" label-align="right" prop="new_school_name">
				<u-input v-if="isNew" type="select" :select-open="isShowSchool_new" v-model="form.new_school_name" placeholder="学校" @click="showSchool_new()"></u-input>
				<u-input v-else type="text" v-model="form.new_school_name" disabled="true"></u-input>
			</u-form-item>
		</u-form>
		<u-button v-if="isNew" type="success" shape="circle" @click="submit">提交申请</u-button>
		<u-button v-else-if="is_process" type="primary" shape="circle">{{ is_change }}</u-button>
		<u-button v-else type="warning" shape="circle" @click="unchange">撤销申请</u-button>

		<u-select v-model="isShowSchool_old" :default-value="defaultValue_old" mode="single-column" :list="schoolList" @confirm="selectSchool_old" v-if="isNew"></u-select>
		<u-select v-model="isShowSchool_new" :default-value="defaultValue_new" mode="single-column" :list="schoolList_scend" @confirm="selectSchool_new" v-if="isNew"></u-select>
		<u-toast ref="uToast" />
		<u-modal v-model="show_revoke" :zoom="false" content="确定要撤销对调申请吗？" :show-cancel-button="true" @confirm="confirmDelete"></u-modal>
	</view>
</template>

<script>
import { getNatureSchools as index } from '@/api/school.js';
import { store, getMsgById, update, add, revoke } from '@/api/exchange.js';
import { Model } from '@/model/exchange';

var session_key = null,
	_self = null,
	pageOptions = null;

export default {
	data() {
		return {
			form: new Model(),
			stu_name: null,
			area: null,
			change_id: '', //对消记录ID
			isNew: true, //是否新建
			is_process: false, //是否匹配成功
			is_change: '', //对调状态
			show_revoke: '', //撤销申请提示框
			isEdit: false,
			isshowchange: false,
			isShowSchool_old: false,
			isShowSchool_new: false,
			isShowArea: false,
			schoolList: null,
			schoolList_scend: [],
			defaultValue_new: [],
			defaultValue_old: [],
			isSchool: false,
			defaultArea: [0],
			temp: [],
			rules: {
				stu_name: [
					{
						required: true,
						message: '请输入姓名',
						// 可以单个或者同时写两个触发验证方式
						trigger: 'blur'
					}
				],
				area: [
					{
						required: true,
						message: '请选择县区',
						// 可以单个或者同时写两个触发验证方式
						trigger: 'blur'
					}
				],
				new_school_name: [
					{
						required: true,
						message: '请选择学校',
						// 可以单个或者同时写两个触发验证方式
						trigger: 'blur'
					}
				],
				old_school_name: [
					{
						required: true,
						message: '请选择学校',
						// 可以单个或者同时写两个触发验证方式
						trigger: 'blur'
					}
				]
			}
		};
	},
	// 必须要在onReady生命周期，因为onLoad生命周期组件可能尚未创建完毕
	async onReady() {
		uni.showLoading({
			title: '数据加载中...',
			mask: true
		});
		this.$refs.uForm.setRules(this.rules);
		let data = await index();
		data = data.map(function(item, index) {
			item.extra = index;
			item.children = item.children.map(function(v2, i2) {
				v2.extra = i2;
				v2.children = v2.children.map(function(v3, i3) {
					v3.extra = i3;
					return v3;
				});
				return v2;
			});
			return item;
		});
		this.temp = data;
		uni.getStorage({
			key: 'student_data',
			success: async res => {
				let { data } = res;
				let { stu_name, stu_residence, stu_residence_code, id, stu_section, school_name, school_id } = data;
				this.form.stu_name = stu_name;
				this.form.area = stu_residence;
				this.form.register_id = id;

				let value = stu_residence_code;
				let item = this.temp.find(e => e.value == value);
				if (!item) {
					this.$tip('您的孩子不符合对调要求。');
				}
				// let schoolList = item.children;
				let schoolList = this.getOldShooldata(item.children, stu_section.substring(0, 2));
				this.schoolList = schoolList;
				if (school_name) {
					this.isSchool = true;
					this.form.old_school_name = school_name;
					this.form.old_school_id = school_id;
					this.schoolList_scend = this.getShooldata(this.schoolList, school_id);
					this.isshowchange = true;
				}
				try {
					let { data } = await getMsgById(this.form.register_id);
					if (data) {
						this.form.old_school_name = data.old_school_name;
						this.form.old_school_id = data.old_school_id;
						this.form.new_school_name = data.new_school_name;
						this.form.new_school_id = data.new_school_id;
						this.change_id = data.id;
						this.isshowchange = true;
						this.isNew = false;
						if (data.is_process) {
							this.is_process = true;
							this.is_change = '对调正在办理中';
						}
						if (data.is_exchange) {
							this.is_change = '对调成功';
						}
					}
				} catch (e) {
					//TODO handle the exception
				}
			}
		});
		uni.hideLoading();
	},
	async onLoad() {},
	methods: {
		submit() {
			this.$refs.uForm.validate(async valid => {
				if (valid) {
					try {
						let data = await add(this.form);
						this.$refs.uToast.show({
							title: '对调申请提交成功！',
							type: 'success',
							url: '/pagesS/student/register/register'
						});
					} catch (e) {
						//TODO handle the exception
					}
				} else {
				}
			});
		},

		//撤销对调
		async unchange() {
			this.show_revoke = true;
			// let data =await revoke()
		},
		async confirmDelete() {
			if (this.change_id) {
				try {
					let data = await revoke(this.change_id);
					this.$refs.uToast.show({
						title: data.info,
						type: 'success',
						url: '/pagesS/student/register/register'
					});
				} catch (e) {
					//TODO handle the exception
				}
			} else {
				this.$tip('未找到对调信息！');
			}
		},
		//原学校
		selectSchool_old(e) {
			this.schoolList_scend = [];
			// 显示选择的学校
			// 设置显示和标识
			this.form.old_school_name = e[0].label;
			this.form.old_school_id = e[0].value;
			// 设置默认值
			this.form.defaultValue_old = e[0].extra;

			this.schoolList_scend = this.getShooldata(this.schoolList, e[0].value);
			this.isshowchange = true;
		},
		//申请对调的新学校
		selectSchool_new(e) {
			this.form.new_school_id = e[0].value;
			this.form.new_school_name = e[0].label;
			this.form.defaultValue_new = e[0].extra;
		},

		showSchool_old() {
			this.isShowSchool_old = true;
		},
		showSchool_new() {
			this.isShowSchool_new = true;
		},
		//根据选定学段获取对调学校数据(原学校)
		getOldShooldata(data, code) {
			let shooldata = [];
			data.forEach(function(item, index) {
				if (item.value === code) {
					item.children.forEach(function(i2, v2) {
						shooldata.push(i2);
					});
				}
			});
			return shooldata;
		},

		//根据选定学段获取对调学校数据（对调到的学校）
		getShooldata(data, code) {
			let shooldata = [];
			data.forEach(function(item, index) {
				if (item.value !== code.toString()) {
					shooldata.push(item);
				}
			});
			return shooldata;
		}
	}
};
</script>
<style>
.container {
	margin: 20rpx;
}
</style>
