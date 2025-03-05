@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
   <div class="container">
      <div class="light-font">
         <ol class="breadcrumb primary-color mb-0">
            <li class="breadcrumb-item"><a class="white-text" href="{{ route('account.orders') }}">My Account</a></li>
            <li class="breadcrumb-item">My Orders Details</li>
         </ol>
      </div>
   </div>
</section>

<section class=" section-11 ">
   <div class="container  mt-5">
      <div class="row">
         <div class="col-md-3">
            @include('front.account.common.sidebar')
         </div>
         <div class="col-md-9">
            <div class="card">
               <div class="card-header">
                  <h2 class="h5 mb-0 pt-2 pb-2">Orders Details</h2>
               </div>
               <div class="card-body pb-0">
                  <!-- Info -->
                  <div class="card card-sm">
                     <div class="card-body bg-light mb-3">
                        <div class="row">
                           <div class="col-6 col-lg-3">
                              <h6 class="heading-xxxs text-muted">Order No:</h6>
                              <p class="mb-lg-0 fs-sm fw-bold"> {{ $orders->id }} </p>
                           </div>
                           <div class="col-6 col-lg-3">
                              <h6 class="heading-xxxs text-muted">Shipped date:</h6>
                              <p class="mb-lg-0 fs-sm fw-bold">
                                 @if (!empty($orders->shipped_date))
                                    <time datetime="2019-10-01"> {{ \Carbon\Carbon::parse($orders->shipped_date)->format('d M, Y') }} </time>
                                 @else
                                    n/a
                                 @endif
                              </p>
                           </div>
                           <div class="col-6 col-lg-3">
                              <h6 class="heading-xxxs text-muted">Status:</h6>
                              <p class="mb-0 fs-sm fw-bold"> 
                                 @if ($orders->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                 @elseif ($orders->status == 'shipped')
                                    <span class="badge bg-info">Shipped</span>
                                 @elseif ($orders->status == 'delivered')
                                    <span class="badge bg-success">Delivered</span>
                                 @else
                                    <span class="badge bg-danger">Cancelled</span>
                                 @endif   
                              </p>
                           </div>
                           <div class="col-6 col-lg-3">
                              <h6 class="heading-xxxs text-muted">Order Amount:</h6>
                              <p class="mb-0 fs-sm fw-bold"> ${{ number_format($orders->grand_total, 2) }} </p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="card-footer p-3">
                  <h6 class="mb-7 h5 mt-4">Order Items ({{$orderItemCount}})</h6>
                  <hr class="my-3">
                  <ul>
                     @if ($orderItem->isNotEmpty())
                        @foreach ($orderItem as $orderDetails)
                           <li class="list-group-item">
                              <div class="row align-items-center">
                                 <div class="col-4 col-md-3 col-xl-2">
                                    @php
                                       $productImg = getProductImg($orderDetails->product_id);
                                    @endphp

                                    @if (!empty($productImg->image))
                                       <img class="img-fluid" src="{{ asset('uploads/product/small/'. $productImg->image) }}" alt="">
                                    @else
                                       <img class="img-fluid" src="{{ asset('front-assets/images/product-1.jpg') }}" alt="">
                                    @endif
                                 </div>
                                 <div class="col">
                                    <p class="mb-4 fs-sm fw-bold">
                                       <p class="text-body" >{{ $orderDetails->name }} * {{ $orderDetails->qty }}</p>
                                       <br>
                                       <span class="text-muted">${{ $orderDetails->price }}</span>
                                    </p>
                                 </div>
                              </div>
                           </li>
                        @endforeach
                     @endif   
                    
                  </ul>
               </div>
            </div>
            <div class="card card-lg mb-5 mt-3">
               <div class="card-body">
                  <h6 class="mt-0 mb-3 h5">Order Total</h6>
                  <ul>
                     <li class="list-group-item d-flex">
                        <span>Subtotal</span>
                        <span class="ms-auto">${{ number_format($orders->subtotal, 2) }}</span>
                     </li>
                     <li class="list-group-item d-flex">
                        <span>Discount {{ (!empty($orders->coupon_code)) ? '('.$orders->coupon_code.')' : ''}}</span>
                        <span class="ms-auto">${{ number_format($orders->discount, 2) }}</span>
                     </li>
                     <li class="list-group-item d-flex">
                        <span>Shipping</span>
                        <span class="ms-auto">${{ number_format($orders->shipping, 2) }}</span>
                     </li>
                     <li class="list-group-item d-flex fs-lg fw-bold">
                        <span>Total</span>
                        <span class="ms-auto">${{ number_format($orders->grand_total, 2) }}</span>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
@endsection