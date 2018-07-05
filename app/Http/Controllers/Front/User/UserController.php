<?php

namespace App\Http\Controllers\Front\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Message;
use App\Ask;
use App\Reply;
use Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($username)
    {

         // Kiểm tra có login hay không
        if (Auth::check()) {
                $data['login'] ="logined";
                $id = \Auth::user()->id;
                $data['currentusername'] = \Auth::user()->username;
        }else{

            $data['login'] ="login";
        }
      
        /*Kiểm tra có tồn tại hay không*/
        
        /*Thông tin bảng user, lấy từ GET request*/
        $username = strtolower(trim($username));

        $user = User::where('username', $username)->first();

        
        if($user == true){
            
            /*Lấy username GET truyền vào*/
            $data['username'] = $username;
            
            /*Lấy id*/
            $id = $user->id;

            $data['total_message'] = Message::where('usersid', '=', $id)->get();

            /*Lấy message theo ID và phân trang*/
            $data['messages'] = Message::where('usersid', '=', $id)->orderBy('id', 'desc')->paginate(5);

        }else{

            /*gán điều kiện người dùng chưa có ở view sau này*/
            $data['username'] = 'theuserisnotregistered';
        }

        /**
        * @category Lấy ra các message mà usersid = $id của người dùng hiện tại
        * @note dùng mối quan hệ trong Model
        */


        return view('frontend/user/home', $data);
    }

     public function read(Request $request, $username)
    {
        
        $valid = Validator::make($request->all(), [
            
            'privatekey' => 'required|string|min:290|max:310',
                                 
        ],[
            /*Customeize the error*/
            
            'privatekey.min' => 'The private key seems too short!',
            'privatekey.max' => 'The private key seems too long!',
        ]);
             
        if($valid->fails()){
            
            return redirect()->back()->withErrors($valid)->withInput();

            }else{

                /*Retrieved from input*/
                $privatekeyinput = $request->input('privatekey');

                /*Store the information from the input "privatekey" into the session name "privatekey" into the session, using Laravel*/
                $request->session()->put('privatekey', $privatekeyinput);

        }


        // Lấy thông tin người dùng hiện tại
        $username = strtolower(trim($username));
        
        $data['username']  = $username;

        /*Thông tin bảng user*/
        $user = User::where('username', $username)->first();

        /*Nếu có người dùng này*/
        if($user == true){
            
            $data['username'] = $username;
            
            $id = $user->id;

            $data['id'] = $user->id;

        }else{

            $data['username'] = 'theuserisnotregistered';
        }
            

        /*Lấy message để verify the key */
        $messages = Message::where('usersid', '=', $id)->take(1)->get();

        /*If you dont have any message, you cannot go forward*/
        if(count($messages) === 0){
           
            return redirect()->back()->with('message', "Opp!, it appears that you donot have any message at the moment! Create one first! ");

        }else{

            foreach ($messages as $message) {

            $encrypted = $message->encrypted;    
                                  
            }

        }

        /*Xác thực privatekey*/

        // Getsession và không xóa session có tên 'privatekey'
        $privatekeysession = $request->session()->get('privatekey');
       

        $decrypted = sodium_crypto_box_seal_open(decrypt($encrypted), decrypt($privatekeysession));

            
        if ($decrypted === false) {
            
             return redirect()->back()->with('message', "The privatekey is incorrect!");

        }else{

               $data['privatekey'] = $privatekeysession;
     
        }

        // Kiểm tra có login hay không
        if (Auth::check()) {
                $data['login'] ="logined";
                $id = \Auth::user()->id;
                $data['currentusername'] = \Auth::user()->username;
        }else{

            $data['login'] ="login";
        }

        /*Truyền dữ liệu sang route get để đọc, truyền dữ liệu session sang hàm readmessage
        @note truyền cookie từ request này sang request tiếp theo, gán giá trị 'guard' là 'vuong', hết hạn trong vòng 1 phút.

        */
        return redirect()-> route('home.userreadmessage', ['id' => $username, 'token' => sha1(rand(1, 1000))])->with(["privatekey" => $data])->cookie('guard', 'vuong', 1);
       
    }
    
    public function readmessage(Request $request, $username)
    {

       /*dữ liệu này là flash session, lấy từ hàm read truyền qua*/
        $data = $request->session()->get('privatekey');


        /*Nếu không có dữ liệu từ route làm read*/

        if($data === NULL){
            
            return redirect()->route('home.userread', ['id' => $username]);

        }else{

            //Regenerating The Session ID
            $request->session()->regenerate();

            /**
            * giữ session từ flash session, thay vì xóa ngay in next request, vì khi redirect() route, session được chuyển sang từ controller này sang controller khác là dạng flash session
            * @see https://laravel.com/docs/5.6/session
            */
            $request->session()->reflash();
        }

        /*so sánh username từ GET và username từ flash data*/

        $id = $data['id'];

        $usernamedata = $data['username'];

        /*username lấy từ GET request*/
        $username = strtolower(trim($username));

        if($usernamedata != $username){
            return redirect()->route('home.userread', ['id' => $username]);
        }
        


        $data['total_message'] = Message::where('usersid', '=', $id)->get();

        /*Lấy message theo ID và phân trang*/

        $data['messages'] = Message::where('usersid', '=', $id)->orderBy('id', 'desc')->paginate(5);

        /*Get the value from cookie*/
        $data['guard'] = $request->cookie('guard');

        return view('frontend/user/read', $data);

    }
    
    public function send($username)
    {
        
        /*username lấy từ GET request*/
        $username = strtolower(trim($username));

         // Kiểm tra có login hay không
        if (Auth::check()) {
                $data['login'] ="logined";
                $id = \Auth::user()->id;
                $data['currentusername'] = \Auth::user()->username;
        }else{

            $data['login'] ="login";
        }
        
        $id = User::where('username', trim($username))->first();

        if($id == true){
            $data['username'] = trim($username);
        }else{

            $data['username'] = 'theuserisnotregistered';
        }
        
        return view('frontend/user/send', $data);

    }

    public function sendstore(Request $request, $username)
    {
        
        $user = User::where('username', $username)->first();

        $key = $user->publickey;
        $id = $user->id;


         /*người dùng đang đăng nhập*/
         // $id = \Auth::user()->id;
                
         //$publickey = \Auth::user()->publickey;


        $valid = Validator::make($request->all(), [
            
            'ask' => 'required|string|min:5|max:500',
            //'g-recaptcha-response' => 'required|captcha'
                                 
        ],[
            /*Customeize the error*/
            
            'ask.min' => 'The message should have more than 5 characters!',
            'ask.max' => 'The message should have less than 500 characters!',
        ]);
      
        
        /*xử lý dữ liệu đầu vào, nếu lỗi thì trả lại trang create*/
        if($valid->fails()){
            
            return redirect()->back()->withErrors($valid)->withInput();

            }else{
                               
                
                $ask = $request->input('ask');

                $ciphertext = sodium_crypto_box_seal($ask, decrypt($key));

                $encrypted = encrypt($ciphertext);

                /*create a new array of data*/
                $ask = Ask::create([
                
                'ask' => $encrypted,
                /*Get the current user*/
                'usersid' => $id,

                ]);
                
                return redirect()->route('home.usersend', ['username' => $username])->with("message", "The message $encrypted to <b>$username </b> has been successfully sent!" );
       
        }


    }
   
   public function replyshow(Request $request, $username)
   {
        /*Lấy người dùng hiện tại đang truy cập*/
         // Lấy thông tin người dùng hiện tại
        

        // /*Thông tin bảng user*/
        // $user = User::where('username', $username)->first();

        // /*Nếu có người dùng này*/
        // if($user == true){
            
        //     $data['username'] = $user->username;
            
        //     $id = $user->id;

        //     $data['id'] = $user->id;

        // }else{

        //     $data['username'] = 'theuserisnotregistered';
        // }

        // if (strlen($username)) {
        //     echo "username có "
        // }

        $username = strtolower(trim($username));

        
        $user = User::where('username', $username)->first();
        if ($user == true) {
            $id = $user->id;
            $useraccess = $user->username;
            $data['username'] = strtolower(trim($useraccess));
        }else{
            
            $data['username'] = 'theuserisnotregistered';
            return redirect()->route('home.user', ['id'=>$username]);

                    
        }
       

        // $data['asks'] = Ask::where('usersid', '=', $id)->orderBy('id', 'desc')->paginate(3);

        $data['asks'] = Ask::where(['usersid' => $id, 'publish'=> 2])->orderBy('id', 'desc')->paginate(5);

        /*Get the value from cookie*/
        $data['guard'] = $request->cookie('guard');

          // Kiểm tra có login hay không
        if (Auth::check()) {
                $data['login'] ="logined";
                $id = \Auth::user()->id;
                $data['currentusername'] = \Auth::user()->username;
        }else{

            $data['login'] ="login";
        }
        
        return view('frontend/user/reply', $data);
   }


    public function replyverify(Request $request, $username)
    {
        
        $valid = Validator::make($request->all(), [
            
            'privatekey' => 'required|string|min:290|max:310',
                                 
        ],[
            /*Customeize the error*/
            
            'privatekey.min' => 'The private key seems too short!',
            'privatekey.max' => 'The private key seems too long!',
        ]);
             
        if($valid->fails()){
            
            return redirect()->back()->withErrors($valid)->withInput();

            }else{

                /*Retrieved from input*/
                $privatekeyinput = $request->input('privatekey');

                /*Store the information from the input "privatekey" into the session name "privatekey" into the session, using Laravel*/
                $request->session()->put('privatekey', $privatekeyinput);

        }
        
        // Lấy thông tin người dùng hiện tại
        $data['username']  = $username;

        /*Thông tin bảng user*/
        $user = User::where('username', $username)->first();

        /*Nếu có người dùng này*/
        if($user == true){
            
            $data['username'] = $user->username;
            
            $id = $user->id;

            $data['id'] = $user->id;

        }else{

            $data['username'] = 'theuserisnotregistered';
        }
            

       /*Lấy message để verify the key */
        $asks = Ask::where('usersid', '=', $id)->take(1)->get();

        /*If you dont have any message, you cannot go forward*/
        if(count($asks) === 0){
           
            return redirect()->back()->with('message', "Opp!, it appears that you donot have any message at the moment! Create one first! ");

        }else{

            foreach ($asks as $ask) {

            $question = $ask->ask;    
                                  
            }

        }

        /*Xác thực privatekey*/

        // Getsession và không xóa session có tên 'privatekey'
        $privatekeysession = $request->session()->get('privatekey');
       
        $decrypted = sodium_crypto_box_seal_open(decrypt($question), decrypt($privatekeysession));

        // dd($decrypted);

            
        if ($decrypted === false) {
            
             return redirect()->back()->with('message', "The privatekey is incorrect!");

        }else{

               $data['privatekey'] = $privatekeysession;
     
        }

        // Kiểm tra có login hay không
        if (Auth::check()) {
                $data['login'] ="logined";
                $id = \Auth::user()->id;
                $data['currentusername'] = \Auth::user()->username;
        }else{

            $data['login'] ="login";
        }

      
        /*Truyền dữ liệu sang route get để đọc, truyền dữ liệu session sang hàm readmessage
        @note truyền cookie từ request này sang request tiếp theo, gán giá trị 'guard' là 'vuong', hết hạn trong vòng 1 phút.

        */
        return redirect()-> route('home.userreplyread', ['id' => $username, 'token' => sha1(rand(1, 1000))])->with(["privatekey" => $data])->cookie('guard', 'vuong', 1);

    }

    public function replyread(Request $request, $username)
    {

         /*Lấy GET user hiện tại*/
        $user = User::where('username', $username)->first();

        
        /*Nếu người dùng này có trong database*/
        if($user != null){
            // $data['username'] = $user->username;
            $key = $user->publickey;
            $id = $user->id;

        }else{

            return redirect()->route('home.user', ['id'=>$username]);
            // $data['username'] = 'theuserisnotregistered';
            // $id = null;
        }
      
        
          /*dữ liệu này là flash session, lấy từ hàm read truyền qua*/
        $data = $request->session()->get('privatekey');

                                       
        /*Nếu không có dữ liệu từ route làm read*/
        if($data === NULL){
            
            return redirect()->route('home.user', ['id'=>$username]);

        }else{

            //Regenerating The Session ID
            $request->session()->regenerate();

            /**
            * giữ session từ flash session, thay vì xóa ngay in next request, vì khi redirect() route, session được chuyển sang từ controller này sang controller khác là dạng flash session
            * @see https://laravel.com/docs/5.6/session
            */
            $request->session()->reflash();
        }

        /*so sánh giữa username GET hiện tại và username từ FLASH data, phòng trường hợp truy cập trực username khác sau khi đã verify key */
        if($data['username']!=$username){

            return redirect()->route('home.user', ['id'=>$username]);

        }

        $data['total_ask'] = Ask::where('usersid', '=', $id)->get();

        /*Lấy message theo ID và phân trang, chỉ hiển thị những câu hỏi đặt publish là 2, truyền vào một array điều kiện*/

        // $data['asks'] = Ask::where('usersid', '=', $id)->orderBy('id', 'desc')->paginate(5);

        $data['asks'] = Ask::where(['usersid' => $id, 'publish'=> 2])->orderBy('id', 'desc')->paginate(5);



        /*Get the value from cookie*/
        $data['guard'] = $request->cookie('guard');


         // Kiểm tra có login hay không
        if (Auth::check()) {
                $data['login'] ="logined";
                $id = \Auth::user()->id;
                $data['currentusername'] = \Auth::user()->username;
        }else{

            $data['login'] ="login";
        }

        
        return view('frontend/user/replyread', $data);
    }


}
