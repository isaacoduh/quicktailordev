<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('users')->get();
        return response()->json(['data' => $orders]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_ref' => 'required|unique:orders',
            'user_id' => 'required',
            'amount' => 'required',
            'items' => 'required',
            'address' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->messages(),400);
        }

        $url ='https://api.paystack.co/transaction/verify'.$request->payment_ref;
        // open connection
        $ch = curl_init();
        //set request parameters
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HTTPHEADER, ["Authorization: Bearer ".env('PAYSTACK_SECRET_KEY').""]);

        //send request
        $req =curl_exec($ch);
        //close connection
        curl_close($ch);
        // array to contain result
        $result = array();

        if($req){
            $result = json_decode($req,true);
        }

        if(array_key_exists('data',$result) && array_key_exists('status', $result['data']) && ($result['data']['status']==='success')){
            $order = Order::create($request->only(['payment_ref', 'user_id', 'amount', 'items', 'address']));
            $order->save();

            return response()->json(['message' =>'Checkout successful. Your order will be processed as soon as possible']);
        }else{
            return response()->json(['message' => 'Transaction failed!.Try again later'], 400);
        }
    }
}
