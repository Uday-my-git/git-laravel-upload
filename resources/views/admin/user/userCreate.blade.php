@extends('admin.layouts.app')

@section('content')
<section class="content-header">					
   <div class="container-fluid my-2">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>User Create Form</h1>
         </div>
         <div class="col-sm-6 text-right">
            <a href="{{ route('user.index') }}" class="btn btn-primary">Back</a>
         </div>
      </div>
   </div>
</section>
<!-- Main content -->
<section class="content">
   <div class="container-fluid">
      <form action="" name="userForm" id="user-form-id" method="POST">     
         <div class="card">
            <div class="card-body">								
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Name" />	
                        <p></p>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="Email" />	
                        <p></p>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="phone">Mobile</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="Mobile" />
                        <p></p>	
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="status">Role</label>
                        <select name="role" class="form-control" id="role">
                           <option value="">Select Role</option>
                           <option value="2">Admin</option>
                           <option value="1">User</option>
                        </select>
                        <p></p>
                     </div>
                  </div>								
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" id="status">
                           <option value="0">Deactive (Default)</option>
                           <option value="1">Active</option>
                        </select>
                        <p></p>
                     </div>
                  </div>	
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" />	
                        <p></p>
                     </div>
                  </div>								
               </div>
            </div>							
         </div>
         <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('user.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
         </div>
      </form>
   </div>
</section>
@endsection

@section('custom-js')
<script>

$(function () {

   $('#user-form-id').submit(function (e) {
      e.preventDefault();

      $.ajax({
         type: "POST",
         url: "{{ route('user.save') }}",
         data: $(this).serialize(),
         dataType: "JSON",
         success: function (response) {
            if (response['status'] === true) {
               $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
               $('#email').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
               $('#phone').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
               $('#role').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
               $('#password').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');

               $('#user-form-id')[0].reset();
               location.href = '{{ route("user.index") }}';
            } else {
               let errors = response.errors;

               $.each(errors, function (key, value) {  
                  $(`#${key}`).addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(value[0]);
               });
            }
         }
      });
   });


});

</script>
@endsection