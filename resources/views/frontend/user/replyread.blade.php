<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>GetBox - An encrypted box for secure life</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="{{asset('themes/appton/vendor/bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="{{asset('themes/appton/vendor/font-awesome/css/font-awesome.min.css')}}">
    <!-- Google fonts - Poppins-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,600">
    <!-- Lightbox-->
    {{-- <link rel="stylesheet" href="{{asset('themes/appton/vendor/lightbox2/css/lightbox.css')}}"> --}}
    <!-- Custom font icons-->
    <link rel="stylesheet" href="{{asset('themes/appton/css/fontastic.css')}}">
    <!-- theme stylesheet
    " id="theme-stylesheet' ???
    -->
    <link rel="stylesheet" href="{{asset('themes/appton/css/style.default.css')}}">

    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="{{asset('themes/appton/css/custom.css')}}">
    <!-- Favicon-->
    <link rel="shortcut icon" href="{{asset('themes/appton/img/favicon.png')}}">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
  </head>
  <body>
   <!-- navbar-->
    <header class="header">
      <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container"><a href="{{route('home.index')}}" class="navbar-brand"><img src="{{asset('themes/appton/img/logo.svg')}}" alt="" class="img-fluid"></a>
          <button type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler navbar-toggler-right">Menu<i class="fa fa-bars ml-2"></i></button>
          <div id="navbarSupportedContent" class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                  </div>
              </li>
            </ul>
          
          {{-- Kiểm tra login hay không --}}
            @if(isset($login) AND $login ==='login')
                <a href="{{route('home.registerindex')}}" data-target="#login" class="btn btn-sm btn-primary navbar-btn ml-0 ml-lg-3">Login </a>
                <a href="{{route('home.registerindex')}}#" class="btn btn-sm btn-primary navbar-btn ml-0 ml-lg-3">Register </a>
            @else
                  <span class="text-info">
                  <form action="{{route('home.user', ['username' => $username])}}" method="GET">
                    Kiora <b><a href="{{route('home.user', ['username' => $currentusername])}}">{{$currentusername}}</b></a>
                  </form>
                  </b></span>
                  <a href="{{route('home')}}" class="btn btn-sm btn-info navbar-btn ml-0 ml-lg-3">Box</a>
                  <a href="{{ route('logout') }}"
                  onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">

                  <span style="padding-left: 10px">
                  <button type="button" class="btn btn-sm btn-danger navbar-btn ml-0 ml-lg-3">Logout</button>
                  </span>
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  {{ csrf_field() }}
                  </form>
           @endif
          </div>
        </div>
      </nav>
    </header>

       
      <!-- Hero Section-->
    <section class="hero">
      <div class="container text-center">
        <!-- breadcrumb-->
        <nav aria-label="breadcrumb" class="d-flex justify-content-center">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
             
              <span id="thename">{{$username}} </span><br><i class="icon-shield-settings"></i>
              </li>
          </ol>
        </nav>


        
          @if($username =='theuserisnotregistered')
          @else
            <a style="display: inline!important; font-size: 24px" href="{{route('home.user', ['username' => $username])}}" class="nav-link">Update</a>
            <a style="display: inline !important; font-size: 24px" href="{{route('home.userreply', ['username' => $username])}}" class="nav-link">Reply</a>
            <a style="display: inline !important; font-size: 24px" href="{{route('home.usersend', ['username' => $username])}}" class="nav-link">Send</a>
          @endif
        
        <div class="row">
          <div class="col-lg-7 mx-auto">
            {{-- <p class="lead text-muted">Ask and anwser</p> --}}
          </div>
        </div>
      </div>
    </section>


     @if(session('message'))
        <div class="container">
            <div class ="col-md-8">
                    <div class="alert alert-danger">
                        {{session('message')}}
                    </div>
            </div>
        </div>
      @endif

    {{-- Lỗi bằng flash session --}}



     @if($username =='theuserisnotregistered')
     @else
    <!-- FAQ Section-->
    <section>
      <div class="container">

      
        <h4 style="display: inline!important">Ask and Reply</h4>


         {{-- Phần giải mã --}}
        {{-- <span style="padding-left: 45%;">
            <button style="display: inline!important; " class='btn-primary btn-sm' data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" class="d-flex align-items-center collapsed"><span> Read</span></button>
        </span> --}}

        @if($privatekey==true)
            <span style="padding-left: 37%;">
              <form style="display: inline!important;" action="{{route('home.userreply', ['id'=> $username])}}" method="GET">
              <button style="display: inline!important;" id='thebutton' class='btn-warning btn-sm' class="d-flex align-items-center collapsed"><span> Closing</span> in <strong><span style="color: green; padding-left: 0px" id='Label1'></span></strong>
              </button>
              </form>
            </span>
          @else
            <span style="padding-left: 37%;">
            <button style="display: inline!important; " class='btn-success btn-sm' data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" class="d-flex align-items-center collapsed"><span> Read</span></button>
            </span>

        @endif



        <div id="collapseTwo" aria-labelledby="headingTwo" data-parent="#accordion" class="collapse">
        <div class="card-body">
              {{-- begin --}}
               <form class="form-horizontal" style="display: inline; padding-left: 20px" method="POST" action="{{ route('home.userreplyverify', ['username'=> $username, 'token' => sha1(rand(1, 1000))])}}">
                        {{ csrf_field() }}
                        
                        <div class="form-group{{ $errors->has('privatekey') ? ' has-error' : '' }}">
                                <label for="privatekey" class="col-md-2 control-label">The private key: </label>

                                <div class="col-md-8">
                                    
                                    <input id="privatekey" type="password" class="form-control" name="privatekey" required>
                                    @if ($errors->has('privatekey'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('privatekey') }}</strong>
                                    </span>
                                    @endif

                                </div>
                              </div>
                              <span style="padding-left: 455px">
                              <button type="submit" class="btn btn-primary btn-sm btn-xs">
                              Decipher
                              </button>
                              </span>
                    </form>
                       
                    <form class="form-horizontal" method="get" style="display: inline!important; padding-left: 5px;" action="{{route('home.user', ['username'=>$username])}}">
                                                                                                          
                      <button type="submit" class="btn btn-sm btn-warning btn-xs">Cancel</button>
                      </form>
          
              {{-- end --}}

        </div>
        </div>
        {{-- end of decipher --}}


         <div class="row"> 
          <div class="col-lg-8">   
            <div id="accordion" class="faq accordion accordion-custom pb-5">
                    
              <!-- question        -->
              <div class="card">
              @if($guard==true)
                @if(count($asks)>0)
                    @foreach($asks as $ask)
                    <div id="headingTwo" class="card-header">
                    <h4 class="mb-0 accordion-heading">
                      <button aria-expanded="false" aria-controls="collapseTwo" class="d-flex align-items-center collapsed">
                        <span class="iconmessage"><i class="icon-light-bulb"></i></span>
                        <span id="timecreated"> @asked at {{$ask->created_at}}.</span>
                      </button>
                    </h4>
                    </div>
                                        
                    <span style="font-size: 14px" class="decryptedmesssage">{{sodium_crypto_box_seal_open(base64_decode($ask->ask), base64_decode($privatekey))}}</span>

                    {{-- Reply --}}

                          <span class="botreply">
                           <div style="" id="headingTwo" class="card-header">
                                <h4 class="mb-0 accordion-heading">
                                  <button aria-expanded="false" aria-controls="collapseTwo" class="d-flex align-items-center collapsed">
                                    {{-- <span class="iconmessage"><i style="color:#9055A2; font-weight: bold" class="icon-shield-settings"></i></span> --}}
                                    <span style="padding-left: 15%" id="timecreated"> @replied by <b>{{$username}}</b> at {{$ask->created_at}}.</span>
                                  </button>
                                </h4>
                           </div>
                           </span>                          
                          
                           <span style="padding-left: 15%" class="replydecryptedmesssage">
                            
                            {{sodium_crypto_box_seal_open(base64_decode($ask->reply['reply']), base64_decode($privatekey))}}</span>

                     {{-- end of reply --}}
                    @endforeach
                @endif
            @else
                  
            <span class="decryptedmesssage" style="font-weight: bold; padding-left: 2%; padding-top: 3%">Your session is closed. Please reauthorize your access with the private key given.</span>
            @endif

              </div>
               

              </div>
            </div>
          </div>
          <span class ='pagination' style="padding-left: 20%"><centre>{{ $asks->links() }}</centre></span>
        </div>
      
    </section>

    @endif

    

    <footer class="main-footer">
      <div class="container">
        <div class="row">
          <div class="col-lg-3 mb-5 mb-lg-0">
            <div class="footer-logo"><img src="{{asset('themes/appton/img/logo-footer.svg')}}" alt="..." class="img-fluid"></div>
          </div>
          <div class="col-lg-3 mb-5 mb-lg-0">
            <h5 class="footer-heading">Site pages</h5>
            <ul class="list-unstyled">
              <li> <a href="index.html" class="footer-link">Home</a></li>
              <li> <a href="faq.html" class="footer-link">FAQ</a></li>
              <li> <a href="contact.html" class="footer-link">Contact</a></li>
              <li> <a href="text.html" class="footer-link">Text Page</a></li>
            </ul>
          </div>
          <div class="col-lg-3 mb-5 mb-lg-0">
            <h5 class="footer-heading">Product</h5>
            <ul class="list-unstyled">
              <li> <a href="#" class="footer-link">Why Appton?</a></li>
              <li> <a href="#" class="footer-link">Enterprise</a></li>
              <li> <a href="#" class="footer-link">Blog</a></li>
              <li> <a href="#" class="footer-link">Pricing</a></li>
            </ul>
          </div>
          <div class="col-lg-3">
            <h5 class="footer-heading">Resources</h5>
            <ul class="list-unstyled">
              <li> <a href="#" class="footer-link">Download</a></li>
              <li> <a href="#" class="footer-link">Help Center</a></li>
              <li> <a href="#" class="footer-link">Guides</a></li>
              <li> <a href="#" class="footer-link">Partners</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="copyrights">
        <div class="container">
          <div class="row">
            <div class="col-lg-6 text-center text-lg-left">
              <p class="copyrights-text mb-3 mb-lg-0">&copy; All rights reserved. GetBox. Frontend by <a href="https://bootstrapious.com/landing-pages" class="external footer-link">Bootstrapious </a></p>
              <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
              
            </div>
            <div class="col-lg-6 text-center text-lg-right">
              <ul class="list-inline social mb-0">
                <li class="list-inline-item"><a href="#" class="social-link"><i class="fa fa-facebook"></i></a><a href="#" class="social-link"><i class="fa fa-twitter"></i></a><a href="#" class="social-link"><i class="fa fa-youtube-play"></i></a><a href="#" class="social-link"><i class="fa fa-vimeo"></i></a><a href="#" class="social-link"><i class="fa fa-pinterest"></i></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <!-- JavaScript files-->
    <script src="{{asset('themes/appton/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('themes/appton/vendor/popper.js/umd/popper.min.js')}}"> </script>
    <script src="{{asset('themes/appton/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('themes/appton/vendor/jquery.cookie/jquery.cookie.js')}}"> </script>
    <script src="{{asset('themes/appton/vendor/lightbox2/js/lightbox.js')}}"></script>
    <script src="{{asset('themes/appton/js/front.js')}}"></script>

    <script type="text/javascript">
    time = 60;
    interval = setInterval(function() {
        time--;
        document.getElementById('Label1').innerHTML = "" + time + " s"
        if (time == 0) {
            // stop timer
            clearInterval(interval);
            // click
            document.getElementById('thebutton').click();            
        }
    }, 1000)

    </script>

  </body>
</html>