@extends('admin.layouts.app')

@section('content')

<section class="content-header">					
   <div class="container-fluid my-2">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Coupon Code</h1>
         </div>
         <div class="col-sm-6 text-right">
            <a href="{{ route('coupons.create') }}" class="btn btn-primary btn-sm">New Coupon Code</a>
         </div>
      </div>
   </div>
</section>

<section class="content">
   <div class="container-fluid">
      @include('admin.message')
      <div class="card">
         <form action="" method="GET">
            <div class="card-header">
               <div class="card-title">
                  <button type="button" class="btn btn-default btn-sm" onclick="window.location.href='{{ route('coupons.list') }}'">Reset</button>
               </div>
               <div class="card-tools">
                  <div class="input-group input-group" style="width: 250px;">
                     <input type="text" name="search" class="form-control float-right" value="{{ Request::get('search') }}" placeholder="Search">
      
                     <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                           <i class="fas fa-search"></i>
                        </button>
                     </div>
                  </div>
               </div>
            </div>
         </form>
         <div class="card-body table-responsive p-0">		            						
            <table class="table table-hover table-sm text-nowrap">
               <thead>
                  <tr>
                     <th width="60">ID</th>
                     <th>Coupon Code</th>
                     <th>Name</th>
                     <th>Description</th>
                     <th>Max Usese</th>
                     <th>Max Uses User</th>
                     <th>Type</th>
                     <th>Discount Amount</th>
                     <th>Starts At</th>
                     <th>Expires At</th>
                     <th>Min Amount</th>
                     <th width="100">Status</th>
                     <th width="100">Action</th>
                  </tr>
               </thead>
               <tbody>
                  @if ($data->isNotEmpty())
                     @foreach ($data as $couponCode)
                        <tr>
                           <td>{{ $couponCode->id }}</td>
                           <td>{{ $couponCode->coupon_code }}</td>
                           <td>{{ $couponCode->nameDiscountCoupon }}</td>
                           <td>{{ $couponCode->description }}</td>
                           <td>{{ $couponCode->max_uses }}</td>
                           <td>{{ $couponCode->max_uses_user }}</td>
                           <td>{{ $couponCode->type }}</td>
                           <td>
                              @if ($couponCode->type == 'percent')
                                 {{ $couponCode->discount_amount }}%   
                              @else
                                 ${{ $couponCode->discount_amount }}
                              @endif
                           </td>
                           <td>{{ (!empty($couponCode->starts_at)) ? \Carbon\Carbon::parse($couponCode->starts_at)->format('Y/m/d H:i:s') : ''}}</td>
                           <td>{{ (!empty($couponCode->expires_at)) ? \Carbon\Carbon::parse($couponCode->expires_at)->format('Y/m/d H:i:s') : ''}}</td>
                           <td>{{ $couponCode->min_amount }}</td>
                           <td>             
                              @if ($couponCode->status == 1)
                                 <svg class="text-success-500 h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                 </svg> 
                              @else
                                 <svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                              </svg>   
                              @endif          
                           </td>
                           <td>
                              <a href="{{ route('coupons.edit', $couponCode->id) }}">
                                 <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                 </svg>
                              </a>
                              <a href="javascript:void(0)" class="text-danger w-4 h-4 mr-1" onclick="deleteCouponFun({{ $couponCode->id }})">
                                 <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                 </svg>
                              </a>
                           </td>
                        </tr>                      
                     @endforeach
                  @endif
               </tbody>
            </table>										
         </div>
         <div class="card-footer clearfix">
            {{ $data->links() }}
         </div>
      </div>
   </div>
</section>
@endsection

@section('custom-js')
<script>

function deleteCouponFun(id)
{
   const url = "{{ route('coupons.destroy', 'ID') }}";
   const newUrl = url.replace('ID', id);

   if (confirm('Are U Sure To Delete')) {
      $.ajax({
         type: "delete",
         url: newUrl,
         dataType: "json",
         success: function (response) {
            if (response["status"] === true) {
               location.href = "{{ route('coupons.list') }}";
            }
         }
      });
   }

 
}

</script>
@endsection