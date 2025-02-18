<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TempImage;
use Image;

class TempImagesController extends Controller
{
    public function create(Request $request) 
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $newName = time() . '.' . $image->getClientOriginalExtension();

            $tempImg = new TempImage();
            $tempImg->name = $newName;
            $tempImg->save();

            $image->move(public_path() . '/temp-img', $newName);

            // create thumbnail
            $sourcePath = public_path() . '/temp-img/' . $newName;
            $destinationPath = public_path() . '/temp-img/thumb/' . $newName;

            $image = Image::make($sourcePath)->fit(300, 275)->save($destinationPath);

            return response()->json(['status' => true, 'img_id' => $tempImg->id, 'img_path' => asset('/temp-img/thumb/' . $newName), 'msg' => 'image uploaded successfully']);
        }
    }


}
