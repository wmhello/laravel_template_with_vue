export default {
  deptTree: config => {
    return { 'msg': 'success', 'code': 0, 'data': [{ 'id': 1, 'pid': 0, 'children': [{ 'id': 3, 'pid': 1, 'children': [{ 'id': 4, 'pid': 3, 'children': [{ 'id': 5, 'pid': 4, 'children': [{ 'id': 6, 'pid': 5, 'children': [], 'name': '一级子1111' }], 'name': '一级子111' }], 'name': '一级子11' }], 'name': '一级子1' }], 'name': '一级' }, { 'id': 2, 'pid': 0, 'children': [{ 'id': 7, 'pid': 2, 'children': [{ 'id': 8, 'pid': 7, 'children': [{ 'id': 9, 'pid': 8, 'children': [], 'name': '并行一级子111' }], 'name': '并行一级子11' }], 'name': '并行一级子1' }], 'name': '并行一级' }] }
  }
}
