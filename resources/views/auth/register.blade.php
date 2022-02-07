@extends('layouts.app')

@section('content')
<div class="container">

   <form class="form-signin" method="POST" action="{{ route('register') }}">
       @csrf
       <a href="{{ url('/') }}" class="text-center brand">
        <img src="{{ asset('assets/img/ashlar.png') }}" srcset="{{ asset('assets/img/ashlar.png') }}" alt="Ashlar Logo" class="login-image"/>
       </a>
       <h2 class="form-signin-heading">Please sign up</h2>
       <div class="form-group">
           <label for="inputName" class="sr-only">Name</label>
           <input type="text" id="inputName" class="form-control  @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Full Name">
           @error('name')
               <span class="invalid-feedback" role="alert">
                   <strong>{{ $message }}</strong>
               </span>
           @enderror
       </div>

       <div class="form-group">
           <label for="inputEmail" class="sr-only">Email address</label>
           <input type="email" id="inputEmail" class="form-control  @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email address" required>
           @error('email')
               <span class="invalid-feedback" role="alert">
                   <strong>{{ $message }}</strong>
               </span>
           @enderror
       </div>

       <div class="form-group">
           <label for="inputPassword" class="sr-only">Password</label>
           <input type="password" id="inputPassword" class="form-control  @error('password') is-invalid @enderror" name="password" placeholder="Password" required>

           @error('password')
               <span class="invalid-feedback" role="alert">
                   <strong>{{ $message }}</strong>
               </span>
           @enderror
       </div>

       <div class="form-group">
           <label for="inputConfirmPassword" class="sr-only">Confirm Password</label>
           <input type="password" id="inputConfirmPassword" class="form-control"  name="password_confirmation" placeholder="Confirm Password" required>
       </div>

       <div class="mt-4 mb-4 checkbox">
           <label class="custom-control custom-checkbox">
               <input type="checkbox" name="term_and_conditions" class="custom-control-input" required>
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">
                   I Agree the <a href="#" class="default-color">terms and conditions.</a>
               </span>
           </label><br>
           @if ($errors->has('term_and_conditions'))
           <span class="text-danger">
               <strong style="color: #dc3545;">{{ $errors->first('term_and_conditions') }}</strong>
           </span>
           @endif
       </div>
       <button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>

       <div class="mt-4">
           <span>
               Already have an account ?
           </span>
           <a href="{{ route('login')}}" class="text-primary">Sign In</a>
       </div>
   </form>

</div>
@endsection
