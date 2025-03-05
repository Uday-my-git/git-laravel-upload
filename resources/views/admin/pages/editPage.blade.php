@extends('admin.layouts.app')

@section('content')

<!-------- Content Header (Page header) -------->
<section class="content-header">					
   <div class="container-fluid my-2">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Create Pages</h1>
         </div>
         <div class="col-sm-6 text-right">
            <a href="{{ route('pages.listPage') }}" class="btn btn-primary">Back</a>
         </div>
      </div>
   </div>
   <!-- /.container-fluid -->
</section>

<!-------------- Main content -------------->
<section class="content">
   <div class="container-fluid">
      <form action="" name="editPagesForm" id="edit-pages-form" method="POST">
         @csrf
         <div class="card">
            <div class="card-body">								
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" value="{{ $pages->name }}" class="form-control" placeholder="Name">	
                        <p></p>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ $pages->slug }}" class="form-control" placeholder="Generated Slug" readonly>	
                     </div>
                  </div>		
                  <div class="col-md-12">
                     <div class="mb-3">
                        <label for="description">Content</label>
                        <textarea name="content" id="content" cols="30" rows="10" class="summernote" placeholder="Shipping Returns">
                           {{ $pages->content }}
                        </textarea>
                        <p></p>
                     </div>
                  </div>														
               </div>
            </div>							
         </div>
         <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route("pages.listPage") }}" class="btn btn-outline-dark ml-3" >Cancel</a>
         </div>
      </form>   
   </div>
</section>
@endsection

@section('custom-js')
<script>

$(function () {  

   $('#edit-pages-form').submit(function (e) {  
      e.preventDefault();

      $.ajax({
         type: "PUT",
         url: "{{ route('pages.update', $pages->id) }}",
         data: $(this).serializeArray(),
         dataType: "JSON",
         success: function (response) {
            if (response.status === true) {
               $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $("#content").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");

               $("#edit-pages-form")[0].reset();
               window.location.href='{{ route("pages.listPage") }}';
            } else {
               var error = response.errors;

               if (error.name) {
                  $("#name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(error.name);
               } else {
                  $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
               if (error.content) {
                  $("#content").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(error.content);
               } else {
                  $("#content").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
            }
         }
      });
   });

   $('#name').change(function () {
      let slugVal = $(this).val();
      
      $.ajax({
         type: "GET",
         url: "{{ route('getSlug') }}",
         data: "title="+slugVal,
         dataType: "JSON",
         success: function (response) {
            if(response['status'] === true) {
               $("#slug").val(response['slug']);
            }
         }
      });
   }); 



});


</script>
@endsection