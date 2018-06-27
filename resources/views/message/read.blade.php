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
                              <button type="submit" class="btn btn-primary btn-xs">Home</button>
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
            <span class="glyphicon glyphicon-envelope"> <b> Message </b><span class="badge"> {{count($total_message)}}
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
             <button type="submit" class="btn btn-danger btn-xs" id='thebutton'>Close</button>
            </form>

            <strong><span style="color: green; padding-left: 5px" id='Label1'></span></strong> left to read the messages.

            {{-- <input type='submit' id='thebutton' onclick="document.getElementById('Label2').innerHTML = 'Clicked!'"></input>  --}}
            
            {{-- <p id='Label2' style='color:red;'> </p> --}}

            {{-- End of Close the message button --}}
            {{-- Reference: https://jsfiddle.net/cmtqzwa7/138/ --}}

          </div>
        </div>


        <div class="panel-body">
        <table class="table">
            <th>No</th>
            {{-- <th>Message</th> --}}
            <th>Decrypted message</th>
            {{-- <th>Edit</th> --}}
            <th>Delete  
            </th>
                                   
             @foreach ($messages as $message)
              <tr>
                
                <td>{{$message->id}}</td>
                <td>

                {{-- {{$message->message}} --}}
                {{-- privatekey truyền sang từ session --}}
                {{sodium_crypto_box_seal_open(base64_decode($message->encrypted), base64_decode($privatekey))}}
                
                </td>
                                        
                <td><a href="{{route('message.delete', ['id' => $message->id])}}" class ='btn btn-danger btn-xs'
                onclick="event.preventDefault();

                window.confirm('Do you really want to delete it?') ? // nếu đồng ý
                document.getElementById('message-{{$message->id}}').submit() : 0; // nếu không đồng ý trả về 0

                ">Delete</a>

                <form action="{{route('message.delete', ['id'=> $message->id])}}" method="post" id="message-{{$message->id}}">
                  {{csrf_field()}}
                  {{method_field('delete')}}
                </form>

                </td>

                {{-- end of xoas --}}

            </tr>

            @endforeach      

        </table>                   

        <center>{{ $messages->links() }}</center>
     
     </div>


 </div>

</div>

</div>
</div>
</div>

<script type="text/javascript">
    time = 60;
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

</script>

@endsection
