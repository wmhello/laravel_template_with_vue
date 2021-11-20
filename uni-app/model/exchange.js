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

export function Model(register_id = null, old_school_id = null, new_school_id = null, stu_name = null, area = null, school_old = null, school_new = null) {
	this.register_id = register_id,
	this.old_school_id = old_school_id
	this.new_school_id = new_school_id
	this.stu_name = stu_name
	this.area = area
	this.school_old = school_old
	this.school_new = school_new
}
