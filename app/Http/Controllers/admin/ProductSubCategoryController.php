<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SubCategoryModel;
use Illuminate\Http\Request;

class ProductSubCategoryController extends Controller
{
    public function index(Request $request)
    {
        if (!empty($request->category_id)) {
            $subCategory = SubCategoryModel::orderBy('name', 'asc')->where('category_id', $request->category_id)->get();

            return response()->json([
                'status' => true,
                'msg' => $subCategory,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => 'Category not found',
            ]);
        }
      
    }


}
