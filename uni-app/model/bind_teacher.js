import store from '@/store'
export const Rule = {
	name: [{
		required: true,
		message: '请输入姓名',
		// 可以单个或者同时写两个触发验证方式
		trigger: 'blur'
	}],
	area: [{
		required: true,
		message: '请选择县区',
		// 可以单个或者同时写两个触发验证方式
		trigger: 'blur'
	}],
	school: [{
		required: true,
		message: '请选择学校',
		// 可以单个或者同时写两个触发验证方式
		trigger: 'blur'
	}],
	id_number: [
		{
		required: true,
		message: '请输入身份证',
		trigger: 'blur'
	},{
		min: 18,
		message: '身份证长度不够',
		trigger: 'blur'
	},
	{
		// 自定义验证函数，见上说明
		validator: (rule, value, callback) => {
			return this.$u.test.idCard(value);
		},
		message: '身份证号码不正确',
		// 触发器可以同时用blur和change
		trigger: ['change', 'blur'],
	}],
	phone: [{
			required: true,
			message: '手机号码必填',
			trigger: 'blur'
		},
		{
			// 自定义验证函数，见上说明
			validator: (rule, value, callback) => {
				// 上面有说，返回true表示校验通过，返回false表示不通过
				// this.$u.test.mobile()就是返回true或者false的
				return this.$u.test.mobile(value);
			},
			message: '手机号码不正确',
			// 触发器可以同时用blur和change
			trigger: ['change', 'blur'],
		}
	]
}

export function Model(name = '', phone = store.state.user.phone, id_number = null, school = null, school_id = null, area = null,area_id = null) {
	this.name = name;
	this.phone = phone;
	this.id_number = id_number;
	this.school_id = school_id;
	this.school = school
	this.area = area
	this.area_id = area_id
	
}
