<?php


namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Message;


class KeyController extends Controller
{

    /*Bảo vệ phải login mới cho tạo keys*/
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        /*lấy dữ liệu post để truyền sang view*/

        /*$data['masterkey'] = User::orderBy('masterkey', 'asc')->get();

        
        /*Get the current user*/
        
        $user= \Auth::user();

        $id = $user->id;

        $publickey = $user->publickey;

        /*Nếu đã có publickey thì không cho tạo privatekey, chuyển lại trang sang home*/

        if ($publickey == true) {

            return redirect()-> route('home');
        }


        /*get the username of the current usser
          Return an array
        */

        $data['user']  = User::where('id', $id)->first();


        
        //$flight = App\Flight::where('active', 1)->first();


        return view('account/createkey', $data);

    }

   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        /*
        * dd($request->all());
        *hàm dd để debug
        *Khi nhấn nút click sẽ hiện ra giá trị form gửi lên. :-)
        */
       
       //dd($request->all());

        /*xác thực dữ liệu đầu vào của form create chuyên mục*/

           
        $valid = Validator::make($request->all(), [
            
            'masterkey' => 'required',
         
        ],[
            /*Báo lỗi bằng tiếng việt*/
            /*'name.required' => 'Vui lòng nhập tên sản phẩm',
            '*/

        ]);

       
          /*xử lý dữ liệu đầu vào, nếu lỗi thì trả lại trang create*/
        if($valid->fails()){

            return redirect()->back()->withErrors($valid)->withInput();


            }else{
                $masterkeygiven = $request->input('masterkey');
            }

        /*Lấy giá trị từ user hiện tại: Get the masterkey */
        $user = \Auth::user();
                
        $masterkey = ($user->masterkey);

        $id = $user->id;

        
        if(password_verify($masterkeygiven, $masterkey) !== true){

               /*'fail' flash messsage
               https://stackoverflow.com/questions/37291975/in-laravel-5-how-to-customize-session-flash-message-with-href?utm_medium=organic&utm_source=google_rich_qa&utm_campaign=google_rich_qa
               */
               return redirect()->back()->with('message', "The masterkey is incorrect!");

       

        }else{

                               /* On Node B: */
                $Keypair = sodium_crypto_box_keypair();
                $PublicKey = sodium_crypto_box_publickey($Keypair);

                /*Convert to encrypted string, that can be stored in database*/
                $public = base64_encode($PublicKey);
                $private = base64_encode($Keypair);

                // Then share $bobPublicKey with Node A

                # Transmission:

                /* Sending from Node A to Node B */
                //$message = 'Hi there! :)';
                //$ciphertext = sodium_crypto_box_seal($message, $bobPublicKey);

                /* On Node B, receiving an encrypted message from Node A */
                //$decrypted = sodium_crypto_box_seal_open($ciphertext, $bobKeypair);
        
        }
   
        
            $keys = User::find($id);

            $keys->username  = $user->username;
            $keys->publickey = $public;
            $keys->masterkey = $masterkey;
            $keys->email     = $user->email;
            $keys->password  = $user->password;            
            $keys->save();

            
            return redirect()-> route('home')->with('message', "Successfully create the publickeys. Your private key is: <br>

                [__COPY_THE_BELOW__]</br> 

                <b>$private </b><br>

                [__COPY_THE_ABOVE_PRIVATE_KEY__]<br>. You have to keep this private key IN SECRET somewhere else.");
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function showtoeditkey()
    {
        $user = \Auth::user();

        $id = $user->id;
        
        $data['publickey'] = $user->publickey;

        $data['user']  = User::where('id', $id)->first();

        return view('account/editkey', $data);
    }

       
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storetoeditkey(Request $request)
    {
        
             
        /*Verify to edit*/
         $valid = Validator::make($request->all(), [
            
            'password' => 'required|confirmed',
            
            // 'privatekey' => 'min:88|max:88',
         
        ],[
            /*Customeize the error*/
            'password.required' => 'You need to authorize your confidential',
            'privatekey.min' => 'Private key seems tobe to short!',
            'privatekey.max' => 'Private key seems tobe to long!',
        ]);

       
          /*xử lý dữ liệu đầu vào, nếu lỗi thì trả lại trang create*/
        if($valid->fails()){
            
            return redirect()->back()->withErrors($valid)->withInput();

            }else{
                
                $password = $request->input('password');

                $newpassword = $request->input('newpassword');

                $newpasswordconfirm = $request->input('newpassword-confirm');

                $privatekeyinput = $request->input('privatekey');
        }

        /*Lấy giá trị từ user hiện tại: Get the masterkey */
        $user = \Auth::user();
        $masterkey = $user->masterkey;
        $id = $user->id;
        $passworddata = $user->password;

        $data['publickey'] = $user->publickey;

        $data['user']  = User::where('id', $id)->first();


        /*Verify password*/
        if(password_verify($password, $passworddata) === true){

                if ($newpassword === $password) {
                    
                    return redirect()->back()->with('message', "Your new password is similar to the current password!");
                }

                if ($newpassword !== $newpasswordconfirm) {
                    
                    return redirect()->back()->with('message', "The new password need to be similar to confirmation");
                }
                
        }else{

            return redirect()->back()->with('message', "The current password is incorrect!");
        }

        /*Nếu có privatekey không rỗng*/
        if(!empty($privatekeyinput)){

                    /*Verify */
                    $messages = Message::where('usersid', '=', $id)->take(1)->get();

                    /*If you dont have any message, you cannot go forward*/
                    if(count($messages) === 0){
                       
                        return redirect()->back()->with('message', "Opp!, it appears that you donot have any message at the moment! Create one first! ");

                    }else{

                        foreach ($messages as $message) {
                        $encrypted = $message->encrypted;    
                                              
                        }
                    }
                    
                    /*Verify privatekey*/
                    if(strlen($privatekeyinput) < 88){

                            return redirect()->back()->with('message', "The privatekey is too short and incorrect!");

                        }elseif (strlen($privatekeyinput) > 88) {

                            return redirect()->back()->with('message', "The privatekey is too long and incorrect!");
                    }else{
                            $decrypted = sodium_crypto_box_seal_open(base64_decode($encrypted), base64_decode($privatekeyinput));
                       
                            if ($decrypted === false) {
                                
                                 return redirect()->back()->with('message', "The privatekey is incorrect!");

                            }else{

                                   // đang ở đây sau khi đã xác thực private key
                                   $data['privatekey'] = $privatekeyinput;
                         
                            }

                    }

                    //return redirect()->back()->with('message', "The privatekeyinput không empty!");


        }else{

            return redirect()->back()->with('message', "Your current messages are deleted and your new privatekey is created as below:");
        }



        // dd($request->all());
            
        /*ĐANG XỬ LÝ*/

        if(password_verify($masterkeygiven, $masterkey) !== true){

               /*'fail' flash messsage
               https://stackoverflow.com/questions/37291975/in-laravel-5-how-to-customize-session-flash-message-with-href?utm_medium=organic&utm_source=google_rich_qa&utm_campaign=google_rich_qa
               */
              return redirect()->back()->with('message', "The masterkey is incorrect!");

        }else{

                               /* On Node B: */
                $Keypair = sodium_crypto_box_keypair();
                $PublicKey = sodium_crypto_box_publickey($Keypair);

                /*Convert to encrypted string, that can be stored in database*/
                $public = base64_encode($PublicKey);
                $private = base64_encode($Keypair);

                // Then share $bobPublicKey with Node A

                # Transmission:

                /* Sending from Node A to Node B */
                //$message = 'Hi there! :)';
                //$ciphertext = sodium_crypto_box_seal($message, $bobPublicKey);

                /* On Node B, receiving an encrypted message from Node A */
                //$decrypted = sodium_crypto_box_seal_open($ciphertext, $bobKeypair);
        
        }
   
        
            $keys = User::find($id);

            $keys->username  = $user->username;
            $keys->publickey = $public;
            $keys->masterkey = $masterkey;
            $keys->email     = $user->email;
            $keys->password  = $user->password;            
            $keys->save();

            
            return redirect()-> route('home')->with('message', "Successfully create the publickeys. Your private key is: <br>

                [__COPY_THE_BELOW__]</br> 

                <b>$private </b><br>

                [__COPY_THE_ABOVE_PRIVATE_KEY__]<br>. You have to keep this private key IN SECRET somewhere else.");
    }


    public function deleteaccountform()
    {
        

        $user = \Auth::user();

        $id = \Auth::user()->id;
        
        $data['publickey'] = $user->publickey;


        $data['user']  = User::where('id', $id)->first();

        return view('account/delete', $data);
    }



    public function deleteaccountsubmit()
    {
        

        /*$user = \Auth::user();

        $id = \Auth::user()->id;
        
        $data['publickey'] = $user->publickey;


        $data['user']  = User::where('id', $id)->first();
*/
        
        $this->middleware('guest')->except('logout');

        return view('welcome');

        
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }






    public function verify()
    {
        
        $user = \Auth::user();

        $id = \Auth::user()->id;
        
        $data['publickey'] = $user->publickey;


        $data['user']  = User::where('id', $id)->first();

        return view('account/verify', $data);
    }


    public function authenticate(Request $request)
    {


        //dd($request->all());

        /*Getting the information from the current user*/
        $user = \Auth::user();
        $id = $user->id;
        $publickey = $user->publickey;

        $data['user']  = User::where('id', $id)->first();

        /**
        *@category Lấy ra các message mà usersid = $id của người dùng hiện tại
        *@note dùng mối quan hệ trong Model
        */

        /*Validate private key */
         $valid = Validator::make($request->all(), [
            
            'privatekey' => 'required|min:88|max:88',
                    
        ],[
            /*Customeize the error*/
            'privatekey.required' => 'Vui lòng nhập privatekey',
            'privatekey.min' => 'Private key seems tobe too short!',
            'privatekey.max' => 'Private key seems tobe too long!',
        ]);

       
          /*xử lý dữ liệu đầu vào, nếu lỗi thì trả lại verify*/
        if($valid->fails()){
           
            return redirect()->back()->withErrors($valid)->withInput();

        }else{
                                
                /*Retrieved from input*/
                $privatekeyinput = $request->input('privatekey');

                /*Store the information from the input "privatekey" into the session name "privatekey" into the session, using Laravel*/
                $request->session()->put('privatekey', $privatekeyinput);

                // /*flash này sẽ có ở request tiếp theo và chỉ hiện ra một lần*/
                // $request->session()->flash('flash', $request->input('privatekey'));
        }

        // Lấy từ session và không xóa session có tên 'privatekey'
        $privatekeysession = $request->session()->get('privatekey');

        // $privatekeysessionflash = $request->session()->get('flash');


        /*lấy và xóa luôn session có tên 'privatekey'*/

        //$privatekey = $request->session()->pull('privatekey', $request->input('privatekey'));
                 
       
        /*Verify */
        $messages = Message::where('usersid', '=', $id)->take(1)->get();

        /*If you dont have any message, you cannot go forward*/
        if(count($messages) === 0){
           
            return redirect()->back()->with('message', "Opp!, it appears that you donot have any message at the moment! Create one first! ");

        }else{

            foreach ($messages as $message) {

            $encrypted = $message->encrypted;    
                                  
            }

        }
        
        /*Verify the private with sodium*/

        // try {
        //     if(empty(sodium_crypto_box_seal_open(base64_decode($encrypted), base64_decode($privatekeysession)))) {
        //         throw new \Exception("The privatekey is incorrect!"); 
        //        }
                
        //         $data['privatekey'] = $privatekeysession;
        //     }
        
        // catch (Exception $e) {
        
        //     echo $e->getMessage(); 
           
        //   }

        $decrypted = sodium_crypto_box_seal_open(base64_decode($encrypted), base64_decode($privatekeysession));

            
        if ($decrypted === false) {
            
             return redirect()->back()->with('message', "The privatekey is incorrect!");

        }else{

               $data['privatekey'] = $privatekeysession;
     
        }
        


        //return view('message/read', $data);

        /**
        * Chuyển trang và truyền dữ liệu dạng Session sang controller phía dưới có route là ('key.read')
        * @category Redirect and return the value to the "key.read" route
        * @link with("data", "value") ==> Redirecting With Flashed Session Data https://laravel.com/docs/5.5/redirects
        * @see https://stackoverflow.com/questions/25078452/how-to-send-data-using-redirect-with-laravel?utm_medium=organic&utm_source=google_rich_qa&utm_campaign=google_rich_qa
        */
        
        return redirect()-> route('key.read', [$id = sha1(rand(1, 1000))."protected"])->with(["privatekey" => $data]);
    }


    public function readMessage(Request $request)
    {   
        
        /*dữ liệu này là flash session, lấy từ hàm authenticate*/
        $data = $request->session()->get('privatekey');

        /*Lấy dữ liệu từ flash session và xóa luôn dữ liệu của session đã lấy*/

        //$data = $request->session()->pull('privatekey', $data);

        /*thử gán sang _SESSION php*/
        // $_SESSION['key'] = $data;
       
        // $data['key'] = $_SESSION['key']['privatekey'];


        if($data === NULL){
            
            return redirect()->route('key.authenticate');

        }else{

            //Regenerating The Session ID
            $request->session()->regenerate();

            /**
            * giữ session từ flash session, thay vì xóa ngay in next request, vì khi redirect() route, session được chuyển sang từ controller này sang controller khác là dạng flash session
            * @see https://laravel.com/docs/5.6/session
            */
            $request->session()->reflash();
        }
        
                
        /*Getting the information from the current user*/
        $user = \Auth::user();

        $id = $user->id;

        $data['user']  = User::where('id', $id)->first();
        
        $data['total_message'] = Message::where('usersid', '=', $id)->get();
        
        $data ['messages'] = Message::where('usersid', '=', $id)->orderBy('id', 'desc')->paginate(7);
        
        return view('message/read', $data);

    }


}
