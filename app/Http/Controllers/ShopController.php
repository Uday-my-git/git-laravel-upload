<?php

namespace App\Http\Controllers;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategoryModel;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug=null, $subCategorySlug=null)
    {
        $categorySelected = "";
        $subCategorySelected = "";

        $categories = Category::orderBy('name', 'asc')->with('sub_category')->where('status', 1)->get();
        $data["categories"] = $categories;

        $brand = Brand::orderBy('name', 'asc')->where('status', 1)->get();
        $data["brand"] = $brand;

        // $products = Product::orderBy('id', 'asc')->where('status', 1)->get();
        
        // apply or listing sub-category of product filter
        $products = Product::where('status', 1);
        
        if (!empty($categorySlug)) {                             //listing sub-category of selected category
            $category = Category::where('slug', $categorySlug)->first();
            $products = $products->where('category_id', $category->id);
            $categorySelected = $category->id;
        }

        if (!empty($subCategorySlug)) {
            $subCategory = SubCategoryModel::where('slug', $subCategorySlug)->first();
            $products = $products->where('sub_category_id', $subCategory->id);
            $subCategorySelected = $subCategory->id;
        }

        $brandsArr = [];

        if (!empty($request->get('brands'))) {                                      // listing filter brands checkboxes
            $brandsArr = explode(',', $request->get('brands'));
            $products = $products->whereIn('brand_id', $brandsArr);
        }
    
        if ($request->get('price_max') != '' && $request->get('price_min') != '') {
            if ($request->get('price_max') == 1000) {                               // show product if price range equal $1000
                $products = $products->whereBetween('price', [intval($request->get('price_min')), 1000000]);
            } else {
                $products = $products->whereBetween('price', [intval($request->get('price_min')), intval($request->get('price_max'))]);
            }
        }

        if ($request->get('sort') != '') {                                        // price sorting filtering                           
            if ($request->get('sort') == 'latest') {
                $products = $products->orderBy('id', 'desc');

            } else if ($request->get('sort') == 'price_asc') {
                $products = $products->orderBy('price', 'asc');

            } else if ($request->get('sort') == 'price_desc') {
                $products = $products->orderBy('price', 'desc');
            }
        } else {
            $products = $products->orderBy('id', 'desc');
        }

        $products = $products->paginate(9);

        $data["products"] = $products;

        $data["categorySelected"] = $categorySelected;
        $data["subCategorySelected"] = $subCategorySelected;
        $data["brandsArr"] = $brandsArr;

        $data["priceMax"] = (intval($request->get('price_max')) == 0) ? 1000 : (intval($request->get('price_max')));
        $data["priceMin"] = intval($request->get('price_min'));
        $data["sort"] = $request->get('sort');

        return view('front.shop', $data);
    }

    public function product($slug)
    {
        $product = Product::where('slug', $slug)->with('productImage')->first();

        if (is_null($product)) {
            abort(404);
        }
        
        $relatedProducts = '';

        if ($product->related_products != '') {
            $productArr = explode(',', $product->related_products);
            $relatedProducts = Product::whereIn('id', $productArr)->get();
        }

        $data['product'] = $product;
        $data['relatedProducts'] = $relatedProducts;
        
        return view('front.product', $data);
    }

   
    
}
