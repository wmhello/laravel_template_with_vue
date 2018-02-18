import fetch from '@/utils/fetch'

export function getInfo (searchObj = {}, page = 1, pageSize = 10) {

  return fetch({
    url: '/api/classTeacher',
    method: 'get',
    params: {
      pageSize,
      page,
      session_id: searchObj.session_id,
      teacher_id: searchObj.teacher_id,
      grade: searchObj.grade
    },
  })
}

export function getInfoById (id) {
  return fetch({
    url: '/api/classTeacher/' + id,
    method: 'get'
  })
}

export function updateInfo (id, data) {
  return fetch({
    url: '/api/classTeacher/' + id,
    method: 'patch',
    data: {
      teacher_id: data.teacher_id,
      leader_type: data.leader_type,
      grade: data.grade,
      class_id: data.class_id,
      remark: data.remark
    }
  })
}

export function addInfo (data) {
  return fetch({
    url: '/api/classTeacher',
    method: 'post',
    data,
  })
}

export function deleteInfoById (id) {
  return fetch({
    url: '/api/classTeacher/' + id,
    method: 'delete'
  })
}

export function uploadFile (data) {
  return fetch({
    url: '/api/classTeacher/upload',
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
    url: '/api/classTeacher/export',
    method: 'post',
    data: {
      page,
      pageSize,
      session_id: searchObj.session_id,
      teacher_id: searchObj.teacher_id,
      grade: searchObj.grade
    }
  })
}

export function exportAll (searchObj = {}) {
  return fetch({
    url: '/api/classTeacher/exportAll',
    method: 'post',
    data: {
      session_id: searchObj.session_id,
      teacher_id: searchObj.teacher_id,
      grade: searchObj.grade
    }
  })
}

export function deleteAll (params) {
  return fetch({
    url: '/api/classTeacher/deleteAll',
    method: 'post',
    data: {
      ids: params
    }
  })
}

export function Model (session_id = null, teacher_id = null, grade = null, class_id = null, remark = null) {
  this.session_id = session_id;
  this.teacher_id = teacher_id;
  this.grade = grade;
  this.class_id = class_id;
  this.remark = remark;
}

export function SearchModel (session_id = null, teacher_id = null, grade = null) {
  this.session_id = session_id;
  this.teacher_id = teacher_id;
  this.grade = grade
}



