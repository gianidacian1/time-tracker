@extends('layouts.app')

@section('content')
<div class="container">
   <div id="login-div">
        <div class="form-container">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="login-title2">
                    <img id="logo2" src="{{asset('/images/logo2.png')}}" alt="">
                    <span id="">Time Tracker</span>
                </div>
                <div class="form-group mb-5">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mb-5">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mb-5">
                    <div class="">
                        <button type="submit" class="btn  w-100 login-button">
                            {{ __('Login') }}
                        </button>

                        
                    </div>
                </div>
                @if (Route::has('password.request'))
                    <a class="d-flex justify-content-center btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
                <span class="register">No account?  <a class="" href="{{ route('register') }}">{{ __('Register') }}</a></span>
                
            </form>
        </div>
   </div>
</div>
@endsection

