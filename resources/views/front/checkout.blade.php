@extends('front.layouts.app')
@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
   <div class="container">
      <div class="light-font">
         <ol class="breadcrumb primary-color mb-0">
            <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
            <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
            <li class="breadcrumb-item">Checkout</li>
         </ol>
      </div>
   </div>
</section>
<section class="section-9 pt-4">
   <div class="container">
      <form action="" name="orderForm" id="order-formId" method="POST">
         <div class="row">
            <div class="col-md-8">
               <div class="sub-title">
                  <h2>Shipping Address</h2>
               </div>
               <div class="card shadow-lg border-0">
                  <div class="card-body checkout-form">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="mb-3">
                              <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" value="{{ (!empty($customerAddress)) ? ($customerAddress->first_name) : ''}}">
                              <p></p>          
                           </div>   
                        </div>
                        <div class="col-md-12">
                           <div class="mb-3">
                              <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" value="{{ (isset($customerAddress)) ? ($customerAddress->last_name) : ''}}">
                              <p></p>  
                           </div>            
                        </div>
                     
                        <div class="col-md-12">
                           <div class="mb-3">
                              <input type="text" name="email" id="email" class="form-control" placeholder="Email" value="{{ (isset($customerAddress)) ? ($customerAddress->email) : ''}}">
                              <p></p>  
                           </div>            
                        </div>
                        <div class="col-md-12">
                           <div class="mb-3">
                              <select name="country" id="country_id" class="form-control">
                                 <option value="">Select a Country</option>
                                 @if ($countreisFetch->isNotEmpty())
                                    @foreach ($countreisFetch as $countries)
                                       <option @selected($customerAddress && $customerAddress->country_id == $countries->id) value="{{ $countries->id }}">{{ $countries->name }}</option>
                                    @endforeach
                                 @endif
                              </select> 
                              <p></p>  
                           </div>            
                        </div>
                        <div class="col-md-12">
                           <div class="mb-3">
                              <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" class="form-control">{{ (isset($customerAddress)) ? ($customerAddress->address) : '' }}</textarea>
                              <p></p>  
                           </div>            
                        </div>
                        <div class="col-md-12">
                           <div class="mb-3">
                              <input type="text" name="apartment" id="apartment" class="form-control" placeholder="Apartment, suite, unit, etc. (optional)" value="{{ (isset($customerAddress)) ? ($customerAddress->apartment) : ''}}">
                           </div>            
                        </div>
                        <div class="col-md-4">
                           <div class="mb-3">
                           <input type="text" name="city" id="city" class="form-control" placeholder="City" value="{{ (isset($customerAddress)) ? ($customerAddress->city) : ''}}">
                           <p></p>  
                           </div>            
                        </div>
                        <div class="col-md-4">
                           <div class="mb-3">
                           <input type="text" name="state" id="state" class="form-control" placeholder="State" value="{{ (isset($customerAddress)) ? ($customerAddress->state) : ''}}">
                           <p></p>  
                           </div>            
                        </div>                     
                        <div class="col-md-4">
                           <div class="mb-3">
                           <input type="text" name="zip" id="zip" class="form-control" placeholder="Zip" value="{{ (isset($customerAddress)) ? ($customerAddress->zip) : ''}}">
                           </div>            
                        </div>
                        <div class="col-md-12">
                           <div class="mb-3">
                           <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile No." value="{{ (isset($customerAddress)) ? ($customerAddress->mobile) : ''}}">
                           <p></p>  
                           </div>            
                        </div>                     
                        <div class="col-md-12">
                           <div class="mb-3">
                              <textarea name="notes" id="notes" cols="30" rows="2" placeholder="Order Notes (optional)" class="form-control"></textarea>
                           </div>            
                        </div>
                     </div>
                  </div>
               </div>    
            </div>
            <div class="col-md-4">
               <div class="sub-title">
                  <h2>Order Summery</h3>
               </div>                    
               <div class="card cart-summery">
                  <div class="card-body">
                     @foreach (Cart::content() as $productItem)
                        <div class="d-flex justify-content-between pb-2">
                           <div class="h6">{{ $productItem->name }} x {{ $productItem->qty }}</div>
                           <div class="h6">${{ $productItem->price * $productItem->qty }}</div>
                        </div>
                     @endforeach
                  
                     <div class="d-flex justify-content-between summery-end">
                        <div class="h6"><strong>Subtotal</strong></div>
                        <div class="h6"><strong>${{ Cart::subtotal() }}</strong></div>
                     </div>
                     <div class="d-flex justify-content-between mt-2">
                        <div class="h6"><strong>Shipping</strong></div>
                        <div class="h6"><strong id="shipping-amount">${{ number_format($totalShippingCharges, 2) }}</strong></div>
                     </div>
                     <div class="d-flex justify-content-between mt-2">
                        <div class="h6"><strong>Discount</strong></div>
                        <div class="h6"><strong id="discountCoupon">${{ $discountCoupon }}</strong></div>
                     </div>
                     <div class="d-flex justify-content-between mt-2 summery-end">
                        <div class="h5"><strong>Total</strong></div>
                        <div class="h5"><strong id="grand-total">${{ number_format($grandTotal, 2) }}</strong></div>
                     </div>                            
                  </div>
               </div>   
               <div class="input-group apply-coupan mt-4">
                  <input type="text" name="coupon_code" placeholder="Coupon Code" class="form-control" id="couponCode">
                  <button class="btn btn-dark" type="button" id="apply-coupon-code">Apply Coupon</button>
               </div>
               <p id="www"></p>

               <div id="coupon-wrapper">
                  @if (Session::has('coupon_code'))
                     <div class="mt-4" id="remove-coupon-response">
                        <strong> {{ Session::get('coupon_code')->coupon_code }} </strong>
                        <a class="btn btn-danger" id="remove-coupon-code"><i class="fa fa-times"></i></a>
                     </div>
                  @endif
               </div>

               <div class="card payment-form ">                        
                  <h3 class="card-title h5 mb-3">Payment Methods:-</h3>
                  <div>
                     <input type="radio" name="payment_method" id="payment-method-one" value="cod" checked>
                     <label for="payment_method" class="form-check-label">COD</label>
                  </div>
                  <div>
                     <input type="radio" name="payment_method" id="payment-method-two" value="stripe">
                     <label for="payment_method_two" class="form-check-label">Stripe</label>
                  </div>
                  
                  <div class="card-body p-0 mt-3 d-none" id="card-payment-form">
                     <h3 class="card-title h5 mb-3">Payment Details:-</h3>
                     <div class="mb-3">
                        <label for="card_number" class="mb-2">Card Number</label>
                        <input type="text" name="card_number" id="card_number" placeholder="Valid Card Number" class="form-control">
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <label for="expiry_date" class="mb-2">Expiry Date</label>
                           <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY" class="form-control">
                        </div>
                        <div class="col-md-6">
                           <label for="expiry_date" class="mb-2">CVV Code</label>
                           <input type="text" name="expiry_date" id="expiry_date" placeholder="123" class="form-control">
                        </div>
                     </div>
                  </div>                        
                  <div class="pt-4">
                     <button type="submit" class="btn-dark btn btn-block w-100" id="paynow-btn">Pay Now</button>
                  </div>
               </div>
            </div>
         </div>
      </form>
   </div>
