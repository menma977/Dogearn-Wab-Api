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
    <div class="col-md-12 mb-2">
      <a href="{{ url('https://play.google.com/store/apps/details?id=net.dogearn') }}" class="btn btn-block btn-success">
        <i class="fab fa-google-play mr-2"></i> Download Via Play Store
      </a>
    </div>
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
    @if($phone ?? '')
      <div class="col-md-12">
        <div class="card card-outline card-success">
          <div class="card-body">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <label class="input-group-text">
                  <i class="fab fa-whatsapp mr-2"></i>
                </label>
              </div>
              <div type="text" class="form-control">{{ $phone }}</div>
            </div>
            <a href="https://api.whatsapp.com/send?phone={{ $phone }}" class="btn btn-block btn-success">
              <i class="fab fa-whatsapp mr-2"></i> Contract Sponsor
            </a>
          </div>
        </div>
      </div>
    @endif
  </div>
@endsection