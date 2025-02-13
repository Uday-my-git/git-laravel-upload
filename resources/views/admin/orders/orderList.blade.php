@extends('admin.layouts.app')
@section('content')
<section class="content-header">					
   <div class="container-fluid my-2">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Order Details</h1>
         </div>
      </div>
   </div>
</section>

<!-- Main content -->
<section class="content">
   <div class="container-fluid">
      @include('admin.message')
      <div class="card">
         <form action="" name="search" method="GET">
            <div class="card-header">
               <div class="card-title">
                  <button type="button" class="btn btn-default btn-sm" onclick="window.location.href='{{ route('orders.index') }}'">Reset Now</button>
               </div>
               <div class="card-tools">
                  <div class="input-group input-group" style="width: 250px;">
                     <input type="text" name="search" class="form-control float-right" placeholder="Search" value="{{ Request::get('search') }}">
      
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
                     <th width="60">Order ID</th>
                     <th>Customer Name</th>
                     <th>Email</th>
                     <th>Mobile</th>
                     <th width="100">Status</th>
                     <th>Total</th>
                     <th width="100">Purchase Date</th>
                  </tr>
               </thead>
               <tbody>
                  @if ($data->isNotEmpty())
                     @foreach ($data as $orderItem)
                        <tr>
                           <td><a href="{{ route('orders.getOrderDetail', $orderItem->id) }}"><button class="btn btn-primary btn-sm" style="color: black">{{ $orderItem->id }}</button></a></td>
                           <td>{{ $orderItem->name }}</td>
                           <td>{{ $orderItem->email }}</td>
                           <td>{{ $orderItem->mobile }}</td>
                           <td>
                              @if ($orderItem->status == 'pending')
                                 <span class="badge bg-warning">Pending</span>
                              @elseif ($orderItem->status == 'shipped')
                                 <span class="badge bg-info">Shipped</span>
                              @elseif ($orderItem->status == 'delivered')
                                 <span class="badge bg-success">Delivered</span>
                              @else
                                 <span class="badge bg-danger">Cancelled</span>
                              @endif       
                           </td>
                           <td>{{ number_format($orderItem->grand_total, 2) }}</td>
                           {{-- <td>{{ $orderItem->formattedDate }}</td> --}}
                           <td>{{ \Carbon\Carbon::parse($orderItem->created_at)->format('d M, Y') }}</td>
                        </tr>     
                     @endforeach 
                  @else
                     <tr><td>No Order Found</td></tr>
                  @endif   
               </tbody>
            </table>										
         </div>
         <div class="card-footer clearfix">
            {{ $data->links() }}
         </div>
      </div>
   </div>
   <!-- /.card -->
</section>
@endsection