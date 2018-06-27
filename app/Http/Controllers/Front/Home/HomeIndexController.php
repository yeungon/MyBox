<?php

namespace App\Http\Controllers\Front\Home;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Message;
use Illuminate\Support\Facades\Auth;

class HomeIndexController extends Controller
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
    public function index()
    {

        // $id = \Auth::user()->id;
        // $data['users']  = User::where('id', $id)->first();
        // $data['total_message'] = Message::where('usersid', '=', $id)->get();
        // $data['message'] = Message::where('usersid', '=', $id)->orderBy('id', 'desc')->paginate(7);

        // Kiểm tra có login hay không
        if (Auth::check()) {
                $data['login'] ="logined";
                $id = \Auth::user()->id;
                $data['username'] = \Auth::user()->username;
        }else{

            $data['login'] ="login";
        }
      
        
        /*Lấy tất cả message*/
        //$data['total_message'] = Message::all();

        /*Lấy message và phân trang*/

        //$data['message'] = Message::orderBy('id', 'desc')->paginate(15);
        
        return view('frontend/home/index', $data);
    }
}
