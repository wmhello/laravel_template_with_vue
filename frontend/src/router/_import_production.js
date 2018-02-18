 module.exports = file => () => import('./../views/' + file + '.vue')
//module.exports = file => require('@/views/' + file + '.vue').default
