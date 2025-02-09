@extends('admin.layouts.app')
@section('content')
    
<section class="content-header">					
   <div class="container-fluid my-2">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Create Brand</h1>
         </div>
         <div class="col-sm-6 text-right">
            <a href="{{ route('brands.brandListing') }}" class="btn btn-primary">Back</a>
         </div>
      </div>
   </div>
</section>
<section class="content">
   <div class="container-fluid">
      <form action="" method="POST" id="updateBrandForm">
         <div class="card">
            <div class="card-body">								
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{ $brand->name }}">	
                        <p></p>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="email">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug" value="{{ $brand->slug }}">
                        <p></p>	
                     </div>
                  </div>									
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="email">Status</label>
                        <select name="status" class="form-control" id="status">
                           <option value="">Select Status</option>
                           <option value="1" {{ ($brand->status == 1) ? 'selected' : ''}}>Active</option>
                           <option value="0" {{ ($brand->status == 0) ? 'selected' : ''}}>Block</option>
                        </select>
                     </div>
                  </div>									
               </div>
            </div>							
         </div>
         <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-primary">Update Now</button>
            <a href="{{ route('brands.brandListing') }}" class="btn btn-outline-dark ml-3">Cancel</a>
         </div>
      </form>     
   </div>
</section>
@endsection

@section('custom-js')
<script>

$(function () {  
   $("#updateBrandForm").submit(function(e) {
      e.preventDefault();

      $.ajax({
         type: "PUT",
         url: "{{ route('brands.update', $brand->id) }}",
         data: $(this).serializeArray(),
         dataType: "JSON",
         success: function (response) {
            if (response.status === true) {
               document.getElementById("updateBrandForm").reset();
               window.location.href= "{{ route('brands.brandListing') }}";
            } else {
               const error = response.errors;

               if (error.name) {
                  $("#name").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(error.name);
               } else {
                  $("#name").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               }

               if (error.slug) {
                  $("#slug").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(error.slug);
               } else {
                  $("#slug").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               }

               if (error.status) {
                  $("#status").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(error.status);
               } else {
                  $("#status").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               }

               if (response.notFound === true) {
                  window.location.href= "{{ route('brands.brandListing') }}";
               }
            }
         }
      });
   });

   // generate slug of name field
   $("#name").change(function () {  
      const slugVal = $(this).val();
      
      $.ajax({
         type: "GET",
         url: "{{ route('getSlug') }}",
         data: {title: slugVal},
         dataType: "JSON",
         success: function (response) {
            if (response.status === true) {
               $("#slug").val(response.slug);
            }
         }
      });
   });
});


</script>
@endsection