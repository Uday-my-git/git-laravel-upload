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
         <form action="{{ route('front.processForgotPassword') }}" method="post">
            @csrf
            <h4 class="modal-title">Change Password</h4>
         
            <div class="form-group">
               <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter Email" value="">
               @error('email')
                  <span class="text-danger">{{ $message }}</span>
               @enderror
            </div>
   
            <input type="submit" class="btn btn-dark btn-block btn-lg" value="Send">              
         </form>			
         <div class="text-center small">Don't have an account? <a href="{{ route('account.register') }}">Sign up</a></div>
      </div>
   </div>
</section>
@endsection