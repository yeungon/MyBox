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

/*route mặc định của authentication tạo login, register*/
Auth::routes();


/*Home Frontend*/
Route::get('/', 'Front\Home\HomeIndexController@index')->name('home.index');
Route::get('/box/create', 'Front\Home\HomeRegisterController@index')->name('home.registerindex');
Route::post('/box/create', 'Front\Home\HomeRegisterController@xacthuc')->name('home.registerxacthuc');
Route::post('/box/logging', 'Front\Home\HomeLoginController@login')->name('home.login');

/*Ghi đè login và register*/
Route::get('/login', 'Front\Home\HomeRegisterController@index')->name('login');
Route::get('/register', 'Front\Home\HomeRegisterController@index')->name('home.registerindex');


/*phòng tránh truy cập trực tiếp vào link nếu không có token*/
Route::get('password/reset', 'Front\Home\HomeRegisterController@index')->name('register');

/*User Frontend*/

Route::get('/{id}', 'Front\User\UserController@index')->where('id', '[A-Za-z0-9_]{1,}+')->name('home.user');
Route::post('/{id}/', 'Front\User\UserController@read')->where('id', '[A-Za-z0-9]+')->name('home.userread');

Route::get('/{id}/{token}.html', 'Front\User\UserController@readmessage')->where(['id' => '[a-z0-9]+', 'token' =>'[A-Za-z0-9_]{40}+'])->name('home.userreadmessage');

Route::get('/{id}/message', 'Front\User\UserController@send')->where('id', '[A-Za-z0-9_]{1,}+')->name('home.usersend');

Route::post('/{id}/message', 'Front\User\UserController@sendstore')->where('id', '[a-z0-9_]{4,}+')->name('home.usersendstore');


Route::get('/{id}/conversations', 'Front\User\UserController@replyshow')->where('id', '[A-Za-z0-9_]{1,}+')->name('home.userreply');
Route::post('/{id}/conversations', 'Front\User\UserController@replyverify')->where('id', '[A-Za-z0-9]+')->name('home.userreplyverify');
Route::get('/{id}/conversations/{token}.html', 'Front\User\UserController@replyread')->where(['id' => '[a-z0-9]+', 'token' =>'[A-Za-z0-9_]{40}+'])->name('home.userreplyread');

Route::get('/box/me', 'Admin\HomeController@index')->name('home')->middleware('auth');

Route::get('/box/me/createkey', 'Admin\KeyController@create')->name('key.create');
Route::post('/me/createkey', 'Admin\KeyController@update')->name('key.store');



Route::get('/box/me/editprofile', 'Admin\KeyController@showtoeditkey')->name('key.edit');
Route::post('/me/editprofile', 'Admin\KeyController@storetoeditkey')->name('key.editstore');


Route::get('/box/me/deleteaccount', 'Admin\KeyController@deleteaccountform')->name('key.delete');

Route::delete('/box/me/deleteaccount', 'Admin\KeyController@deleteaccountsubmit')->name('key.deleteaccount');
Route::get('/box/me/verify', 'Admin\KeyController@verify')->name('key.verify');
Route::post('/box/me/verify/', 'Admin\KeyController@authenticate')->name('key.authenticate');

/*->middleware('auth'); bảo vệ*/
Route::get('/box/me/read/{id}', 'Admin\KeyController@readMessage')->where('id', '[a-z0-9]{49}+')->name('key.read')->middleware('auth');

Route::get('/box/me/message', 'Admin\MessageController@create')->name('message.index');
Route::get('/box/me/message/create', 'Admin\MessageController@create')->name('message.create');
Route::post('/box/me/message/', 'Admin\MessageController@store')->name('message.store');
Route::delete('/box/me/message/{id}', 'Admin\MessageController@delete')->name('message.delete');
Route::POST('/box/me/like', 'Admin\LikeController@index')->name('boxme.like');

/*Ask and Reply*/

Route::post('/box/me/ask/verify/', 'Admin\AskController@authenticate')->name('ask.authenticate');
Route::get('/box/me/ask/', 'Admin\AskController@readAsk')->where('id', '[a-z0-9]{49}+')->name('ask.read')->middleware('auth');
Route::post('/box/me/ask/', 'Admin\AskController@Reply')->where('id', '[a-z0-9]{49}+')->name('ask.reply')->middleware('auth');
Route::post('/box/me/ask/editreply/', 'Admin\AskController@updateReply')->where('id', '[a-z0-9]+')->name('ask.replyedit')->middleware('auth');
Route::post('/box/me/ask/publish/{id}', 'Admin\AskController@publish')->where('id', '[a-z0-9]+')->name('ask.publish')->middleware('auth');
Route::delete('/box/me/ask/deletereply/{id}', 'Admin\AskController@deletereply')->name('ask.deletereply')->middleware('auth');
Route::delete('/box/me/ask/deleteask/{id}', 'Admin\AskController@deleteask')->name('ask.deleteask')->middleware('auth');

			