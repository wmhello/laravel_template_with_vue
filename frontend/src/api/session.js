import fetch from '@/utils/fetch'

export function getInfo() {
  return fetch({
    url: '/api/session',
    method: 'get'
  })
}

export function getInfoById(id) {
  return fetch({
    url: '/api/session/' + id,
    method: 'get'
  })
}

export function updateInfo(id, data) {

  data.year = parseInt(data.year.getFullYear())
  return fetch({
    url: '/api/session/' + id,
    method: 'patch',
    data
  })
}

export function addInfo(data) {
  data.year = parseInt(data.year.getFullYear())
  return fetch({
    url: '/api/session',
    method: 'post',
    data
  })
}

export function deleteInfoById(id) {
  return fetch({
    url: '/api/session/' + id,
    method: 'delete'
  })
}

export function Model (year = new Date(), team = 1, one = 1, two = 1, three = 1) {
  this.year = year
  this.team = team
  this.one = one
  this.two = two
  this.three = three
}
