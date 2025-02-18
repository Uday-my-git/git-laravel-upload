<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = User::latest();

        if (!empty($request->get('search'))) {
            $user = $user->where('name', 'like', '%'. $request->get('search') .'%')
                    ->orWhere('email', 'like', '%'. $request->get('search') .'%')
                    ->orWhere('id', 'like', '%'. $request->get('search') .'%');
        }

        $user = $user->paginate(10);

        return view('admin.user.userListing', compact('user'));
    }
    
    public function create()
    {
        return view('admin.user.userCreate');
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'phone' => 'required',
            'role' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        } else {
            $users = new User;

            $users->name = $request->name;
            $users->email = $request->email;
            $users->phone = $request->phone;
            $users->role = $request->role;
            $users->status = $request->status;
            $users->password = Hash::make($request->password);
            $users->save();
        }

        session()->flash('success', 'User Account Created Successfully');
        return response()->json(['status' => true, 'msg' => 'user account created successfully']);
    }

    public function edit(Request $request, $id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            session()->flash('errors', 'User Account Not Found');
            return response()->json(['status' => false, 'msg' => 'user account not found']);
        }

        return view('admin.user.userEdit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $users = User::find($id);

        if (empty($users)) {
            $request->session()->flash('error', 'User Account Not Found');
            return response()->json(['status' => true, 'msg' => 'user account not found']);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$id.',id',
            'phone' => 'required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        } else {
            $users->name = $request->name;
            $users->email = $request->email;
            $users->phone = $request->phone;
            $users->role = $request->role;
            $users->status = $request->status;

            if (!empty($request->password)) {
                $users->password = Hash::make($request->password);
            }
            $users->save();
        }

        session()->flash('success', 'User Account Updated Successfully');
        return response()->json(['status' => true, 'msg' => 'user account updated successfully']);
    }

    public function remove(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            $request->session()->flash('error', 'This User Does Not Exist');
            return response()->json(['status' => true, 'msg' => 'This User Does Not Exist']);
        }

        $user->delete();

        $request->session()->flash('success', 'User delete successfully');
        return response()->json(['status' => true, 'msg' => 'User delete successfully']);
    }


}
