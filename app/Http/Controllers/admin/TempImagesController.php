<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TempImage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
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

           // Generate Image Thumbnail
            $srcPath = public_path() . '/temp/' . $newName;
            $destiNationPath = public_path() . '/temp/thumb/' . $newName;

            $manager = new ImageManager(Driver::class);
            $image = $manager->read($srcPath);
            $image->cover(300, 275);
            $image->save($destiNationPath);
            
            return response()->json(['status' => true, 'img_id' => $tempImg->id, 'img_path' => asset('/temp-img/thumb/' . $newName), 'msg' => 'image uploaded successfully']);
        }
    }


}
