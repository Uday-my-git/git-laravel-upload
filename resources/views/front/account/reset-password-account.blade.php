@extends('front.layouts.app')
@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
   <div class="container">
      <div class="light-font">
         <ol class="breadcrumb primary-color mb-0">
            <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
            <li class="breadcrumb-item">Login</li>
         </ol>
      </div>
   </div>
</section>

<section class=" section-10">
   @if ($message = Session::get('success'))
      <div class="alert alert-success d-flex align-items-center" role="alert">
         <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
         <div>
            {{ $message }}
         </div>
      </div>
   @endif

   @if (Session::has('error'))
      <div class="alert alert-danger">
         {{ Session::get('error') }}
      </div>
   @endif
   
   <div class="container">
      <div class="login-form">    
         <form action="{{ route('front.processResetPasswordAccount') }}" method="post">
            @csrf
            <h4 class="modal-title">Reset Password</h4>
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="form-group">
               <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" placeholder="Enter New Password" value="">
               @error('new_password')
                  <span class="text-danger">{{ $message }}</span>
               @enderror
            </div>
            <div class="form-group">
               <input type="password" name="confirm_password" class="form-control @error('confirm_password') is-invalid @enderror" placeholder="Enter Confirm Password" value="">
               @error('confirm_password')
                  <span class="text-danger">{{ $message }}</span>
               @enderror
            </div>
   
            <input type="submit" class="btn btn-dark btn-block btn-lg" value="Submit Now">              
         </form>			
         <div class="text-center small">Don't have an account? <a href="{{ route('account.register') }}">Sign up</a></div>
      </div>
   </div>
</section>
@endsection