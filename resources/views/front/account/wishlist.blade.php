@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
   <div class="container">
      <div class="light-font">
         <ol class="breadcrumb primary-color mb-0">
            <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
            <li class="breadcrumb-item">Settings</li>
         </ol>
      </div>
   </div>
</section>

<section class=" section-11 ">
   <div class="container  mt-5">
      <div class="row">
         <div class="col-md-12">
            @include('front.account.common.message')
         </div>
         <div class="col-md-3">
            @include('front.account.common.sidebar')
         </div>
         <div class="col-md-9">
            <div class="card">
                  <div class="card-header">
                     <h2 class="h5 mb-0 pt-2 pb-2">My Wishlist</h2>
                  </div>
                  <div class="card-body p-4">
                     @if ($wishlist->isNotEmpty())
                        @foreach ($wishlist as $wishlistItem)
                           <div class="d-sm-flex justify-content-between mt-lg-4 mb-4 pb-3 pb-sm-2 border-bottom">
                              <div class="d-block d-sm-flex align-items-start text-center text-sm-start">
                                 @php
                                    $productImg = getProductImg($wishlistItem->product_id);
                                 @endphp

                                 @if (!empty($productImg))
                                    <a href="{{ route('front.product', $wishlistItem->product->slug) }}" class="d-block flex-shrink-0 mx-auto me-sm-4" style="width: 10rem;"><img src="{{ asset('/uploads/product/large/' . $productImg->image) }}" alt="Product"></a>
                                 @else
                                    <a href="#" class="d-block flex-shrink-0 mx-auto me-sm-4" style="width: 10rem;"><img src="{{ asset('/uploads/dummy-img.jpg') }}" alt="Product"></a>  
                                 @endif
                                 
                                 <div class="pt-2">
                                    <h3 class="product-title fs-base mb-2">
                                       <a href="{{ route('front.product', $wishlistItem->product->slug) }}">{{ $wishlistItem->product->title }}</a>
                                    </h3>                                        
                                    <div class="fs-lg text-accent pt-2">${{ number_format($wishlistItem->product->price) }}</div>
                                 </div>
                              </div>
                              <div class="pt-2 ps-sm-3 mx-auto mx-sm-0 text-center">
                                 <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeWishlistFun('{{ $wishlistItem->product_id }}')">
                                    <i class="fas fa-trash-alt me-2"></i>   
                                    Remove
                                 </button>
                              </div>
                           </div>   
                        @endforeach 
                     @else 
                        <tr><td>Wishlist Is Empty</td></tr>   
                     @endif
                     
                  </div>
            </div>
         </div>
      </div>
   </div>
</section>
@endsection

@section('custom-js')
<script>

function removeWishlistFun(product_id)
{
   if (confirm('Aru u sure to delete product')) {
      $.ajax({
         type: "POST",
         url: "{{ route('account.removeWishlist') }}",
         data: {product_id:product_id},
         dataType: "JSON",
         success: function (response) {
            if (response.status === true) {
               window.location.href = '{{ route("account.wishlist") }}';
            }
         }
      });
   }
  
}

</script>
@endsection