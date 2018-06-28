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
        <div class="container"><a href="./" class="navbar-brand"><img src="{{asset('themes/appton/img/logo.svg')}}" alt="" class="img-fluid"></a>
          <button type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler navbar-toggler-right">Menu<i class="fa fa-bars ml-2"></i></button>
          <div id="navbarSupportedContent" class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                  <li class="nav-item"> <a href="{{route('home.index')}}" class="nav-link active">Home</a></li>
                  
            </ul>
            <a href="{{route('home.registerindex')}}" data-toggle="modal" data-target="#login" class="btn btn-primary btn btn-sm navbar-btn ml-0 ml-lg-3">Login </a>
            <a href="{{route('home.registerindex')}}" class="btn btn-primary btn btn-sm navbar-btn ml-0 ml-lg-3">Register </a>

          </div>
        </div>
      </nav>
    </header>

    <!-- Register Section-->
    <section>
      <div class="container">
        <div class="row">
          <div class="col-lg-6">

                

            <header class="section-header"><h2 class="mb-2">Register a new box</h2></header>
            
             <form class="form-horizontal" method="POST" action="{{ route('home.registerxacthuc') }}">
                      {{ csrf_field() }}

                      <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                          <label for="username" class="col-md-4 control-label">Username</label>
                          
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


                      <div class="form-group{{ $errors->has('matkhau') ? ' has-error' : '' }}">
                          <label for="matkhau" class="col-md-4 control-label">Password</label>

                          <div class="col-md-6">
                              <input id="matkhau" type="password" class="form-control" name="matkhau" required>

                              @if ($errors->has('matkhau'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('matkhau') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="matkhau-confirm" class="col-md-4 control-label">Password Confirmation</label>

                          <div class="col-md-6">
                              <input id="matkhau-confirm" type="password" class="form-control" name="matkhau_confirmation" required>
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
               
                     {{-- login ben phai --}}
                     <div class="col-lg-6">
                      
                      @if(session('message'))
                     <div class="panel-body">
                     <div class="alert alert-danger">
                       {!!session('message')!!}
                      </div>
                      @endif

                     <header class="section-header"><h2 class="mb-2">Login</h2></header>
                     <form class="form-horizontal" method="POST" action="{{ route('home.login') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <label for="username" class="col-md-4 control-label">Username</label>
                                
                                <div class="col-md-6">
                                    <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>

                                    @if ($errors->has('username'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('username') }}</strong>
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
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Login
                                    </button>
                                </div>
                            </div>

                            <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                       </form>
                   

                     {{-- end of login --}}

                       <div class="form-group">
                          {{--   <div class="col-md-8 col-md-offset-4">
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a> --}}

                           


                                {{-- Tùy chỉnh forgot password --}}
                                <br>
                                <button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#readreply">Forgot Your Password?</button>
                                <br>
                                <br>
                                <div id="readreply" class="collapse">
                                {{-- Reset password --}}
                                <div class="panel-body">
                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <form class="form-horizontal" method="POST" action="{{ route('password.password') }}">
                                    {{ csrf_field() }}

                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        {{-- <label for="email" class="col-md-4 control-label">E-Mail Address</label> --}}

                                        <div class="col-md-8">
                                            <input id="email" type="email" placeholder="Your email" class="form-control" name="email" value="{{ old('email') }}" required>
                                           
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <button style="display: inline!important" type="submit" class="btn btn-primary">
                                                Request
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                                  
                      </div>  

                       @if ($errors->has('email'))
                      <span class="help-block">
                          <strong>{{ $errors->first('email') }}</strong>
                      </span>
                      @endif

                      </div> {{-- end of tùy chỉnh request from--}}

                 </div>
                        
            </div>
            </div>

            
        </section>    


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
  </body>
</html>