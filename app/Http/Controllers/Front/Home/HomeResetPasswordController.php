<?php

namespace App\Http\Controllers\Front\Home;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; /*thư viện thời gian
https://scotch.io/tutorials/easier-datetime-in-laravel-and-php-with-carbon*/
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Mail;
use Illuminate\Mail\Mailable;


class HomeResetPasswordController extends Controller
{	
	/*Chỉ khi không đăng nhập mới được reset password*/
	 public function __construct()
    {
        $this->middleware('guest');
    }


    public function showResetForm(Request $request, $token = null, $email = null)
    {	
	    if($token == null || $email == null){

	    	return redirect()->route("home.index");
	    }


	    if ($token != null){

	    	$reset = DB::table('password_resets')->where('email', $email)->first();

	    	/*Lấy username*/

	    	$username = DB::table('users')->where('email', $email)->first();

		    /*Kiểm tra xem token hết hạn chưa*/

		    /*Thời gian 1 tiếng trước, dùng thư viện Carbon có sẵn trên Laravel*/
		    $onehour = Carbon::now()->subHours(1);
			

		    if($reset->created_at < $onehour){
		    	echo "Yoop! It appears that your token is expired already!";
		    	die();
		    }

		    /*Lấy token và hashed để đối chiếu với database, gợi ý
			https://github.com/laravel/framework/issues/18570
	    	*/

		    /*Đối chiếu token trong database*/
		    if (!Hash::check($token, $reset->token)) {
    			
    			echo "It appears that your token is incorrect";

			}else{

				/*Truyền username và email sang view => function reset để xử lý*/
				$data['username'] = $username->username;

				$data['email'] = $username->email;

				return view("frontend.home.resetpassword", $data);
			}

		}else{

			return redirect()->route("home.index");

		}
    	    	
    }


    public function reset(Request $request)
    {

    	$valid = Validator::make($request->all(), [
            
            'password' => 'required|string|min:5|max:50|confirmed',
            
                                 
        ],[
            /*Customeize the error*/
            
            'password.min' => 'The message should have more than 5 characters!',
            'password.max' => 'The message should have less than 50 characters!',
        ]);


        if($valid->fails()){
            
            return redirect()->back()->withErrors($valid)->withInput();

         }else{

         	$username = $request->usernametoupdate;
         	$password = $request->password;
         	$email = $request->email;

         	/*https://scotch.io/tutorials/easier-datetime-in-laravel-and-php-with-carbon
			https://carbon.nesbot.com/docs/
         	*/

    		/*Thiết lập timezone cho PHP, cần thiết lập trên phpini*/     	
	       	date_default_timezone_set('Europe/London');
         	
         	$currenttime = Carbon::now('Europe/London');

         	$formattime =  $currenttime->toDayDateTimeString();

         	/*test xem có thể gửi email
			Gửi string email, dùng closure để truyền vào email là biến use($email)
			https://laracasts.com/discuss/channels/laravel/send-raw-text-mail-without-using-a-view-in-laravel-53
			*/
			$content = "Hi $username! Your password has been changed at $formattime (London timezone)! If you have not done that, please do not hestitate to contact us by replying this email! Cheer!";

			Mail::raw($content, function ($message) use($email, $username) {
			   $message ->	to($email);
			   $message ->  subject('Urgent! Your password has been changed!');
			   $message ->  from('myboxdotnz@gmail.com', 'Mybox.nz - Secured Box');
			});

			
			/*cập nhật password mới và xóa token trong reset*/         	
			/*Update new password, dùng Eloquent*/
			User::where('username', $username)->update(['password' => bcrypt($password)]);
			/*Cập nhật bảng password_resets, xóa token, vì hem có Model thui em chơi DB :-), as we dont have a model for the table password_resets, then we have to use Query Builder*/
			DB::table('password_resets')->where('email', $email)->update(['token' => "", 'created_at' => null]);

			
			return redirect()->route("home.index")->with('message', "Your password has been successfully changed! ");


         }

    }

   

}
