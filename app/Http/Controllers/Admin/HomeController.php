<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Message;
use App\Ask;

class HomeController extends Controller
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
    public function index()
    {

         $id = \Auth::user()->id;

        /*get the username of the current usser
          Return an array
        */

        $data['users']  = User::where('id', $id)->first();

        //$flight = App\Flight::where('active', 1)->first();

        /**
        *@category Lấy ra các message mà usersid = $id của người dùng hiện tại
        *@note dùng mối quan hệ trong Model
        */

        $data['total_message'] = Message::where('usersid', '=', $id)->get();

        $data['total_ask'] = Ask::where('usersid', '=', $id)->get();


        /*Lấy message theo ID và phân trang*/

        $data['message'] = Message::where('usersid', '=', $id)->orderBy('id', 'desc')->paginate(7);

        
        /*Lấy tất cả message*/
        //$data['total_message'] = Message::all();

        /*Lấy message và phân trang*/

        //$data['message'] = Message::orderBy('id', 'desc')->paginate(15);
        
        return view('home', $data);
    }
}
