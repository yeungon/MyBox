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
// Route::get('/box/resetpassword', 'Front\Home\ForgotPasswordController@index')->name('home.resetpassword');

/*Ghi đè login và register*/
Route::get('/login', 'Front\Home\HomeRegisterController@index')->name('login');
Route::get('/register', 'Front\Home\HomeRegisterController@index')->name('home.registerindex');


/*
Password Reset Routes...https://tutorials.kode-blog.com/laravel-authentication-with-password-reset
1) ghi đè  sendPasswordResetNotification trong Model User
https://laravel.com/docs/5.5/passwords
2) Tạo thư với nội dung mới, dùng Notifications
3) Truyền username và email trong email để validate

*/
// Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
// Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}/{email}', 'Front\Home\HomeResetPasswordController@showResetForm')->name('password.reset.token');
Route::post('password/reset', 'Front\Home\HomeResetPasswordController@reset')->name('password.reset.submit');

/*phòng tránh truy cập trực tiếp vào link nếu không có token*/
Route::get('password/reset', 'Front\Home\HomeRegisterController@index')->name('register');




/*User Frontend*/

Route::get('/{id}', 'Front\User\UserController@index')->where('id', '[A-Za-z0-9_]{1,}+')->name('home.user');
Route::post('/{id}/', 'Front\User\UserController@read')->where('id', '[A-Za-z0-9]+')->name('home.userread');

Route::get('/{id}/{token}.html', 'Front\User\UserController@readmessage')->where(['id' => '[a-z0-9]+', 'token' =>'[A-Za-z0-9_]{40}+'])->name('home.userreadmessage');

Route::get('/{id}/send', 'Front\User\UserController@send')->where('id', '[A-Za-z0-9_]{1,}+')->name('home.usersend');

Route::post('/{id}/send', 'Front\User\UserController@sendstore')->where('id', '[a-z0-9_]{4,}+')->name('home.usersendstore');


Route::get('/{id}/reply', 'Front\User\UserController@replyshow')->where('id', '[A-Za-z0-9_]{1,}+')->name('home.userreply');
Route::post('/{id}/reply', 'Front\User\UserController@replyverify')->where('id', '[A-Za-z0-9]+')->name('home.userreplyverify');
Route::get('/{id}/reply/{token}.html', 'Front\User\UserController@replyread')->where(['id' => '[a-z0-9]+', 'token' =>'[A-Za-z0-9_]{40}+'])->name('home.userreplyread');

/*End of the user u/{id}*/


/*User Backend*/
/*->middleware('auth'); bảo vệ route*/
Route::get('/box/me', 'Admin\HomeController@index')->name('home')->middleware('auth');

//Route::get('/admin/user', 'Admin\MessageController@index');

//Route::get('/admin/key/create', 'KeyController@create')->name('key.create');

Route::get('/box/me/createkey', 'Admin\KeyController@create')->name('key.create');
Route::post('/me/createkey', 'Admin\KeyController@update')->name('key.store');



Route::get('/box/me/editprofile', 'Admin\KeyController@showtoeditkey')->name('key.edit');
Route::post('/me/editprofile', 'Admin\KeyController@storetoeditkey')->name('key.editstore');


Route::get('/box/me/deleteaccount', 'Admin\KeyController@deleteaccountform')->name('key.delete');

Route::delete('/box/me/deleteaccount', 'Admin\KeyController@deleteaccountsubmit')->name('key.deleteaccount');




Route::get('/box/me/verify', 'Admin\KeyController@verify')->name('key.verify');
Route::post('/box/me/verify/', 'Admin\KeyController@authenticate')->name('key.authenticate');

/*where("name", "REGEX"
* '[a-z0-9]{49}+' chấp nhận a-z và 0-9 và đúng 49 kí tự

*/
/*->middleware('auth'); bảo vệ*/
Route::get('/box/me/read/{id}', 'Admin\KeyController@readMessage')->where('id', '[a-z0-9]{49}+')->name('key.read')->middleware('auth');


Route::get('/box/me/message', 'Admin\MessageController@create')->name('message.index');

Route::get('/box/me/message/create', 'Admin\MessageController@create')->name('message.create');

Route::post('/box/me/message/', 'Admin\MessageController@store')->name('message.store');

Route::delete('/box/me/message/{id}', 'Admin\MessageController@delete')->name('message.delete');



Route::POST('/box/me/like', 'Admin\LikeController@index')->name('boxme.like');


