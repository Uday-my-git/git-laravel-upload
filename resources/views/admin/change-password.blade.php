@extends('admin.layouts.app')@section('content')
<section class="content-header">					
   <div class="container-fluid my-2">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Change Password</h1>
         </div>
         {{-- <div class="col-sm-6 text-right">
            <a href="categories.html" class="btn btn-primary">Back</a>
         </div> --}}
      </div>
   </div>
</section>
<section class="content">
   <div class="container-fluid">
      @include('admin.message')

      <div class="card">
         <div class="card-body">		
            <form action="{{ route('admin.chagePassword') }}" method="POST">	
               @csrf					
               <div class="row">
                  <div class="col-md-12">
                     <div class="mb-3">
                        <label for="name">Old Password</label>
                        <input type="password" name="old_password" id="old_password" class="form-control" placeholder="Enter Old Password">	
                        @error('old_password')
                           <span class="text-danger">{{ $message }}</span>
                        @enderror
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="mb-3">
                        <label for="email">New Password</label>
                        <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Enter New Password">	
                           @error('new_password')
                              <span class="text-danger">{{ $message }}</span>
                           @enderror
                     </div>
                  </div>									
                  <div class="col-md-12">
                     <div class="mb-3">
                        <label for="email">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Enter Confirm Password">	
                        @error('confirm_password')
                           <span class="text-danger">{{ $message }}</span>
                        @enderror
                     </div>
                  </div>									
               </div>
               <div class="pb-5 pt-3">
                  <button type="submit" class="btn btn-primary">Save</button>
               </div>
            </form>
         </div>							
      </div>
   </div>
</section>
@endsection