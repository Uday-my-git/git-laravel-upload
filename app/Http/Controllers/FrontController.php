<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Page;
use App\Models\User;
use App\Models\Wishlist;
use App\Mail\ContactUsEmail;
use Illuminate\Support\Facades\Mail;

class FrontController extends Controller
{
    public function index()
    {
        $product = Product::where('is_featured', 'Yes')->orderBy('id', 'desc')->take(8)->where('status', 1)->get();
        $data['featuredProducts'] = $product;
       
        $latestProduct = Product::orderBy('id', 'desc')->where('status', 1)->take(8)->get();
        $data['latestProducts'] = $latestProduct;
        
        return view('front.home', $data);
    }

    public function addToWishlist(Request $request)
    {
        if (Auth::check() == false) {                           // if user login then add wishlist product
            session(['url.intended' => url()->previous()]);     // capture current URL if user login and redirect to checkout page
            return response()->json(['status' => false, 'msg' => 'login first then add to wishlist product']);
        }

        $product = Product::where('id', $request->productId)->first();

        if (is_null($product)) {
            return response()->json(['status' => false, 'msg' => '<div class="alert alert-success">Product not found</div>']);
        }

        Wishlist::updateOrCreate(                // this method prevent duplicate product entery another wise below save method also used
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->productId,
            ],
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->productId,
            ]
        ); 

        // $wishlist = new Wishlist;
        // $wishlist->user_id = Auth::user()->id;
        // $wishlist->product_id = $request->productId;
        // $wishlist->save();

        return response()->json(['status' => true, 'msg' => '<div class="alert alert-success"><strong>'.$product->title.'</strong> added in wishlist</div>']);
    }

    public function page($slug) 
    {
        $page = Page::where('slug', $slug)->first();

        if (is_null($page)) abort(404); 
        
        return view('front.page', ['page' => $page]);
    }

    public function sendContactUsEmail(Request $request) 
    {
        $validatedData = $request->validate([
            'name' => ['required'],
            'email' => ['required'],
            'subject' => ['required'],
            'message' => ['required', 'min:20'],
        ]);

        if ($validatedData) {
            $mailData = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
            ];

            $admin = User::where('id', 1)->first();

            Mail::to($admin->email)->send(new ContactUsEmail($mailData));

            return redirect()->back()->with('success', 'Your contact detail send admin to successfully');
        } else {
            
        }
    }

}
