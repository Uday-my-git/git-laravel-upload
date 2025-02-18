<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SubCategoryModel;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = SubCategoryModel::orderBy('id', 'desc')
                ->select('sub_categorys.*', 'categories.name as categoryName')
                ->join('categories', 'sub_categorys.category_id', '=', 'categories.id');

        if (!empty($request->get('search'))) {
            $category = $categories->where('sub_categorys.name', 'like', '%' .$request->get('search'). '%')
                            ->orWhere('sub_categorys.slug', 'like', '%' .$request->get('search'). '%')
                            ->orWhere('categories.name', 'like', '%' .$request->get('search'). '%');
        }

        $category = $categories->paginate(10);
        return view('admin.sub_category.sub-categoryListing', compact('category')); 
    }

    public function create()
    {
        $categories = Category::orderBy('id', 'DESC')->get();
        $data['categories'] = $categories;

        return view('admin.sub_category.create', $data);      
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category' => 'required',
            'slug' => 'required|unique:sub_categorys',
            'status' => 'required',
        ]);

        if ($validator->passes()) {
            $subCategory = new SubCategoryModel();

            $subCategory->category_id = $request->category;
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->showHome = $request->showHome;
            $subCategory->save();

            $request->session()->flash('success', 'Category addedd succesffully');
            return response()->json(['status' => true, 'msg' => 'sub-categories addedd successfully']);
        } else {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }

    public function edit(Request $request, $id)
    {
        $subCategory = SubCategoryModel::find($id);

        if (empty($subCategory)) {
            $request->session()->flash('error', 'Record Not Found');
            return redirect()->route('sub-category.index');
        }
        
        $category = Category::orderBy('name', 'asc')->get();

        $data['category'] = $category;
        $data['subCategory'] = $subCategory;

        return view('admin.sub_category.sub-categoryEdit', $data);
    }

    public function update(Request $request, $id)
    {
        $subCategory = SubCategoryModel::find($id);

        if (empty($subCategory->id)) {
            $request->session()->flash('error', 'This Categor Not Exists For Update Data');
            return response()->json(['status' => true, 'notFound' => true, 'msg' => 'This Subcategory Not Exists For Update Data']);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categorys,slug,'.$subCategory->id. ',id',
            'status' => 'required',
        ]);

        if ($validator->passes()) {
            $subCategory->category_id = $request->category;
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->showHome = $request->showHome;
            $subCategory->save();

            $request->session()->flash('success', 'Subcategory updated successfully');
            return response()->json(['status' => true, 'msg' => 'Subcategory updated successfully']);
        } else {
            $request->session()->flash('error', 'Subcategory updated successfully');
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }

    public function destroy(Request $request, $id)
    {
        $subCategory = SubCategoryModel::find($id);

        if (empty($subCategory->id)) {
            $request->session()->flash('success', 'SubCategory Does Not Exists');
            return response()->json(['status' => false, 'msg' => 'Brands Does Not Exists']);
        }
        
        $subCategory->delete();
        $request->session()->flash('success', 'Subcategory deleted successfully');
        return response()->json(['status' => true, 'msg' => 'Subcategory deleted successfully']);
    }
}
