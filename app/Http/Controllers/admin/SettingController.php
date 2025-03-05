<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function chagePasswordForm()
    {
        return view('admin.change-password');
    }

    public function chagePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|min:5|max:15',
            'new_password' => 'required|min:5|max:15',
            'confirm_password' => 'required|same:new_password',
        ]);

        $admin = User::where('id', Auth::guard('admin')->user()->id)->first();
        $currentPasswordStatus = Hash::check($request->old_password, $admin->password);

        if (!$currentPasswordStatus) {
            return redirect()->back()->with('error', 'Current Password does not match with Old Password');
        } else {
            User::findOrFail(Auth::guard('admin')->user()->id)->update([
                'password' => Hash::make($request->new_password),
            ]);

            return redirect()->back()->with('success', 'Password Updated Successfully');
        }
    }
}
