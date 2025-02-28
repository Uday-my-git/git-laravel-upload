<?php

namespace App\Http\Controllers;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Country;
use App\Models\DiscountCoupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipping;
use App\Models\CustomerAddress;
use Carbon\Carbon;
use DB;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product = Product::with('productImage')->find($request->id);

        if ($product == null) {
            return response()->json(['status' => false, 'msg' => 'Product Not Found']);
        }

        if (Cart::count() > 0) {
            $cartContent = Cart::content();
            
            $cartItemExist = false;
            foreach ($cartContent as $cartItem) {
                if ($cartItem->id == $product->id) {
                    $cartItemExist = true;
                }
            }

            if ($cartItemExist == false) {
                Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->productImage->first())) ? $product->productImage->first() : '']);

                $status = true;
                $msg = $product->title . ' Added In Your Cart123';
            } else {
                $status = false;
                $msg = $product->title . ' Product already added in Cart';
            }
        } else {
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->productImage->first())) ? $product->productImage->first() : '']);

            $status = true;
            $msg = $product->title . ' Added in cart';
        }
        return response()->json(['status' => $status, 'msg' => $msg]);
    }

    public function cart()
    {
        $cartItem = Cart::content();
        // dd($cartItem);
        $data['cartItem'] = $cartItem;

        return view('front.cart', $data);
    }

    public function updateCart(Request $request)
    {
        if (isset($request->rowId)) {
            $rowId = $request->rowId;
            $qty = $request->qty;
            
            $productStockQty = Cart::get($rowId);
            $product = Product::find($productStockQty->id);
    
            if ($product->track_qty == "Yes") {        // check product qty is avaliabel in stock
                if ($qty <= $product->qty) {
                    Cart::update($rowId, $qty);
    
                    $msg = '<strong>'. $product->title .'</strong> Updaed Successfully';
                    $status = true;
                    session()->flash('success', $msg);
                } else {
                    $msg = 'Requested qty('.$qty.') not availabel in stock';
                    $status = false;
                    session()->flash('error', $msg);
                }
            } else {
                Cart::update($rowId, $qty);
    
                $msg = '<strong>'. $product->title .'</strong> Updaed Successfully';
                $status = true;
                session()->flash('success', $msg); 
            }
            return response()->json(['status' => $status, 'msg' => $msg]);
        }     
    }

    public function removeCartItem(Request $request)
    {
        $productItem = Cart::get($request->rowId);

        if (is_null($productItem)) {
            $msg = 'Product not found in Cart';
            $status = false;
        } else {   
            Cart::remove($request->rowId);
            
            $status = true;
            $msg = 'Product remove successfully';
            
            session()->flash('error', $msg);
            return response()->json(['status' => $status, 'msg' => $msg]); 
        }
    }

    public function checkout()
    {
        $discountCoupon = 0;
        $data['discountCoupon'] = $discountCoupon;

        if (Cart::count() == 0) {                       // if cart is empty, redirect to cart page
            return redirect()->route('front.cart');
        }

        if (Auth::check() == false) {                   // if user not login, redirect login page
            if (!session()->has('url.intended')) {      // capture current URL
                session(['url.intended' => url()->current()]);
            }
            return redirect()->route('account.login');
        }

        $subTotal = Cart::subtotal(2, '.', '');

        if (session()->has('coupon_code')) {            // not remove data when page reload
            $couponCode = session()->get('coupon_code');

            if ($couponCode->type == 'percent') {
                $discountCoupon = ($couponCode->discount_amount / 100) * $subTotal;
            } else {
                $discountCoupon = $couponCode->discount_amount;
            }
            $data['discountCoupon'] = $discountCoupon;
        }
        
        $countreisFetch = Country::orderBy('name', 'asc')->get();
        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first(); 

        $data['countreisFetch'] = $countreisFetch;
        $data['customerAddress'] = $customerAddress;
        
        if ($customerAddress != '') {    // calculate shipping charges according country
            $userCountry = $customerAddress->country_id;     
            $shippingCharges = Shipping::where('country_id', $userCountry)->first();
    
            $totalQty = 0;
            $grandTotal = 0;
            $totalShippingCharges = 0;
            
            foreach (Cart::content() as $cartItems) {
                $totalQty += $cartItems->qty;
            }
    
            $totalShippingCharges = $totalQty * $shippingCharges->amount;
            $grandTotal = ($subTotal - $discountCoupon) + $totalShippingCharges;

            $data['totalShippingCharges'] = $totalShippingCharges;
            $data['grandTotal'] = $grandTotal;
        } else {
            $grandTotal = $subTotal - $discountCoupon;
            $data['grandTotal'] = $grandTotal;
            
            $totalShippingCharges = 0;
            $data['totalShippingCharges'] = $totalShippingCharges;
        }

        session()->forget('url.intended');
        return view('front.checkout', $data);
    }

    public function getOrderSummery(Request $request) {
        $subTotal = Cart::subtotal(2, '.', '');
        $discountCoupon = 0;
        $couponHtml = 0;
        $data['discountCoupon'] = $discountCoupon;

        if ($request->country_id > 0) {
            $shipping = Shipping::where('country_id', $request->country_id)->first();
           
            $totalQty = 0;
            $grandTotal = 0;

            foreach (Cart::content() as $cartItem) {
                $totalQty += $cartItem->qty;
            }

            if (session()->has('coupon_code')) {    // apply coupon code calculation
                $couponCode = session()->get('coupon_code');

                if ($couponCode->type == 'percent') {
                    $discountCoupon = ($couponCode->discount_amount / 100) * $subTotal;
                } else {
                    $discountCoupon = $couponCode->discount_amount;
                }
                
                /*********** show coupon code when new coupon code apply wihout page reload ***********/
                $couponHtml = '<div class="mt-4" id="remove-coupon-response">
                    <strong>'. session()->get('coupon_code')->coupon_code .'</strong>      
                    <a class="btn btn-danger" id="remove-coupon-code"><i class="fa fa-times"></i></a>
                </div>';

                $data['discountCoupon'] = number_format($discountCoupon, 2);
                $data['couponHtml'] = $couponHtml;
            }
            
            if ($shipping != null) {
                $shippingCharge = $totalQty * $shipping->amount;   
                $grandTotal = $shippingCharge + ($subTotal - $discountCoupon);
                
            } else {
                $shipping = Shipping::where('country_id', 'rest_of_world')->first();

                $shippingCharge = $totalQty * $shipping->amount;
                $grandTotal = $shippingCharge + ($subTotal - $discountCoupon);
            }

            $data['shippingCharge'] = number_format($shippingCharge, 2);
            $data['grandTotal'] = number_format($grandTotal, 2);
            
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            $shippingCharge = 0;
            $data['couponHtml'] = $couponHtml;
            $data['shippingCharge'] = number_format($shippingCharge, 2);
            $data['grandTotal'] = number_format(($subTotal - $discountCoupon), 2);

            return response()->json(['status' => true, 'data' => $data]);
        }
    }

    public function processCheckout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:5',
            'last_name' => 'required',
            'email' => 'required',
            'country' => 'required',
            'address' => 'required|min:30',
            'city' => 'required',
            'state' => 'required',
            'mobile' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'Error validation', 'errors' => $validator->errors()]);
        } else {
            $user = Auth::user();
            
            CustomerAddress::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'country_id' => $request->country,
                    'address' => $request->address,
                    'apartment' => $request->apartment,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                ]
            );
            
            if ($request->payment_method == 'cod') {    
                $shipping = 0;
                $totalQty = 0;
                $discountCoupon = 0;
                $discountCodeId = NULL;
                $promoCode = '';

                $subTotal = Cart::subtotal(2, '.', '');    
                
                if (session()->has('coupon_code')) {           
                    $couponCode = session()->get('coupon_code');
        
                    if ($couponCode->type == 'percent') {
                        $discountCoupon = ($couponCode->discount_amount / 100) * $subTotal;
                    } else {
                        $discountCoupon = $couponCode->discount_amount;
                    }
                    $data['discountCoupon'] = $discountCoupon;

                    $discountCodeId = $couponCode->id;
                    $promoCode = $couponCode->coupon_code;
                }

                $shipping = Shipping::where('country_id', $request->country)->first();
    
                foreach (Cart::content() as $cartItem) {
                    $totalQty += $cartItem->qty;
                }

                if ($shipping != null) {    
                    $shippingCharge = $totalQty * $shipping->amount; 
                    $grandTotal = $shippingCharge + ($subTotal - $discountCoupon);
                } else {
                    $shipping = Shipping::where('country_id', 'rest_of_world')->first();

                    $shippingCharge = $totalQty * $shipping->amount;
                    $grandTotal = $shippingCharge + ($subTotal - $discountCoupon);
                }
                
                $order = new Order;
                $order->subTotal    = $subTotal;
                $order->shipping    = $shippingCharge;
                $order->grand_total = $grandTotal;
                $order->payment_status = 'not_paid';
                $order->status = 'pending';

                $order->discount    = $discountCoupon;
                $order->coupon_code_id = $discountCodeId;
                $order->coupon_code = $promoCode;

                $order->user_id     = $user->id;
                $order->first_name  = $request->first_name;
                $order->last_name   = $request->last_name;
                $order->email       = $request->email;
                $order->mobile      = $request->mobile;
                $order->country_id  = $request->country;
                $order->address     = $request->address;
                $order->apartment   = $request->apartment;
                $order->state       = $request->state;
                $order->city        = $request->city;
                $order->zip         = $request->zip;
                $order->notes       = $request->notes;
                $order->save();

                 
                foreach (Cart::content() as $item) {                // save orders in order_item table
                    $orderItem = new OrderItem;
                    $orderItem->product_id = $item->id;
                    $orderItem->order_id = $order->id;

                    $orderItem->name = $item->name;
                    $orderItem->qty = $item->qty;
                    $orderItem->price = $item->price;
                    $orderItem->total = $item->price * $item->qty;
                    $orderItem->save();

                    $productData = Product::find($item->id);

                    if ($productData->track_qty == 'Yes') {   // check track quantity remain in product
                        $currentQty = $productData->qty;
                        $updateQty = $currentQty - $item->qty;
                        $productData->qty = $updateQty;
                        $productData->save();
                    }
                }
            
                orderEmail($order->id, 'customer');
            } else {
                # stripe payment method
            }

            Cart::destroy();
            session()->forget('coupon_code');
            session()->flash('success', 'You have successfully placed your order');
            return response()->json(['status' => true, 'orderId' => $order->id, 'msg' => 'shipping address inserted successful']);
        }
    } 

    public function applyCouponCode(Request $request)
    {
        $couponCode = DiscountCoupon::where('coupon_code', $request->couponCode)->first();
        
        if ($couponCode == null) {
            return response()->json(['status' => false, 'msg' => 'not valid coupon code value']);
        }

        $now = Carbon::now();

        if ($couponCode->starts_at != '') {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $couponCode->starts_at);

            if ($now->lt($startDate)) {
                return response()->json(['status' => false, 'msg' => 'start date not greter then current date & time']);
            }
        }

        if ($couponCode->expires_at != '') {
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $couponCode->expires_at);

            if ($now->gt($endDate)) {
                return response()->json(['status' => false, 'msg' => 'start date not greter then Expires date & time']);
            }
        }

        if ($couponCode->max_uses > 0) {    // check maximum number of used this coupon code
            $couponUsed = Order::where('coupon_code_id', $couponCode->id)->count();

            if ($couponUsed >= $couponCode->max_uses) {
                return response()->json(['status' => false, 'msg' => 'max limit used this coupon code']);
            }
        }

        if ($couponCode->max_uses_user > 0) {    // check maximum number of user used this coupon code
            $maxUserUsed = Order::where(['coupon_code_id' => $couponCode->id, 'user_id' => Auth::user()->id])->count();

            if ($maxUserUsed >= $couponCode->max_uses_user) {
                return response()->json(['status' => false, 'msg' => 'max number of user used this coupon code']);
            }
        }

        if ($couponCode->min_amount > 0) {     // check minimum amount of product price
            $subTotal = Cart::subtotal(2, '.', '');
            
            if ($subTotal < $couponCode->min_amount) {
                return response()->json(['status' => false, 'msg' => 'your minimum amount must be $' . $couponCode->min_amount]);
            }
        }

        session()->put('coupon_code', $couponCode);
        return $this->getOrderSummery($request);   
    }

    public function removeCouponCode(Request $request)
    {
        session()->forget('coupon_code');
        return $this->getOrderSummery($request);
    }

    public function thankyouPage($id)
    {
        return view('front.thankyouPage', ['id' => $id]);
    }

}
