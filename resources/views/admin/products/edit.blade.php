@extends('admin.layouts.app') @section('content') <section class="content-header">
   <div class="container-fluid my-2">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Edit Product</h1>
         </div>
         <div class="col-sm-6 text-right">
            <a href="{{ route('product.productListing') }}" class="btn btn-primary btn-sm">Back</a>
         </div>
      </div>
   </div>
   <!-- /.container-fluid -->
</section>
<section class="content">
   <form action="" name="productForm" id="productForm" method="POST">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-8">
               <div class="card mb-3">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="mb-3">
                              <label for="title">Title</label>
                              <input type="text" name="title" id="title" class="form-control" value="{{ $product->title }}" placeholder="Title">
                              <p class="error"></p>
                           </div>
                        </div>
                        <div class="col-md-12">
                           <div class="mb-3">
                              <label for="slug">Slug</label>
                              <input type="text" name="slug" id="slug" class="form-control" value="{{ $product->slug }}" placeholder="Slug" readonly>
                              <p class="error"></p>
                           </div>
                        </div>
                        <div class="col-md-12">
                           <div class="mb-3">
                              <label for="description">Short Description</label>
                              <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote" placeholder="Short Description">{{ $product->short_description }}</textarea>
                              <p class="error"></p>
                           </div>
                        </div>
                        <div class="col-md-12">
                           <div class="mb-3">
                              <label for="description">Description</label>
                              <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description">{{ $product->description }}</textarea>
                              <p class="error"></p>
                           </div>
                        </div>
                        <div class="col-md-12">
                           <div class="mb-3">
                              <label for="description">Shipping Returns</label>
                              <textarea name="shipping_returns" id="shipping_returns" cols="30" rows="10" class="summernote" placeholder="Description">{{ $product->shipping_returns }}</textarea>
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
                  @if ($getproductImg->isNotEmpty())
                     @foreach ($getproductImg as $productImage)
                        <div class="col-md-3" id="img-row-id-{{ $productImage->id }}">
                           <div class="card">
                              <img src="{{ asset('uploads/product/small/' . $productImage->image) }}" class="card-img-top" height="150" width="120" alt="...">
                              
                              <input type="hidden" name="imge_array[]" value="{{ $productImage->id }}">
                              <div class="card-body">
                                 <a href="javascript:void(0)" class="btn btn-danger" onclick="deleteImgFun({{ $productImage->id }})">Delete</a>
                              </div>
                           </div>   
                        </div>
                     @endforeach    
                  @endif                 
               </div>
               <div class="card mb-3">
                  <div class="card-body">
                     <h2 class="h4 mb-3">Pricing</h2>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="mb-3">
                              <label for="price">Price</label>
                              <input type="text" name="price" id="price" class="form-control" value="{{ $product->price }}" placeholder="Price">
                              <p class="error"></p>
                           </div>
                        </div>
                        <div class="col-md-12">
                           <div class="mb-3">
                              <label for="compare_price">Compare at Price</label>
                              <input type="text" name="compare_price" id="compare_price" class="form-control" value="{{ $product->compare_price }}" placeholder="Compare Price">
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
                              <input type="text" name="sku" id="sku" class="form-control" value="{{ $product->sku }}" placeholder="sku">
                              <p class="error"></p>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="mb-3">
                              <label for="barcode">Barcode</label>
                              <input type="text" name="barcode" id="barcode" class="form-control" value="{{ $product->barcode }}" placeholder="Barcode">
                           </div>
                        </div>
                        <div class="col-md-12">
                           <div class="mb-3">
                              <div class="custom-control custom-checkbox">
                                 <input type="hidden" name="track_qty" value="No">

                                 <input name="track_qty" class="custom-control-input" type="checkbox" id="track_qty" value="Yes" {{ ($product->track_qty ==  'Yes') ? 'checked' : '' }}  >
                                 <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                 <p class="error"></p>
                              </div>
                           </div>
                           <div class="mb-3">
                              <input type="number" min="0" name="qty" id="qty" class="form-control" value="{{ $product->qty }}" placeholder="Qty">
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
                           @if ($relatedProducts)
                              @foreach ($relatedProducts as $relatedProductItems)
                                 <option selected value="{{ $relatedProductItems->id }}">{{ $relatedProductItems->title }}</option>
                              @endforeach                               
                           @endif
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
                           <option value="1" {{ ($product->status == 1) ? 'selected' : ''}}>Active</option>
                           <option value="0" {{ ($product->status == 0) ? 'selected' : ''}}>Block</option>
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
                              @foreach ($category as $categoryItem)
                                 <option value="{{ $categoryItem->id }}" @selected($product->category_id == $categoryItem->id)>{{ $categoryItem->name }}</option>
                              @endforeach    
                           @else
                           @endif
                        </select>
                     </div>
                     <div class="mb-3">
                        <label for="subCategory">Sub category</label>
                        <select name="sub_category" id="sub_category" class="form-control">

                           @if ($subCategory->isNotEmpty())
                              @foreach ($subCategory as $subcategory)
                                 <option value="{{ $subcategory->id }}" @selected($product->sub_category_id == $subcategory->id)>{{ $subcategory->name }}</option>                                  
                              @endforeach
                           @endif
                           {{-- <option value="">Home Theater</option>
                           <option value="">Headphones</option> --}}
                        </select>
                     </div>
                  </div>
               </div>
               <div class="card mb-3">
                  <div class="card-body">
                     <h2 class="h4 mb-3">Product brand</h2>
                     <div class="mb-3">
                        <select name="brand" id="brand_id" class="form-control">
                           @if ($brands->isNotEmpty())
                              @foreach ($brands as $brandsItem)
                                 <option value="{{ $brandsItem->id }}" @selected($product->brand_id == $brandsItem->id)>{{ $brandsItem->name }}</option> 
                              @endforeach    
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
                           <option value="Yes" {{ ($product->is_featured == 'Yes') ? 'selected' : '' }}>Yes</option>
                           <option value="No" {{ ($product->is_featured == 'No') ? 'selected' : '' }}>No</option>
                        </select>
                        <p class="error"></p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="pb-5 pt-3">
            <button class="btn btn-primary">Update</button>
            <a href="{{ route('product.productListing') }}" class="btn btn-outline-dark ml-3">Cancel</a>
         </div>
      </div>
   </form>
