<?php

namespace App\Http\Controllers\Front\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Foundation\Auth\ThrottlesLogins;

class HomeLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    /*The AuthenticatesUsers trait uses ThrottlesLogins in its definition so you already have ThrottlesLogins by having AuthenticatesUsers.*/
    use AuthenticatesUsers;

    
    
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/p/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /*
    Tùy chọn số lần đăng nhập sai 5 lần và 10 phút
    https://laracasts.com/discuss/channels/laravel/customize-laravel-login-throttling-54*/
    /**
     * Determine if the user has too many failed login attempts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), 5, 10
        );
    }


    public function login(Request $request)
    {

         $valid = Validator::make($request->all(), [
            
            'username' => 'required|string|min:4|max:100|',
            'password' => 'required|string|min:6|',
            
            // 'privatekey' => 'min:88|max:88',
         
        ],[
            /*Customeize the error*/
            'password.required' => 'Your need to authorize your confidential',
            'password.min' => 'It seems that your password have at least 6 characters!',
            'username.max' => 'Password should have less than 100 characters!',
        ]);
      
        
        /*xử lý dữ liệu đầu vào, nếu lỗi thì trả lại trang create*/
        if($valid->fails()){
            
            return redirect()->back()->withErrors($valid)->withInput();

        }else{
                
                /*Xác thực bằng Auth:attempt*/
                $credentials = $request->only('username', 'password');

                
                if (Auth::attempt($credentials)) {

                    $username = strtolower(trim($request['username']));

                    $direct = '/'.$username;
                    
                    return redirect()->intended($direct);

                }else{

                    return redirect()->route('home.registerindex')->with('message', "Either your account or password is incorrect!");
                }
            
        }
     
    }

}
