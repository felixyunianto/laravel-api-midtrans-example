<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MidtransController extends Controller
{
    public function test(Request $request){
        \Midtrans\Config::$serverKey = 'SB-Mid-server-ek36Up0qHY97kwsllL2XmVbW';
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $shipping_address = array(
            "first_name" => $request->customer_details["first_name"],
            "last_name" => $request->customer_details["last_name"],
            "phone" => $request->customer_details["phone"],
            "address" => $request->customer_details["shipping_address"]["address"],
            "city" => $request->customer_details["shipping_address"]["city"],
            "postal_code" => $request->customer_details["shipping_address"]["postal_code"],
        );

        $billing_address = array(
            "first_name" => $request->customer_details["first_name"],
            "last_name" => $request->customer_details["last_name"],
            "phone" => $request->customer_details["phone"],
            "address" => $request->customer_details["billing_address"]["address"],
            "city" => $request->customer_details["billing_address"]["city"],
            "postal_code" => $request->customer_details["billing_address"]["postal_code"],
        );

        $customer_details = array(
            "first_name" => $request->customer_details["first_name"],
            "last_name" => $request->customer_details["last_name"],
            "email" => $request->customer_details["email"],
            "phone" => $request->customer_details["phone"],
            // 'billing_address'  => $billing_address,
            // 'shipping_address' => $shipping_address
        );

        $params = array(
            'transaction_details' => $request->transaction_details,
            'item_details' => $request->item_details,
            // 'customer_details' => $customer_details
        );
            
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;

        // dd($request->all());

        return response()->json([
            "token" => $snapToken,
            "redirect_url" => $paymentUrl
        ]);
    }
}
