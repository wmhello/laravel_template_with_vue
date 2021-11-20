export function Model(member_name = '', random_number = null, user_id = null, phone = null, ) {
	this.member_name = member_name
	this.user_id = null,
	this.phone = phone,
	this.random_number = random_number
}

export const Rule = {
	member_name:[{
		required: true,
		message: '请输入姓名',
		// 可以单个或者同时写两个触发验证方式 
		trigger: ['change', 'blur'],
		min: 2,
		max: 4
	}],
	phone:[{
		required: true,
		message: '请输入手机号码',
		// 可以单个或者同时写两个触发验证方式 
		trigger: ['change', 'blur']
		}
	],
	random_number:[{
		required: true,
		message: '请输入验证码',
		// 可以单个或者同时写两个触发验证方式 
		trigger: ['change', 'blur']
	}]
};
