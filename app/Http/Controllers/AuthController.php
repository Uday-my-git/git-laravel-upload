<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Order;
use App\Models\Wishlist;
use App\Models\OrderItem;

class AuthController extends Controller
{
    public function login() 
    {
        return view('front.account.login');
    }

    public function register() 
    {
        return view('front.account.register');
    }

    public function saveRegisterForm(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'name' =>'required',
            'email' =>'required|unique:users',
            'phone' =>'required',
            'password' =>'required|min:5|max:12|confirmed',
        ]);

        if ($validator->passes()) {
            $user = new User();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = $request->password;
            $user->save();
            
            session()->flash('success', 'User Register Successfully, Now Login Your Account');
            return response()->json(['status' => true, 'msg' => 'User Register Successfully']);
        } else {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }

    public function authenticate(Request $request)  
    {
        $validator = Validator::make($request->all(), [
            'email' =>'required|email',
            'password' =>'required',
        ]);

        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
                if (session()->has('url.intended')) {    // capture current URL if user login and redirect to checkout page
                    return redirect(session()->get('url.intended'));
                }
                return redirect()->route('account.profile');
            } else {
                return redirect()->route('account.login')->with('error', 'Either Email Or Password Should Be Wrong')->withInput($request->only('email'));
            }
        } else {
            return redirect()->route('account.login')->withErrors($validator)->withInput($request->only('email'));
        }
    }

    public function profile() 
    {
        return view('front.account.profile');
    }

    public function orders() 
    {
        $user = Auth::user();
        
        $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        $data['orders'] = $orders;
        return view('front.account.order', $data);
    }

    public function get_orderDetail($orderId) 
    {
        $data = [];
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)->where('id', $orderId)->first();
        $orderItem = OrderItem::where('order_id', $orderId)->get();
        $orderItemCount = OrderItem::where('order_id', $orderId)->count();

        $data['orders'] = $orders;
        $data['orderItem'] = $orderItem;
        $data['orderItemCount'] = $orderItemCount;
        
        return view('front.account.order-detail', $data);
    }

    public function wishlist() 
    {
        $data = [];
        $user = Auth::user();

        $wishlist = Wishlist::where('user_id', Auth::user()->id)->with('product')->get();   
        $data['wishlist'] = $wishlist;

        return view('front.account.wishlist', $data);
    }

    public function removeWishlist(Request $request) 
    {
        $user = Auth::user();

        $product = Wishlist::where('user_id', $user->id)->where('product_id', $request->product_id)->first();
        
        if (is_null($product)) {
            session()->flash('error', 'Product alreay remove');
            return response()->json(['status' => false, 'msg' => 'product alreay remove']);
        } else {
            Wishlist::where('user_id', $user->id)->where('product_id', $request->product_id)->delete();

            session()->flash('success', 'product remove successfully');
            return response()->json(['status' => true, 'msg' => 'product remove successfully']);
        }
    }

    public function logout() 
    {
        Auth::logout();
        return redirect()->route('account.login')->with('success', 'You logout successfully');
    }
}
