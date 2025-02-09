<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductImage;
use Illuminate\Support\Facades\File;
use Image;

class ProductImageController extends Controller
{
    public function update(Request $request) {
        $productImage = new ProductImage();

        $productImage->product_id = $request->product_id;
        $productImage->image = "NULL";
        $productImage->save();

        if ($request->has('image')) {
            $image = $request->image;
            $extension = $image->getClientOriginalExtension();
            $sourcePath = $image->getPathName();   // source path of image
    
            $newImgName = $request->product_id . '-' . $productImage->id . '-' . time() . '-' . $extension;
            $productImage->image = $newImgName;
            $productImage->save();
    
            // Save Resized Large Image
            $destinationPath = public_path() . '/uploads/product/large/' . $newImgName;
            $image = Image::make($sourcePath);
    
            $image->resize(1400, null, function ($constraint) {
                $constraint->aspectRatio();
            });
    
            $image->save($destinationPath);
    
            // Save Resized Small Image
            $destinationPath = public_path() . '/uploads/product/small/' . $newImgName;
            $imge = Image::make($sourcePath)->fit(300, 300)->save($destinationPath);
        }
             
        return response()->json([
            'status' => true,
            'img_id' => $productImage->id,
            'img_path' => asset('uploads/product/small/' . $productImage->image), 
            'msg' => 'Image Save Successfully'
        ]);
    }

    public function deleteProductImage(Request $request) {
        $productImg = ProductImage::find($request->id);

        // if user changes the URL id
        if (empty($productImg)) {
            return response()->json(['status' => false, 'msg' => 'Image Not Found']);;
        }

        File::delete(public_path('uploads/product/large/' . $productImg->image));
        File::delete(public_path('uploads/product/small/' . $productImg->image));

        $productImg->delete();
        
        return response()->json(['status' => true, 'msg' => 'Image Deleted Successfully']);
    }


}