</section>
@endsection

@section('custom-js')
    
<script>

Dropzone.autoDiscover = false;

$(function() {

   $("#productForm").submit(function (e) {  
      e.preventDefault();
      const formData = $(this).serializeArray();

      $.ajax({
         type: "PUT",
         url: "{{ route('product.update', $product->id) }}",
         data: formData,
         dataType: "JSON",
         success: function (response) {
            if (response.status === true) {
               $(".error").removeClass("invalid-feedback").html("");
               $("input[type='text'], select, input[type='number']").removeClass("is-invalid");

               location.href = '{{ route('product.productListing') }}';
            } else {
               const error = response.errors;

               $(".error").removeClass("invalid-feedback").html("");
               $("input[type='text'], select, input[type='number']").removeClass("is-invalid");

               $.each(error, function (key, errValue) {
                  $(`#${key}`).addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(errValue);
               });
            }
         }
      });
   });


   $("#title").change(function () { 
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
                  $("#sub_category").html('<option value="">Select Sub-Category</option>');

                  $.each(response.msg, function (key, value) {  
                     $("#sub_category").append(`<option value="${value.id}">${value.name}</option>`);
                  });
               }
            }
         });
      }
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
      url: "{{ route('product-image.update') }}",
      maxFiles: 10,
      paramName: 'image',
      params: {'product_id' : '{{ $product->id }}'},
      addRemoveLinks: true,
      acceptedFiles: "image/jpeg,image/png,image/gif",
      headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }, 
      success: function(file, response){
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


function deleteImgFun(id) {
   if (confirm("Are U Sure To Delete ??")) {
      $.ajax({
         type: "delete",
         url: "{{ route('product-image.delete', $product->id) }}",
         data: "id="+id,
         success: function (response) {
            if (response.status === true) {
               $("#img-row-id-"+id).remove();
               alert(response.msg);
            } else {
               console.log(response.msg);
            }
         }
      });
   }
}
</script>
@endsection