@extends('admin.layouts.app')

@section('content')
<section class="content-header">					
   <div class="container-fluid my-2">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Order Id: #{{ $order->id }}</h1>
         </div>
         <div class="col-sm-6 text-right">
            <a href="{{ route('orders.index') }}" class="btn btn-primary">Back</a>
         </div>
      </div>
      @include('admin.message')
   </div>
</section>

<section class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-9">
            <div class="card">
               <div class="card-header pt-3">
                  <div class="row invoice-info">
                     <div class="col-sm-4 invoice-col">
                     <h1 class="h5 mb-3">Shipping Address</h1>
                     <address>
                        <strong>{{ $order->first_name . ' ' . $order->last_name }}</strong><br>
                        {{ $order->address }},<br>
                        {{ $order->city }}, {{ $order->countryName }}, {{ $order->zip }}<br>
                        Phone: (804) {{ $order->mobile }}<br>
                        Email: {{ $order->email }} <br>
                        <b>Shipping Date:</b> {{ \Carbon\Carbon::parse($order->shipped_date)->format('d M, Y') }}   
                     </address>
                     </div>
                     <div class="col-sm-4 invoice-col">
                        <b>Invoice #007612</b><br>
                        <br>
                        <b>Order ID:</b> {{ $order->id }}<br>
                        <b>Total:</b> {{ $order->grand_total }}<br>

                        <b>Status:</b> <span class="text-success">
                           @if ($order->status == 'pending')
                              <span class="badge bg-warning">Pending</span>
                           @elseif ($order->status == 'shipped')
                              <span class="badge bg-info">Shipped</span>
                           @elseif ($order->status == 'delivered')
                              <span class="badge bg-success">Delivered</span>
                           @else
                              <span class="badge bg-danger">Cancelled</span>
                           @endif   
                        </span>
                        <br>
                     </div>
                  </div>
               </div>
               <div class="card-body table-responsive p-3">								
                  <table class="table table-striped">
                     <thead>
                        <tr>
                           <th>Product</th>
                           <th width="100">Price</th>
                           <th width="100">Qty</th>                                        
                           <th width="100">Total</th>
                        </tr>
                     </thead>
                     <tbody>
                        @if ($orderItem->isNotEmpty())
                           @foreach ($orderItem as $orderItems)
                              <tr>
                                 <td>{{ $orderItems->name }}</td>
                                 <td>{{ number_format($orderItems->price, 2) }}</td>
                                 <td>{{ $orderItems->qty }}</td>
                                 <td>{{ number_format($orderItems->total, 2) }}</td>
                              </tr>
                           @endforeach
                           <tr>
                              <th colspan="3" class="text-right">Subtotal:</th>
                              <td>${{ number_format($order->subtotal) }}</td>
                           </tr>                           
                           <tr>
                              <th colspan="3" class="text-right">Shipping:</th>
                              <td>+ ${{ number_format($order->shipping, 2) }}</td>
                           </tr>
                           <tr>
                              <th colspan="3" class="text-right">Discount: {{ (!empty($order->coupon_code)) ? '('.$order->coupon_code.')' : ''}}</th>
                              <td>- ${{ number_format($order->discount, 2) }}</td>
                           </tr>
                           <tr>
                              <th colspan="3" class="text-right">Grand Total:</th>
                              <td>${{ number_format($order->grand_total, 2) }}</td>
                           </tr>    
                        @endif                        
                     </tbody>
                  </table>								
               </div>                            
            </div>
         </div>
         <div class="col-md-3">
            <div class="card">
               <form action="" name="orderStatus" id="orderStatus" method="POST">
                  <div class="card-body">
                     <h2 class="h4 mb-3">Order Status</h2>
                     <div class="mb-3">
                        <select name="status" id="status" class="form-control">
                           <option value="pending" {{ $order->status == 'pending' ? 'selected' : ''}}>Pending</option>
                           <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : ''}}>Shipped</option>
                           <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : ''}}>Delivered</option>
                           <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : ''}}>Cancelled</option>
                        </select>
                     </div>
                     <div class="mb-3">
                        <label for="starts_at">Shipping Date</label>
                        <input type="text" name="shipped_date" id="shipped_date" class="form-control" value="{{ $order->shipped_date }}" placeholder="Select Date & Time" autocomplete="off">	
                     </div>
                     <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                     </div>
                  </div>
               </form>
            </div>
            <div class="card">
               <div class="card-body">
                  <form action="" name="sendEmailInvoice" id="send-email-invoice">
                     <h2 class="h4 mb-3">Send Inovice Email</h2>
                     <div class="mb-3">
                        <select name="userTypte" id="user-typte" class="form-control">
                           <option value="customer">Customer</option>                                                
                           <option value="admin">Admin</option>
                        </select>
                     </div>
                     <div class="mb-3">
                        <button type="button" class="btn btn-primary" id="send-email-invoice-btn">Send</button>
                        <p id="error-send-email"></p>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
@endsection

@section('custom-js')
<script>

$(function () {

   $('#shipped_date').datetimepicker({
      format:'Y-m-d H:i:s',
   });

   $("#orderStatus").submit(function (e) {  
      e.preventDefault();
      var orderStatus = $("#status").val();
      var shipped_date = $("#shipped_date").val();

      if (orderStatus !== "") {
         $.ajax({
            type: "POST",
            url: "{{ route('orders.changeOrderStatus', $order->id) }}",
            data: "orderStatus="+orderStatus+"&shipped_date="+shipped_date,
            dataType: "JSON",
            success: function (response) {
               window.location.href = '{{ route("orders.getOrderDetail", $order->id) }}';
            }
         });
      }
     
   });

   $("#send-email-invoice-btn").click(function (e) {    // send email button .click event of j.s, Type: 1
      e.preventDefault(); 

      if (confirm('Are u send email ??')) {
         $('button[type="button"]').prop('disabled', true);
         $('button[type="submit"]').prop('disabled', true);
         $("#send-email-invoice-btn").text("Please Wait....");

         $.ajax({
            type: "POST",
            url: "{{ route('orders.sendInvoiceEmail', $order->id) }}",
            data: $("#send-email-invoice").serializeArray(),
            dataType: "JSON",
            success: function (response) {
               if (response.status === true) {  
                  window.location.href = '{{ route("orders.getOrderDetail", $order->id) }}';
                  $('button[type="button"]').prop('disabled', false);
                  $('button[type="submit"]').prop('disabled', false);
               }
            },
            error: function (xhr, status, error) {
               console.error(error);
               $("#error-send-email").text("Error sending email.");
            }
         });
      }
   });

 
   // $("#send-email-invoice_old").submit(function (e) {     // send email .submit event of j.s, Type: 2
   //    e.preventDefault();
     
   //    if (confirm('Are u send email ??')) {
   //       $('button[type="submit"]').prop('disabled', true);
   //       $("#waitingId").html("Please Wait...");

   //       $.ajax({
   //          type: "POST",
   //          url: "{{ route('orders.sendInvoiceEmail', $order->id) }}",
   //          data: $(this).serializeArray(),
   //          dataType: "JSON",
   //          success: function (response) {
   //             if (response.status === true) {  
   //                window.location.href = '{{ route("orders.getOrderDetail", $order->id) }}';
   //                $('button[type="submit"]').prop('disabled', false);
   //             }
   //          }
   //       });
   //    }
   // });

});

</script>
@endsection