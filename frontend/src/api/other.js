import fetch from '@/utils/fetch'

export function getSession () {
  return fetch({
    url: '/api/getSession',
    method: 'get',
  })
}

export function getTeach () {
  return fetch({
    url: '/api/getTeach',
    method: 'get',
  })
}

export function getTeacher () {
  return fetch({
    url: '/api/getTeacher',
    method: 'get',
  })
}

export function getDefaultSession () {
  return fetch({
    url: '/api/getDefaultSession',
    method: 'get',
  })
}

export function getClassByGrade (grade = 0, teach_id) {
  return fetch({
    url: '/api/getClassNumByGrade',
    method: 'get',
    params: {
      grade,
      teach_id
    }
  })
}


export function getTeacherByTeachingId (teaching_id = 0) {
  // 获取指定学科的教师信息
  return fetch({
    url: '/api/getTeacherByTeachingId',
    method: 'get',
    params: {
      teaching_id
    }
  })
}

export function getSelectClassByGrade(grade = 1, id = 1) {
    // 获取指定的教师在指定学期的任课班级
   return fetch({
     url: '/api/getSelectClass/' + id + '/grade/' + grade,
     method: 'get',
   })
}

export function getClassByTeachingId(id) {
// 根据教学信息获取当前学期，学科，当前年级可以使用的班级
  return fetch({
    url: '/api/getClassByTeachingId/' + id,
    method: 'get'
  })
}

