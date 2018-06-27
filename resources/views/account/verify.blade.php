@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Verify your identificatoin to read the message:</b></div>

                    <div class="panel-body">

                        <p>Hi <b>{{$user['username']}}</b>! You need to provide your private key to unlock the encrypted message.</p>

                        {{-- thông báo tạo thêm người dùng mới, phải dùng session khi return từ controller create function --}}

                        @if(session('message'))

                        <div class="alert alert-danger">
                            {{session('message')}}
                        </div>
                        @endif

                        {{-- end of anouncement --}}
                                              
                        <form class="form-horizontal" method="POST" style="display: inline!important;" action="{{route('key.authenticate')}}">
                            
                        {{csrf_field()}}


                            <div class="form-group{{ $errors->has('privatekey') ? ' has-error' : '' }}">
                                <label for="privatekey" class="col-md-4 control-label">The private key: </label>

                                <div class="col-md-6">
                                    
                                    <input id="privatekey" type="password" class="form-control" name="privatekey" required>
                                    
                                    @if ($errors->has('privatekey'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('privatekey') }}</strong>
                                    </span>
                                    @endif

                                </div>
                              </div>

                                    <span style="padding-left: 255px">
                                    <button type="submit" class="btn btn-primary btn-xs">
                                    Verify
                                    </button>
                                    </span>

                        </form>

                        {{--cancel form  --}}
                        <form class="form-horizontal" method="get" style="display: inline!important; padding-left: 5px;" action="{{route('home')}}">
                                                                                                           
                                    <button type="submit" class="btn btn-warning btn-xs">
                                        Cancel
                                    </button>
                        </form>
                        {{-- cancel form --}}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
