<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarndController extends Controller
{
    public function index(Request $request) {
        $brands = Brand::latest('id');

        if (!empty($request->get('search'))) {
            $brand = $brands->where('name', 'like', '%'. $request->get('search') .'%')
                            ->orWhere('slug', 'like', '%'. $request->get('search') .'%');
        }

        $brand = $brands->paginate(10);
        return view('admin.brands.brandListing', compact('brand'));
    }

    public function create() {
        return view('admin.brands.create');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands',
            'status' => 'required',
        ]);

        if ($validator->passes()) {
            $brands = new Brand;

            $brands->name = $request->name;
            $brands->slug = $request->slug;
            $brands->status = $request->status;
            $brands->save();

            $request->session()->flash('success', 'Brands Addedd Successfully');
            return response()->json(['status' => true, 'msg' => 'Brands Addedd Successfully']);
        } else {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }

    public function edit(Request $request, $id) {
        $brand = Brand::find($id);
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, $id) {
        $brands = Brand::where('id', $id)->first();
        
        if (empty($brands->id)) {
            $request->session()->flash('success', 'Brands Does Not Exists');
            return response()->json(['status' => true, 'notFound' => true, 'msg' => 'Brands Does Not Exists']);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$brands->id.',id',
        ]);

        if ($validator->passes()) {
            $brands->name = $request->name;
            $brands->slug = $request->slug;
            $brands->status = $request->status;
            $brands->save();

            $request->session()->flash('success', 'Brands Updated Successfully');
            return response()->json(['status' => true, 'msg' => 'Brands Updated Successfully']);
        } else {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }

    public function destroy(Request $request, $id) {
        $brands = Brand::find($id);

        if (empty($brands->id)) {
            $request->session()->flash('success', 'Brands Does Not Exists');
            return response()->json(['status' => false, 'msg' => 'Brands Does Not Exists']);
        }

        if (isset($brands->id)) 
            $brands->delete();
        
        $request->session()->flash('success', 'Brands Deleted Successfully');
        return response()->json(['status' => true, 'msg' => 'Brands Deleted Successfully']);
    }
}
