@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Verify your identificatoin to edit your key:</b></div>

                    <div class="panel-body">

                        <p>Hi <b>{{$user['username']}}</b>! You are going to change your profile.</p>

                        {{-- thông báo tạo thêm người dùng mới, phải dùng session khi return từ controller create function --}}

                        @if(session('message'))

                        <div class="alert alert-danger">
                            {!!session('message')!!}
                        </div>
                        @endif

                         @if(session('tinnhan'))

                        <div class="alert alert-success">
                            {!!session('tinnhan')!!}
                        </div>
                        @endif

                        {{-- end of anouncement --}}
                                              
                        <form id = "editkey" class="form-horizontal" method="POST" style="display: inline!important;" action="{{route('key.editstore')}}">
                            {{ csrf_field() }}


                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Your current password <span style="font-weight: normal!important">(required):</span> </label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                              </div>

                            <div class="form-group{{ $errors->has('newpassword') ? ' has-error' : '' }}">
                                <label for="newpassword" class="col-md-4 control-label">Your new password <span style="font-weight: normal!important">(if you wish):</span></label>

                                <div class="col-md-6">
                                    <input id="newpassword" type="password" class="form-control" name="newpassword" required>

                                    @if ($errors->has('newpassword'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('newpassword') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                             <div class="form-group">
                                <label for="newpassword-confirm" class="col-md-4 control-label">Confirm new password  <span style="font-weight: normal!important">(required if you want to change password):</span> </label>

                                <div class="col-md-6">
                                    <input id="newpassword-confirm" type="password" class="form-control" name="newpassword-confirm" required>
                                </div>
                            </div>


                            <div class="form-group{{ $errors->has('privatekey') ? ' has-error' : '' }}">
                                <label for="privatekey" class="col-md-4 control-label">Your current private key:  <span style="font-weight: normal!important">(required if you want to retain your current data):</span>  </label>
                                <div class="col-md-6">
                                    <input id="privatekey" type="password" class="form-control" name="privatekey">

                                    @if ($errors->has('privatekey'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('privatekey') }}</strong>
                                    </span>
                                    @endif
                                </div>
                              </div>

                            <span style="padding-left: 35%">
                                

                                {{-- <button id="editkey"></button> --}}
                             

                             <a href="{{route('key.editstore')}}" class ='btn btn-danger btn-xs'
                                                  onclick="event.preventDefault();

                                                  window.confirm('Do you really want to edit your key? Your current data is likely being deleted if you do not provide the current private key! Click on Cancel to return back!') ? // nếu đồng ý
                                                  document.getElementById('editkey').submit() : 0; // nếu không đồng ý trả về 0, submit form có id editkey

                            ">Change</a>
                        
                           </span>


                        </form>


                        {{--cancel form  --}}
                        <form class="form-horizontal" method="get" style="display: inline!important; padding-left: 5px;" action="{{route('home')}}">
                                    {{-- {{ csrf_field() }} --}}
                                    <button type="submit" class="btn btn-warning btn-xs">
                                        Cancel
                                    </button>
                        </form>
                        {{-- cancel form --}}
                        <div style="padding-left: 3%;">
                            <br>
                            <br>
                            <strong>Note:</strong>
                            <p>1. The <strong>private key</strong> is not required to edit your profile. However, if you donot provide your current private key, your existing messages, status and questions will be subsequently <strong style="color: red">eradicated.</strong></p>
                            <p>2. Both new public and private keys will be created for encryption and decryption current and upcoming messages if your current private key is given. </p>
                            <p>3. You can leave both the new <strong>password</strong> and <strong>confirm new password</strong> inputs as blank if you do not want to change your current password.</p>
                            <p>4. For security purpose, you cannot change your current password and retain your current data if you are not able to provide your current private key.</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
