<?php

namespace App\Http\Controllers;

use App\Models\BillingSummary;
use App\Models\Cities;
use App\Models\CustomerInformation;
use App\Models\Orders;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(Request $request){
        $cities = Cities::get()->toArray();

        return view('welcome', ['cities' => $cities]);
    }

    public function createOrder(Request $request)
    {
//        dd($request->all());
        $this->validate($request, [
            'name'          => 'required|string',
            'email'         => 'required|email|unique:customer_information',
            'contactNo'             => 'required|required|regex:/[6-9]{1}[0-9]{9}/',
            'sub_total'             => 'required|numeric',
            'discount'              => 'required|numeric|min:0',
            'grand_total'           => 'required|numeric|min:0',
            'summary'               => 'required|array',
            'summary.*.productName' => 'required|string',
            'summary.*.quantity'    => 'required|numeric|min:0',
            'summary.*.price'       => 'required|numeric|min:0',
            'summary.*.total'       => 'required|numeric|min:0',
        ]);


        try{

            \DB::beginTransaction();

            //create user
            $customer = CustomerInformation::create([
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contactNo,
                'city' => $request->city ?? "",
            ]);

            $orderId = time();
            $order = Orders::create([
                'order_id'    => $orderId,
                'customer_id' => $customer->id,
                'sub_total'   => $request->sub_total,
                'discount'    => $request->discount,
                'gst'         => $request->gst,
                'grand_total' => $request->grand_total,
            ]);

            // create order summary

            $orderSummary = [];
            foreach ($request->summary as $item) {
                $orderSummary[] = [
                    'order_id'            => $orderId,
                    'product_description' => $item['productName'],
                    'qty'                 => $item['quantity'],
                    'price'               => $item['price'],
                    'total'               => $item['total'],
                ];
            }

            BillingSummary::insert($orderSummary);
            \DB::commit();

            return view('success', ['orderId' => $orderId]);

        }catch(\Exception $e){
            \DB::rollback();

            return redirect()->back()->withErrors([$e->getMessage()]);
        }

    }
}
