<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dogearn</title>
  <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/favicon/apple-icon-57x57.png') }}">
  <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/favicon/apple-icon-60x60.png') }}">
  <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/favicon/apple-icon-72x72.png') }}">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/favicon/apple-icon-76x76.png') }}">
  <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/favicon/apple-icon-114x114.png') }}">
  <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/favicon/apple-icon-120x120.png') }}">
  <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/favicon/apple-icon-144x144.png') }}">
  <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/favicon/apple-icon-152x152.png') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon/apple-icon-180x180.png') }}">
  <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/favicon/android-icon-192x192.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/favicon/favicon-96x96.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon/favicon-16x16.png') }}">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
  <meta name="theme-color" content="#ffffff">

  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.5">
  <!-- Framework Css -->
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/lib/bootstrap.min.css') }}">
  <!-- Owl Carousel -->
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/lib/owl.carousel.min.css') }}">
  <!-- Slick Carousel -->
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/lib/slick.css') }}">
  <!-- Animation -->
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/lib/animations.min.css') }}">
  <!-- Google Font -->
  <link href="{{ url('https://fonts.googleapis.com/css?family=Muli:300,400,400i,600,700,700i,800,900') }}" rel="stylesheet">
  <!-- Style Theme -->
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
  <!-- Responsive Theme -->
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ url('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') }}">

  @yield('addCss')
</head>

<body>
<div class="wrapper">
  <!--===================== Header ========================-->
  <header>
    <div class="container">
      <div class="row align-items-center">
        <div class="col-xl-3 col-lg-3 col-md-12">
          <a href="{{ url('/') }}">
            <img src="{{ asset('assets/img/logo.png') }}" class="logo" alt="images">
          </a>
        </div>
        <div class="col-xl-9 col-lg-9 col-md-12 text-right">
          <!--===================== Header Block ========================-->
          <div class="header-block">
            <ul class="nav-menu list-unstyled list-inline">
              <li class="list-inline-item"><a href="{{ url('/') }}">Home</a></li>
              <li class="list-inline-item"><a href="{{ route('about') }}">About Us</a></li>
              <li class="list-inline-item"><a href="#service">Download</a></li>
            </ul>
          </div>
          <button class="mobile-btn">
            <span></span>
          </button>
          <!--===================== End of Header Block ========================-->
        </div>
      </div>
    </div>
  </header>
  <!--===================== End of Header ========================-->

@yield('content')

