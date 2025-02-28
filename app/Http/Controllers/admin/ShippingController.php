<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Shipping;
use App\Models\Country;

class ShippingController extends Controller
{
    public function create()
    {
        $countries = Country::orderBy('name', 'asc')->get();
        $data['countries'] = $countries;
      
        $shipping = Shipping::select('shipping_charges.*', 'countries.name as cName')
                        ->leftJoin('countries', 'countries.id', '=', 'shipping_charges.country_id')
                        ->simplePaginate(5);
        
        $data['shipping'] = $shipping;
        return view('admin.shipping.create', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country' => 'required',
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        } else {
            $shippingCount = Shipping::where('country_id', $request->country)->count();
            
            if ($shippingCount > 0) {
                session()->flash('error', 'This shipping country already exist');
                return response()->json(['status' => true, 'msg' => 'This shipping country already exist']);
            } else {
                $countries = new Shipping();
                $countries->country_id = $request->country;
                $countries->amount = $request->amount;
                $countries->save();
    
                return response()->json(['status' => true, 'msg' => 'Shipping Countries Charges save successfull']);
            }
        }
    }

    public function edit($id)
    {
        $countries = Country::orderBy('name', 'asc')->get();
        $data['countries'] = $countries;
        
        $shipping = Shipping::find($id);
        $data['shipping'] = $shipping;

        return view('admin.shipping.edit', $data);
    }

    public function update(Request $request, $id)
    {   
        $validator = Validator::make($request->all(), [
            'country' => 'required',
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        } else {
            $countries = Shipping::find($id);

            if (isset($countries->id) || !is_null($countries->id)) {
                $countries->country_id = $request->country;
                $countries->amount = $request->amount;
                $countries->save();
                
                return response()->json(['status' => true, 'msg' => 'Shipping Countries Charges update successfull']);
            } else {
                session()->flash('error', 'Shipping data not found');
                return response()->json(['status' => true, 'msg' => 'shipping data not found']);
            }
        }
    }

    public function destroy($id)
    {
        $shipping = Shipping::find($id);
        
        if (isset($shipping->id) || !is_null($shipping)) {
            $shipping->delete();

            session()->flash('success', 'Shipping data delete successfull');
            return response()->json(['status' => true, 'msg' => 'shipping data delete successfull']);
        } else {
            session()->flash('error', 'Shipping data not found');
            return response()->json(['status' => true, 'msg' => 'shipping data not found']);
        }
    }


}
