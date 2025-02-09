<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

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

    
}
