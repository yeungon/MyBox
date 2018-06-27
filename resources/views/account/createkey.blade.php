@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Create your private and public keys:</b></div>

                    <div class="panel-body">

                        <p>Hi <b>{{$user['username']}}</b>! You need to provide your authorization to create your private and public key.</p>

                        {{-- thông báo tạo thêm người dùng mới, phải dùng session khi return từ controller create function --}}

                        @if(session('message'))

                        <div class="alert alert-danger">
                            {{session('message')}}
                        </div>
                        @endif
                        {{-- end of anouncement --}}
                                              
                        <form class="form-horizontal" style="display: inline!important;" method="POST" action="{{route('key.store')}}">
                            {{ csrf_field() }}


                            <div class="form-group{{ $errors->has('masterkey') ? ' has-error' : '' }}">
                                <label for="masterkey" class="col-md-4 control-label">Maskerkey: </label>

                                <div class="col-md-6">
                                    <input id="masterkey" type="password" class="form-control" name="masterkey" required>

                                    @if ($errors->has('masterkey'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('masterkey') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                                <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">

                                    <button type="submit" class="btn btn-primary" style="display: inline!important; padding-left: 5px;">
                                        Create
                                    </button>
                                   
                                </div>
                            </div>
                        </form>

                        {{--cancel form  --}}
                        <form class="form-horizontal" method="get" style="display: inline!important; padding-left: 5px;" action="{{route('home')}}">
                                    {{ csrf_field() }}
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
