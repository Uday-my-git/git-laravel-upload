<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Page;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $pages = Page::latest();

        if (!empty($request->get('search'))) {
            $pages = $pages->where('name', 'like', '%'. $request->get('search') .'%');
        }

        $pages = $pages->simplePaginate(5);

        return view('admin.pages.listPage', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.createPage');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:pages',
            'content' => 'required',
        ]);

        if ($validator->passes()) {
            $pages = Page::create([
                'name' => $request->name,
                'content' => $request->content,
                'slug' => $request->slug,
            ]);

            session()->flash('success', 'Pages Add Successfully');
            return response()->json(['status' => true, 'msg' => 'pages add successfully']);
        } else {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }

    public function edit(Request $request, $id)
    {
        $pages = Page::find($id);

        if (is_null($pages)) {
            session()->flash('error', 'Page Not Found');
            return response()->json(['status' => true, 'msg' => 'page not found']);
        }

        return view('admin.pages.editPage', ['pages' => $pages]);
    }

    public function update(Request $request, $id)
    {
        $page = Page::where('id', $id)->first();

        if (is_null($page)) {
            session()->flash('error', 'Page Not Found');
            return response()->json(['status' => true, 'msg' => 'page not found']);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        } else {
            $page->name = $request->name;
            $page->slug = $request->slug;
            $page->content = $request->content;
            $page->save();
            
            session()->flash('success', 'Pages Updated Successfully');
            return response()->json(['status' => true, 'msg' => 'pages updated successfully']);
        }
    }

    public function delete(Request $request, $id)
    {
        $pages = Page::find($id);

        if (is_null($pages)) {
            session()->flash('error', 'Page Not Exist');
            return response()->json(['status' => true, 'msg' => 'page not exist']);
        }

        $pages->delete();

        session()->flash('success', 'Pages Deleted Successfully');
        return response()->json(['status' => true, 'msg' => 'pages deleted successfully']);
    }
}
