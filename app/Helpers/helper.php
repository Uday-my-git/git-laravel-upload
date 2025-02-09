<?php 

use App\Models\Category;
use App\Models\ProductImage;

function getCategoriesFun()
{
   return Category::orderBy('name', 'asc')->with('sub_category')->orderBy('id', 'desc')->where([['showHome', 'Yes'], ['status', 1]])->get();
}

function getProductImg($product_id)
{
   return ProductImage::where('product_id', $product_id)->first();
}

?>