// Route::delete('/me/messageread/{id}', 'Admin\MessageController@deletewhenread')->name('message.deletewhenread');


/*Ask and Reply*/

Route::post('/box/me/ask/verify/', 'Admin\AskController@authenticate')->name('ask.authenticate');

Route::get('/box/me/ask/', 'Admin\AskController@readAsk')->where('id', '[a-z0-9]{49}+')->name('ask.read')->middleware('auth');

Route::post('/box/me/ask/', 'Admin\AskController@Reply')->where('id', '[a-z0-9]{49}+')->name('ask.reply')->middleware('auth');

Route::post('/box/me/ask/editreply/', 'Admin\AskController@updateReply')->where('id', '[a-z0-9]+')->name('ask.replyedit')->middleware('auth');


Route::post('/box/me/ask/publish/{id}', 'Admin\AskController@publish')->where('id', '[a-z0-9]+')->name('ask.publish')->middleware('auth');


Route::delete('/box/me/ask/deletereply/{id}', 'Admin\AskController@deletereply')->name('ask.deletereply')->middleware('auth');

Route::delete('/box/me/ask/deleteask/{id}', 'Admin\AskController@deleteask')->name('ask.deleteask')->middleware('auth');





/*Route::get('/users', 'UserController@index')->name('user.index');
Route::get('/users/create', 'UserController@create')->name('user.create');
Route::post('/users', 'UserController@store')->name('user.store');*/


// Route::get('abc/mahoa', function(){

	
	   

//           /* On Node B: */
//     $private  = sodium_crypto_box_keypair();
//     $public  = sodium_crypto_box_publickey($private);
//     // Then share $bobPublicKey with Node A

       
//         $key =  encrypt($public);

//         echo "<br>";


//         $private = encrypt($private);

//       //   // echo "<br>";

//       //   // echo base64_encode($public);

       
//       //   // // $key2 = encrypt($public_Key);


//       //   // //echo decrypt($abc);
       
//       //   // die();

       
//       // //       die();

//       // //       $decrypted2 = sodium_crypto_box_seal_open(decrypt($encrypted), decrypt($privatekeysession));
            
//       //   $key = 'eyJpdiI6InczMDIxc1U1MjFuTWJLYkRpMUttc1E9PSIsInZhbHVlIjoicjNXR3RDSXltMzdjalVXTEgyNDNIMlJ4bzVFQ0RwN2ZGQUhpUGR0YWI4TWI0SmtTZWJoUGRvaytoZHRST1lNWiIsIm1hYyI6ImQyZTBiMjQyNDA5OTlhN2ZlOGM3MGE1M2M2ZjdiMTAzODY2ZGYzM2U3MTA5Y2E3N2NiNWZkNDY3YmI5MDI1ZjcifQ==';

//       //   $private = 'eyJpdiI6Ik5CSk90eUlNcnFSdUFGV2VySnhaWWc9PSIsInZhbHVlIjoielNzYzdXdkMzMW9NSjlVYzNCMmZHeDcwa1wvOEdlQldnenBUdUt6Ym55dGdpb3VreVFzQWdad2gyS2hIZmNPMVJBU3RYNSthM25LckNQVDE0V01GUGlZV2RKVnVrNXd1dk9rVUJxbk1PeW9vPSIsIm1hYyI6IjkzNjgzMWZhMTgwNjU1MTM2OGE2Y2IwYzRmNjk3ODZmMTcxYTJkZjk5Nzk5MDhmY2IwZWY1NWFlYTE2MTdkMDQifQ==';
         
//       //   // $key = 'VlFN1/Y/X6Q/habhs9kNo7sjMiH30Un3abP7X2C1jig=';

//       //   // $private = '/2fqCiVEx0Q2K2HrYqoCy85tegdIXcyTs0IA9T//szxWUU3X9j9fpD+FpuGz2Q2juyMyIffRSfdps/tfYLWOKA==';
            
//         $message = "Thư mật nè Hi there,fda fdafds fafsfd  thử xem độ dài của thư, thử xem độ dài của thư, thử xem";
            
//         $ciphertext = sodium_crypto_box_seal($message, decrypt($key));
            
//             //echo  base64_decode($ciphertext);

//         /* On Node B, receiving an encrypted message from Node A */

//         $data = encrypt($ciphertext);

        

//         echo $decrypted = sodium_crypto_box_seal_open(decrypt($data), decrypt($private));

        

// });




