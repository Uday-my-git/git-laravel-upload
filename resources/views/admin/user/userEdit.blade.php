@extends('admin.layouts.app')

@section('content')
<section class="content-header">					
   <div class="container-fluid my-2">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>User Edit Form</h1>
         </div>
         <div class="col-sm-6 text-right">
            <a href="{{ route('user.index') }}" class="btn btn-primary">Back</a>
         </div>
      </div>
   </div>
</section>
<!-- Main content -->
<section class="content">
   <!-- Default box -->
   <div class="container-fluid">
      <form action="" name="userUpdateForm" id="user-update-id">     
         <div class="card">
            <div class="card-body">								
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" value="{{ $user->name }}" class="form-control" placeholder="Name" />	
                        <p></p>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" value="{{ $user->email }}" class="form-control" placeholder="Email" />	
                        <p></p>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="phone">Mobile</label>
                        <input type="text" name="phone" id="phone" value="{{ $user->phone }}" class="form-control" placeholder="Mobile" />
                        <p></p>	
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="status">Role</label>
                        <select name="role" class="form-control" id="role">
                           <option value="2" {{ ($user->role == 2) ? 'selected' : '' }}>Admin</option>
                           <option value="1" {{ ($user->role == 1) ? 'selected' : '' }}>User</option>
                        </select>
                        <p></p>
                     </div>
                  </div>								
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" id="status">
                           <option value="1" {{ ($user->status == 1) ? 'selected' : '' }}>Active</option>
                           <option value="0" {{ ($user->status == 0) ? 'selected' : '' }}>Deactive</option>
                        </select>
                     </div>
                  </div>	
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="" class="form-control" placeholder="Password" />	
                        <p class="text-danger">ignore this field, if not to update password field</p>
                     </div>
                  </div>								
               </div>
            </div>							
         </div>
         <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('user.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
         </div>
      </form>
   </div>
   <!-- /.card -->
</section>
@endsection

@section('custom-js')
<script>

$(function () {

   $('#user-update-id').submit(function (e) {
      e.preventDefault();

      $.ajax({
         type: "PUT",
         url: "{{ route('user.userupdate', $user->id) }}",
         data: $(this).serialize(),
         dataType: "JSON",
         success: function (response) {
            if (response['status'] === true) {
               $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
               $('#email').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
               $('#phone').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
               $('#role').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');

               $('#user-update-id')[0].reset();
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