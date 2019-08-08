<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>F5 - An encrypted box for secure life</title>
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

    <style type="text/css">

    /*NationalBook and Saile fonts are not free :-), willing to buy soon, using here for testing only*/
    @font-face {
      font-family: "NationalBook";
      font-weight: 400;

      src: url("https://cdn.auckland.ac.nz/aem/etc/designs/uoa-digital/clientlibs/css/base/fonts/NationalWeb-Book.woff");
    }


    @font-face {
      font-family: "Sailec";
      font-weight: 400;
      src: url("themes/appton/fonts/Sailec.otf");

    }

      
     body{
        font-family: Sailec,/*Helvetica Neue,Helvetica,Arial,sans-serif,*/ -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, Ubuntu!important; 
        font-size: 15.5px;
        
      }


    </style>

    <!-- JavaScript files-->
    <script src="{{asset('themes/appton/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('themes/appton/vendor/popper.js/umd/popper.min.js')}}"> </script>
    <script src="{{asset('themes/appton/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('themes/appton/vendor/jquery.cookie/jquery.cookie.js')}}"> </script>
    <script src="{{asset('themes/appton/vendor/lightbox2/js/lightbox.js')}}"></script>
    <script src="{{asset('themes/appton/js/front.js')}}"></script>



  </head>
  <body>
    <!-- navbar-->
    <header class="header">
      <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container"><a href="{{route('home.index')}}" class="navbar-brand"><img src="{{asset('themes/appton/img/logo.svg')}}" alt="" class="img-fluid"></a>
          <button type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler navbar-toggler-right">Menu<i class="fa fa-bars ml-2"></i></button>
          <div id="navbarSupportedContent" class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                  <!-- Link-->
                  <li class="nav-item"> <a href="{{route('home.index')}}" class="nav-link active">Home</a></li>
                  </div>
              </li>
            </ul>

            @if(isset($login) AND $login ==='login')

                <a href="{{route('home.registerindex')}}" data-target="#login" class="btn btn-sm btn-primary navbar-btn ml-0 ml-lg-3">Login </a>
                <a href="{{route('home.registerindex')}}#" class="btn btn-sm btn-primary navbar-btn ml-0 ml-lg-3">Register </a>
            @else
                  <span class="text-info">
                  <form action="{{route('home.user', ['username' => $username])}}" method="GET">
                    Kiora <b><a href="{{route('home.user', ['username' => $username])}}">{{$username}}</b></a>
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


    <!-- Login Modal-->
    <div id="login" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade bd-example-modal-lg">
      <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header border-bottom-0">
            <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body p-4 p-lg-5">
            <form action="#" class="login-form text-left">
              <div class="form-group mb-4">
                <label>Email address</label>
                <input type="email" name="email" placeholder="name@company.com" class="form-control">
              </div>
              <div class="form-group mb-4">
                <label>Password</label>
                <input type="password" name="password" placeholder="Min 8 characters" class="form-control">
              </div>
              <div class="form-group">
                <input type="submit" value="Login" class="btn btn-primary">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

     <!-- Register Modal-->
    <div id="register" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade bd-example-modal-lg">
      <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header border-bottom-0">
            <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body p-4 p-lg-5">

           <form class="form-horizontal" method="POST" action="{{ route('home.registerxacthuc') }}">
                      {{ csrf_field() }}

                      <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                          <label for="username" class="col-md-4 control-label">https://hopthu.org/me/</label>
                          
                          <div class="col-md-6">
                              <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>

                              @if ($errors->has('username'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('username') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                          <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                          <div class="col-md-6">
                              <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                              @if ($errors->has('email'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('email') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                          <label for="password" class="col-md-4 control-label">Password</label>

                          <div class="col-md-6">
                              <input id="password" type="password" class="form-control" name="password" required>

                              @if ($errors->has('password'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('password') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                          <div class="col-md-6">
                              <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="col-md-6 col-md-offset-4">
                              <button type="submit" class="btn btn-primary">
                                  Register
                              </button>
                          </div>
                      </div>
                  </form>

            

          </div>
        </div>
      </div>
    </div>
    <!-- Hero Section-->

    

    <section class="hero">
       <div class="container mb-5">
        <div class="row align-items-center">
          <div class="col-lg-12">
            {{-- Thông báo sau khi đăng ký --}}
            @if(session('message'))
                        <div class="alert alert-success">
                            {!!session('message')!!}
                        </div>
            @endif
          </div>

          <div class="col-lg-6">
            
        
           
            <h1 class="hero-heading mb-0">An encrypted box <br> for a secure life </h1>
            <div class="row">
              <div class="col-lg-10">
                <p class="lead text-muted mt-4 mb-4">Everything is encrypted with state of art algorithm. </p>
              </div>
            </div>
                        
          </div>
          <div class="col-lg-6"><img src="{{asset('themes/appton/img/illustration-hero.svg')}}" alt="..." class="hero-image img-fluid d-none d-lg-block"></div>
        </div>
      </div>
    </section>
    <!-- Intro Section-->
    <section>
      <div class="container">
        <div class="text-center">
          <h2>A box that can only be opened by your private key </h2>
          <p class="lead text-muted mt-2">Your box is stored and transfered with encryption.</p>

          <!-- <a href="#" class="btn btn-primary">Learn More</a> -->

        </div>
        <div class="row">
          <div class="col-lg-7 mx-auto mt-5"><img src="{{asset('themes/appton/img/illustration-1.svg')}}" alt="..." class="intro-image img-fluid"></div>
        </div>
      </div>
    </section>
    <!-- Divider Section-->
    <section class="bg-primary text-white">
      <div class="container">
        <div class="text-center">
          <h2>A private space that can be shared</h2>
          <div class="row">
            <div class="col-lg-9 mx-auto">
              <p class="lead text-white mt-2">The message, status and information exchanged in general kept in secret but can be shared with your own control</p>
            </div>
          </div><a href="#" class="btn btn-outline-light">Learn More</a>
        </div>
      </div>
    </section>
    <!-- Integrations Section-->
    <section>
      <div class="container">
        <div class="text-center">
          <h2>Features</h2>
          <div class="row">
            <div class="col-lg-8 mx-auto">
              <p class="lead text-muted mt-2">Main features given to you by GetBox</p>
            </div>
          </div>
        </div>
        <div class="integrations mt-5">
          <div class="row">
            <div class="col-lg-4">
              <div class="box text-center">
                <div class="icon d-flex align-items-end"><img src="{{asset('themes/appton/img/monitor.svg')}}" alt="..." class="img-fluid"></div>
                <h3 class="h4">Secured information</h3>
                <p class="text-small font-weight-light">Your data is encrypted and secured kept in our data, but no body can access it since only your private key can open it</p>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="box text-center">
                <div class="icon d-flex align-items-end"><img src="{{asset('themes/appton/img/target.svg')}}" alt="..." class="img-fluid"></div>
                <h3 class="h4">Anonymously platform</h3>
                <p class="text-small font-weight-light">You can anonymously get in touch with those you want</p>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="box text-center">
                <div class="icon d-flex align-items-end"><img src="{{asset('themes/appton/img/chat.svg')}}" alt="..." class="img-fluid"></div>
                <h3 class="h4">Free service</h3>
                <p class="text-small font-weight-light">F5 is free, of course. </p>
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>
    <!-- CLients Section-->
    <section class="bg-gray">
      <div class="container">
        <div class="text-center">
          <h2>Your data is invaluable that need to be protected</h2>
          <div class="row">
            <div class="col-lg-8 mx-auto">
              <p class="lead text-muted mt-2">F5 is where you can communicate in silent, safety and fun</p>
            </div>
          </div>
        </div>
        <div class="clients mt-5">
          <div class="row">
            <div class="col-lg-2"><img src="{{asset('themes/appton/img/client-1.svg')}}" alt="" class="client-image img-fluid"></div>
            <div class="col-lg-2"><img src="{{asset('themes/appton/img/client-2.svg')}}" alt="" class="client-image img-fluid"></div>
            <div class="col-lg-2"><img src="{{asset('themes/appton/img/client-3.svg')}}" alt="" class="client-image img-fluid"></div>
            <div class="col-lg-2"><img src="{{asset('themes/appton/img/client-4.svg')}}" alt="" class="client-image img-fluid"></div>
            <div class="col-lg-2"><img src="{{asset('themes/appton/img/client-5.svg')}}" alt="" class="client-image img-fluid"></div>
            <div class="col-lg-2"><img src="{{asset('themes/appton/img/client-6.svg')}}" alt="" class="client-image img-fluid"></div>
          </div>
        </div>
      </div>
    </section>
   
   
    <!-- How it works Section-->
    <section class="bg-gray">
      <div class="container text-center text-lg-left">
        <div class="row align-items-center">
          <div class="col-lg-7">
            <h2 class="divider-heading">Curious how F5 <br>works for you?</h2>
            <div class="row">
              <div class="col-lg-10">
                <p class="lead divider-subtitle mt-2 text-muted">How can I be sure that I am protected? Check the FAQ!</p>
              </div>
            </div><a href="#" class="btn btn-primary">Learn More</a>
          </div>
          <div class="col-lg-5 mt-5 mt-lg-0"><img src="{{asset('themes/appton/img/illustration-2.svg')}}" alt="" class="divider-image img-fluid"></div>
        </div>
      </div>
    </section>
    

    <footer class="main-footer">
      <div class="container">

        {{-- <div class="row">

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
         --}}
      </div>

      <div class="copyrights">
        <div class="container">
          <div class="row">
            <div class="col-lg-6 text-center text-lg-left">
              <p class="copyrights-text mb-3 mb-lg-0">&copy; All rights reserved. F5, since 2018. {{-- Frontend by <a href="https://bootstrapious.com/landing-pages" class="external footer-link">Bootstrapious </a> --}}</p>
              <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)
              Thanks for the theme, paid you already :-)
              -->
              
            </div>
            
            {{-- <div class="col-lg-6 text-center text-lg-right">
              <ul class="list-inline social mb-0">
                <li class="list-inline-item"><a href="#" class="social-link"><i class="fa fa-facebook"></i></a><a href="#" class="social-link"><i class="fa fa-twitter"></i></a><a href="#" class="social-link"><i class="fa fa-youtube-play"></i></a><a href="#" class="social-link"><i class="fa fa-vimeo"></i></a><a href="#" class="social-link"><i class="fa fa-pinterest"></i></a></li>
              </ul>
            </div> --}}


          </div>
        </div>
      </div>
    </footer>

 <script type="text/javascript">
  $(document).ready(function(){
    

    $('.launch-modal').click(function(){
      $('#register').modal({
        backdrop: 'static'
      });

      event.preventDefault();

    });


  });
  </script>


  </body>
</html>