@extends('admin.layouts.app') 
@section('content') <section class="content-header">
   <div class="container-fluid my-2">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Create Product</h1>
         </div>
         <div class="col-sm-6 text-right">
            <a href="{{ route('product.productListing') }}" class="btn btn-primary btn-sm">Back</a>
         </div>
      </div>
   </div>
</section>

<section class="content">
   <form action="" name="productForm" id="createProductForm" method="POST">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-8">
               <div class="card mb-3">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="mb-3">
                              <label for="title">Title</label>
                              <input type="text" name="title" id="title" class="form-control" placeholder="Title">
                              <p class="error"></p>
                           </div>
                        </div>
                        <div class="col-md-12">
                           <div class="mb-3">
                              <label for="slug">Slug</label>
                              <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug" readonly>
                              <p class="error"></p>
                           </div>
                        </div>
                        <div class="col-md-12">
                           <div class="mb-3">
                              <label for="short_description">Short Description</label>
                              <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote" placeholder="Short Description"></textarea>
                              <p class="error"></p>
                           </div>
                        </div>
                        <div class="col-md-12">
                           <div class="mb-3">
                              <label for="description">Description</label>
                              <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description"></textarea>
                              <p class="error"></p>
                           </div>
                        </div>
                        <div class="col-md-12">
                           <div class="mb-3">
                              <label for="description">Shipping Returns</label>
                              <textarea name="shipping_returns" id="shipping_returns" cols="30" rows="10" class="summernote" placeholder="Shipping Returns"></textarea>
                              <p class="error"></p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="card mb-3">
                  <div class="card-body">
                     <h2 class="h4 mb-3">Media</h2>
                     <div id="image" class="dropzone dz-clickable">
                        <div class="dz-message needsclick">
                           <br>Drop files here or click to upload. <br>
                           <br>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row" id="product-gallery">
                  
               </div>
               <div class="card mb-3">
                  <div class="card-body">
                     <h2 class="h4 mb-3">Pricing</h2>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="mb-3">
                              <label for="price">Price</label>
                              <input type="text" name="price" id="price" class="form-control" placeholder="Price">
                              <p class="error"></p>
                           </div>
                        </div>
                        <div class="col-md-12">
                           <div class="mb-3">
                              <label for="compare_price">Compare At Price</label>
                              <input type="text" name="compare_price" id="compare_price" class="form-control" placeholder="Compare Price">
                              <p class="text-muted mt-3"> To show a reduced price, move the product’s original price into Compare at price. Enter a lower value into Price. </p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="card mb-3">
                  <div class="card-body">
                     <h2 class="h4 mb-3">Inventory</h2>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="mb-3">
                              <label for="sku">SKU (Stock Keeping Unit)</label>
                              <input type="text" name="sku" id="sku" class="form-control" placeholder="sku">
                              <p class="error"></p>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="mb-3">
                              <label for="barcode">Barcode</label>
                              <input type="text" name="barcode" id="barcode_id" class="form-control" placeholder="Barcode">
                           </div>
                        </div>
                        <div class="col-md-12">
                           <div class="mb-3">
                              <div class="custom-control custom-checkbox">
                                 <input type="hidden" name="track_qty" value="No">
                                 <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" value="Yes" checked>
                                 <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                 <p class="error"></p>
                              </div>
                           </div>
                           <div class="mb-3">
                              <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty">
                              <p class="error"></p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="card mb-3">
                  <div class="card-body">
                     <label for="barcode">Related product</label>
                     <div class="mb-3">
                        <select name="related_products[]" class="related-product w-100" id="related_products" multiple>
                        </select>
                        <p class="error"></p>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-4">
               <div class="card mb-3">
                  <div class="card-body">
                     <h2 class="h4 mb-3">Product status</h2>
                     <div class="mb-3">
                        <select name="status" id="status" class="form-control">
                           <option value="1">Active</option>
                           <option value="0">Block</option>
                        </select>
                     </div>
                  </div>
               </div>
               <div class="card">
                  <div class="card-body">
                     <h2 class="h4  mb-3">Product category</h2>
                     <div class="mb-3">
                        <label for="category">Category</label>
                        <select name="category" id="category_id" class="form-control">
                           <option value="">Select Category</option>

                           @if ($category->isNotEmpty())
                              @foreach ($category as $categoryData)
                                 <option value="{{ $categoryData->id }}">{{ $categoryData->name }}</option>
                              @endforeach
                           @else                          
                           @endif
                        </select>
                        <p class="error"></p>
                     </div>
                     <div class="mb-3">
                        <label for="category">Sub category</label>
                        <select name="sub_category" id="sub_category_id" class="form-control">
                           <option value="">Select Sub-Category</option>
                        </select>
                        <p class="error"></p>
                     </div>
                  </div>
               </div>
               <div class="card mb-3">
                  <div class="card-body">
                     <h2 class="h4 mb-3">Product brand</h2>
                     <div class="mb-3">
                        <select name="brand" id="brand_id" class="form-control">
                           <option value="">Select Product Brand</option>

                           @if ($brands->isNotEmpty())
                              @forelse ($brands as $brandsItem)
                                 <option value="{{ $brandsItem->id }}">{{ $brandsItem->name }}</option>
                              @empty
                                 <tr><td>No Product Brands Availabel</td></tr>                                 
                              @endforelse
                           @else
                           @endif
                        </select>
                     </div>
                  </div>
               </div>
               <div class="card mb-3">
                  <div class="card-body">
                     <h2 class="h4 mb-3">Featured product</h2>
                     <div class="mb-3">
                        <select name="is_featured" id="is_featured" class="form-control">
                           {{-- <option>Select Featured Product</option> --}}
                           <option value="No">No (Default)</option>
                           <option value="Yes">Yes</option>
                        </select>
                        <p class="error"></p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('product.productListing') }}" class="btn btn-outline-dark ml-3">Cancel</a>
         </div>
      </div>
   </form>
