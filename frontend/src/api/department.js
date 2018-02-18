import fetch from '@/utils/fetch'

export function getInfo (searchObj = {}, page = 1, pageSize = 10) {

  return fetch({
    url: '/api/department',
    method: 'get',
    params: {
      pageSize,
      page,
      session_id: searchObj.session_id,
      teacher_id: searchObj.teacher_id,
      grade: searchObj.grade,
      leader: searchObj.leader
    },
  })
}

export function getInfoById (id) {
  return fetch({
    url: '/api/department/' + id,
    method: 'get'
  })
}

export function updateInfo (id, data) {
  return fetch({
    url: '/api/department/' + id,
    method: 'patch',
    data: {
      teacher_id: data.teacher_id,
      leader: data.leader,
      grade: data.grade,
      teach_id: data.teach_id,
      remark: data.remark
    }
  })
}

export function addInfo (data) {
  console.log(data)
  return fetch({
    url: '/api/department',
    method: 'post',
    data,
  })
}

export function deleteInfoById (id) {
  return fetch({
    url: '/api/department/' + id,
    method: 'delete'
  })
}

export function uploadFile (data) {
  return fetch({
    url: '/api/department/upload',
    method: 'post',
    data,
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
}

// 导出当前内容
export function exportCurrentPage (pageSize = 10, page = 1, searchObj = {}) {
  return fetch({
    url: '/api/department/export',
    method: 'post',
    data: {
      page,
      pageSize,
      session_id: searchObj.session_id,
      teacher_id: searchObj.teacher_id,
      grade: searchObj.grade,
      leader: searchObj.leader
    }
  })
}

export function exportAll (searchObj = {}) {
  return fetch({
    url: '/api/department/exportAll',
    method: 'post',
    data: {
      session_id: searchObj.session_id,
      teacher_id: searchObj.teacher_id,
      grade: searchObj.grade,
      leader: searchObj.leader
    }
  })
}

export function deleteAll (params) {
  return fetch({
    url: '/api/department/deleteAll',
    method: 'post',
    data: {
      ids: params
    }
  })
}

export function Model (session_id = null, teacher_id = null, grade = null, teach_id = null, leader = null, remark = null) {
  this.session_id = session_id;
  this.teacher_id = teacher_id;
  this.grade = grade;
  this.leader = leader;
  this.teach_id = teach_id;
  this.remark = remark;
}

export function SearchModel (session_id = null, teacher_id = null, grade = null, leader = null) {
  this.session_id = session_id;
  this.teacher_id = teacher_id;
  this.grade = grade
  this.leader = leader
}



