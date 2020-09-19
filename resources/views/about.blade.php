@extends('layouts.frontEndApp')

@section('content')
  <!--===================== First Section ========================-->
  <div class="first-section animatedParent">
    <div class="container">
      <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
          <div class="text">
            <h1>About <br> Dogearn</h1>
            <span class="d-flex align-items-center"><b class="line">&nbsp;</b>Community<b class="line">&nbsp;</b></span>
          </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-12">
          <img src="assets/img/about-img.png" alt="about-img">
        </div>
      </div>
    </div>
    <div class="cloud">&nbsp;</div>
    <div class="cloud-two">&nbsp;</div>
    <div class="mini-cloud"></div>
    <div class="mini-cloud two"></div>
    <div class="mini-cloud three"></div>
  </div>
  <!--===================== About Us========================-->
  <div class="philosophy-block">
    <div class="container-fluid">
      <div class="row animatedParent">
        <div class="col-md-6 philosophy-div1">

        </div>
        <div class="col-md-6 philosophy-div2">
          <h4>About Us</h4>
          <h2 class="h2-main" id="aboutus">What is Dogearn?</h2>
          <p>DOGEARN community is a decentralized community network based on the DOGECOIN blockchain. and also Dogearn:</p>
          <li>Not Ponzi.</li>
          <br>
          <li>Not ROI.</li>
          <br>
          <li>Not an investment scheme.</li>
          <br>
          <li>Not Trading or Mining.</li>
          <br>
          <li>No system load / scam.</li>
        </div>
      </div>
    </div>
  </div>
  <!--===================== End of philosophy Block ========================-->

  <!--===================== About Us========================-->
  <div class="container light">
    <div class="container-fluid">
      <div class="row animatedParent">
        <div class="col-md-12 philosophy-div2">
          <h4>DCP</h4>
          <h2 class="h2-main">Digital Community Program</h2>
          <p>DCP is a support system, as an educational forum with a structured,
            directed and sustainable curriculum, as well as a platform for personal development (attitude, leadership, & speaking skills)
            as well as network development for the entire Dogearn community.</p>
          <br>
          <br>
          <li>Vision</li>
          <p>Successfully cultivating and building the potential of human resources in each individual and fostering the values ​​of togetherness,
            the spirit of mutual cooperation, and a high sense of solidarity, so as to create a community that has a harmonious,
            solid, resilient & potential ecosystem.</P>
          <br>
          <li>Mission</li>
          <p>Prepare infrastructure & curriculum with integrity.</p>
          <p>Consistently providing correct and updated education about cryptocurrency.</p>
          <p>Consistently fostering dynamic, measured and harmonious emotional bonds.</p>
        </div>
      </div>
    </div>
  </div>
  <!--===================== End of philosophy Block ========================-->
  <br><br><br><br>
  <!--===================== Join Us ========================-->
  <div class="container join-us" id="service">
    <div class="row">
      <div class="col-xl-12">
        <div class="text-head text-center">
          <h2 class="h2-main">Join our community</h2>
          <a href="{{ url('download/dogearn.apk') }}" class="see-brd-btn">Download</a>
          <a href="{{ url('https://play.google.com/store/apps/details?id=net.dogearn') }}" class="see-brd-btn">Via play Store</a>
        </div><!--text-head-->
      </div>
    </div>
  </div>
  <!--===================== End of Join Us ========================-->
@endsection