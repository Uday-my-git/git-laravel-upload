<?php 

use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Order;
use App\Models\Country;
use App\Mail\orderEmail;
use Illuminate\Support\Facades\Mail;


function getCategoriesFun()
{
   return Category::orderBy('name', 'asc')->with('sub_category')->orderBy('id', 'desc')->where([['showHome', 'Yes'], ['status', 1]])->get();
}

function getProductImg($product_id)
{
   return ProductImage::where('product_id', $product_id)->first();
}

// send email to admin

function orderEmail($orderId, $userTypte = 'customer')
{
   $order = Order::where('id', $orderId)->with('emailItems')->first();

   if ($userTypte == 'customer') {
      $subject = 'Thanks for your order booking';
      $email = $order->email;
   } else {
      $subject = 'Hellow Admin, You have recived order booking';
      $email = env('ADMIN_EMAIL');
   }

   $mailData = [
      'subject' => 'Thanks for your orders',
      'order' => $order,
      'userTypte' => $userTypte
   ];
   
   Mail::to($email)->send(new orderEmail($mailData));
}

function getCountries($id)
{
   return Country::where('id', $id)->first();
}

?>