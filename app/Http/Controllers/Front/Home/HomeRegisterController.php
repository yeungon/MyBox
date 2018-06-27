<?php

namespace App\Http\Controllers\Front\Home;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class HomeRegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    /*gốc là array $array*/

    public function index()
    {
        return view('frontend\home\homeregister');
    }



    public function xacthuc(Request $request)
    {

         $valid = Validator::make($request->all(), [
            
            'username' => 'required|string|min:5|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'matkhau' => 'required|string|min:6|confirmed',
            
            // 'privatekey' => 'min:88|max:88',
         
        ],[
            /*Customeize the error*/
            'matkhau.required' => 'You need to authorize your confidential',
            'matkhau.min' => 'Password should have at least 6 characters!',
            'matkhau.max' => 'Password should have less than 250 characters!',
        ]);
      
        
        /*xử lý dữ liệu đầu vào, nếu lỗi thì trả lại trang create*/
        if($valid->fails()){
            
            return redirect()->back()->withErrors($valid)->withInput();

            }else{
                $username = $request['username'];
                $email = $request['email'];
                $password = $request['matkhau'];

                $Keypair = sodium_crypto_box_keypair();
                $PublicKey = sodium_crypto_box_publickey($Keypair);

                /*Convert to encrypted string, that can be stored in database*/
                $public = base64_encode($PublicKey);
                $private = base64_encode($Keypair);

                 User::create([
                'username' => $username,
                'email' => $email,
                'password' => bcrypt($password),
                'publickey' => $public,
                
                ]);

                return redirect()->route('home.index')->with("message", "The account <strong>$username</strong> is created! Your private key is: <br>

                [__COPY_THE_BELOW__]</br><br>

                <b>$private </b><br><br>

                [__COPY_THE_ABOVE_PRIVATE_KEY__]<br> You have to keep this private key IN SECRET somewhere else." );
       
        }

    }



    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}