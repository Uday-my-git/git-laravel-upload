@extends('admin.layouts.app')

@section('content')

<section class="content-header">					
   <div class="container-fluid my-2">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Create Sub Category</h1>
         </div>
         <div class="col-sm-6 text-right">
            <a href="{{ route('sub-category.index') }}" class="btn btn-primary">Back</a>
         </div>
      </div>
   </div>
   <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
   <!-- Default box -->
   <div class="container-fluid">
      <form action="" name="sub-categoryForm" id="sub-categoryFormId">     
         <div class="card">
            <div class="card-body">								
               <div class="row">
                  <div class="col-md-12">
                     <div class="mb-3">
                        <label for="name">Category</label>
                        <select name="category" id="category_id" class="form-control">
                           <option value="">Select Sub Category</option>  
                           @if ($category->isNotEmpty())
                              @foreach ($category as $getSubCategory)
                                 {{-- <option value="{{ $getSubCategory->id }}" {{ ($subCategory->category_id == $getSubCategory->id) ? 'selected' : ''}}>{{ $getSubCategory->name }}</option> --}}

                                 <option value="{{ $getSubCategory->id }}" @selected($subCategory->category_id == $getSubCategory->id)>
                                    {{ $getSubCategory->name }}
                                 </option>
                              @endforeach
                           @else                              
                           @endif                                           
                        </select>
                        <p></p>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" value="{{ $subCategory->name }}" class="form-control" placeholder="Name">	
                        <p></p>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ $subCategory->slug }}" class="form-control" placeholder="Slug" readonly>
                        <p></p>	
                     </div>
                  </div>									
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" id="status">
                           <option value="">Select Status</option>
                           <option value="1" @selected($subCategory->status == 1)>Active</option>
                           <option value="0" @selected($subCategory->status == 0)>Block</option>
                        </select>
                        <p></p>
                     </div>
                  </div>	
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="showHome">Show On Home</label>
                        <select class="form-control" name="showHome" id="showHome">
                           <option value="No" @selected($subCategory->showHome == "No")>Deactive</option>   
                           <option value="Yes" @selected($subCategory->showHome == "Yes")>Active</option>   
                        </select>	
                     </div>
                  </div>									
               </div>
            </div>							
         </div>
         <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-primary">Update Now</button>
            <a href="{{ route('sub-category.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
         </div>
      </form>
   </div>
   <!-- /.card -->
</section>
@endsection

@section('custom-js')
<script>

$(function() {

   $("#sub-categoryFormId").submit(function(event) {
      event.preventDefault();

      $.ajax({
         type: "PUT",
         url: "{{ route('sub_categories.update', $subCategory->id) }}",
         data: $(this).serializeArray(),
         dataType: "JSON",
         success: function (response) {
            if (response.status === true) {
               $("#name").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               $("#slug").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               location.href="{{ route('sub-category.index') }}";
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

               if (response["notFound"] === true) {
                  location.href="{{ route('sub-category.index') }}";
               }
            }
         }
      });
   });

   $("#name").change(function () { 
      const slugVal = $(this).val();
      
      $.ajax({
         type: "GET",
         url: "{{ route('getSlug') }}",
         data: {title: slugVal},
         dataType: "JSON",
         success: function (response) {
            if (response["status"]) {
               $("#slug").val(response["slug"]);
            }
         }
      });
   });
});


</script>
@endsection