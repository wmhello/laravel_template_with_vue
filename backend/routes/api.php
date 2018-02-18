<?php

use Illuminate\Http\Request;
use \Illuminate\Http\Response;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', 'UserController@getUserInfo')->name('admin.userInfo');
Route::post('/login', 'Auth\LoginController@login')->name('login.login');
Route::post('/token/refresh', 'Auth\LoginController@refresh')->name('login.refresh');
Route::post('/logout', 'Auth\LoginController@logout')->name('login.logout');
Route::post('/test', 'PermissionController@index')->name('soft.test');
Route::middleware('auth:api')->group(function() {
    // 用户管理
    Route::Resource('admin', 'UserController');
    Route::post('/admin/modify', 'UserController@modify' )->name('admin.modify');
    Route::post('/admin/{id}/reset', 'UserController@reset')->name('admin.reset');
    Route::post('/admin/uploadAvatar', 'UserController@uploadAvatar')->name('admin.uploadAvatar');
    Route::post('/admin/upload', 'UserController@upload')->name('admin.upload');
    Route::post('/admin/export', 'UserController@export')->name('admin.export');
    Route::post('/admin/exportAll', 'UserController@exportAll')->name('admin.exportAll');
    Route::post('/admin/deleteAll', 'UserController@deleteAll')->name('admin.deleteAll');

    // 角色管理
    Route::Resource('role', 'RoleController');
    Route::get('getRoles', 'RoleController@getRoles')->name('role.get');

    // 其他支持API
    Route::get('/getSession', 'SessionController@getSession')->name('session.get'); // 获取所有学期
    Route::get('/getDefaultSession', 'SessionController@getDefaultSession')->name('session.getDefault'); //获得当前学期
    Route::get('/getTeacher', 'TeacherController@getTeacher')->name('teacher.get'); // 获取所有教师
    Route::get('/getTeach', 'TeachController@getTeach')->name('teach.get');  // 获取任教学科
    Route::get('/getClassNumByGrade', 'SessionController@getClassNumByGrade')->name('session.getClassNum'); // 根据年级获取最大班级数
    Route::get('/getTeacherByTeachingId', 'TeacherController@getTeacherByTeachingId')->name('teacher.getTeacher'); // 根据学科获取任课教师
    Route::get('/getSelectClass/{id}/grade/{grade}', 'TeachingController@getSelectClassByGrade')->name('teaching.getTeacher'); // 根据学科获取任课教师
    Route::get('/getClassByTeachingId/{id}', 'TeachingController@getClassByTeachingId')->name('teaching.getClass'); // 根据ID获取当前学期，当前科目和当前年级可以使用的班级


    // 学期管理
    Route::Resource('session', 'SessionController');
    Route::post('/session/upload', 'SessionController@upload')->name('session.upload');

    // 行政领导管理
    Route::Resource('leader', 'LeaderController');
    Route::post('/leader/upload', 'LeaderController@upload')->name('leader.upload');
    Route::post('/leader/export', 'LeaderController@export')->name('leader.export');
    Route::post('/leader/exportAll', 'LeaderController@exportAll')->name('leader.exportAll');
    Route::post('/leader/deleteAll', 'LeaderController@deleteAll')->name('leader.deleteAll');

    // 班主任管理
    Route::Resource('classTeacher', 'ClassTeacherController');
    Route::post('/classTeacher/upload', 'ClassTeacherController@upload')->name('classTeacher.upload');
    Route::post('/classTeacher/export', 'ClassTeacherController@export')->name('classTeacher.export');;
    Route::post('/classTeacher/exportAll', 'ClassTeacherController@exportAll')->name('classTeacher.exportAll');;
    Route::post('/classTeacher/deleteAll', 'ClassTeacherController@deleteAll')->name('classTeacher.deleteAll');;

    // 教研组长管理
    Route::Resource('department', 'DepartmentController');
    Route::post('/department/upload', 'DepartmentController@upload')->name('department.upload');
    Route::post('/department/export', 'DepartmentController@export')->name('department.export');
    Route::post('/department/exportAll', 'DepartmentController@exportAll')->name('department.exportAll');
    Route::post('/department/deleteAll', 'DepartmentController@deleteAll')->name('department.deleteAll');

    // 教师教学管理
    Route::Resource('teaching', 'TeachingController');
    Route::post('/teaching/upload', 'TeachingController@upload')->name('teaching.upload');
    Route::post('/teaching/export', 'TeachingController@export')->name('teaching.export');
    Route::post('/teaching/exportAll', 'TeachingController@exportAll')->name('teaching.exportAll');
    Route::post('/teaching/deleteAll', 'TeachingController@deleteAll')->name('teaching.deleteAll');

    // 程序功能管理
    Route::Resource('permissions', 'PermissionController');
    Route::post('/permissions/addGroup', 'PermissionController@addGroup')->name('permissions.addGroup');
    Route::post('/permissions/getGroup', 'PermissionController@getGroup')->name('permissions.getGroup');
    Route::post('/permissions/deleteAll', 'PermissionController@deleteAll')->name('permissions.deleteAll') ;
    Route::post('/permissions/getPermissionByTree', 'PermissionController@getPermissionByTree')->name('Permission.getPermissionByTree');
});
