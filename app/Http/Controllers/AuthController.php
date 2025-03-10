<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\ResetPasswordEmail;
use App\Models\CustomerAddress;
use App\Models\Country;
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
            $user->password = Hash::make($request->password);
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
                // dd(Auth::check());
                if (session()->has('url.intended')) {    // capture current URL if user login and redirect to checkout page
                    return redirect(session()->get('url.intended'));
                }
                return redirect()->route('account.profile');
            } else {
                return redirect()->route('account.login')->withInput($request->only('email'))->with('error', 'Either Email Or Password Should Be Wrong');
            }
        } else {
            return redirect()->route('account.login')->withErrors($validator)->withInput($request->only('email'));
        }
    }

    public function profile() 
    {
        $user = Auth::user();
        $data = User::where('id', $user->id)->first();
        $address = CustomerAddress::where('user_id', $user->id)->get()->first();
        $country = Country::orderBy('name', 'asc')->get();
        
        $data['data'] = $data;
        $data['address'] = $address;
        $data['country'] = $country;

        return view('front.account.profile', $data);
    }

    public function updateProfile(Request $request) 
    {
        $userId = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email, '.$userId.',id',
            'phone' => 'required',
        ]);

        if ($validator->passes()) {
            $user = User::find($userId);

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();

            session()->flash('success', 'User Profile Update Successfully');
            return response()->json(['status' => true, 'msg' => 'user profile update successfully']);
        } else {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }

    public function updateAddress(Request $request) 
    {
        $userId = Auth::user()->id;
       
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
        ]);

        if ($validator->passes()) {
            $user = CustomerAddress::where('user_id', $userId)->first();

            CustomerAddress::updateOrCreate(
                ['user_id' => $userId],
                [
                    'user_id' => $userId,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'country_id' => $request->country,
                    'state' => $request->state,
                    'city' => $request->city,
                    'apartment' => $request->apartment,
                    'address' => $request->address,
                    'zip' => $request->zip,
                ]
            );

            // $user->user_id = $userId;
            // $user->first_name = $request->first_name;
            // $user->last_name = $request->last_name;
            // $user->email = $request->email;
            // $user->mobile = $request->mobile;
            // $user->country_id = $request->country;
            // $user->state = $request->state;
            // $user->city = $request->city;
            // $user->apartment = $request->apartment;
            // $user->address = $request->address;
            // $user->zip = $request->zip;
            // $user->save();

            session()->flash('success', 'User Profile Update Successfully');
            return response()->json(['status' => true, 'msg' => 'user profile update successfully']);
        } else {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }

    public function orders() 
    {
        $data = [];
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

        $wishlist = Wishlist::where('user_id', $user->id)->get(); 

        return view('front.account.wishlist', compact('wishlist'));
    }

    public function removeWishlist(Request $request) 
    {
        $user = Auth::user();

        $product = Wishlist::where('user_id', $user->id)->where('product_id', $request->product_id)->first();
        
        if (is_null($product)) {
            session()->flash('error', 'Product alreay remove');
            return response()->json(['status' => false, 'msg' => 'product alreay remove']);
        } else {
            $product->delete();

            session()->flash('success', 'product remove successfully');
            return response()->json(['status' => true, 'msg' => 'product remove successfully']);
        }
    }

    public function logout() 
    {
        Auth::logout();
        return redirect()->route('account.login')->with('success', 'You logout successfully');
    }

    public function changePasswordForm() 
    {
        return view('front.account.changePassword');
    }

    public function changePassword(Request $request) 
    {
        $request->validate([
            'old_password' => 'required|min:5|max:15',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ]);

        $userId = Auth::user()->id;

        if (isset($userId) && !empty($userId)) {
            $user = User::select('id', 'password')->where('id', $userId)->first();
            $currentPasswordStatus = Hash::check($request->old_password, $user->password);

            if (!$currentPasswordStatus){
                return redirect()->back()->with('error','Current Password does not match with Old Password');
            } else {
                User::findOrFail($userId)->update([
                    'password' => Hash::make($request->new_password),
                ]);
            
                return redirect()->back()->with('success','New Password Updated Successfully');
            }
        }
    }

    public function forgotPassword() 
    {
        return view('front.account.forgot-password');
    }

    public function processForgotPassword(Request $request) 
    {
        $validation = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $existingToken = \DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if ($existingToken) {
            $token = $existingToken->token;
        } else {
            $token = Str::random(60);

            // \DB::table('password_reset_tokens')->where('email', $request->email)->delete();
    
            \DB::table('password_reset_tokens')->insert([
                'email' => (string) $request->email,  // Ensure email is a string
                'token' => (string) $token,
                'created_at' => Carbon::now()
            ]);
        }     

        $userDetail = User::where('email', $request->email)->first();
    
        $formData = [
            'token' => $token,
            'user' => $userDetail,
            'subject' => 'You have requested to reset yours password',
        ];

        Mail::to($request->email)->send(new ResetPasswordEmail($formData));

        return redirect()->route('front.forgotPassword')->with('success','Please check your inbox, to reset your password');
    }

    public function resetPasswordAccount($token) 
    {
        $tokenExist = \DB::table('password_reset_tokens')->where('token', $token)->first();

        if (is_null($tokenExist)) {
            return redirect()->route('front.forgotPassword')->with('error','Invalid Request, Or Invalid Reaponse');
        }

        return view('front.account.reset-password-account', ['token' => $token]);
    }

    public function processResetPasswordAccount(Request $request) 
    {
        $request->validate([
            'new_password' => 'required|min:5|max:15',
            'confirm_password' => 'required|same:new_password',
        ]);

        $token = $request->token;

        if ($token || $token != '') {
            $tokenExist = \DB::table('password_reset_tokens')->where('token', $token)->first();

            if (is_null($tokenExist)) return redirect()->route('front.forgotPassword')->with('error','Invalid Request, Or Invalid Reaponse');
    
            $user = User::where('email', $tokenExist->email)->first();
    
            User::where('id', $user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);
    
            \DB::table('password_reset_tokens')->where('email', $user->email)->delete();
        }
        return redirect()->route('front.forgotPassword')->with('success','Yout have successfully update your password');
    }


}
