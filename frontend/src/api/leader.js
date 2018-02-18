import fetch from '@/utils/fetch'

export function getInfo (searchObj = {}, page = 1 , pageSize = 10) {

  return fetch({
    url: '/api/leader',
    method: 'get',
    params: {
      pageSize,
      page,
      session_id: searchObj.session_id,
      teacher_id: searchObj.teacher_id,
      leader_type: searchObj.leader_type,
    },
  })
}

export function getInfoById (id) {
  return fetch({
    url: '/api/leader/' + id,
    method: 'get'
  })
}

export function updateInfo (id, data) {
  return fetch({
    url: '/api/leader/' + id,
    method: 'patch',
    data: {
      teacher_id: data.teacher_id,
      leader_type: data.leader_type,
      job: data.job,
      remark: data.remark
    }
  })
}

export function addInfo (data) {
  return fetch({
    url: '/api/leader',
    method: 'post',
    data,
  })
}

export function deleteInfoById (id) {
  return fetch({
    url: '/api/leader/' + id,
    method: 'delete'
  })
}

export function uploadFile(data) {
  return fetch({
    url: '/api/leader/upload',
    method: 'post',
    data,
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
}

// 导出当前内容
export function exportCurrentPage(pageSize = 10, page = 1, searchObj={}) {
  return fetch({
    url: '/api/leader/export',
    method: 'post',
    data: {
      page,
      pageSize,
      session_id: searchObj.session_id,
      teacher_id: searchObj.teacher_id,
      leader_type: searchObj.leader_type,
    }
  })
}

export function exportAll (searchObj = {}) {
  return fetch({
    url: '/api/leader/exportAll',
    method: 'post',
    data: {
      session_id: searchObj.session_id,
      teacher_id: searchObj.teacher_id,
      leader_type: searchObj.leader_type,
    }
  })
}

export function deleteAll(params) {
  return fetch({
    url: '/api/leader/deleteAll',
    method: 'post',
    data: {
      ids:params
    }
  })
}

export function Model (session_id = null, teacher_id = null, leader_type = null, job = null, remark = null) {
  this.session_id = session_id;
  this.teacher_id = teacher_id;
  this.leader_type = leader_type;
  this.job = job;
  this.remark = remark;
}

export function SearchModel(session_id = null, teacher_id = null, leader_type = null) {
  this.session_id = session_id;
  this.teacher_id = teacher_id;
  this.leader_type = leader_type;
}


