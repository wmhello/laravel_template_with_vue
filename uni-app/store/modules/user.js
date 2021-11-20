export default {
	state: {
		roles: [],
		token: '',
		avatar: '',
		nickname: '',
		phone:'',

	},
	actions: {
	},
	mutations: {
		SET_ROLES(state, value){
			state.roles = value
		},
		SET_TOKEN(state, value){
			state.token = value
		},
		SET_AVATAR(state, value){
			state.avatar = value
		},
		SET_PHONE(state, value){
			state.phone = value
		},
		SET_NICKNAME(state, value){
			state.nickname = value
		},
		
	}
}