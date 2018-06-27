@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">

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


        <div class="panel-body">

            {{-- Kiora <b>{{$user['username']}}</b>! You are logged in! --}}
            @if ($creates !==NULL)
            
                  {{$creates}}

                  <form action="{{route('key.create')}}" method="get" style=" display:inline!important; padding-left: 15px">
                                {{csrf_field()}}
                  <button type="submit" class="btn btn-primary btn-xs" >Create </button>
                  </form>

            @else

                  <form class="form-horizontal" style=" display:inline!important;" method="POST" action="{{ route('message.store') }}">
                      {{ csrf_field() }}

                  <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
                             <div class="col-md-10">
                                    
                                      <label for="message">Write new message</label>
                                      <textarea id="message" class="form-control rounded-0 u-form-control g-resize-none" name="message" value="{{ old('message') }}" required autofocus></textarea>


                                      @if ($errors->has('message'))
                                          <span class="help-block">
                                              <strong>{{ $errors->first('message') }}</strong>
                                          </span>
                                      @endif
                              </div>
                 </div>

                  {{-- Nút radio publish or pending --}}
                  Published: 
                  <div class="btn-group" data-toggle="buttons">
                      <label class="btn btn-primary btn-xs active">
                      <input type="radio" name="options" id="option1" autocomplete="off" checked> Publish
                      </label>

                      <label class="btn btn-primary btn-xs">
                      <input type="radio" name="options" id="option2" autocomplete="off"> Pending
                      </label>
                  </div>
                  <br>
                 {{-- end of radio publish --}}

                 <button type="submit" class="btn btn-primary btn-xs">Create </button>
                 </form>

                  {{-- end of nut create --}}


                      {{-- nut cancel --}}
                      
                  <form action="{{route('home')}}" method="get" style=" display:inline!important; padding-left: 15px">
                  {{csrf_field()}}
                  <button type="submit" class="btn btn-info btn-xs" >Cancel </button>
                  </form>
                      {{-- end of cancel button --}}
          
            @endif


        </div>

       

 </div>

</div>

</div>
</div>
</div>

@endsection