</section>
@endsection

@section('custom-js')
<script>

Dropzone.autoDiscover = false;

$(function () {  

   $("#createProductForm").submit(function (e) {  
      e.preventDefault();
      $("button[type='submit']").prop('disabled', true);

      const formData = $(this).serialize();

      $.ajax({
         type: "POST",
         url: "{{ route('product.save') }}",
         data: formData,
         dataType: "JSON",
         success: function (response) {
            $("button[type='submit']").prop("disabled", false);

            if (response.status === true) {
               $(".error").removeClass("invalid-feedback").html("");
               $("input[type='text'], select, input[type='number']").removeClass("is-invalid");

               window.location.href = "{{ route('product.productListing') }}";
            } else {
               const error = response.errors;

               $(".error").removeClass("invalid-feedback").html("");
               $("input[type='text'], select, input[type='number']").removeClass("is-invalid");

               $.each(error, function(key, value) {
                  $(`#${key}`).addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(value);
               });
            }
         }
      });
   });

   // sub category fetch
   $("#category_id").on("change", function () {  
      const categoryId = $(this).val();
      
      if (categoryId != "") {
         $.ajax({
            type: "GET",
            url: "{{ route('product.subCategory') }}",
            data: {category_id: categoryId},
            dataType: "JSON",
            success: function (response) {
               if (response.status === true) {
                  $("#sub_category_id").html('<option value="">Select Sub-Category</option>');

                  $.each(response.msg, function (key, value) {  
                     $("#sub_category_id").append(`<option value="${value.id}">${value.name}</option>`);
                  });
               }
            }
         });
      }
   });

   $("#title").change(function () {  
      const slugVal = $(this).val();

      $.ajax({
         type: "GET",
         url: "{{ route('getSlug') }}",
         data: "title="+slugVal,
         dataType: "JSON",
         success: function (response) {
            if (response.status === true) {
               $("#slug").val(response.slug);
            }
         }
      });
   });

   // dropzone image 
   const dropzone = $("#image").dropzone({ 
      init: function() {
         this.on('addedfile', function(file) {
            if (this.files.length > 10) {
               this.removeFile(this.files[0]);
            }
         });
      },
      url:  "{{ route('temp-images.create') }}",
      maxFiles: 10,
      paramName: 'image',
      addRemoveLinks: true,
      acceptedFiles: "image/jpeg,image/png,image/gif",
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }, 
      success: function(file, response){
         // console.log(response);
         
         var html = `<div class="col-md-3" id="img-row-id-${response.img_id}">
            <div class="card">
               <img src="${response.img_path}" class="card-img-top" height="150" width="100" alt="...">
               
               <input type="hidden" name="imge_array[]" value="${response.img_id}">
               <div class="card-body">
                  <a href="javascript:void(0)" class="btn btn-danger" onclick="deleteImgFun(${response.img_id})">Delete</a>
               </div>
            </div>   
         </div>`

         $("#product-gallery").append(html);
      },
      // complete: function(file) {
      //    this.removeFile(file);
      // }
   });

    // selet2 dropdowan
    $('.related-product').select2({
      ajax: {
         url: '{{ route("product.getRelatedProducts") }}',
         dataType: 'json',
         tags: true,
         multiple: true,
         minimumInputLength: 3,
         processResults: function (data) {
            return {
               results: data.tags
            };
         }
      }
   }); 


});



function deleteImgFun(id) 
{
   $("#img-row-id-"+id).remove();
}

</script>
@endsection