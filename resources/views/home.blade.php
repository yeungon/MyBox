@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">

                <div class="panel-heading">
                 
                 <span class="glyphicon glyphicon-envelope"> Kiora <b>{{$users['username']}} !</b></span>


                 <form class="form-horizontal" style="display: inline !important" method="GET" action="{{ route('key.edit') }}">
                    {{ csrf_field() }}

                    <button type="submit" class="btn btn-default btn-xs" style="padding-right: 15px; padding-left:  13px;">
                       Password
                    </button>
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

        <b style="padding-left: 10px">Manage your keys:</b>
        <div class="panel-body">

                {{-- Edit your account  --}}
                1. <form class="form-horizontal" style="display: inline !important" method="GET" action="{{ route('key.edit') }}">
                          <button type="submit" class="btn btn-default btn-xs" style="padding-right: 15px; padding-left:  13px;">
                             Edit
                          </button>
                    </form>
                Edit your key. You need to provide your current password and private key to change your account.
                {{-- end of the deleting a new masterkey section --}}
                <br>
                <br>
               
        </div>



        <div class="panel panel-default">
          <div class="panel-body">
            <span class="glyphicon glyphicon-envelope"> <b> Status </b><span class="badge"> {{count($total_message)}}
            </span></span>

           {{-- Write button--}}
            <span style="padding-left: 2%;"><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#write">Write</button></span>

            <span style="padding-left: 5%px;"><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#demo">Read</button></span>
            <br>


            <div id="write" class="collapse">

            <br>
                          
                   <span style="padding-left: 15%">Hi <strong>{{$users->username}}</strong>! Write new update: </span>
                    <br>
                    <br>
                  
                  <form class="form-horizontal" style=" display:inline!important;" method="POST" action="{{ route('message.store') }}">
                  
                   {{ csrf_field() }}
                        
                        <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
                                {{-- <label for="privatekey" class="col-md-2 control-label">The private key: </label> --}}

                                <div class="col-md-8">
                                    
                                    {{-- <input id="privatekey" type="password" class="form-control" name="privatekey" required> --}}

                                    <span style="margin-left: 20%"><textarea style="margin-left: 25%" class="form-control" name="message" {{-- id="editor" --}} {{-- required --}} placeholder="You can write some basic HTML code."></textarea></span>

                                                                        
                                    
                                    @if ($errors->has('message'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('message') }}</strong>
                                    </span>
                                    @endif

                                </div>
                              </div>

                                    <span style="padding-left: 42%">
                                    <button type="submit" class="btn btn-primary btn-xs">
                                    Create
                                    </button>
                                    </span>
                    </form>
           
            {{-- End of Read the message button --}}
                      {{--cancel form  --}}
                      {{-- <form class="form-horizontal" method="get" style="display: inline!important; padding-left: 5px;" action="{{route('home')}}"> --}}
                                                                                                          
                      <button style="display:inline!important;" data-toggle="collapse" data-target="#write" class="btn btn-default btn-xs">Cancel</button>


                      {{-- </form> --}}
                      {{-- cancel form --}}

         </div> {{-- end of popup --}}


           {{-- End of write button --}}

           
           {{-- Read button --}}
          {{--   @if(session('message'))

                        <div class="alert alert-danger">
                            {{session('message')}}
                        </div>
            @endif --}}

            {{-- Báo lỗi privatekey  --}}
            @if ($errors->has('privatekey'))
              <span class="help-block">
              <br>
              <div class="alert alert-danger">
                  {{ $errors->first('privatekey') }}
              </div>
              </span>
            @endif

            {{-- End of báo lỗi privatekey  --}}

            <br>
            <div id="demo" class="collapse">
                          
                    Decrypte your encrypted message with private key.
                    <br>
                      <form class="form-horizontal" style="display: inline; padding-left: 20px" method="POST" action="{{ route('key.authenticate') }}">
                        {{ csrf_field() }}
                        
                        <div class="form-group{{ $errors->has('privatekey') ? ' has-error' : '' }}">
                                <label for="privatekey" class="col-md-2 control-label">The private key: </label>

                                <div class="col-md-6">
                                    
                                    <input id="privatekey" type="password" class="form-control" name="privatekey" required>
                                    
                                    @if ($errors->has('privatekey'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('privatekey') }}</strong>
                                    </span>
                                    @endif

                                </div>
                              </div>

                                    <span style="padding-left: 35%">
                                    <button type="submit" class="btn btn-primary btn-xs">
                                    Verify
                                    </button>
                                    </span>
                    </form>
           
            {{-- End of Read the message button --}}
            {{--cancel form  --}}
                      {{-- <form class="form-horizontal" method="get" style="display: inline!important; padding-left: 5px;" action="{{route('home')}}">
                                                                                                          
                      <button data-toggle="collapse" data-target="#demo" class="btn btn-default btn-xs">Cancel</button>
                      </form> --}}

                      <button data-toggle="collapse" data-target="#demo" class="btn btn-default btn-xs">Cancel</button>
          {{-- cancel form --}}

         </div> {{-- end of popup --}}

         </div>
        </div>

        {{-- Ask and Reply --}}
        <div class="panel panel-default">
            <div class="panel-body">
              <span class="glyphicon glyphicon-envelope"> <b> Questions </b><span class="badge"> {{count($total_ask)}}
              </span></span>

              <span style="padding-left: 15px;"><button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#readreply">Read & Reply</button>


            </div>
        </div>

        {{-- Báo lỗi privatekey  --}}
            @if ($errors->has('privatekey'))
              <span class="help-block">
              <br>
              <div class="alert alert-danger">
                  {{ $errors->first('privatekey') }}
              </div>
              </span>
            @endif

            {{-- End of báo lỗi privatekey  --}}

            <br>
            <div id="readreply" class="collapse">
                          
                   <p style="padding-left: 3%"> Decrypte your encrypted message with private key.</p>
                   <form class="form-horizontal" style="display: inline; padding-left: 10%" method="POST" action="{{ route('ask.authenticate') }}">
                        {{ csrf_field() }}
                        
                        <div class="form-group{{ $errors->has('privatekey') ? ' has-error' : '' }}">
                                <label for="privatekey" class="col-md-2 control-label">The private key: </label>

                                <div class="col-md-6">
                                    
                                    <input id="privatekey" type="password" class="form-control" name="privatekey" required>
                                    
                                    @if ($errors->has('privatekey'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('privatekey') }}</strong>
                                    </span>
                                    @endif

                                </div>
                              </div>

                                    <span style="padding-left: 35%">
                                    <button type="submit" class="btn btn-primary btn-xs">
                                    Verify
                                    </button>
                                    </span>
                    </form>
           
            {{-- End of Read the message button --}}
            {{--cancel form  --}}
                      {{-- <form class="form-horizontal" method="get" style="display: inline!important; padding-left: 5px;" action="{{route('home')}}"> --}}
                                                                                                          
                      <button data-toggle="collapse" data-target="#readreply" class="btn btn-default btn-xs">Cancel</button>
                      {{-- </form> --}}
          {{-- cancel form --}}

         </div> {{-- end of popup --}}


        {{-- end of ask and reply --}}


        <div class="panel-body">
           
        </div>


</div>
</div>
</div>
</div>
</div>


@endsection

