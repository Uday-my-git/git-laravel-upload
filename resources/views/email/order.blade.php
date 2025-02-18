<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Send Email With Emailtrap</title>
</head>
<body>

   @if ($mailData['userType'] == 'customer')
      <h1>Thanks for your orders!!</h1>
      <h2>your Order Id Is: #{{ $mailData['order']->id }}</h2>
   @else
      <h1>Admin, You recived new orders</h1>
      <h2>Order Id Is: #{{ $mailData['order']->id }}</h2>
   @endif

   <h2>Shipping Address</h2>
   <address>
      <strong>{{ $mailData['order']->first_name . ' ' . $mailData['order']->last_name }}</strong><br>

      {{ $mailData['order']->address }},<br>
      {{ $mailData['order']->city }}, {{ getCountries($mailData['order']->country_id )->name }}, {{ $mailData['order']->zip }}<br>

      Phone: (804) {{ $mailData['order']->mobile }}<br>
      Email: {{ $mailData['order']->email }} <br><br>

      <b>Shipping Date:</b> {{ \Carbon\Carbon::parse($mailData['order']->shipped_date)->format('d M, Y') }} <br> 
      <b>Order Status:</b>{{ $mailData['order']->status }}
   </address>

   <table cellpading="3" cellspacing="3" border="0">
      <thead>
         <tr style="background: #ccc;">
            <th>Product</th>
            <th width="100">Price</th>
            <th width="100">Qty</th>                                        
            <th width="100">Total</th>
         </tr>
      </thead>
      <tbody>
         @if ($mailData['order']->emailItems->isNotEmpty())
            @foreach ($mailData['order']->emailItems as $orderItems)
               <tr>
                  <td>{{ $orderItems->name }}</td>
                  <td>{{ number_format($orderItems->price, 2) }}</td>
                  <td>{{ $orderItems->qty }}</td>
                  <td>{{ number_format($orderItems->total, 2) }}</td>
               </tr>
            @endforeach
            <br>
            <tr>
               <th colspan="3" class="text-right">Subtotal:</th>
               <td>${{ number_format($mailData['order']->subtotal) }}</td>
            </tr>                           
            <tr>
               <th colspan="3" class="text-right">Shipping:</th>
               <td>${{ number_format($mailData['order']->shipping, 2) }}</td>
            </tr>
            <tr>
               <th colspan="3" class="text-right">Discount: {{ (!empty($order->coupon_code)) ? '('.$order->coupon_code.')' : ''}}</th>
               <td>${{ number_format($mailData['order']->discount, 2) }}</td>
            </tr>
            <tr>
               <th colspan="3" class="text-right">Grand Total:</th>
               <td>${{ number_format($mailData['order']->grand_total, 2) }}</td>
            </tr>    
         @endif                        
      </tbody>
   </table>			
</body>
</html>