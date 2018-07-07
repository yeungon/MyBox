<?php


namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Message;
use App\Ask;
use App\Reply;
use Session;
use Mail;
use Carbon\Carbon; 


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
    /*HÀM NÀY KHÔNG DÙNG*/
    public function update(Request $request)
    {
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
                $public = encrypt($PublicKey);
                $private = encrypt($Keypair);

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
            
            'password' => 'required',
            
            // 'privatekey' => 'min:88|max:88',
         
        ],[
            /*Customeize the error*/
            'password.required' => 'You need to authorize your access before changing your profile',
            // 'privatekey.min' => 'Private key seems tobe to short!',
            // 'privatekey.max' => 'Private key seems tobe to long!',
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

        /*Lấy giá trị từ user hiện tại: Get the public key */
        $user = \Auth::user();
        $publickey = $user->publickey;
        $id = $user->id;
        $username = $user->username;
        $email = $user->email;
        
        $passworddata = $user->password;

        $data['publickey'] = $user->publickey;

        $data['user']  = User::where('id', $id)->first();


        /*Verify password*/
        if(password_verify($password, $passworddata) === true){

            /*Nếu nhập new password*/
            if(!empty($newpassword)){

                            if ($newpassword === $password) {
                                
                                return redirect()->back()->with('message', "Your new password is similar to the current password!");
                            }

                            if ($newpassword !== $newpasswordconfirm) {
                                
                                return redirect()->back()->with('message', "The new password need to be similar to password confirmation");
                            }

                            if (strlen($newpassword) <6) {
                                
                                return redirect()->back()->with('message', "The new password should have at least 6 characters");
                            }
            }

            $newpassword = $request->input('newpassword');

                
        }else{

            return redirect()->back()->with('message', "The current password is incorrect!");
        }

        /*Nếu có privatekey không rỗng*/
        if(!empty($privatekeyinput)){

                    /*Verify */
                    $messages = Message::where('usersid', '=', $id)->take(1)->get();
                    $asks = Ask::where('usersid', '=', $id)->take(1)->get();

                    /*If you dont have any message, a new public and private key will be created**************************************
                    ***************** BẮT BUỘC PHẢI TẠO LẠI KEY MỚI VÌ TRONG DATABASE KHÔNG CÓ NỘI DUNG ĐỂ VERIFY KEY*****************
                    */

                    if(count($messages) === 0 && count($asks) ===0){
                        
                        /*vì không có message nào nên không thể verify*/

                        $privateKey  = sodium_crypto_box_keypair();
                        $publicKey  = sodium_crypto_box_publickey($privateKey);


                        /*Convert to encrypted string, that can be stored in database*/
                        $public = encrypt($publicKey);
                        $private = encrypt($privateKey);

                        /*Cập nhật database*/
                        $keys = User::find($id);
                        $keys->username  = $user->username;
                        $keys->publickey = $public;
                        
                        /*Nếu có new password thì cập nhật*/
                        if($newpassword == true){

                            $keys->password  = bcrypt($newpassword);            
                        }

                        $keys->save();
                        
                        /*Nếu đổi mật khẩu thì YÊU CẦU LOGIN LẠI, ép LOGOUT BẰNG CÁC XÓA SESSION*/
                        if($newpassword == true){
                            

                            /**
                            *************************************SEND EMAIL ************************************************************
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
                               $message ->  to($email);
                               $message ->  subject('Urgent! Your password has been changed!');
                               $message ->  from('myboxdotnz@gmail.com', 'Mybox.nz - Secured Box');
                            });

                            /**
                            *************************************END OF SEND EMAIL ************************************************************
                            */
                           
                            /*xóa session để bắt login lại*/
                            Session::flush();

                            return redirect()->route('home.index')->with('message', "Opp!, it appears that you donot have any status or question at the moment! Create one first! Your new private key is therefore issued as the following:
                                <br>
                                <pre>$private</pre>
                                <br>
                                You should keep this private key in secret!
                                <br>
                                Your password is also updated! You can re-login with your new password from now on.

                            ");

                        }else{

                            
                            /**
                            *************************************SEND EMAIL ************************************************************
                            */
                            /*Thiết lập timezone cho PHP, cần thiết lập trên phpini*/       
                            date_default_timezone_set('Europe/London');
                            
                            $currenttime = Carbon::now('Europe/London');

                            $formattime =  $currenttime->toDayDateTimeString();

                            /*test xem có thể gửi email
                            Gửi string email, dùng closure để truyền vào email là biến use($email)
                            https://laracasts.com/discuss/channels/laravel/send-raw-text-mail-without-using-a-view-in-laravel-53
                            */
                            $content = "Hi $username! Your private key has been changed at $formattime (London timezone)! If you have not done that, please do not hestitate to contact us by replying this email! Cheer!";

                            Mail::raw($content, function ($message) use($email, $username) {
                               $message ->  to($email);
                               $message ->  subject('Urgent! Your private key has been changed!');
                               $message ->  from('myboxdotnz@gmail.com', 'Mybox.nz - Secured Box');
                            });

                            /**
                            *************************************END OF SEND EMAIL ************************************************************
                            */


                            return redirect()->back()->with('tinnhan', "Opp!, it appears that you donot have any status or question at the moment! Create one first! 
                            <br>
                            Your new private key is therefore issued as the following: <pre>$private</pre>. 
                            <br>

                            You have to keep this private key in secret. Your previous private key is no longer applicable.
                            ");

                        }
                    
                    /*****************************************************************************************************
                        else => Nếu có bài viết hoặc ask, có thể xác thực và sau đó GIẢI MÃ -> MÃ HÓA LẠI -> LƯU LẠI VÀO MẬT KHẨU
                    *******************************************************************************************************
                    */

                    }else{ 

                        $encrypted = false;

                        if (count($messages)>0){
                                foreach ($messages as $message) {
                                $encrypted = $message->encrypted;    
                            }
                        }

                        $encryptedask = false;

                        if(count($asks) >0 ){
                                foreach ($asks as $ask) {
                                $encryptedask = $ask->ask;    
                            }
                        }
                    }
                    
                    /*Verify privatekey*/
                    if(strlen($privatekeyinput) < 290){

                            return redirect()->back()->with('message', "The privatekey is too short and incorrect!");

                        }elseif (strlen($privatekeyinput) > 310) {

                            return redirect()->back()->with('message', "The privatekey is too long and incorrect!");
                    }else{

                            /*********************************************************************************************************
                                                    nếu có status trong database để verify
                            **********************************************************************************************************/
                            if($encrypted == true){

                                    $decrypted = sodium_crypto_box_seal_open(decrypt($encrypted), decrypt($privatekeyinput));
                               
                                    if ($decrypted === false) {
                                        
                                         return redirect()->back()->with('message', "The privatekey is incorrect!");

                                    }else{

                                            /*UPDATE THE MESSAGE*/
                                            $messages = Message::where('usersid', $id)->get();
                                           
                                            /*thêm một item là private key vào collection ==> KHÔNG CẦN*/

                                            //$messages->prepend($privatekeyinput);

                                            /*Giải mã và chuyển sang plain text, then encrypt again with new key issued afterwards
                                            Noted: Dùng closure use($variable) để truyền dữ liệu vào trong call-back function*/
                                            $messages->transform(function ($item, $key)use($privatekeyinput) {

                                                $item->encrypted = sodium_crypto_box_seal_open(decrypt($item->encrypted), decrypt($privatekeyinput));
                                            
                                                return $item;
                                              
                                            });
                                                                                                                                 
                                           /*Issued NEW KEYS*/
                                            $privateKey  = sodium_crypto_box_keypair();
                                            $publicKey  = sodium_crypto_box_publickey($privateKey);

                                            /*Convert to string that can be stored in database using asymetric encryption provided by Laravel (OpenSSL)*/
                                            $newpublic = encrypt($publicKey);
                                            $newprivate = encrypt($privateKey);

                                            /*MÃ HÓA LẠI - Re-encryp*/
                                            $messages->transform(function ($item, $key)use($newpublic) {

                                                $item->encrypted = sodium_crypto_box_seal($item->encrypted, decrypt($newpublic));
                                                                
                                                $item->encrypted = encrypt($item->encrypted);
                                                                                            
                                                return $item;
                                              
                                            });
                                            
                                            foreach ($messages as $message) {
                                                
                                                //UPDATE NEW ENCRYPTED DATA TO THE DATABASE
                                                $newmessage = Message::find($message->id);
                                                $newmessage->message  = "updated on okey $message->updated_at".$message->message;
                                                $newmessage->encrypted = $message->encrypted;
                                                $newmessage->usersid = $message->usersid;
                                                $newmessage->created_at = $message->created_at;
                                                $newmessage->save();
                                            }

                                            /*UPDATE THE ASK/QUESTIONS, nếu có ASK sẽ có REPLY*/
                                            $asks = Ask::where('usersid', $id)->get();

                                            if(count($asks)>0){

                                                /*Giải mã và chuyển sang plain text, then encrypt again with new key issued afterwards privatekeyinput là KEY hiện tại nhập từ input*/
                                                $asks->transform(function ($item, $key)use($privatekeyinput) {

                                                    $item->ask = sodium_crypto_box_seal_open(decrypt($item->ask), decrypt($privatekeyinput));
                                            
                                                    return $item;                                              
                                                });

                                                /*DECRYPT AGAIN AND UPDATE THE DATABSE*/
                                                $asks->transform(function ($item, $key)use($newpublic) {

                                                    /*encypt using Sodium*/
                                                    $item->ask = sodium_crypto_box_seal($item->ask, decrypt($newpublic));
                                                    /*encrypt using OpenSSL*/
                                                    $item->ask = encrypt($item->ask);
                                            
                                                    return $item;                                              
                                                });
                                                /*Iterating and store to the database*/

                                                foreach ($asks as $ask) {
                                                   
                                                    //UPDATE NEW ENCRYPTED DATA TO THE DATABASE
                                                    $newask = Ask::find($ask->id);
                                                    $newask->ask        = $ask->ask;
                                                    $newask->usersid    = $ask->usersid;
                                                    $newask->publish    = $ask->publish;
                                                    $newask->created_at = $ask->created_at;
                                                    $newask->save();
                                                }
                                            }

                                            /*UPDATE THE REPLY, nếu có ASK sẽ có REPLY*/
                                            $replies = Reply::where('usersid', $id)->get();

                                            if(count($replies)>0){

                                                /*Giải mã và chuyển sang plain text, then encrypt again with new key issued afterwards privatekeyinput là KEY hiện tại nhập từ input*/
                                                $replies->transform(function ($item, $key)use($privatekeyinput) {

                                                    $item->reply = sodium_crypto_box_seal_open(decrypt($item->reply), decrypt($privatekeyinput));
                                            
                                                    return $item;                                              
                                                });

                                                /*DECRYPT AGAIN AND UPDATE THE DATABSE*/
                                                $replies->transform(function ($item, $key)use($newpublic) {

                                                    /*encypt using Sodium*/
                                                    $item->reply = sodium_crypto_box_seal($item->reply, decrypt($newpublic));
                                                    /*encrypt using OpenSSL*/
                                                    $item->reply = encrypt($item->reply);
                                            
                                                    return $item;                                              
                                                });
                                                /*Iterating and store to the database*/

                                                foreach ($replies as $reply) {
                                                   
                                                    //UPDATE NEW ENCRYPTED DATA TO THE DATABASE
                                                    $newreply = Reply::find($reply->id);
                                                    $newreply->reply      = $reply->reply;
                                                    $newreply->usersid    = $reply->usersid;
                                                    $newreply->asksid     = $reply->asksid;
                                                    $newreply->created_at = $reply->created_at;
                                                    $newreply->save();
                                                }
                                            }

                                             /*UPDATE NEW PUBLIC KEY AND NEW PASSWORD IF SELECTED*/
                                            $users = User::find($id);
                                            $users->publickey = $newpublic;
                                            
                                            /*Nếu có new password thì cập nhật, NẾU KHÔNG THÌ DÙNG LẠI password cũ*/
                                            if($newpassword == true){
                                                $users->password  = bcrypt($newpassword);

                                            }

                                            $users->save();
                                            
                                            /*Nếu có new password thì DELETE SESSION AND ASK RE-LOGIN*/
                                            if($newpassword == true){

                                                                      /**
                                                *************************************SEND EMAIL ************************************************************
                                                */
                                                /*Thiết lập timezone cho PHP, cần thiết lập trên phpini*/       
                                                date_default_timezone_set('Europe/London');
                                                
                                                $currenttime = Carbon::now('Europe/London');

                                                $formattime =  $currenttime->toDayDateTimeString();

                                                /*test xem có thể gửi email
                                                Gửi string email, dùng closure để truyền vào email là biến use($email)
                                                https://laracasts.com/discuss/channels/laravel/send-raw-text-mail-without-using-a-view-in-laravel-53
                                                */
                                                $content = "Hi $username! Both of your privat key and password have been changed at $formattime (London timezone)! If you have not done that, please do not hestitate to contact us by replying this email! Cheer!";

                                                Mail::raw($content, function ($message) use($email, $username) {
                                                   $message ->  to($email);
                                                   $message ->  subject('Urgent! Your password and your private key have been changed!');
                                                   $message ->  from('myboxdotnz@gmail.com', 'Mybox.nz - Secured Box');
                                                });

                                                /**
                                                *************************************END OF SEND EMAIL ************************************************************
                                                */

                                                 /*xóa session để bắt login lại*/
                                                Session::flush();

                                                return redirect()->route('home.index')->with('message', "Your profile has been updated and your new privatekey is issued as below:
                                                    <br>
                                                    <pre>$newprivate</pre>
                                                    <br>
                                                    You should keep this private key in secret!
                                                    <br>
                                                    Your password is also updated! You can re-login with your new password from now on.

                                                ");

                                            }else{

                                                                /**
                                                    *************************************SEND EMAIL ************************************************************
                                                    */
                                                    /*Thiết lập timezone cho PHP, cần thiết lập trên phpini*/       
                                                    date_default_timezone_set('Europe/London');
                                                    
                                                    $currenttime = Carbon::now('Europe/London');

                                                    $formattime =  $currenttime->toDayDateTimeString();

                                                    /*test xem có thể gửi email
                                                    Gửi string email, dùng closure để truyền vào email là biến use($email)
                                                    https://laracasts.com/discuss/channels/laravel/send-raw-text-mail-without-using-a-view-in-laravel-53
                                                    */
                                                    $content = "Hi $username! Your private key has been changed at $formattime (London timezone)! If you have not done that, please do not hestitate to contact us by replying this email! Cheer!";

                                                    Mail::raw($content, function ($message) use($email, $username) {
                                                       $message ->  to($email);
                                                       $message ->  subject('Urgent! Your private key has been changed!');
                                                       $message ->  from('myboxdotnz@gmail.com', 'Mybox.nz - Secured Box');
                                                    });

                                                    /**
                                                    *************************************END OF SEND EMAIL ************************************************************
                                                    */

                                                    
                                                    return redirect()->back()->with('message', "Your profile has been updated and your new privatekey is issued as below:
                                                    <br>
                                                    <pre>$newprivate</pre>
                                                    <br>
                                                    You should keep this private key in secret!
                                                    <br>                                                    
                                                ");
                                            }

                                        }

                                    } // end of xác thực key với message

                            
                                    /*********************************************************************************************************
                                                            nếu chỉ có question trong database để verify
                                    **********************************************************************************************************/
                                    if($encryptedask == true){

                                            $encryptedaskdecrypted = sodium_crypto_box_seal_open(decrypt($encryptedask), decrypt($privatekeyinput));
                                       
                                            if ($encryptedaskdecrypted === false) {
                                                
                                                 return redirect()->back()->with('message', "The privatekey is incorrect!");

                                            }else{

                                            /*ĐỔI DỮ LIỆU CHO ASK VÀ REPLY*/
                                            /*UPDATE THE ASK/QUESTIONS, nếu có ASK sẽ có REPLY*/
                                            $asks = Ask::where('usersid', $id)->get();

                                            if(count($asks)>0){

                                                /*Giải mã và chuyển sang plain text, then encrypt again with new key issued afterwards privatekeyinput là KEY hiện tại nhập từ input*/
                                                $asks->transform(function ($item, $key)use($privatekeyinput) {

                                                    $item->ask = sodium_crypto_box_seal_open(decrypt($item->ask), decrypt($privatekeyinput));
                                            
                                                    return $item;                                              
                                                });

                                                /*CREATE NEW PUBLIC AND PRIVATE KEY*/
                                                $privateKey  = sodium_crypto_box_keypair();
                                                $publicKey  = sodium_crypto_box_publickey($privateKey);

                                                /*Convert to string that can be stored in database using asymetric encryption provided by Laravel (OpenSSL)*/
                                                $newpublic = encrypt($publicKey);
                                                $newprivate = encrypt($privateKey);

                                                /*DECRYPT AGAIN AND UPDATE THE DATABSE*/
                                                $asks->transform(function ($item, $key)use($newpublic) {

                                                    /*encypt using Sodium*/
                                                    $item->ask = sodium_crypto_box_seal($item->ask, decrypt($newpublic));
                                                    /*encrypt using OpenSSL*/
                                                    $item->ask = encrypt($item->ask);
                                            
                                                    return $item;                                              
                                                });
                                                /*Iterating and store to the database*/

                                                foreach ($asks as $ask) {
                                                   
                                                    //UPDATE NEW ENCRYPTED DATA TO THE DATABASE
                                                    $newask = Ask::find($ask->id);
                                                    $newask->ask        = $ask->ask;
                                                    $newask->usersid    = $ask->usersid;
                                                    $newask->publish    = $ask->publish;
                                                    $newask->created_at = $ask->created_at;
                                                    $newask->save();
                                                }
                                            }

                                            /*UPDATE THE REPLY, nếu có ASK sẽ có REPLY*/
                                            $replies = Reply::where('usersid', $id)->get();

                                            if(count($replies)>0){

                                                /*Giải mã và chuyển sang plain text, then encrypt again with new key issued afterwards privatekeyinput là KEY hiện tại nhập từ input*/
                                                $replies->transform(function ($item, $key)use($privatekeyinput) {

                                                    $item->reply = sodium_crypto_box_seal_open(decrypt($item->reply), decrypt($privatekeyinput));
                                            
                                                    return $item;                                              
                                                });


                                                /*DECRYPT AGAIN AND UPDATE THE DATABSE*/
                                                $replies->transform(function ($item, $key)use($newpublic) {

                                                    /*encypt using Sodium*/
                                                    $item->reply = sodium_crypto_box_seal($item->reply, decrypt($newpublic));
                                                    /*encrypt using OpenSSL*/
                                                    $item->reply = encrypt($item->reply);
                                            
                                                    return $item;                                              
                                                });
                                                /*Iterating and store to the database*/

                                                foreach ($replies as $reply) {
                                                   
                                                    //UPDATE NEW ENCRYPTED DATA TO THE DATABASE
                                                    $newreply = Reply::find($reply->id);
                                                    $newreply->reply      = $reply->reply;
                                                    $newreply->usersid    = $reply->usersid;
                                                    $newreply->asksid     = $reply->asksid;
                                                    $newreply->created_at = $reply->created_at;
                                                    $newreply->save();
                                                }
                                            }

                                             /*UPDATE NEW PUBLIC KEY AND NEW PASSWORD IF SELECTED*/
                                            $users = User::find($id);
                                            $users->publickey = $newpublic;
                                            
                                            /*Nếu có new password thì cập nhật, NẾU KHÔNG THÌ DÙNG LẠI password cũ*/
                                            if($newpassword == true){
                                                $users->password  = bcrypt($newpassword);

                                            }

                                            $users->save();
                                            
                                            /*Nếu có new password thì DELETE SESSION AND ASK RE-LOGIN*/
                                            if($newpassword == true){


                                                                      /**
                                                *************************************SEND EMAIL ************************************************************
                                                */
                                                /*Thiết lập timezone cho PHP, cần thiết lập trên phpini*/       
                                                date_default_timezone_set('Europe/London');
                                                
                                                $currenttime = Carbon::now('Europe/London');

                                                $formattime =  $currenttime->toDayDateTimeString();

                                                /*test xem có thể gửi email
                                                Gửi string email, dùng closure để truyền vào email là biến use($email)
                                                https://laracasts.com/discuss/channels/laravel/send-raw-text-mail-without-using-a-view-in-laravel-53
                                                */
                                                $content = "Hi $username! Both of your privat key and password have been changed at $formattime (London timezone)! If you have not done that, please do not hestitate to contact us by replying this email! Cheer!";

                                                Mail::raw($content, function ($message) use($email, $username) {
                                                   $message ->  to($email);
                                                   $message ->  subject('Urgent! Your password and your private key have been changed!');
                                                   $message ->  from('myboxdotnz@gmail.com', 'Mybox.nz - Secured Box');
                                                });

                                                /**
                                                *************************************END OF SEND EMAIL ************************************************************
                                                */



                                                 /*xóa session để bắt login lại*/
                                                Session::flush();

                                                return redirect()->route('home.index')->with('message', "Your profile has been updated and your new privatekey is issued as below:
                                                    <br>
                                                    <pre>$newprivate</pre>
                                                    <br>
                                                    You should keep this private key in secret!
                                                    <br>
                                                    Your password is also updated! You can re-login with your new password from now on.

                                                ");

                                            }else{
                                                                /**
                                                    *************************************SEND EMAIL ************************************************************
                                                    */
                                                    /*Thiết lập timezone cho PHP, cần thiết lập trên phpini*/       
                                                    date_default_timezone_set('Europe/London');
                                                    
                                                    $currenttime = Carbon::now('Europe/London');

                                                    $formattime =  $currenttime->toDayDateTimeString();

                                                    /*test xem có thể gửi email
                                                    Gửi string email, dùng closure để truyền vào email là biến use($email)
                                                    https://laracasts.com/discuss/channels/laravel/send-raw-text-mail-without-using-a-view-in-laravel-53
                                                    */
                                                    $content = "Hi $username! Both of your privat key and password have been changed at $formattime (London timezone)! If you have not done that, please do not hestitate to contact us by replying this email! Cheer!";

                                                    Mail::raw($content, function ($message) use($email, $username) {
                                                       $message ->  to($email);
                                                       $message ->  subject('Urgent! Your password and your private key have been changed!');
                                                       $message ->  from('myboxdotnz@gmail.com', 'Mybox.nz - Secured Box');
                                                    });

                                                    /**
                                                    *************************************END OF SEND EMAIL ************************************************************
                                                    */

                                                    return redirect()->back()->with('message', "Your profile has been updated and your new privatekey is issued as below:
                                                    <br>
                                                    <pre>$newprivate</pre>
                                                    <br>
                                                    You should keep this private key in secret!
                                                    <br>                                                    
                                                ");
                                            }
    
                                        }
                                    } // end of xác thực key với question
                            }
                            //return redirect()->back()->with('message', "The privatekeyinput không empty!");


        /*********************************************************************************************************
                                  else  nếu KHÔNG CÓ private key, bắt buộc phải XÓA data
        **********************************************************************************************************/
        }else{ 
           
           /***************************************************************
            *Nếu nhập new password để TẠO MẬT KHẨU MỚI,
            *************************************************************/
            
            if(!empty($newpassword)){

                    if ($newpassword === $password) {
                        
                        return redirect()->back()->with('message', "Your new password is similar to the current password!");
                    }

                    if ($newpassword !== $newpasswordconfirm) {
                        
                        return redirect()->back()->with('message', "The new password need to be similar to password confirmation");
                    }

                    if (strlen($newpassword) <6) {
                                
                                return redirect()->back()->with('message', "The new password should have at least 6 characters");
                    }

                    /*Mật khẩu mới*/
                    $newpassword = $request->input('newpassword');

                    /*Xóa dữ liệu cũ*/
                    Message::where('usersid', $id)->delete();
                    // DB::delete('DELETE FROM users WHERE id = 1');
                    // để xóa khi có khóa ngoại, xóa yếu tố ràng buộc trước https://laravel.com/docs/5.5/queries#deletes

                    //DB::table('replies')->where('usersid', '=', $id)->delete();cách này okey

                    Reply::where('usersid', $id)->delete();

                    Ask::where('usersid', $id)->delete();

                    $privateKey  = sodium_crypto_box_keypair();
                    $publicKey  = sodium_crypto_box_publickey($privateKey);
                        

                    /*Convert to encrypted string, that can be stored in database*/
                    $public = encrypt($publicKey);
                    $private = encrypt($privateKey);

                    /*Cập nhật database*/
                    $users = User::find($id);
                    $users->publickey = $public;
                    $users->password  = bcrypt($newpassword);            
                    $users->save();


                                          /**
                    *************************************SEND EMAIL ************************************************************
                    */
                    /*Thiết lập timezone cho PHP, cần thiết lập trên phpini*/       
                    date_default_timezone_set('Europe/London');
                    
                    $currenttime = Carbon::now('Europe/London');

                    $formattime =  $currenttime->toDayDateTimeString();

                    /*test xem có thể gửi email
                    Gửi string email, dùng closure để truyền vào email là biến use($email)
                    https://laracasts.com/discuss/channels/laravel/send-raw-text-mail-without-using-a-view-in-laravel-53
                    */
                    $content = "Hi $username! Your data have been deleted as you have not provided your current privateky when changing your password at $formattime (London timezone)! If you have not done that, please do not hestitate to contact us by replying this email! Cheer!";

                    Mail::raw($content, function ($message) use($email, $username) {
                       $message ->  to($email);
                       $message ->  subject('Urgent! Your data are deleted and your private key, password have been changed!');
                       $message ->  from('myboxdotnz@gmail.com', 'Mybox.nz - Secured Box');
                    });

                    /**
                    *************************************END OF SEND EMAIL ************************************************************
                    */
                    
                    /*xóa session để bắt login lại*/
                    Session::flush();

                    return redirect()->route('home.index')->with('message', "Your current messages are deleted and your new privatekey is issued as below:
                        <br>
                        <pre>$private</pre>
                        <br>
                        You should keep this private key in secret!
                        <br>
                        Your password is also updated! You can re-login with your new password from now on.

                    ");

            /***************************************************************
            *Nếu KHÔNG NHẬP new password
            *************************************************************/
            }else{
                 
                 /*Nếu không đổi password*/

                 /*Xóa dữ liệu cũ*/
                    Message::where('usersid', $id)->delete();
                    // DB::delete('DELETE FROM users WHERE id = 1');
                    // để xóa khi có khóa ngoại, xóa yếu tố ràng buộc trước https://laravel.com/docs/5.5/queries#deletes

                    //DB::table('replies')->where('usersid', '=', $id)->delete();cách này okey

                    Reply::where('usersid', $id)->delete();
                    Ask::where('usersid', $id)->delete();

                    $privateKey  = sodium_crypto_box_keypair();
                    $publicKey  = sodium_crypto_box_publickey($privateKey);
                        

                    /*Convert to encrypted string, that can be stored in database*/
                    $public = encrypt($publicKey);
                    $private = encrypt($privateKey);


                    /*Cập nhật database*/
                    $users = User::find($id);
                    $users->publickey = $public;
                    $users->save();

                      /**
                    *************************************SEND EMAIL ************************************************************
                    */
                    /*Thiết lập timezone cho PHP, cần thiết lập trên phpini*/       
                    date_default_timezone_set('Europe/London');
                    
                    $currenttime = Carbon::now('Europe/London');

                    $formattime =  $currenttime->toDayDateTimeString();

                    /*test xem có thể gửi email
                    Gửi string email, dùng closure để truyền vào email là biến use($email)
                    https://laracasts.com/discuss/channels/laravel/send-raw-text-mail-without-using-a-view-in-laravel-53
                    */
                    $content = "Hi $username! Your data have been deleted as you have not provided your current privateky when editing your profile at $formattime (London timezone)! If you have not done that, please do not hestitate to contact us by replying this email! Cheer!";

                    Mail::raw($content, function ($message) use($email, $username) {
                       $message ->  to($email);
                       $message ->  subject('Urgent! Your data are deleted and your private key has been changed!');
                       $message ->  from('myboxdotnz@gmail.com', 'Mybox.nz - Secured Box');
                    });

                    /**
                    *************************************END OF SEND EMAIL ************************************************************
                    */
                    
                    return redirect()->back()->with('tinnhan', "Your current messages are deleted and your new privatekey is issued as below:
                        <br>
                        <pre>$private</pre>
                        <br>
                        You should keep this private key in secret!
                        <br>
                   ");

               }
         
         }
     
    }

    public function sendEmail($option){

        if($option == "noprivatekeynonewpassword"){


        }elseif ($option == "noprivatekeyandpassword") {
            
        }else if ($option == "yesprivatekey") {
            # code...
        }else{


        }

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
            
            'privatekey' => 'required|min:290|max:310',
                    
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
        //     if(empty(sodium_crypto_box_seal_open(decrypt($encrypted), decrypt($privatekeysession)))) {
        //         throw new \Exception("The privatekey is incorrect!"); 
        //        }
                
        //         $data['privatekey'] = $privatekeysession;
        //     }
        
        // catch (Exception $e) {
        
        //     echo $e->getMessage(); 
           
        //   }

        $decrypted = sodium_crypto_box_seal_open(decrypt($encrypted), decrypt($privatekeysession));

            
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
