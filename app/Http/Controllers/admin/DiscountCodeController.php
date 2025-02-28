<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiscountCoupon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class DiscountCodeController extends Controller
{
    public function index(Request $request)
    {
        $data = DiscountCoupon::latest();

        if (!empty($request->get('search'))) {
            $data = $data->where('coupon_code', 'like', '%' . $request->get('search') . '%')
                        ->orWhere('description', 'like', '%' . $request->get('search') . '%');
        }
        $data = $data->paginate(5);

        return view('admin.coupons.list', compact('data'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required',
            'nameDiscountCoupon' => 'required',
            'starts_at' => 'required',
            'expires_at' => 'required',
            'discount_amount' => 'required',
            'min_amount' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        } else {
            $couponCodeCount = DiscountCoupon::where('coupon_code', $request->coupon_code)->count();
            
            if ($couponCodeCount > 0) {
                return response()->json(['status' => false, 'msg' => 'This coupon already exist']);
            } else {
                if (!empty($request->starts_at)) {
                    $now = Carbon::now();
                    $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                    
                    if ($startAt->lte($now) == true) {
                        return response()->json(['status' => false, 'errors' => ['starts_at' => 'start date can not be less than to cureent date & time']]);
                    }
                }

                if (!empty($request->starts_at) && !empty($request->expires_at)) {
                    $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                    $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);

                    if (!empty($expiresAt->gt($startAt)) == false) {
                        return response()->json(['status' => false, 'errors' => ['expires_at' => 'expiry date must be greater than to start date & time']]);
                    }
                }

                $table = new DiscountCoupon();
                
                $table->coupon_code = $request->coupon_code;
                $table->nameDiscountCoupon = $request->nameDiscountCoupon;
                $table->description = $request->description;
                $table->max_uses = $request->max_uses;
                $table->max_uses_user = $request->max_uses_user;
                $table->type = $request->type;
                $table->starts_at = $request->starts_at;
                $table->expires_at = $request->expires_at;
                $table->discount_amount = $request->discount_amount;
                $table->min_amount = $request->min_amount;
                $table->status = $request->status;
                $table->save();

                session()->flash('success', 'Discount coupons add successfully');
                return response()->json(['status' => true, 'msg' => 'discount coupons add successfully']);
            }
        }
    }

    public function edit(Request $request, $id)
    {
        $data = DiscountCoupon::find($id);

        if ($data == null) {
            session()->flash('error', 'Discount coupons records not found');
            return redirect()->route('coupons.list');
        }

        $data['data'] = $data;
        return view('admin.coupons.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $data = DiscountCoupon::find($id);

        if (is_null($data)) {
            session()->flash('errors', 'Records not found');
            return response()->json(['status' => true, 'errors' => 'Records not found']);
        }

        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required',
            'nameDiscountCoupon' => 'required',
            'starts_at' => 'required',
            'expires_at' => 'required',
            'discount_amount' => 'required',
            'min_amount' => 'required',
        ]);

        if ($validator->passes()) {
            if (!empty($data->starts_at) && !empty($data->expires_at)) {
                $starts_at = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                $expires_at = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);

                if ($expires_at->gt($starts_at) == false) {
                    return response()->json(['status' => false, 'errors' => ['starts_at' => 'expires date musr be greter then start date & time']]);
                }
            }

            $data->coupon_code = $request->coupon_code;
            $data->nameDiscountCoupon = $request->nameDiscountCoupon;
            $data->description = $request->description;
            $data->max_uses = $request->max_uses;
            $data->max_uses_user = $request->max_uses_user;
            $data->type = $request->type;
            $data->starts_at = $request->starts_at;
            $data->expires_at = $request->expires_at;
            $data->discount_amount = $request->discount_amount;
            $data->min_amount = $request->min_amount;
            $data->status = $request->status;
            $data->save();

            session()->flash('success', 'Discount coupons updated successfully');
            return response()->json(['status' => true, 'msg' => 'discount coupons updated successfully']);
        } else {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }

    public function destroy(Request $request, $id)
    {
        $data = DiscountCoupon::find($id);

        if (is_null($data)) {
            session()->flash('error', 'Records not found');
            return response()->json(['status' => true, 'errors' => 'Records not found']);
        }

        $data->delete();

        session()->flash('success', 'Discount coupons deleted successfully');
        return response()->json(['status' => true, 'msg' => 'discount coupons deleted successfully']);
    }
}