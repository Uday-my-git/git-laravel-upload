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
</section>
<!-- Main content -->
<section class="content">
   <!-- Default box -->
   <div class="container-fluid">
      <form action="" name="sub-categoryForm" id="sub_category_id">     
         <div class="card">
            <div class="card-body">								
               <div class="row">
                  <div class="col-md-12">
                     <div class="mb-3">
                        <label for="name">Category</label>
                        <select name="category" id="category_id" class="form-control">
                           <option value="">Select Sub Category</option>

                           @if ($categories->isNotEmpty())
                              @foreach ($categories as $category)
                                 <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                        <input type="text" name="name" id="name" class="form-control" placeholder="Name" />	
                        <p></p>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug" readonly />
                        <p></p>	
                     </div>
                  </div>									
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" id="status">
                           {{-- <option value="">Select Status</option> --}}
                           <option value="0">Block</option>
                           <option value="1">Active</option>
                        </select>
                        <p></p>
                     </div>
                  </div>	
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="showHome">Show On Home</label>
                        <select class="form-control" name="showHome" id="showHome">
                           <option value="No">Deactive</option>   
                           <option value="Yes">Active</option>   
                        </select>	
                     </div>
                  </div>									
               </div>
            </div>							
         </div>
         <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="subcategory.html" class="btn btn-outline-dark ml-3">Cancel</a>
         </div>
      </form>
   </div>
   <!-- /.card -->
</section>
@endsection

@section('custom-js')
<script>

$(document).ready(function () {  
   
   $("#sub_category_id").submit(function (e) {  
      e.preventDefault();
      const data = $(this).serializeArray();

      $.ajax({
         type: "POST",
         url: "{{ route('sub_categories.store') }}",
         data: data,
         dataType: "JSON",
         success: function (response) {
            if (response.status === true) {
               $("#category_id").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               $("#name").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               $("#slug").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");

               window.location.href = "{{ route('sub-category.index') }}";
            } else {
               const error = response.errors;

               if (error.category) {
                  $("#category_id").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(error.category);
               }

               if (error.name) {
                  $("#name").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(error.name);
               }

               if (error.slug) {
                  $("#slug").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(error.slug);
               }
            }            
         },
         error: function (jqXHR, exception) {
            console.log("errror!! occured");
         }
      });
   });

   // generate slug
   $("#name").change(function () {  
      const slug = $(this).val();
      
      $.ajax({
         type: "GET",
         url: "{{ route('getSlug') }}",
         data: {title: slug},
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