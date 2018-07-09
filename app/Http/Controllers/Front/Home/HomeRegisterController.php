<?php

namespace App\Http\Controllers\Front\Home;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
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
        return view('frontend.home.homeregister');
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
                /*Bỏ dấu trắng và chuyển thành lowercase*/
                $username = strtolower(trim($request['username']));
                $email = $request['email'];
                $password = trim($request['matkhau']);

                $Keypair = sodium_crypto_box_keypair();
                $PublicKey = sodium_crypto_box_publickey($Keypair);

                /*Convert to encrypted string, that can be stored in database*/
                $public = encrypt($PublicKey);
                $private = encrypt($Keypair);

                 /*Gửi mail cho người đăng ký
                 http://www.learnlaravelwithmohit.com/sending-email-messages-in-laravel-5-5-contact-enquiry-form-example/
                 */
                /*Truyền data qua view chứ template của mail*/
                $data = array(
                'name' => $username,
                'key' => $private,
                'email' => $email,
                );
                
                /*Tạo file chứa private key*/
                 /*Tạo file zip nén chứa private key bảo vệ bằng chính password
                http://php.net/manual/en/ziparchive.setencryptionname.php
                */

                // $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
                // fwrite($myfile, $private);
                
                // $zip = new \ZipArchive();
                // if ($zip->open('key.zip', ZipArchive::CREATE) === TRUE) {
                //     $zip->setPassword('secret');
                //     $zip->addFile($myfile);
                //     $zip->setEncryptionName('key.txt', ZipArchive::EM_AES_256);
                //     $zip->close();
                //     echo "Ok\n";
                // } else {
                //     echo "KO\n";
                // }

                // fclose($myfile);

                // die;



                $address = 'vuonghe@gmail.com';
                Mail::send('mail.welcomemail', $data, function ($message) use ($username, $address){
                        $message->from('postmaster@mail.mybox.nz', 'MyBox');
                    
                        $message->to($address)->subject("Hi $username, your account at mybox.nz has been created!");
                        });
                
                /*end of gửi mail*/

                echo "okey";
                die(); //test

                 User::create([
                'username' => $username,
                'email' => $email,
                'password' => bcrypt($password),
                'publickey' => $public,
                
                ]);

                return redirect()->route('home.index')->with("message", "The account <strong>$username</strong> is created! Your private key is: <br>

                [__COPY_THE_BELOW__]</br><br>

                <pre>$private </pre><br><br>

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
