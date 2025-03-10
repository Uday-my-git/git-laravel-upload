<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $data['orders'] = Order::where('status', '!=', 'cancelled')->count();
        $data['products'] = Product::count();
        $data['user'] = User::where('role', 1)->count();
        $data['totalRevenue'] = Order::where('status', '!=', 'cancelled')->sum('grand_total');

        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $currentDate = Carbon::now()->format('Y-m-d');

        $data['revenueThisMonth'] = Order::where('status', '!=', 'cancelled')
                        ->whereDate('created_at', '>=', $startOfMonth)
                        ->whereDate('created_at', '>=', $currentDate)
                        ->sum('grand_total');

        $lastMonStartDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        $endMonEndDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        $data['lastMonName'] = Carbon::now()->subMonth()->startOfMonth()->format('M');

        $data['revenueLastMonth'] = Order::where('status', '!=', 'cancelled')
                            ->whereDate('created_at', '>=', $lastMonStartDate)
                            ->whereDate('created_at', '<=', $endMonEndDate)
                            ->sum('grand_total');

        return view('admin.dashboard', $data);
        
        // $admin = Auth::guard('admin')->user();
        // echo "Welcome " . $admin->name . '<a href="'.route('admin.logout').'"> Logout Now</a>';
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

  


}
