<?php 

use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Order;
use App\Models\Country;
use App\Models\Page;
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

function orderEmail($orderId, $userType = 'customer')
{
   $order = Order::where('id', $orderId)->with('emailItems')->first();

   if ($userType == 'customer') {
      $subject = 'Thanks for your order booking';
      $email = $order->email;
   } else {
      $subject = 'Hellow Admin, You have recived order booking';
      $email = env('ADMIN_EMAIL');
   }

   $mailData = [
      'subject' => 'Thanks for your orders',
      'order' => $order,
      'userType' => $userType
   ];
   
   Mail::to($email)->send(new orderEmail($mailData));
}

function getCountries($id)
{
   return Country::where('id', $id)->first();
}

function staticPages()
{
   return $page = Page::orderBy('name', 'asc')->get();
}

?>