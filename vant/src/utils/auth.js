import Vue from 'vue'

let Token = 'access_token'

export function getToken(){
   let token = Vue.ls.get(Token)
   return token
}

export function setToken(value) {
   let result = eval(process.env.VUE_APP_TIMEOUT)   
    Vue.ls.set(Token, value, result)
}

export function removeToken() {
   Vue.ls.remove(Token)
}
