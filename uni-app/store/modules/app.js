import { getShareInfo } from '@/api/app.js'
export default {
	state: {
		clientWidth: 0,
		clientHeight: 0,
		shareDesc: '',
		shareImg: '',
		shareTitle: ''
	},
	actions: {
		getShare({commit, state}) {
			return new Promise(async (resolve, reject) => {
				if (state.shareDesc && state.shareTitle) {
					resolve()
				} else {
					let { data }	= await getShareInfo()
					let { shareTitle, shareDesc, shareImg } = data
					commit('SET_TITLE', shareTitle)
					commit('SET_DESC', shareDesc)
					commit('SET_IMG', shareImg)
					resolve()
				}
			})
			
		}
	},
	mutations: {
		SET_WIDTH(state, value){
		    state.clientWidth = value	
		},
		SET_HEIGHT(state, value){
			state.clientHeight = value
		},
		SET_TITLE(state, value){
			state.shareTitle = value
		},
		SET_DESC(state, value){
			state.shareDesc = value
		},
		SET_IMG(state, value){
			state.shareImg = value
		},
	}
}