import fetch from '@/utils/fetch'

export function getInfo (searchObj = {}, page = 1, pageSize = 10) {

  return fetch({
    url: '/api/teaching',
    method: 'get',
    params: {
      pageSize,
      page,
      session_id: searchObj.session_id,
      teacher_id: searchObj.teacher_id,
      teach_id: searchObj.teach_id,
      grade: searchObj.grade,
      class_id: searchObj.class_id
    },
  })
}

export function getInfoById (id) {
  return fetch({
    url: '/api/teaching/' + id,
    method: 'get'
  })
}

export function updateInfo (id, data) {
  return fetch({
    url: '/api/teaching/' + id,
    method: 'patch',
    data
  })
}

export function addInfo (data) {
  console.log(data)
  return fetch({
    url: '/api/teaching',
    method: 'post',
    data,
  })
}

export function deleteInfoById (id) {
  return fetch({
    url: '/api/teaching/' + id,
    method: 'delete'
  })
}

export function uploadFile (data) {
  return fetch({
    url: '/api/teaching/upload',
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
    url: '/api/teaching/export',
    method: 'post',
    data: {
      page,
      pageSize,
      session_id: searchObj.session_id,
      teacher_id: searchObj.teacher_id,
      teach_id: searchObj.teach_id,
      grade: searchObj.grade,
      class_id: searchObj.class_id
    }
  })
}

export function exportAll (searchObj = {}) {
  return fetch({
    url: '/api/teaching/exportAll',
    method: 'post',
    data: {
      session_id: searchObj.session_id,
      teacher_id: searchObj.teacher_id,
      teach_id: searchObj.teach_id,
      grade: searchObj.grade,
      class_id: searchObj.class_id
    }
  })
}

export function deleteAll (params) {
  return fetch({
    url: '/api/teaching/deleteAll',
    method: 'post',
    data: {
      ids: params
    }
  })
}

export function Model (session_id = null, teacher_id = null, grade = null, class_id =null, teach_id = null, hour = null, classAll=[], remark = null) {
  this.session_id = session_id;
  this.teacher_id = teacher_id;
  this.grade = grade;
  this.class_id = class_id;
  this.teach_id = teach_id;
  this.hour = hour;
  this.remark = remark;
  this.classAll = classAll
}

export function SearchModel (session_id = null, teacher_id = null, grade = null, class_id = null, teach_id = null) {
  this.session_id = session_id;
  this.teacher_id = teacher_id;
  this.grade = grade
  this.class_id = class_id
  this.teach_id = teach_id
}



