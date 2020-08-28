@extends('layouts.appLogin')

@section('content')
  <div class="login-logo">
    <a href="{{ url('/') }}">
      <img src="{{ asset('images/Logo3.png') }}?new" class="login-logo" style="width: 100%;" alt="logo">
    </a>
  </div>

  <div class="row">
    <div class="col-md-12 mb-2">
      <a href="{{ url('download/dogearn.apk') }}" class="btn btn-block btn-primary">
        <i class="fab fa-android mr-2"></i> Download Wallet DOGEARN
      </a>
    </div>
    <div class="col-md-12">
      <a href="#" class="btn btn-block btn-success">
        <i class="fab fa-google-play mr-2"></i> Download Via Play Store
      </a>
    </div>
  </div>
@endsection