@extends('layouts.appLogin')

@section('content')
  <div class="login-logo">
    <a href="{{ url('/') }}">
      <img src="{{ asset('images/Logo2.png') }}?new" class="login-logo" style="width: 100px;" alt="logo">
    </a>
  </div>
  <!-- /.login-logo -->
  <div class="card elevation-2">
    <div class="card-body login-card-body">
      <form action="{{ route('login') }}" method="post">
        @csrf
        @error('username')
        <div class="text-danger" role="alert">
          <small>{{ $message }}</small>
        </div>
        @enderror
        <div class="input-group mb-3">
          <input id="email" type="email" class="form-control @error('email')is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                 placeholder="Email Address">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Masukan Katasandi">
          <div class="input-group-append">
            <div class="input-group-text">
              <div class="fas fa-lock"></div>
            </div>
          </div>
          @error('password')
          <div class="text-danger" role="alert">
            <small>{{ $message }}</small>
          </div>
          @enderror
        </div>

        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">
              Login
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
