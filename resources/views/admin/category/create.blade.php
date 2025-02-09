@extends('admin.layouts.app')

@section('content')

<!-------- Content Header (Page header) -------->
<section class="content-header">					
   <div class="container-fluid my-2">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Create Category</h1>
         </div>
         <div class="col-sm-6 text-right">
            <a href="{{ route('categories.index') }}" class="btn btn-primary">Back</a>
         </div>
      </div>
   </div>
   <!-- /.container-fluid -->
</section>

<!-------------- Main content -------------->
<section class="content">
   <div class="container-fluid">
      <form action="" name="categorieForm" id="categorieForm" method="POST">
         @csrf
         <div class="card">
            <div class="card-body">								
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Name">	
                        <p></p>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control" placeholder="Generated Slug" readonly>	
                        <p></p>
                     </div>
                  </div>		
                  <div class="col-md-6">
                     <div class="mb-3">
                        <input type="hidden" name="image_id" id="image_id" value="">
                        <label for="image">Image</label>
                        <div id="image" class="dropzone dz-clickable">
                           <div class="dz-message needsclick">    
                              <br>Drop files here or click to upload.<br><br>                                            
                           </div>
                       </div>   
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
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="status">Status</label>
                        <select class="form-control" name="status" id="status-id">
                           {{-- <option value="">Select Status</option>    --}}
                           <option value="1">Active (Default)</option>   
                           <option value="0">Deactive</option>   
                        </select>	
                     </div>
                  </div>									
               </div>
            </div>							
         </div>
         <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route("categories.index") }}" class="btn btn-outline-dark ml-3" >Cancel</a>
         </div>
      </form>   
   </div>
</section>
<!-- /.content -->

@endsection

@section('custom-js')
    
<script>
   
Dropzone.autoDiscover = false;

$(document).ready(function() {

   $("#categorieForm").submit(function(e) {
      e.preventDefault();
      const data = $(this).serializeArray();

      $.ajax({
         type: "POST",
         url: "{{ route('categories.store') }}",
         data: data,
         dataType: "JSON",
         success: function (response) {
            if (response.status === true) {
               $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");

               $("#categorieForm")[0].reset();
               window.location.href='{{ route("categories.index") }}';
            } else{
               const error = response.errors;

               if (error.name) {
                  $("#name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(error.name);
               } else {
                  $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }

               if (error.slug) {
                  $("#slug").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(error.slug);
               }else {
                  $("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
            }
         }, 
         error: function (jqXHR, exception) {  
            console.log("error occured!!");
         }
      });
   });

   // slug generator
   $("#name").change(function() {  
      var slugVal = $(this).val();
      
      $.ajax({
         type: "GET",
         url: "{{ route('getSlug') }}",
         data: {title: slugVal},
         dataType: "JSON",
         success: function (response) {
            if(response['status'] == true) {
               $("#slug").val(response['slug']);
            }
         }
      });   
   });

   // dropzone image 
   const dropzone = $("#image").dropzone({ 
      init: function() {
         this.on('addedfile', function(file) {
            if (this.files.length > 1) {
               this.removeFile(this.files[0]);
            }
         });
      },
      url:  "{{ route('temp-images.create') }}",
      maxFiles: 1,
      paramName: 'image',
      addRemoveLinks: true,
      acceptedFiles: "image/jpeg,image/png,image/gif",
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }, 
      success: function(file, response){
         $("#image_id").val(response.img_id);
         // console.log(response)
      }
   });
   


});



  


</script>

@endsection