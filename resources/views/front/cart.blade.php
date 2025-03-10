@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
   <div class="container">
      <div class="light-font">
         <ol class="breadcrumb primary-color mb-0">
            <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
            <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
            <li class="breadcrumb-item">Cart</li>
         </ol>
      </div>
   </div>
</section>

<section class="section-9 pt-4">
   <div class="container">
      <div class="row">
         @if (Session::has('success'))
            <div class="col-md-12">
               <div class="alert alert-success alert-dismissible fade show" role="alert">
                  {!! Session::get('success') !!}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
               </div>   
            </div>
         @endif

         @if (Session::has('error'))
            <div class="col-md-12">
               <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  {{ Session::get('error') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
               </div>   
            </div>
         @endif

         @if (Cart::count() > 0)
            <div class="col-md-8">
               <div class="table-responsive">
                  <table class="table" id="cart">
                     <thead>
                        <tr>
                           <th>Item</th>
                           <th>Price</th>
                           <th>Quantity</th>
                           <th>Total</th>
                           <th>Remove</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach ($cartItem as $cartContent)
                           <tr>
                              <td>
                                 <div class="d-flex align-items-center">
                                    @if (!empty($cartContent->options->productImage->image))                           
                                       <img src="{{ asset('/uploads/product/small/' . $cartContent->options->productImage->image) }}" alt="">
                                    @else
                                       <img src="{{ asset('/uploads/dummy-img.jpg') }}" alt="">
                                    @endif

                                    <h2>{{ $cartContent->name }}</h2>
                                 </div>
                              </td>
                              <td>${{ $cartContent->price }}</td>
                              <td>
                                 <div class="input-group quantity mx-auto" style="width: 100px;">
                                    <div class="input-group-btn">
                                       <button class="btn btn-sm btn-dark btn-minus p-2 pt-1 pb-1 sub" data-id="{{ $cartContent->rowId }}">
                                          <i class="fa fa-minus"></i>
                                       </button>
                                    </div>
                                    <input type="text" class="form-control form-control-sm  border-0 text-center" value="{{ $cartContent->qty }}">
                                    <div class="input-group-btn">
                                       <button class="btn btn-sm btn-dark btn-plus p-2 pt-1 pb-1 add" data-id="{{ $cartContent->rowId }}">
                                          <i class="fa fa-plus"></i>
                                       </button>
                                    </div>
                                 </div>
                              </td>
                              <td>${{ $cartContent->price * $cartContent->qty}}</td>
                              <td>
                                 <button class="btn btn-sm btn-danger" onclick="removeCartItemFun('{{ $cartContent->rowId }}')"><i class="fa fa-times"></i></button>
                              </td>
                           </tr>     
                        @endforeach
                     </tbody>
                  </table>
               </div>
            </div>
            <div class="col-md-4">            
               <div class="card cart-summery">
                  <div class="card-body">
                     <div class="sub-title">
                        <h2 class="bg-white">Cart Summery</h3>
                     </div>
                     <div class="d-flex justify-content-between pb-2">
                        <div>Total</div>
                        <div>${{ Cart::subtotal() }}</div>
                     </div>
                     <div class="pt-2">
                        <a href="{{ route('front.checkout') }}" class="btn-dark btn btn-block w-100">Proceed to Checkout</a>
                     </div>
                  </div>
               </div>     
            </div>
         @else 
            <div class="col-md-12">
               <div class="card">
                  <div class="card-body d-flex justify-content-centre align-items-center">
                     <h3>No Product Found In Cart</h3>
                  </div>
               </div>
            </div>
         @endif
      </div>
   </div>
</section>
@endsection

@section('custom-js')
<script>

$(function () {
   $(".add").click(function () {
      var qtyElement = $(this).parent().prev();
      var qtyValue = parseInt(qtyElement.val());

      if (qtyValue < 10) {
         qtyElement.val(qtyValue + 1);
         var rowId = $(this).data("id");
         var newQty = qtyElement.val();
         updateCartFun(rowId, newQty);
      }
   });


   $(".sub").click(function () {
      var qtyElement = $(this).parent().next();
      var qtyValue = parseInt(qtyElement.val());

      if (qtyValue > 1) {
         qtyElement.val(qtyValue - 1)
         var rowId = $(this).data("id");
         var newQty = qtyElement.val();
         updateCartFun(rowId, newQty);
      }
   });

});

function updateCartFun(rowId, qty)
{
   $.ajax({
      type: "POST",
      url: "{{ route('front.updateCart') }}",
      data: {rowId:rowId, qty:qty},
      dataType: "JSON",
      success: function (response) {
         window.location.href = '{{ route("front.cart") }}';
      }
   });
}

function removeCartItemFun(rowId) 
{
   if (confirm("Are U Sure To Delete")) {
      $.ajax({
         type: "POST",
         url: "{{ route('front.removeCartItem') }}",
         data: "rowId=" +rowId,
         dataType: "JSON",
         success: function (response) {
            window.location.href = '{{ route("front.cart") }}';
         }
      });
   }
 
}


</script>
@endsection