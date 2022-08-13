// 模板部分内容
var temp = {
   // 内容追加后下起一行
  route: `Route::middleware(['auth:admin','role'])->prefix('admin')->namespace('Admin')->group(function(){
    Route::apiResource('##routeName##', '##name##Controller');
  });`
}
exports.temp = temp