</section>
@endsection

@section('custom-js')
<script>

$(function () {  

   $("#payment-method-one").click(function () {
      if ($(this).is(":checked") == true) {
         $("#card-payment-form").addClass("d-none");
      }
   });

   $("#payment-method-two").click(function () {
      if ($(this).is(":checked") == true) {
         $("#card-payment-form").removeClass("d-none");
      }
   });

   $("#order-formId").submit(function (e) { 
      e.preventDefault();

      $("button[type='submit']").prop('disabled', true);
      $('#paynow-btn').text('Please Wait....');

      $.ajax({
         type: "POST",
         url: "{{ route('front.processCheckout') }}",
         data: $(this).serializeArray(),
         dataType: "JSON",
         success: function (response) {
            $("button[type='submit']").prop('disabled', false);

            if (response.status === true) {
               $("#first_name").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               $("#last_name").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               $("#email").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               $("#country_id").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               $("#address").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               $("#city").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               $("#state").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               $("#mobile").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");

               $("#order-formId")[0].reset();
               location.href="{{ url('/thank-you-page/') }}/"+response.orderId;
            } else {
               var error = response.errors;

               if (error.first_name) {
                  $("#first_name").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(error.first_name);
               } else {
                  $("#first_name").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               }
               if (error.last_name) {
                  $("#last_name").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(error.last_name);
               } else {
                  $("#last_name").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               }
               if (error.email) {
                  $("#email").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(error.email);
               } else {
                  $("#email").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               }
               if (error.country) {
                  $("#country_id").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(error.country);
               } else {
                  $("#country_id").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               }
               if (error.address) {
                  $("#address").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(error.address);
               } else {
                  $("#address").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               }
               if (error.city) {
                  $("#city").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(error.city);
               } else {
                  $("#city").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               }
               if (error.state) {
                  $("#state").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(error.state);
               } else {
                  $("#state").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               }
               if (error.mobile) {
                  $("#mobile").addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(error.mobile);
               } else {
                  $("#mobile").removeClass("is-invalid").siblings("p").removeClass("invalid-feedback").html("");
               }
            }
         }
      });
   });

   $("#country_id").change(function () {  
      var country_id = $(this).val();      

      $.ajax({
         type: "post",
         url: "{{ route('front.getOrderSummery') }}",
         data: "country_id=" +country_id,
         dataType: "json",
         success: function (response) {
            if (response.status === true) {               
               $("#shipping-amount").html('$'+ response.data.shippingCharge);
               $("#grand-total").html('$'+ response.data.grandTotal);
            }
         }
      });
   });

   // apply coupon code ajax
   $("#apply-coupon-code").click(function () {  
      var country_id = $("#country_id").val();      
      var couponCode = $("#couponCode").val();  

      $.ajax({
         type: "post",
         url: "{{ route('front.applyCouponCode') }}",
         data: "country_id="+country_id+"&couponCode="+couponCode,
         dataType: "json",
         success: function (response) {
            if (response.status === true) {                              
               $("#shipping-amount").html('$'+ response.data.shippingCharge);
               $("#discountCoupon").html('$'+ response.data.discountCoupon);
               $("#grand-total").html('$'+ response.data.grandTotal);
               $("#coupon-wrapper").html(response.data.couponHtml);
            } else {
               $("#coupon-wrapper").html("<span class='text-danger'>"+response.msg+"</span>");
            }
         }
      });
   });

   // remove apply coupon code ajax
   // $("#remove-coupon-code").click(function () {  

   $("body").on("click", "#remove-coupon-code", function () {  
      var country_id = $("#country_id").val();      

      if (country_id != "") {
         $.ajax({
            type: "post",
            url: "{{ route('front.removeCouponCode') }}",
            data: "country_id="+country_id,
            dataType: "json",
            success: function (response) {               
               if (response.status === true) {                              
                  $("#shipping-amount").html('$'+ response.data.shippingCharge);
                  $("#discountCoupon").html('$'+ response.data.discountCoupon);
                  $("#grand-total").html('$'+ response.data.grandTotal);
                  $("#remove-coupon-response").html("");
               } 
            }
         });
      }
   });

});




</script>
@endsection