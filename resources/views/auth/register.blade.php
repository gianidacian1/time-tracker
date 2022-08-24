@extends('layouts.app')

@section('content')
<div class="container">
   <div id="login-div">
        <div class="form-container">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="login-title2">
                    <img id="logo2" src="{{asset('/images/logo2.png')}}" alt="">
                    <span id="">Time Tracker</span>
                </div>
                <div class="form-group mb-5">
                    <input id="name" placeholder="Name..." type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mb-5">
                    <input id="email" placeholder="Email..." type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mb-5">
                    <input id="password" placeholder="Password..." type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mb-5">
                        <input placeholder="Confirm password" id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                </div>


                <div class="form-group mb-5">
                    <div class="">
                        <button type="submit" class="btn  w-100 login-button">
                            {{ __('Register') }}
                        </button>

                        
                    </div>
                </div>
          
            </form>
        </div>
   </div>
</div>
@endsection