<!--===================== Footer ========================-->
  <footer>
    <div class="container">
      <div class="row footer-menu-wrap">
        <div class="col-xl-4 col-lg-4 col-md-6 col-12">
          <div class="logo">
            <a href="{{ url('/') }}"><img src="{{ asset('assets/img/footer-logo.png') }}" alt="images"></a>
            <p>DOGEARN community is a decentralized community network based on the DOGECOIN blockchain.</p>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-12">
          <div class="inside-column">
            <h4 class="title">Home</h4>
            <ul class="list-unstyled">
              <li><a href="about-us.php">About Us</a></li>
              <li><a href="#service">Download</a></li>
            </ul>
          </div>
        </div>

      </div>
    </div>
    <!--===================== Copyright ========================-->
    <div class="copyright">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-6 col-6">
            <p><img class="svg social-link" src="{{ asset('assets/img/copyright.svg') }}" alt="images">2020 All Rights Reserved By Dogearn</p>
          </div>
          <div class="col-md-6 col-6">
            <div class="footer-social-wrapper">
              <a href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="16" viewBox="0 0 16 30">
                  <path id="f" class="cls-1"
                        d="M2246.4,13660h-3.84c-4.31,0-7.1,2.9-7.1,7.4v3.4h-3.86a0.622,0.622,0,0,0-.6.6v4.9a0.561,0.561,0,0,0,.6.6h3.86v12.5a0.624,0.624,0,0,0,.61.6h5.03a0.622,0.622,0,0,0,.6-0.6v-12.5h4.51a0.564,0.564,0,0,0,.61-0.6v-4.9a0.453,0.453,0,0,0-.18-0.4,0.666,0.666,0,0,0-.42-0.2h-4.52v-2.9c0-1.4.33-2.1,2.11-2.1h2.59a0.622,0.622,0,0,0,.6-0.6v-4.6A0.622,0.622,0,0,0,2246.4,13660Z"
                        transform="translate(-2231 -13660)"></path>
                </svg>
              </a>
              <a href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 32 31">
                  <path id="in" class="cls-1"
                        d="M2329,13679v12h-6.86v-11.2c0-2.8-.99-4.7-3.47-4.7a3.781,3.781,0,0,0-3.52,2.5,5.146,5.146,0,0,0-.23,1.7v11.7h-6.86s0.09-19,0-20.9h6.86v2.9c-0.01.1-.03,0.1-0.05,0.1h0.05v-0.1a6.812,6.812,0,0,1,6.18-3.4C2325.62,13669.6,2329,13672.6,2329,13679Zm-28.12-19a3.622,3.622,0,0,0-3.88,3.6,3.567,3.567,0,0,0,3.79,3.6h0.05A3.609,3.609,0,1,0,2300.88,13660Zm-3.47,31h6.86v-20.9h-6.86v20.9Z"
                        transform="translate(-2297 -13660)"></path>
                </svg>
              </a>
              <a href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="16" viewBox="0 0 39.18 30.4">
                  <path id="g" class="cls-1"
                        d="M2396.48,13673.8h-14.04v4.7h8.57a10.039,10.039,0,1,1-9.57-13.1,9.772,9.772,0,0,1,7.13,3l3.4-3.7a14.923,14.923,0,0,0-10.53-4.4,15.2,15.2,0,0,0,0,30.4,15.382,15.382,0,0,0,15.04-12.2v-4.7h0Zm9.12,0h-2.87v-2.9h-2.46v2.9h-2.87v2.5h2.87v2.9h2.46v-2.9h2.87v-2.5Z"
                        transform="translate(-2366.41 -13660.3)"></path>
                </svg>
              </a>
              <a href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="16" viewBox="0 0 37 31">
                  <path id="t" class="cls-1"
                        d="M2477.16,13663.6a11.4,11.4,0,0,1-1.79.6,7.626,7.626,0,0,0,1.61-2.9,0.513,0.513,0,0,0-.19-0.6,0.583,0.583,0,0,0-.68-0.1,12.96,12.96,0,0,1-4.16,1.7,7.79,7.79,0,0,0-5.56-2.3,8.016,8.016,0,0,0-7.95,8.1,10.152,10.152,0,0,0,.07,1.1,20.1,20.1,0,0,1-13.96-7.6,0.5,0.5,0,0,0-.51-0.2,0.518,0.518,0,0,0-.47.3,8.22,8.22,0,0,0,.82,9.3,4.52,4.52,0,0,1-1.07-.5,0.445,0.445,0,0,0-.58.1,0.589,0.589,0,0,0-.3.5v0.1a8.331,8.331,0,0,0,3.89,7,1.329,1.329,0,0,0-.61-0.1,0.442,0.442,0,0,0-.56.2,0.616,0.616,0,0,0-.12.6,8.051,8.051,0,0,0,5.82,5.4,13.329,13.329,0,0,1-7.51,2.2,9.421,9.421,0,0,1-1.68-.1,0.608,0.608,0,0,0-.64.4,0.563,0.563,0,0,0,.24.7,20.894,20.894,0,0,0,11.59,3.5,20.343,20.343,0,0,0,15.96-7.2,23.09,23.09,0,0,0,5.54-14.8c0-.2-0.01-0.4-0.01-0.7a14.182,14.182,0,0,0,3.55-3.8,0.729,0.729,0,0,0-.04-0.8A0.6,0.6,0,0,0,2477.16,13663.6Z"
                        transform="translate(-2441 -13660)"></path>
                </svg>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--===================== End of Copyright ========================-->
  </footer>
  <!--===================== End of Footer ========================-->
</div>
<!--wrapper-->
<script src="{{ asset('assets/js/lib/jquery-3.2.1.js') }}"></script>
<script src="{{ asset('assets/js/lib/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/masonry.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/slick.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/css3-animate-it.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
@yield('addJs')
</body>

</html>