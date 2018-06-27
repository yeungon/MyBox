@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Verify your identificatoin to edit your key:</b></div>

                    <div class="panel-body">

                        <p>Hi <b>{{$user['username']}}</b>! You are going to change your profile.</p>

                        {{-- thông báo tạo thêm người dùng mới, phải dùng session khi return từ controller create function --}}

                        @if(session('message'))

                        <div class="alert alert-danger">
                            {{session('message')}}
                        </div>
                        @endif

                        {{-- end of anouncement --}}
                                              
                        <form class="form-horizontal" method="POST" style="display: inline!important;" action="{{route('key.editstore')}}">
                            {{ csrf_field() }}


                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Your current password: </label>

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
                                <label for="newpassword" class="col-md-4 control-label">Your new password: </label>

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
                                <label for="newpassword-confirm" class="col-md-4 control-label">Confirm new password</label>

                                <div class="col-md-6">
                                    <input id="newpassword-confirm" type="password" class="form-control" name="newpassword-confirm" required>
                                </div>
                            </div>


                            <div class="form-group{{ $errors->has('privatekey') ? ' has-error' : '' }}">
                                <label for="privatekey" class="col-md-4 control-label">Your current private key: </label>
                                <div class="col-md-6">
                                    <input id="privatekey" type="password" class="form-control" name="privatekey">

                                    @if ($errors->has('privatekey'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('privatekey') }}</strong>
                                    </span>
                                    @endif
                                </div>
                              </div>

                            <span style="padding-left: 255px">
                                <button type="submit" class="btn btn-primary btn-xs">Edit</button>
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
                            <br>
                            <strong>Noted:</strong>
                            <p>1. The private key is not required to edit your profile. However, if you cannot provide your current private key, your existing messages will be subsequently eradicated.</p>
                            <p>2. Both new public and private keys will be created for encryption and decryption current and upcoming messages. </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
