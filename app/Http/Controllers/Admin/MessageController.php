<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\User;

use App\Message;


class MessageController extends Controller
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
                $id = \Auth::user()->id;

                $data['user']  = User::where('id', $id)->first();

                /*get the username of the current usser
                Return an array
                */


//        $secret_key = sodium_crypto_secretbox_keygen();
//
//        echo "Khóa mật: ", encrypt($secret_key);
//
//        echo "<br>";
//
//        $khoachinh = 'sfxlY+DVO72SZtUdcdMmoTIK3wibkyn6IY5u7PZhJC0=';

        $mySigningKeypair = sodium_crypto_sign_keypair();

        $secretKey = sodium_crypto_sign_secretkey($mySigningKeypair);
        $publicKey = sodium_crypto_sign_publickey($mySigningKeypair);

        echo "Khóa phụ:", encrypt($publicKey);

        echo "<br>";

        echo "Khóa chính:", encrypt($secretKey);

        echo "<br>";

        $message = 'nội dung thư authenticate me';

        $testkhoaphu = 'He3TJUMfOCt7PzsCfsQ8nCzGy5Jnm4SMzb9QkZhMPZc=';
        $testkhoachinh = 'fymG0vNV9LELf3okVGBgEJNav2+8RMOKbw2aloItjiod7dMlQx84K3s/OwJ+xDycLMbLkmebhIzNv1CRmEw9lw==';

    /* Sign the message, using your secret key (which is NOT given out): */
        $signature = sodium_crypto_sign_detached($message, $secretKey);

    //echo encrypt($signature);


    /* Now validate the signature with your public key (which IS given out): */
    if (sodium_crypto_sign_verify_detached($signature, $message, $publicKey)) {

        echo "thư chuẩn <br>";

    } else {
        
        throw new Exception('Invalid signature. Do not trust the message.');
    }



    /* On Node B: */
    $bobKeypair = sodium_crypto_box_keypair();
    $bobPublicKey = sodium_crypto_box_publickey($bobKeypair);
        // Then share $bobPublicKey with Node A

    # Transmission:

    /* Sending from Node A to Node B */
        $message = 'Hi there! :)';
        $ciphertext = sodium_crypto_box_seal($message, $bobPublicKey);

    /* On Node B, receiving an encrypted message from Node A */
        $decrypted = sodium_crypto_box_seal_open($ciphertext, $bobKeypair);




        $key = 'mpBPY6vFXXvaJ6fAGavVYjETdI7p2uMrcmvXvtnvWj8=';

        $private = 'Gl+gqRll+1ohNzb3TpREixlg/aMNmty9wfC/RjK6Rs6akE9jq8Vde9onp8AZq9ViMRN0juna4ytya9e+2e9aPw==';

               

        $message = "Nội dung thư mật là gì? có ai biết? khá là vất vả :-) nội dung thư mới";

        /*mỗi lần cập nhât message là ciphertext sẽ thay đổi*/

        $ciphertext = sodium_crypto_box_seal($message, decrypt($key));

        $ciphertextdata = encrypt($ciphertext);

        echo "<br>";

        echo  "lưu vào database: ", $ciphertextdata;


    $noidung = 'atevuMkizm3Mn75NudZkC1DBDEVqjF8OvregkTtmd1b7Jp3e66c93YgxzxsA5buR/wL5W4f3JJxdOmMcuLwrvUvdEWQ9GbylII/DROKz2G9gin1y/yp8O6ZBLAig6YMWYjsTf1t8lPWWSxoJ1YjtasUv2Yx9MJlL2TscF5PNZYhuRbFOlFFQp3O5ug==';

    /* On Node B, receiving an encrypted message from Node A */


    $data = decrypt($noidung);

    echo "<br>";


    echo $decrypted = sodium_crypto_box_seal_open($data, decrypt($private));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         /*Get the current user*/
         $user = \Auth::user();

         $data["creates"] = null;

         //$user->publickey = null;

         //dd($user->publickey);

         if (strlen($user->publickey) === 0) {
             
             $data['creates'] = "You need to create your keys first before you can write the message.";
         }

         //dd($data['creates']);

        return view('message/create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
/*        dd($request->all()); */

        /*
        
        *hàm dd để debug
        *Khi nhấn nút click sẽ hiện ra giá trị form gửi lên. :-)
        */

        
        /*xác thực dữ liệu đầu vào của form create chuyên mục*/
        $valid = Validator::make($request->all(), [
            'message' => 'required',
           
            
        ],[
            /*mã hóa tiếng việt*/
            'message.required' => 'Vui lòng nhập message',
        
        ]);

        /*xử lý dữ liệu đầu vào, nếu lỗi thì trả lại trang create*/
        if($valid->fails()){

            return redirect()->back()->withErrors($valid)->withInput();
            /*nếu dữ liệu okay, tạo mảng dữ liệu, từ nguồn */
        }else{
                
                /*Get the $id and public key a from the current user, {user table}*/
                $id = \Auth::user()->id;
                
                $publickey = \Auth::user()->publickey;

               
                $message = $request->input('message');

                $ciphertext = sodium_crypto_box_seal($message, decrypt($publickey));

                $encrypted = encrypt($ciphertext);


                /*create a new array of data*/
                $message = Message::create([

                'message' => $message,
                'encrypted' => $encrypted,
                /*Get the current user*/
                'usersid' => $id,
                                     
                ]);

                return redirect()-> route('home')->with('message', "The message <pre>$message->encrypted</pre> has been successfully created.");
        }

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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
      
      /*Lấy session từ route trước sau khi xóa và truyền ngược lại route('key.read')*/
      $data = $request->session()->get('privatekey');

      $number = $id;

      $message = Message::find($id);

            if($message !==NULL){

                $message->delete();

                return redirect()-> route('key.read', [$id = sha1(rand(1, 1000))."protected"])->with('message', "Successfully delete the messsage numbered $number!")->with(["privatekey" => $data]);
            }

            return redirect()-> route('key.read', [$id = sha1(rand(1, 1000))."protected"])->with(["privatekey" => $data]);
    }
}
