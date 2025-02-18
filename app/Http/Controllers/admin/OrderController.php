<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $data = Order::orderBy('orders.created_at', 'desc')
                    ->select('orders.*', 'users.name', 'users.email')
                    ->leftjoin('users', 'users.id', '=', 'orders.user_id');

        if (!empty($request->get('search'))) {
            $data = $data->where('users.name', 'like','%'. $request->get('search') .'%')
                        ->orWhere('users.email', 'like','%'. $request->get('search') .'%')
                        ->orWhere('orders.id', 'like','%'. $request->get('search') .'%');
        }
        
        $data = $data->paginate(10);
        
        // $modifiedCollection = $data->getCollection()->transform(function ($item) {     // get date time format like 12 oct, 2025
        //     $item->formattedDate = Carbon::parse($item->created_at)->format('d M, Y');
        //     return $item;
        // });

        // $data = new LengthAwarePaginator(     // Create a new paginator with the modified collection
        //     $modifiedCollection, // Updated collection
        //     $data->total(),
        //     $data->perPage(),
        //     $data->currentPage(),
        //     ['path' => request()->url(), 'query' => request()->query()] // Keep query parameters
        // );

        return view('admin.orders.orderList', compact('data'));
    }

    public function getOrderDetail($orderId)
    {
        $data = Order::where('orders.id', $orderId)
                    ->select('orders.*', 'countries.name as countryName')
                    ->join('countries', 'orders.country_id', '=', 'countries.id')
                    ->first();

        $orderItem = OrderItem::where('order_id', $orderId)->get();

        $data['order'] = $data;
        $data['orderItem'] = $orderItem;

        return view('admin.orders.orderDetails', $data);
    }

    public function changeOrderStatus(Request $request, $statusId)
    {
        $orders = Order::find($statusId);
        
        $orders['status'] = $request->orderStatus;
        $orders['shipped_date'] = $request->shipped_date;
        $orders->save();

        session()->flash('success', 'Order status updated successfull');
        return response()->json(['status' => true, 'msg' => 'order status updated successfull']);
    }

    public function sendInvoiceEmail(Request $request, $orderId) 
    {
        orderEmail($orderId, $request->userType);
        
        $msg = 'Order mail send successfully';
        
        session()->flash('success', 'Order email send successfull');
        return response()->json(['status' => true, 'msg' => 'order email send successfull']);
    }


}
