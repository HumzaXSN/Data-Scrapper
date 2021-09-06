@extends('layouts.app')

@section('content')

<main>
    <div class="container">
        <form  class="form-signin" method="POST" action="{{ route('password.email') }}">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            @csrf
            <a class="text-center brand" href="{{ url('/') }}">
                <img src="assets/img/logo-dark.png" srcset="assets/img/logo-dark@2x.png 2x" alt=""/>
            </a>
            <div class="form-group">
                <label for="email" class="sr-only">E-Mail Address</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email address" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <button type="submit" class="btn btn-lg btn-primary btn-block">
                {{ __('Send Password Reset Link') }}
            </button>
        </form>
    </div>
</main>

@endsection
