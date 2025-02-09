@extends('admin.layouts.app')

@section('content')

<!-------- Content Header (Page header) -------->
<section class="content-header">					
   <div class="container-fluid my-2">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Update Coupon Code</h1>
         </div>
         <div class="col-sm-6 text-right">
            <a href="{{ route('coupons.list') }}" class="btn btn-primary">Back</a>
         </div>
      </div>
   </div>
   <!-- /.container-fluid -->
</section>

<!-------------- Main content -------------->
<section class="content">
   <div class="container-fluid">
      <form action="" name="discountForm" id="discount_form" method="POST">
         @csrf
         <div class="card">
            <div class="card-body">								
               <div class="row">
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="name">Coupon Code</label>
                        <input type="text" name="coupon_code" id="coupon_code" class="form-control" value="{{ $data->coupon_code }}" placeholder="Coupon Code">	
                        <p></p>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="nameDiscountCoupon">Name</label>
                        <input type="text" name="nameDiscountCoupon" id="nameDiscountCoupon" class="form-control" value="{{ $data->nameDiscountCoupon }}" placeholder="Name">	
                        <p></p>
                     </div>
                  </div>		
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="description">Description</label>
                        <textarea name="description" class="form-control" id="description" cols="30" rows="3" placeholder="Short Description">
                           {{ $data->description }}
                        </textarea>
                     </div>
                  </div>	
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="max_uses">Max Uses</label>
                        <input type="text" name="max_uses" id="max_uses" class="form-control" value="{{ $data->max_uses }}" placeholder="Max Uses">	
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="max_uses_user">Max Uses User</label>
                        <input type="text" name="max_uses_user" id="max_uses_user" class="form-control" value="{{ $data->max_uses_user }}" placeholder="Max Uses">	
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="max_uses">Type</label>
                        <select class="form-control" name="type" id="type">
                           <option value="fixed" @selected($data->type == 'fixed')>Fixed (Default)</option>   
                           <option value="percent" @selected($data->type == 'percent')>Percentage</option>   
                        </select>	
                     </div>
                  </div>
                 
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="max_uses">Starts At</label>
                        <input type="text" name="starts_at" id="starts_at" class="form-control" value="{{ $data->starts_at }}" placeholder="Starts At">	
                        <p></p>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="max_uses">Expires At</label>
                        <input type="text" name="expires_at" id="expires_at" class="form-control" value="{{ $data->expires_at }}" placeholder="Expires At">	
                        <p></p>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="max_uses">Discount Amount</label>
                        <input type="text" name="discount_amount" id="discount_amount" class="form-control" value="{{ $data->discount_amount }}" placeholder="Discount Amount">	
                        <p></p>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="max_uses">Min Amount</label>
                        <input type="text" name="min_amount" id="min_amount" class="form-control" value="{{ $data->min_amount }}" placeholder="Min Amount">	
                        <p></p>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="mb-3">
                        <label for="status">Status</label>
                        <select class="form-control" name="status" id="status-id">
                           {{-- <option value="">Select Status</option>    --}}
                           <option value="1" {{ ($data->status == 1) ? 'selected' : ''}}>Active (Default)</option>   
                           <option value="0" {{ ($data->status ==0) ? 'selected' : ''}}>Deactive</option>   
                        </select>	
                     </div>
                  </div>									
               </div>
            </div>							
         </div>
         <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-primary">Update Now</button>
            <a href="{{ route("coupons.list") }}" class="btn btn-outline-dark ml-3" >Cancel</a>
         </div>
      </form>   
   </div>
</section>
<!-- /.content -->
@endsection
@section('custom-js')
<script>

$(function () {

   $('#starts_at').datetimepicker({
      format:'Y-m-d H:i:s',
   });

   $('#expires_at').datetimepicker({
      format:'Y-m-d H:i:s',
   });

   
   $("#discount_form").submit(function (event) {
      event.preventDefault();

      $.ajax({
         type: "put",
         url: "{{ route('coupons.update', $data->id) }}",
         data: $(this).serializeArray(),
         dataType: "json",
         success: function (response) {
            if (response['status'] === true) {
               $("#discount_form")[0].reset();
               window.location.href = '{{ route("coupons.list") }}';
            } else {
               const res = response.errors;

               if (res.coupon_code) {
                  $("#coupon_code").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(res.coupon_code);
               } else {
                  $("#coupon_code").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               }

               if (res.nameDiscountCoupon) {
                  $("#nameDiscountCoupon").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(res.nameDiscountCoupon);
               } else {
                  $("#nameDiscountCoupon").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               }

               if (res.discount_amount) {
                  $("#discount_amount").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(res.discount_amount);
               } else {
                  $("#discount_amount").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               }

               if (res.starts_at) {
                  $("#starts_at").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(res.starts_at);
               } else {
                  $("#starts_at").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               }

               if (res.expires_at) {
                  $("#expires_at").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(res.expires_at);
               } else {
                  $("#expires_at").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               }

               if (res.min_amount) {
                  $("#min_amount").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(res.min_amount);
               } else {
                  $("#min_amount").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               }
            }
            
         }
      });
   });
})


</script>
@endsection