<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>MyBox - An encrypted box for secure life</title>
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
    -->
    <link rel="stylesheet" href="{{asset('themes/appton/css/style.default.css')}}">

    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="{{asset('themes/appton/css/custom.css')}}">
    <!-- Favicon-->
    <link rel="shortcut icon" href="{{asset('themes/appton/img/favicon.png')}}">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
        {{-- test front --}}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">


    <style type="text/css">

    /*NationalBook is not free :-), willing to buy soon, use here for testing only*/
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
                <a href="{{route('home')}}" class="btn btn-sm btn-primary navbar-btn ml-0 ml-lg-3">Box</a>
                <a href="{{ route('logout') }}"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">

                <span style="padding-left: 10px">
                <button type="button" class="btn btn-sm btn-warning navbar-btn ml-0 ml-lg-3">Logout</button>
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
            <span id="thename">{{$username}} </span>

            <br><i class="icon-shield-settings"></i>
            </li>
        </ol>
      </nav>
      
      @if($username =='theuserisnotregistered')
      @else
        <a style="display: inline!important; font-size: 24px" href="{{route('home.user', ['username' => $username])}}" class="nav-link">Update</a>
        <a style="display: inline !important; font-size: 24px" href="{{route('home.userreply', ['username' => $username])}}" class="nav-link">Reply</a>
        <a style="display: inline !important; font-size: 24px" href="{{route('home.usersend', ['username' => $username])}}" class="nav-link">Send</a>
      @endif
    </div>
  </section>

 <!-- FAQ Section-->
  @if($username =='theuserisnotregistered')
  @else
    <section>
      <div class="container">

        <h4 style="display: inline!important">Update</h4>

        {{-- Phần giải mã --}}
        @if($privatekey==true)
            <span style="padding-left: 25%;">
              <form style="display: inline!important; " action="{{route('home.user', ['id'=> $username])}}" method="GET">
              <button onclick="closethebutton()" style="display: inline!important; border-radius: 5%" id='thebutton' class='btn-warning btn-sm' class="d-flex align-items-center collapsed"><span>Closing </span><strong><span style="color: green; padding-left: 0px" id='Label1'></span></strong>
              </button>
              </form>
            </span>
            <br>
            <br>
        @else
            <span style="padding-left: 48%;">
            <button style="display: inline!important; " class='btn-success btn-sm' data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" class="d-flex align-items-center collapsed"><span> Read</span></button>
            </span>
        @endif

        <div id="collapseTwo" aria-labelledby="headingTwo" data-parent="#accordion" class="collapse">
        <div class="card-body">
              {{-- begin --}}
               <form class="form-horizontal" style="display: inline; padding-left: 20px" method="POST" action="{{ route('home.userread', ['username'=> $username, 'token' => sha1(rand(1, 1000))])}}">
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

                                    <span style="padding-left: 25%">
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
            <!-- question   -->
              {{-- <div class="card"> --}}

                {{-- Nếu cookie hết hạn trong vòng 1 phút --}}
                @if($guard==true)
                      @if(count($messages)>0)
                          @foreach($messages as $message)
                        <div style="background-color: white;">
                         <span class="iconmessage"><i class="icon-light-bulb" style="font-size: 150%; padding-right: 2%; margin-left: 0px" ></i></span>
                        <span style="padding-left: 1%;" id="timecreated"> Posted by <b>{{$username}}</b> at {{$message->created_at}}.</span>
                        </div>                                          
                        <span class="decryptedmesssage">{{sodium_crypto_box_seal_open(decrypt($message->encrypted), decrypt($privatekey))}}</span>

                      {{-- nút like/dislike --}}
                          {{-- <br>
                          <br>
                          <button class ="likebutton"  id="likebutton{{$message->id}}" style="pointer-events: auto; display: inline-block;!important" onclick="likeclick({{$message->id}})" ><i class="far fa-thumbs-up"></i></button> <span id="datalike{{$message->id}}">4</span>

                          <span style="padding-left: 2%; display: inline!important"></span>

                          <button class ="likebutton" id="disklikebutton{{$message->id}}" style="pointer-events: auto;" onclick="dislikeclick({{$message->id}})" ><i class="far fa-thumbs-down"></i></button> <span id="datadislike{{$message->id}}">0</span> --}}

                      {{-- end of nút like/dislike --}}

                        <br>
                        <br>
                          @endforeach
                      @endif
                @else
                  
                  <span class="decryptedmesssage" style="font-weight: bold; padding-left: 2%; padding-top: 3%">Your session is closed. Please reauthorize your access with the private key given.</span>
                @endif
              {{-- </div> --}}
             </div>
          </div>
      </div>
   
    <center><span class ='pagination'>{{ $messages->links() }}</span></center>


    

    </section>
  
  @endif

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
        </div> --}}
      </div>
      <div class="copyrights">
        <div class="container">
          <div class="row">
            <div class="col-lg-6 text-center text-lg-left">
              <p class="copyrights-text mb-3 mb-lg-0">&copy; All rights reserved. MyBox, since 2018. {{-- Frontend by <a href="https://bootstrapious.com/landing-pages" class="external footer-link">Bootstrapious </a> --}}</p>
              <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)
              Thanks for the theme, paid you already :-)
              -->
              
            </div>
            {{-- <div class="col-lg-6 text-center text-lg-right">
              <ul class="list-inline social mb-0">
                <li class="list-inline-item"><a href="#" class="social-link"><i class="fa fa-facebook"></i></a><a href="#" class="social-link"><i class="fa fa-twitter"></i></a><a href="#" class="social-link"><i class="fa fa-youtube-play"></i></a><a href="#" class="social-link"><i class="fa fa-vimeo"></i></a><a href="#" class="social-link"><i class="fa fa-pinterest"></i></a></li>
              </ul>
            </div>
             --}}
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
    {{-- lấy thời gian sau khi decipher - Get the current second left after providing private key--}}
    
    var now = sessionStorage.getItem("currenttimehomepagelocal");

    if(now == null){
      time = 60;
    }else if(now <= 60 && now >0){
      time = now;
    }else{
      time = 60;
    }
    
    interval = setInterval(function() {
        time--;
        
        document.getElementById('Label1').innerHTML = "" + time + " s";

        /*lưu giữ thời gian hiện tại
        * @see https://toidicode.com/localstorage-va-sessionstorage-189.html
        * @see https://www.w3schools.com/jsref/prop_win_sessionstorage.asp
        */
        sessionStorage.setItem("currenttimehomepagelocal", time);

      if (time == 0) {
            // stop timer
            clearInterval(interval);
            // click
            document.getElementById('thebutton').click();

            /*Xóa sessionStorage*/// 

            sessionStorage.removeItem('currenttimehomepagelocal');         
        }

    }, 1000)

    /*Xóa dữ liệu tạm trên sessionStore khi click*/
    function closethebutton(){

        sessionStorage.removeItem('currenttimehomepagelocal');
    }


    /*******************************nút like*****************************/
    

  
  function likeclick(target){

    
    /*lấy giá trị ban đầu sau khi bị clik*/
         
    var newgetcurrentlikenumber = sessionStorage.getItem("newcurrentlike"+target);
    
        /*số like hiện tại*/
    var likenumber = document.getElementById('datalike'+target).innerHTML;
    
    if(likenumber == false || likenumber ==0){

      /*Set to 1 if it is 0*/
      document.getElementById('datalike' + target).innerHTML = 1;

      /*giá trị ban đầu bằng 0*/

      sessionStorage.setItem("newcurrentlike"+target, 0);

      /*turn the button to blue*/
      document.getElementById("likebutton"+target).style.color = "blue";

      /*change the color of the dislike button*/
      var dislikecolor = document.getElementById("disklikebutton" + target).style.color;

        if (dislikecolor == "blue"){

          document.getElementById("disklikebutton"+target).style.color = "";     
        }
    }else{
          
         /*nếu click thêm lần nữa*/
          if (newgetcurrentlikenumber != null && likenumber != newgetcurrentlikenumber){

            var newlikenumberget = --likenumber;

            document.getElementById('datalike'+target).innerHTML = newlikenumberget;
            
            document.getElementById("likebutton"+target).style.color = "";

            var dislikecolor = document.getElementById("disklikebutton"+target).style.color;

            if (dislikecolor == "blue"){

              document.getElementById("disklikebutton"+target).style.color = "";     
            }


          }else{

            var newlikenumberget = ++likenumber;

            document.getElementById('datalike'+target).innerHTML = newlikenumberget;

            document.getElementById("likebutton"+target).style.color = "blue";
            var dislikecolor = document.getElementById("disklikebutton"+target).style.color;

            /*giữ giá trị ban đầu vì nó tăng, cần trừ đi 1*/
            var newcurrentlikenumber = document.getElementById('datalike'+target).innerHTML;

            sessionStorage.setItem("newcurrentlike"+target, newcurrentlikenumber -1);

          if (dislikecolor == "blue"){

          document.getElementById("disklikebutton"+target).style.color = "";     
        }

      }
    }


       /*Xử lý like backend*/
          
          // var data = new FormData();
          // data.append('user', 'person');
          // data.append('pwd', 'password');
          // data.append('organization', 'place');
          // data.append('requiredkey', 'key');


          // var http = new XMLHttpRequest();
          // var url = 'http://localhost/www/project/hopthu/laravel/public/box/me/like';
          // var params = 'orem=ipsum&name=binny';

          // http.open('GET', url, true);

          
          // //Send the proper header information along with the request
          // http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

          // /*Khi truyền dữ liệu thành công và kết quả trả về*/
          // http.onreadystatechange = function() {//Call a function when the state changes.
          //     if(http.readyState == 4 && http.status == 200) {
          //         document.getElementById("demo").innerHTML = http.responseText;
          //     }
          // }

          // http.send(params);


  
        /*end of xử lý backend*/

        /*backend jQuery*/

          var id = 12; // A random variable for this example

          $.ajax({
              method: 'POST', // Type of response and matches what we said in the route
              url: '/box/me/like', // This is the url we gave in the route
              data: {'id' : id}, // a JSON object to send back
              success: function(response){ // What to do if we succeed
                  console.log(response); 
              },

              
              error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                  console.log(JSON.stringify(jqXHR));
                  console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
              }
          });


        /*end of backend*/


    /*Không cho click sau khi đã click, hai code sau đều okey*/
    //document.getElementById('likebutton').style['pointer-events'] = 'none';
    // document.getElementById("likebutton").style.pointerEvents = "none";
    
    /*đổi trạng thái của nút dislike sang auto*/
    // var stylebutton = document.getElementById('disklikebutton').style.pointerEvents;

    // if(stylebutton == 'none'){

    //  document.getElementById("disklikebutton").style.pointerEvents = "auto";
    
    // }

    /*giảm giá trị của dislike nếu lớn hơn 0 và nếu dislike hiện tại khác disklike ban đầu*/
    var dislikenumber = document.getElementById('datadislike'+target).innerHTML;

    var newcurrentdislikedungbenlike = sessionStorage.getItem("newcurrentdislike"+target);

    
    // alert("dislike hiện tại là" + dislikenumber + "và dislike ban đầu" + newcurrentdislikedungbenlike);

    if(newcurrentdislikedungbenlike != null && dislikenumber != newcurrentdislikedungbenlike){
        if(dislikenumber> 0){
        document.getElementById('datadislike'+target).innerHTML = -- dislikenumber;    
        }
      }

  }

  function dislikeclick(target){
    
        
    var newgetcurrentdislikenumber =  sessionStorage.getItem("newcurrentdislike"+target);

    var dislikenumber = document.getElementById('datadislike'+target).innerHTML;


    //https://stackoverflow.com/questions/3390396/how-to-check-for-undefined-in-javascript

    if(dislikenumber == false || dislikenumber == 0){
    
      document.getElementById('datadislike'+target).innerHTML = 1;

      /*Giá trị ban đầu là 0*/
      sessionStorage.setItem("newcurrentdislike"+target, 0);


      document.getElementById("disklikebutton"+target).style.color = "blue";

      var likecolor = document.getElementById("likebutton"+target).style.color;

        if (likecolor == "blue"){

          document.getElementById("likebutton"+target).style.color = "";     
        }

    }else{

      /*khi click lại*/

      //alert(newgetcurrentdislikenumber + "và" + dislikenumber);


      if (newgetcurrentdislikenumber!= null && dislikenumber !=newgetcurrentdislikenumber){
        
        var newdislikenumberget = --dislikenumber;

        document.getElementById('datadislike'+target).innerHTML = newdislikenumberget;

        document.getElementById("disklikebutton"+target).style.color = "";

        var likecolor = document.getElementById("likebutton"+target).style.color;

        if (likecolor == "blue"){

          document.getElementById("likebutton"+target).style.color = "";     
        }

      }else{

        var newdislikenumberget = ++dislikenumber
        document.getElementById('datadislike'+target).innerHTML = newdislikenumberget;

        document.getElementById("disklikebutton"+target).style.color = "blue";
        var likecolor = document.getElementById("likebutton"+target).style.color;

         /*giữ giá trị ban đầu vì nó tăng, cần trừ đi 1*/
        var newcurrentdislikenumber = document.getElementById('datadislike'+target).innerHTML;

        /*giữ giá trị ban đầu*/
        sessionStorage.setItem("newcurrentdislike"+target, newcurrentdislikenumber -1);

        
        if (likecolor == "blue"){

          document.getElementById("likebutton"+target).style.color = "";     
        }

      }

    }
        
    
    /*trừ giá trị nút like*/
    var likenumber = document.getElementById('datalike'+target).innerHTML;

    /*Store these values current like to sessionStore*/

    var newcurrentlikedungbendislike =  sessionStorage.getItem("newcurrentlike"+target);
    

    // alert(newcurrentlikedungbendislike);


    if(newcurrentlikedungbendislike!= null && likenumber != newcurrentlikedungbendislike){

          if (likenumber >0) {
            document.getElementById('datalike'+target).innerHTML = --likenumber;   
          }
        }
    }

  /*end of nút like*/

    </script>

  </body>
</html>
