<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\User;
use App\Message;
use App\Ask;
use App\Reply;
use Cookie;


class AskController extends Controller
{

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


    public function index(){

         /*Get the current user*/
  
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
        $messages = Ask::where('usersid', '=', $id)->take(1)->get();

        /*If you dont have any message, you cannot go forward*/
        if(count($messages) === 0){
           
            return redirect()->back()->with('message', "Opp!, it appears that you donot have any question at the moment! Create one first! ");

        }else{

            foreach ($messages as $message) {

            $encrypted = $message->ask;
                                 
            }

        }
        
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
        
        return redirect()-> route('ask.read')->with(["privatekey" => $data])->cookie('guard', 'vuong', 8);

    }


    public function readAsk(Request $request)
    {   
        
        /*dữ liệu này là flash session, lấy từ hàm authenticate*/
        $data = $request->session()->get('privatekey');

        /*Lấy dữ liệu từ flash session và xóa luôn dữ liệu của session đã lấy*/

        //$data = $request->session()->pull('privatekey', $data);

        /*thử gán sang _SESSION php*/
        // $_SESSION['key'] = $data;
       
        // $data['key'] = $_SESSION['key']['privatekey'];

        
        if($data === NULL){
            
            return redirect()->route('home');

        }else{

            //Regenerating The Session ID
            $request->session()->regenerate();

            /**
            * giữ session từ flash session, thay vì xóa ngay in next request, vì khi redirect() route, session được chuyển sang từ controller này sang controller khác là dạng flash session
            * @see https://laravel.com/docs/5.6/session
            */
            $request->session()->reflash();
        }
        
        /*Get the value from cookie*/
        $data['guard'] = $request->cookie('guard');


        /*Getting the information from the current user*/
        $user = \Auth::user();

        $id = $user->id;

        $data['user']  = User::where('id', $id)->first();
        
        $data['total_asks'] = Ask::where('usersid', '=', $id)->get();
        
        $data ['asks'] = Ask::where('usersid', '=', $id)->orderBy('id', 'desc')->paginate(7);

        /*Get the reply*/
       /*Liên kết 1 - 1, trong Ask có method reply(), reply này trỏ tới cột reply trong bảng Reply*/
       // $replies = Ask::find(29)->reply->usersid;

       // dd($replies);


       return view('askreply/read', $data);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function Reply(Request $request)
    {
        
         /*Lấy session sau khi xóa và truyền ngược lại route('ask.read')*/
        $data = $request->session()->get('privatekey');



        $valid = Validator::make($request->all(), [
            'reply' => 'required',
                       
            
        ],[
            /*mã hóa tiếng việt*/
            'reply.required' => 'Please type your reply'
        ]);

        /*xử lý dữ liệu đầu vào, nếu lỗi thì trả lại trang create*/
        if($valid->fails()){

            return redirect()->back()->withErrors($valid)->withInput();
            /*nếu dữ liệu okay, tạo mảng dữ liệu, từ nguồn */
        }else{
                
                /*Get the $id and public key a from the current user, {user table}*/
                $id = \Auth::user()->id;
                
                $publickey = \Auth::user()->publickey;

                $message = $request->input('reply');

                $asksid = $request->input('asksid');

                $ciphertext = sodium_crypto_box_seal($message, decrypt($publickey));

                $encrypted = encrypt($ciphertext);


                /*create a new array of data*/
                $reply = Reply::create([

                'reply' => $encrypted,
                'asksid' => $asksid,
                'usersid' => $id,
                                     
                ]);

                return redirect()-> route('ask.read')->with('message', "Response to the question numbered $asksid is being made.")->with(["privatekey" => $data]);
        }
    }

    public function publish(Request $request, $id)
    {

        $asksid = $id;

        $publish = $request->input('publish');
        
        $asks = Ask::find($asksid);

        $asks->publish = $publish;

        $asks->save();

        /*Lấy session sau khi xóa và truyền ngược lại route('ask.read')*/
        $data = $request->session()->get('privatekey');

        /*tùy chỉnh thông báo*/

        if($publish =='2'){
            
            $message = "The question $asksid is being published!";

        }else{

            $message = "The question $asksid is being saved as pending!";
        }


        return redirect()-> route('ask.read')->with('message', $message)->with(["privatekey" => $data]);


    }

     public function updateReply(Request $request)
    {
        
         $valid = Validator::make($request->all(), [
            'reply' => 'required',
                       
            
        ],[
            /*mã hóa tiếng việt*/
            'reply.required' => 'Please type your reply'
        ]);

        /*Lay duwx lieu tuf session truyen nguoc lai*/
        
        $data = $request->session()->get('privatekey');

        /*xử lý dữ liệu đầu vào, nếu lỗi thì trả lại trang*/
        if($valid->fails()){

            return redirect()->back()->withErrors($valid)->withInput();
            /*nếu dữ liệu okay, tạo mảng dữ liệu, từ nguồn */
        }else{
                
                /*Get the $id and public key a from the current user, {user table}*/
                $id = \Auth::user()->id;
                
                $publickey = \Auth::user()->publickey;

                $replymessage = $request->input('reply');

                $replyid = $request->input('replyid');


                $ciphertext = sodium_crypto_box_seal($replymessage, decrypt($publickey));

                $encrypted = encrypt($ciphertext);

                $reply = Reply::find($replyid);

                $reply->reply = $encrypted;

                $reply->save();

                
                return redirect()-> route('ask.read')->with('message', "Response is updated.")->with(["privatekey" => $data]);
             }
           
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletereply(Request $request, $id)
    {
        /*Lấy session sau khi xóa và truyền ngược lại route('ask.read')*/
        $data = $request->session()->get('privatekey');

        $reply = Reply::find($id);
        
        /*Lấy người dùng hiện tại*/
        $id = \Auth::user()->id;

        /*Lấy reply của ask hiện tại*/
        //$reply = Reply::where(['usersid' => $id, 'asksid' => $id]);

            
            if($reply !==NULL){

                /*Xóa luôn data theo mối quan hệ 1 - 1
                https://www.easylaravelbook.com/blog/introducing-the-one-to-one-relationship/
                */
                $reply->delete();
                
                return redirect()-> route('ask.read')->with('message', "Successfully delete the question numbered $id!")->with(["privatekey" => $data]);
            }

            return redirect()-> route('ask.read')->with(["privatekey" => $data]);

    /*Bổ sung phần xóa reply https://www.easylaravelbook.com/blog/introducing-the-one-to-one-relationship/
    https://laracasts.com/discuss/channels/eloquent/laravel-relationship-foreign-key-delete?page=1
    */
    }

    
    public function deleteask(Request $request, $id)
    {
        /*Lấy session sau khi xóa và truyền ngược lại route('ask.read')*/
        $data = $request->session()->get('privatekey');

        $ask = Ask::find($id);

        /*Lấy người dùng hiện tại*/
        $id = \Auth::user()->id;

        /*Lấy reply của ask hiện tại*/
        //$reply = Reply::where(['usersid' => $id, 'asksid' => $id]);

        

            if($ask !==NULL){

                /*Xóa luôn data theo mối quan hệ 1 - 1
                https://www.easylaravelbook.com/blog/introducing-the-one-to-one-relationship/
                */

                /*Xóa reply trước sau đó xóa 
                # Dùng eloquent, quan hệ 1 - 1 
                ask*/
                $ask->reply()->delete();

                /*Xóa ask*/
                $ask->delete();
                
                return redirect()-> route('ask.read')->with('message', "Successfully delete the question numbered $id!")->with(["privatekey" => $data]);
            }

            return redirect()-> route('ask.read')->with(["privatekey" => $data]);

    /*Bổ sung phần xóa reply https://www.easylaravelbook.com/blog/introducing-the-one-to-one-relationship/
    https://laracasts.com/discuss/channels/eloquent/laravel-relationship-foreign-key-delete?page=1
    */
    }





        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   

   
}
