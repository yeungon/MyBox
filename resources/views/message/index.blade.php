@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">

                <div class="panel-heading">
                 <span class="glyphicon glyphicon-user">Profile</span>
                 
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


        <div class="panel-body">

            Kiora <b>{{$user['username']}}</b>! You are logged in!
            <br>
            <p>Create your own Private key. You will need to provide your Masterkey to create your Private key and Public key.</p>

            <form class="form-horizontal" method="GET" action="{{ route('key.create') }}">
                {{-- {{ csrf_field() }} --}}

                <button type="submit" class="btn btn-primary">
                    Create
                </button>
            </form>

        </div>

        <div class="panel panel-default">
          <div class="panel-body">

            
            
        </div>

        <div class="panel-body">                   
         
         
     </div>


 </div>

</div>

</div>
</div>
</div>

@endsection
