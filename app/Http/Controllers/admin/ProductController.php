<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\TempImage;
use App\Models\SubCategoryModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Image;

class ProductController extends Controller
{
    public function index(Request $request) 
    {
        $product = Product::latest('title')->with('productImage')
                    ->select('products.*', 'categories.name as categoryName')
                    ->join('categories', 'products.category_id', '=', 'categories.id');

        if (!empty($request->get("search"))) {
            $product = $product->where('title', 'like', '%' . $request->get("search") . '%');
        }

        $product = $product->paginate(10);
        $data["products"] = $product;

        return view('admin.products.productListing', $data);
    }

    public function create() 
    {
        $data = [];
        $category = Category::orderBy('name', 'asc')->get();
        $brands = Brand::orderBy('name', 'asc')->get();

        $data['category'] = $category;
        $data['brands'] = $brands;
        
        return view('admin.products.create', $data);
    }
    
    public function store(Request $request) 
    {
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'description' => 'required',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == "Yes") {
            $rules["qty"] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $product = new Product;

            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->short_description = $request->short_description;
            $product->description = $request->description;
            $product->shipping_returns = $request->shipping_returns;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            
            $product->brand_id = $request->brand;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->is_featured = $request->is_featured;
            $product->related_products = (!empty($request->related_products)) ? implode(',', $request->related_products) : '';

            $product->save();

            // save gallery image
            if (!empty($request->imge_array)) {
                foreach ($request->imge_array as $temp_img_id) {
                    $tempImgInfo = TempImage::find($temp_img_id);

                    $extensionArr = explode(".", $tempImgInfo->name);
                    $extension = last($extensionArr);

                    $productImg = new ProductImage();
                    $productImg->product_id = $product->id;
                    $productImg->image = "NULL";
                    $productImg->save();

                    $imgName = $product->id . "-" . $productImg->id . "-" . time() . "." . $extension;
                    $productImg->image = $imgName;
                    $productImg->save();
                    
                    // Save Resized Image & Large Image
                    $sourcePath = public_path() . "/temp-img/" . $tempImgInfo->name;
                    $destinationPath = public_path() . "/uploads/product/large/" . $imgName;

                    $image = Image::make($sourcePath);

                    $image->resize(1400, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    
                    $image->save($destinationPath);

                    // small image
                    $destinationPath = public_path() . "/uploads/product/small/" . $imgName;
                    $image = Image::make($sourcePath)->fit(300, 300)->save($destinationPath);
                }
            }
            
            $request->session()->flash('success', 'Product Added Successfully');
            return response()->json(['status' => true, 'msg' => 'Product Added Successfully']);
        } else {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }

    public function edit(Request $request, $id) 
    {
        $data = [];
        $relatedProducts = '';
       
        $product = Product::find($id);
        $data["product"] = $product;
       
        if (empty($product)) {           // if user changes the URL id
            return redirect()->route('product.productListing')->with('error', 'Product Not Found');
        }

        // fetch related products
        if ($product->related_products != '') {
            // DB::enableQueryLog();          // genereate mysql query
            $productArr = explode(',', $product->related_products);
            $relatedProducts = Product::whereIn('id', $productArr)->with('productImage')->get();
            // dd(DB::getQueryLog());         // genereate mysql query
        }
        $data['relatedProducts'] = $relatedProducts;
      
        $getproductImg = ProductImage::where('product_id', $product->id)->get();
        $data["getproductImg"] = $getproductImg;
       
        $category = Category::latest('name')->get();
        $data["category"] = $category;
        
        $subCategory = SubCategoryModel::where('category_id', $product->category_id)->get();
        $data["subCategory"] = $subCategory;
        
        $brands = Brand::latest('name')->get();
        $data["brands"] = $brands;
        
        return view('admin.products.edit', $data);
    }

    public function update(Request $request, $id) 
    {
        $product = Product::find($id);

        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,'. $product->id. ',id',
            'description' => 'required',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,'. $product->id. '.id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == "Yes") {
            $rules["qty"] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->short_description = $request->short_description;
            $product->description = $request->description;
            $product->shipping_returns = $request->shipping_returns;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->brand_id = $request->brand;

            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->is_featured = $request->is_featured;
            $product->related_products = (!empty($request->related_products)) ? implode(',', $request->related_products) : '';
            
            $product->save();

            // save gallery image
            // if (!empty($request->imge_array)) {
            //     foreach ($request->imge_array as $temp_img_id) {
            //         $tempImgInfo = TempImage::find($temp_img_id);

            //         $extensionArr = explode(".", $tempImgInfo->name);
            //         $extension = last($extensionArr);

            //         $productImg = new ProductImage();
            //         $productImg->product_id = $product->id;
            //         $productImg->image = "NULL";
            //         $productImg->save();

            //         $imgName = $product->id . "-" . $productImg->id . "-" . time() . "." . $extension;
            //         $productImg->image = $imgName;
            //         $productImg->save();
                    
            //         // Save Resized Image & Large Image
            //         $sourcePath = public_path() . "/temp-img/" . $tempImgInfo->name;
            //         $destinationPath = public_path() . "/uploads/product/large/" . $imgName;

            //         $image = Image::make($sourcePath);

            //         $image->resize(1400, null, function ($constraint) {
            //             $constraint->aspectRatio();
            //         });

            //         $image->save($destinationPath);

            //         // small image
            //         $destinationPath = public_path() . "/uploads/product/small/" . $imgName;
            //         $image = Image::make($sourcePath)->fit(300, 300)->save($destinationPath);
            //     }
            // }
            
            $request->session()->flash('success', 'Product Updated Successfully');
            return response()->json(['status' => true, 'msg' => 'Product Updated Successfully']);
        } else {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }

    public function deleteProduct(Request $request, $id) 
    {
        $productId = Product::find($id);

        // if user changes the URL id
        if (empty($productId)) {
            $request->session()->flash('error', 'Product Not Exiset In Database');
            return response()->json(['status' => false, 'notFound' => true, 'msg' => 'Product Not Exiset In Database']);
        }
        
        $productImg = ProductImage::where('product_id', $id)->get();

        if (!empty($productImg)) {
            foreach ($productImg as $delProductId) {
                File::delete(public_path('uploads/product/large/' . $delProductId->image));
                File::delete(public_path('uploads/product/small/' . $delProductId->image));
            }

            ProductImage::where('product_id', $id)->delete();
        }

        $productId->delete();
        return response()->json(['status' => true, 'msg' => 'Product Delete Successfully']);
    }

    public function getRelatedProducts(Request $request)
    {
        $tempProduct = [];

        if ($request->term != '') {
            $products = Product::where('title', 'like', '%'. $request->term .'%')->get();
            
            if (!is_null($products)) {
                foreach ($products as $product) {
                    $tempProduct[] = array('id' => $product->id, 'text' => $product->title);
                }
            }
        }
        return response()->json(['status' => true, 'tags' => $tempProduct]);
    }



}
