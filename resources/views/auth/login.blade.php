@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="{{URL::asset('../../vendors/iconfonts/mdi/css/materialdesignicons.min.css')}}">
  <link rel="stylesheet" href="{{URL::asset('../../vendors/css/vendor.bundle.base.css')}}">
  <link rel="stylesheet" href="{{URL::asset('../../css/style.css')}}">
  <link rel="shortcut icon" href="/images/ncaa-logo.png" />
@stop

@section('content')
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
            <div class="row w-100">
                <div class="col-lg-4 mx-auto">
                    
                    <div class="auth-form-light text-left p-5">
                        <div class="brand-logo">
                            <img src="{{URL::asset('../../images/ncaa-logo.png')}}" style="width:120px; height:120px; float:right">
                        </div>
                        <h4>Nigerian Civil Aviation Authority</h4>
                        <h6 class="font-weight-light">
                            @if(Auth::check())
                                <span class="mdi mdi-alert" style="display:block; color:#ff0000; margin-top:20px; margin-bottom:20px; font-weight:bold"> IMPORTANT </span>
                            @else
                                Sign in to continue
                            @endif
                        </h6>
                        @if(Auth::check())
                            
                           <p style="color:green; font-size:13px; line-height:25px;"> Hi, {{Auth::user()->name}}! Thank you for registering. Your Account is still pending verification from the Administrator. If approved by the Admin a user role will be assigned.
                            Thanks.</p>
                        @else
                        <form class="pt-3"method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus placeholder="Email">

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                            </div>
                            <div class="form-group">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="Password">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                                    {{ __('Login') }}
                                </button>
                            </div>
                            <div class="my-2 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <label class="form-check-label text-muted" style="font-size:12px;">
                                <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                Keep me signed in
                                </label>
                            </div>
                            <!-- <a href="#" class="auth-link text-black">Forgot password?</a> -->
                                @if (Route::has('password.request'))
                                    <a class="auth-link text-black" href="{{ route('password.request') }}" style="font-size:12px">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
@endsection

