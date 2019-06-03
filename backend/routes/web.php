<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('testRedis','RedisController@testRedis')->name('testRedis');

Route::get('/ship', function(\Illuminate\Http\Request $request){
  $id = $request -> input('id');
  event(new \App\Events\OrderShipped($id));
  return Response::make('Order Shipped');
});

