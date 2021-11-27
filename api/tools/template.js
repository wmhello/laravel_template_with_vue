// 模板部分内容
var temp = {
   // 内容追加后下起一行
  route: `Route::apiResource('##routeName##', '##name##Controller')->prefix('admin')->namespace('Admin')->middleware(['auth:admin','role']);
  `
}
exports.temp = temp
