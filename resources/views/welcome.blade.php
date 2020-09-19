@extends('layouts.frontEndApp')

@section('content')
  <!--===================== Slider ========================-->
  <div class="courusel-container">
    <div class="container">
      <div class="row">
        <div class="col-xl-12">
          <div id="sync1" class="owl-carousel owl-theme">
            <div class="item">
              <div class="inside">
                <h1>Download App Dogearn</h1>
                <p>Now it's even easier to use Dogearn on Google Play and Play Store.</p>
                <a href="#service" class="blue-brd-btn">Download</a>
              </div>
            </div>
            <div class="item">
              <div class="inside">
                <h1>DOGEARN community.</h1>
                <p>DOGEARN community is a decentralized community network based on the DOGECOIN blockchain.</p>
                <a href="{{ route('about') }}" class="blue-brd-btn">About Us</a>
              </div>
            </div>
            <div class="item">
              <div class="inside">
                <h1>Digital Community Program (DCP).</h1>
                <p>Is a support system, as an educational forum with a structured,
                  directed and sustainable curriculum.</p>
                <a href="{{ route('about') }}" class="blue-brd-btn">About Us</a>
              </div>
            </div>
            <div class="item">
              <div class="inside">
                <h1>Era Digital Cryptocurrency.</h1>
                <p>in a very short period of time, thousands of cryptocurrencies have appeared in the world that have been used for transactions.</p>
              </div>
            </div>
          </div>
          <div id="sync2" class="owl-carousel owl-theme">
            <div class="item">
              <span></span>
            </div>
            <div class="item">
              <span></span>
            </div>
            <div class="item">
              <span></span>
            </div>
            <div class="item">
              <span></span>
            </div>
          </div><!--owl-carousel-->
          <br>
        </div>
      </div>
    </div>
  </div>
  <!--===================== End of Slider ========================-->

  <!--===================== Iphone Block ========================-->
  <div class="iphone-block animatedParent" id="service">
    <div class="container">
      <div class="row">
        <div class="col-xl-12 text-center">
          <div class="icon">
            <img src="{{ asset('assets/img/icon-phone.svg') }}" alt="icon-phone">
          </div>
          <h2 class="h2-main">Download App</h2>
          @if($register ?? '')
            <div class="col-md-12">
              <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                <p>Failed / expired verification</p>
                <p>Please re-register / Contact your sponsor</p>
              </div>
            </div>
          @endif
          <p>Now Dogearn is easier to use on your smartphone.</p>
          <a href="{{ url('download/dogearn.apk') }}" class="see-brd-btn">Download</a>
          <a href="{{ url('https://play.google.com/store/apps/details?id=net.dogearn') }}" class="see-brd-btn">Via play Store</a>
        </div>
      </div>
    </div>
  </div>
  <!--===================== End of Iphone Block ========================-->

  <!--===================== About Us========================-->
  <div class="philosophy-block">
    <div class="container-fluid">
      <div class="row animatedParent">
        <div class="col-md-6 philosophy-div1">
          <div class="coin-speacial-block">
            <img src="{{ asset('assets/img/logo-squard.png') }}" alt="images">
          </div>
        </div>
        <div class="col-md-6 philosophy-div2">
          <h4>About Us</h4>
          <h2 class="h2-main" id="aboutus">What is Dogearn?</h2>
          <p>DOGEARN community is a decentralized community network based on the DOGECOIN blockchain. and also Dogearn:</p>
          <ul>
            <li>Not Ponzi.</li>
            <li>Not ROI.</li>
            <li>Not an investment scheme.</li>
            <li>Not Trading or Mining.</li>
            <li>No system load / scam.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!--===================== End of philosophy Block ========================-->
  <br>
  <br>
  <br>
  <!--===================== Service ========================-->
  <div class="container animatedParent">
    <div class="row reasons-block">
      <div class="col-xl-4 col-lg-4 col-12">
        <h2 class="h2-main">Why should Doge coin?</h2>
      </div>
      <div class="col-xl-8 col-lg-8 col-12">
        <div class="row">
          <div class="col-sm-6 animated bounceInRight delay-250">
            <!--===================== Reason Block Cont ========================-->
            <div class="reason-block-cont">
              <div class="reasons-icon-div">
                <img src="{{ asset('assets/img/reason-icon1.svg') }}" alt="images">
              </div>
              <div class="reasons-desc-div">
                <h4>Famous</h4>
                <p>Doge coin is known throughout the world.</p>
              </div>
            </div>
            <!--===================== End of Reason Block Cont ========================-->
          </div>
          <div class="col-sm-6">
            <!--===================== Reason Block Cont ========================-->
            <div class="reason-block-cont">
              <div class="reasons-icon-div">
                <img src="{{ asset('assets/img/reason-icon2.svg') }}" alt="images">
              </div>
              <div class="reasons-desc-div">
                <h4>Cheap</h4>
                <p>The price is still relatively cheap.</p>
              </div>
            </div>
            <!--===================== End of Reason Block Cont ========================-->
          </div>
          <div class="col-sm-6">
            <!--===================== Reason Block Cont ========================-->
            <div class="reason-block-cont">
              <div class="reasons-icon-div">
                <img src="{{ asset('assets/img/reason-icon3.svg') }}" alt="images">
              </div>
              <div class="reasons-desc-div">
                <h4>Market</h4>
                <p>Marketers are selling and buying already on hundreds of exchangers.</p>
              </div>
            </div>
            <!--===================== End of Reason Block Cont ========================-->
          </div>
          <div class="col-sm-6">
            <!--===================== Reason Block Cont ========================-->
            <div class="reason-block-cont">
              <div class="reasons-icon-div">
                <img src="{{ asset('assets/img/reason-icon4.svg') }}" alt="images">
              </div>
              <div class="reasons-desc-div">
                <h4>Rising price</h4>
                <p>The trend in the future is predicted that the price of Doge coin will continue to increase.</p>
              </div>
            </div>
            <!--===================== End of Reason Block Cont ========================-->
          </div>
        </div>
      </div>
    </div>
  </div>

  <!--===================== Last Mews ========================-->
  <div class="container" id="blog">
    <div class="row">
      <div class="col-xl-12">
        <div class="last-news-title-wrap text-center">
          <div class="icon">
            <img src="{{ asset('assets/img/news-icon.svg') }}" alt="icon-phone">
          </div>
          <h2 class="h2-main">Information</h2>
        </div>
        <div id="news-owl-carousel" class="owl-carousel news-owl-carousel">
          <div class="item">
            <!--===================== Last News ========================-->
            <article class="post">
              <div class="last-news-img-wrap">
                <img src="{{ asset('assets/img/new-image1.jpg') }}" alt="images">
              </div>
              <div class="last-news-info-wrap">
                <h4 class="last-news-date">12/08</h4>
                <a href="#"><h4 class="last-news-title">Best Ways To Get Seller Leads In 2017: Tips From ...</h4></a>
                <a href="#" class="read-more-btn">Read More
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="11" height="8" viewBox="0 0 11 8">
                    <defs>
                      <path id="zqnma2"
                            d="M246.3 6201.78a.31.31 0 0 1 0 .44l-3.26 3.3a.3.3 0 0 1-.44 0 .31.31 0 0 1 0-.45l2.73-2.76h-8.41a.31.31 0 0 1-.31-.31c0-.17.14-.31.3-.31h8.42l-2.73-2.76a.31.31 0 0 1 0-.44.3.3 0 0 1 .44 0z"/>
                    </defs>
                    <g>
                      <g transform="translate(-236 -6198)">
                        <use fill="#20add0" xlink:href="#zqnma2"/>
                      </g>
                    </g>
                  </svg>
                </a>
              </div>
            </article>
            <!--===================== End of Last News ========================-->
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--===================== End of Last Mews ========================-->
  @if($phone ?? '')
    <a href="https://api.whatsapp.com/send?phone={{ $phone }}" class="act-btn">
      <i class="fab fa-whatsapp"></i>
    </a>
  @endif
@endsection

@section('addCss')
  <style>
      .act-btn {
          background: #00cd5a;
          display: block;
          width: 50px;
          height: 50px;
          line-height: 50px;
          text-align: center;
          color: white;
          font-size: 30px;
          font-weight: bold;
          border-radius: 50%;
          -webkit-border-radius: 50%;
          text-decoration: none;
          transition: ease all 0.3s;
          position: fixed;
          right: 30px;
          bottom: 30px;
      }

      .act-btn:hover {
          background: #0b2e13;
      }
  </style>
@endsection