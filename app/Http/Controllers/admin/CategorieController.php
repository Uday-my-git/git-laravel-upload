<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Image;

class CategorieController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('id', 'desc');

        if (!empty($request->get('search'))) {
            $data = $categories->where('name', 'like', '%'. $request->get('search') .'%');
        }

        $data = $categories->paginate(10);
        
        return view('admin.category.list', ['data' => $data]);
        $request->session()->flash('success', 'New Category Added Successfully');
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);

        if ($validator->passes()) {
            $category = Category::create([            // Type 1
                'name' => $request->name,
                'slug' => $request->slug,
                'status' => $request->status,
                'showHome' => $request->showHome,
            ]);

            // $category = new Category();           // Type 2

            // $category->name = $request->name;
            // $category->slug = $request->slug;
            // $category->status = $request->status;
            // $category->showHome = $request->showHome;
            // $category->save();

            if (!empty($request->image_id)) {
                $tempImg = TempImage::find($request->image_id);
                $extensionArr = explode('.', $tempImg->name);
                $lastExtension = last($extensionArr);

                $newImg = $category->id . '.' . $lastExtension;
                  
                $sourcePath = public_path('/temp-img/') . $tempImg->name; 
                $destinationPath = public_path('/uploads/category/') . $newImg; 
                
                File::copy($sourcePath, $destinationPath);

                // Intervention Image Librarey For resize image to fixed size
                $newDestinationPath = public_path(). '/uploads/thumb/' . $newImg; 
                $img = Image::make($sourcePath);

                // add callback functionality to retain maximal original image size
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });

                $img->save($newDestinationPath);

                $category->image = $newImg;
                $category->update();     // Type 1

                // $category->save();    // Type 2
            }

            $request->session()->flash('success', 'Category addedd succesffully');
            return response()->json(['status' => true, 'msg' => 'Category addedd succesffully']);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id)
    {
        $category = Category::where('id', $id)->first();

        if (!isset($category) || empty($category)) {
            return redirect()->route('categories.index');
        }
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::where('id', $id)->first();

        if (empty($category->id)) {
            $request->session()->flash('error', 'This Categor Not Exists For Updateing Data');
            return response()->json(['status' => true, 'notFound' => true, 'msg' => 'This Categor Not Exists For Update User Data']);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$category->id.',id',
        ]);

        if ($validator->passes()) {
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            // Check if file exists and delete them
            $thumbPath = public_path('uploads/thumb/') . $category->image;
            $mainPath = public_path('uploads/category/') . $category->image;
        
            if (File::exists($thumbPath) || File::exists($mainPath)) {
                File::delete([$thumbPath, $mainPath]);
            }
    
            if (isset($request->image_id)) {
                $tempImg = TempImage::find($request->image_id);
                $extensionArr = explode('.', $tempImg->name);
                $lastExtension = last($extensionArr);

                $newImg = $category->id . '-' . time() . '.' . $lastExtension;
                $sourcePath = public_path(). '/temp-img/' . $tempImg->name;
                $destinationPath = public_path(). '/uploads/category/' . $newImg;

                File::copy($sourcePath, $destinationPath);
                
                $newDestinationPath = public_path() . '/uploads/thumb/' . $newImg;           // generate image thumbnail
                $img = Image::make($sourcePath);

                $img->fit(450, 600, function ($constraint) {        // add callback functionality to retain maximal original image size
                    $constraint->upsize();
                });

                $img->save($newDestinationPath);

                $category->image = $newImg;
                $category->save();
            }
            $request->session()->flash('success', 'Categories updaed successfully');
            return response()->json(['status' => true, 'msg' => 'Categories updaed successfully']);
        } else {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }

    public function destroy(Request $request, $id)
    {   
        $category = Category::find($id);
    
        if (empty($category->id)) {
            $request->session()->flash('error', 'This Categor Not Exists');
            return response()->json(['status' => true, 'msg' => 'This Categor Not Exists']);
        }

        if (isset($category->image)) {
            $mainPath = public_path() . '/uploads/thumb/' . $category->image;
            $thumbPath = public_path() . '/uploads/category/' . $category->image; 

            if (File::exists($mainPath) || File::exists($thumbPath))
                File::delete([$mainPath, $thumbPath]);
        }

        $category->delete();
        
        $request->session()->flash('success', 'Categories delete successfully');
        return response()->json(['status' => true, 'msg' => 'Category delete successfully']);
    }
}
