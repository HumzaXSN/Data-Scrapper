@extends('layouts.app')

@section('content')

    <div class="container">
        <form class="form-signin" method="POST" action="{{ route('login') }}">
            @csrf
            <a href="{{ url('/') }}" class="text-center brand">
                <img src="{{ asset('assets/img/ashlar.png') }}" srcset="{{ asset('assets/img/ashlar.png') }}" alt="Ashlar Logo" class="login-image"/>
            </a>
            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            <h2 class="form-signin-heading">Please sign in</h2>
            <div class="form-group">
                <label for="inputEmail" class="sr-only">Email address</label>
                <input type="email" id="inputEmail" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email address" required>

                @error('email')
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="inputPassword" class="sr-only">Password</label>
                <input type="password" id="inputPassword" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required>

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mt-4 mb-4 checkbox">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" name="remember" class="custom-control-input">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">
                        Remember me
                    </span>
                </label>
                <a href="{{ route('password.request') }}"  class="float-right text-muted">Forgot Password ?</a>
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>

            <div class="mt-4">
                <span>
                    Don't have an account yet ?
                </span>
                <a href="{{ route('register')}}" class="text-primary">Sign Up</a>
            </div>
        </form>

    </div>

@endsection

