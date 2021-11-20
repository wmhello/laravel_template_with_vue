export default {
	clientHeight: state => state.app.clientHeight,
	clientWidth:  state => state.app.clientWidth,
	isNew: state => state.isNew,
	isEdit: state => state.isEdit,
	nickname: state => state.user.nickname,
	token: state => state.user.token,
	avatar: state => state.user.avatar,
	roles: state => state.user.roles,
	phone: state => state.user.phone,
	
}