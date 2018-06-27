@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">

                <div class="panel-heading">
                 
                             <span class="glyphicon glyphicon-envelope"> Kiora <b>{{$user['username']}} !</b></span>

                               {{-- Home button --}}
                              <form class="form-horizontal" style="display: inline; padding-left: 10px" method="GET" action="{{ route('home') }}">
                              {{-- {{ csrf_field() }} --}}
                              <button type="submit" class="btn btn-default btn-xs">Home</button>
                              </form>

                             
                             {{-- logout --}}
                              <a href="{{ route('logout') }}"
                              onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">

                              <span style="padding-left: 10px">
                              <button type="button" class="btn btn-danger btn-xs">Logout</button>
                              </span>
                              </a>

                              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                              {{ csrf_field() }}
                              </form>
                              {{-- end of logout --}}
                </div>

                <div class="panel panel-default">

                    @if(session('message'))

                    <div class="panel-body">
                     <div class="alert alert-success">
                       {!!session('message')!!}

                       <button type="button" class="close" data-dismiss="alert" aria-label="Close">

                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>

            </div>
            @endif
        </div>

        <div class="panel panel-default">
          <div class="panel-body">

            <span class="glyphicon glyphicon-envelope"> <b> Questions </b><span class="badge"> {{count($total_asks)}}

            </span></span>

           
            {{-- create new message --}}
           {{--  <form class="form-horizontal" style="display: inline; padding-left: 10px" method="GET" action="{{ route('message.create') }}">
                

                <button type="submit" class="btn btn-primary btn-xs">
                    Write
                </button>
            </form> --}}

            {{-- End of create the message button --}}

             {{-- Close the message --}}
            
             <form class="form-horizontal" style="display: inline; padding-left: 15px" method="GET" action="{{ route('home') }}">
             <button type="submit" class="btn btn-danger btn-xs" id='thebutton'>
                    Close  
                </button>
            </form>

            <strong><span style="color: green; padding-left: 5px" id='Label1'></span></strong> left to read and reply the questions.

            {{-- <input type='submit' id='thebutton' onclick="document.getElementById('Label2').innerHTML = 'Clicked!'"></input>  --}}
            
            {{-- <p id='Label2' style='color:red;'> </p> --}}

            {{-- End of Close the message button --}}
            {{-- Reference: https://jsfiddle.net/cmtqzwa7/138/ --}}

          </div>
        </div>

        <div class="panel-body">
          @if($guard==true)

                    <table class="table">
                    <th>No</th>
                    <th>Decrypted message</th>
                    <th>Reply</th>
                    <th>Delete</th>
                    <th>Display</th>
                    @foreach ($asks as $message)
                    <tr>
                        
                        <td>{{$message->id}}</td>
                        <td>

                        {{-- privatekey truyền sang từ session --}}
                        <span style='font-family: "roboto","Helvetica Neue",Helvetica,Arial,sans-serif';>{{sodium_crypto_box_seal_open(base64_decode($message->ask), base64_decode($privatekey))}}</span
                                                
                        </td>

                        {{-- Reply --}}

                        @if($message->reply == true)                        
                              <td>

                                 <span style="padding-left: 12%;"><button type="button" class="btn btn-primary btn-xs" data-toggle="collapse" data-target="#readreply{{$message->id}}">Edit</button>

                              </td>
                        @else
                               <td>

                                 <span style="padding-left: 12%;"><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#readreply{{$message->id}}">Reply</button>

                              </td>
                       @endif  

                       {{-- Xóa --}}
                        <td><a href="{{route('ask.deleteask', ['id' => $message->id])}}" class ='btn btn-warning btn-xs'
                        onclick="event.preventDefault();

                        window.confirm('Do you really want to delete it?') ? // nếu đồng ý
                        document.getElementById('message-{{$message->id}}').submit() : 0; // nếu không đồng ý trả về 0

                        ">Delete</a>

                        <form action="{{route('ask.deleteask', ['id'=> $message->id])}}" method="post" id="message-{{$message->id}}">
                          {{csrf_field()}}
                          {{method_field('delete')}}
                        </form>
                        </td>{{-- end of xoas --}}


                       {{-- Publish --}} 
                      <td>
                       @if($message->publish == 1)

                            <form action="{{route('ask.publish', ['id'=> $message->id])}}" method="POST" id="message-{{$message->id}}">
                              {{csrf_field()}}

                              <input type="hidden" value ="2" name="publish" id ='publish'>
                              <button class ='btn btn-success btn-xs'>Publish</button>
                            </form>
                        @else

                            <form action="{{route('ask.publish', ['id'=> $message->id])}}" method="POST" id="message-{{$message->id}}">
                            {{csrf_field()}}

                            <input type="hidden" value ="1" name="publish" id ='publish'>
                            <button class ='btn btn-warning btn-xs'>Unpublish</button>
                          </form>

                        @endif

                        </td>                                        
                        {{-- end of publish --}}
                     

                    </tr>
                    <tr> 
                          <td></td>
                                    {{-- Nếu có comment thì hiện ra, không thì hiện bảng nhập comment --}}
                                    @if($message->reply == true)
                                    <td>
                                      
                                          <div style="padding-left: 5%; font-family: Helvetica, Arial;" id="readreply{{$message->id}}" class="collapse">
                                                
                                                <span style='font-family: "roboto","Helvetica Neue",Helvetica,Arial,sans-serif';>
                                                {{sodium_crypto_box_seal_open(base64_decode($message->reply['reply']), base64_decode($privatekey))}}
                                                </span>
                                                 <br>
                                                 <br>                                                                                      
                                                
                                                {{--Hàm hidedisplay sẽ ẩn hiện phần sửa reply còn data-toggle="collapse" sẽ ẩn chính div hiện tại khi click vào --}}

                                                  <button class="btn btn-default btn-xs" data-toggle="collapse" data-target="#readreply{{$message->id}}" onclick="hidedisplay('readreplyedit{{$message->reply['id']}}')">Edit</button>


                                                  <a href="{{route('ask.deletereply', ['id' => $message->reply['id']])}}" class ='btn btn-warning btn-xs'
                                                  onclick="event.preventDefault();

                                                  window.confirm('Do you really want to delete it?') ? // nếu đồng ý
                                                  document.getElementById('message-{{$message->reply['id']}}').submit() : 0; // nếu không đồng ý trả về 0

                                                  ">Delete</a>
                                                  <form action="{{route('ask.deletereply', ['id'=> $message->reply['id']])}}" method="post" id="message-{{$message->reply['id']}}">
                                                    {{csrf_field()}}
                                                    {{method_field('delete')}}
                                                  </form> 
                                        
                                        </div>

                                      
                                        {{-- </td> --}}

                                        
                                        {{--  sửa edit reply --}}
                                        <br>
                                        <div id="readreplyedit{{$message->reply['id']}}" class="collapse">
                                                <form class="form-horizontal" style="display: inline; margin:0px; padding: 0px" method="POST" action="{{route('ask.replyedit')}}">
                                                    {{ csrf_field() }}
                                                   
                                                    <textarea style="padding: 0px; margin: 0px; display: inline-block;" id="reply" class="form-control" rows="3" name="reply" required>
                                                   
                                                   {{trim(sodium_crypto_box_seal_open(base64_decode($message->reply['reply']), base64_decode($privatekey)))}}
                                                    
                                                    </textarea>
                                                    
                                                    <input type="hidden" name="replyid" value="{{$message->reply['id']}}">
                                                    <br>
                                                    <span style="padding-left: 30%"><button type="submit" class="btn btn-primary btn-xs">Update</button></span>
                                                </form>
                                                      
                                              {{--   <span style="padding-left: 15px;"><button 

                                                  onClick="(function(){    
                                                  document.getElementById('readreplyedit{{$message->reply['id']}}').style.display = 'none';
                                                  
                                                  return false;
                                              })();return false;" 

                                              class="btn btn-default btn-xs" data-toggle="collapse" data-target="#readreplyedit{{$message->id}}">Cancel</button>
                                              Click to hide https://jsfiddle.net/3r9mf58t/9/ 
                                                    https://www.w3schools.com/howto/tryit.asp?filename=tryhow_js_toggle_hide_show
                                                    --}}

                                              <button class="btn btn-default btn-xs" onclick="hidedisplay('readreplyedit{{$message->reply['id']}}')">Cancel</button>
                                          </div>
                                        </td>

                                        {{-- end of sửa reply--}}

                                    @else
                                        <td>
                                          <div id="readreply{{$message->id}}" class="collapse">
                                                <form class="form-horizontal" style="display: inline;" method="POST" action="{{ route('ask.reply')}}">
                                                    {{ csrf_field() }}
                                                    <textarea id="reply" class="form-control" rows="4" cols= "30" name="reply" required></textarea>
                                                    <input type="hidden" name="asksid" value="{{$message->id}}">
                                                    <span style="padding-left: 40%"><button type="submit" class="btn btn-primary btn-xs">Reply</button></span>
                                                </form>
                                                      
                                                <span style="padding-left: 15px;"><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#readreply{{$message->id}}">Cancel</button>
                                          </div>
                                    <td>
                                    @endif
                            
                    </tr> {{-- End of Reply --}}

                  
                    @endforeach      
                </table>                   
                <center>{{ $asks->links() }}</center>
          @else
              <span class="decryptedmesssage" style="font-weight: bold; padding-left: 2%; padding-top: 3%">Your session is closed. Please reauthorize your access with the private key given.</span>
          @endif
     </div>
</div>
</div>
</div>
</div>
</div>

<script type="text/javascript">
    time = 480;
    interval = setInterval(function() {
        time--;
        document.getElementById('Label1').innerHTML = "" + time + " seconds"
        if (time == 0) {
            // stop timer
            clearInterval(interval);
            // click
            document.getElementById('thebutton').click();            
        }
    }, 1000)

    /*ẩn hiện*/
    function show(target) {
        document.getElementById(target).style.display = 'block';
      }

    function hide(target) {
        document.getElementById(target).style.display = 'none';
      }

    /*end of ẩn hiện*/

    function hidedisplay(target) {
    var x = document.getElementById(target);
    if (x.style.display === "block") {
        x.style.display = "none";
    } else {
        x.style.display = "block";
      }
  }


</script>





@endsection
