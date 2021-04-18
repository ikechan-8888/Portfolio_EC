<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buy;
use Illuminate\Support\Facades\Auth;

class BuyController extends Controller
{
    public function buy(Request $request, Buy $buy){
        $loginId = null;
        if(Auth::check()){
            $loginId = Auth::user()->login_id;
        }else{
            $loginId = session('_token');
        }

        $record = $buy::create(
            [
                'login_id' => $loginId,
                'buy_price' => $request['money'],
                'buy_name' => $request['delivery']['name'],
                'postal_code' => $request['delivery']['code'],
                'delivery_address' => $request['delivery']['address'],
                'tel' => $request['delivery']['tel'],
                'shipping_flag' => 0,
            ]
        );

        return $this->jsonResponse($record);
    }

    public function GetBoughtList(Buy $buy){
        $loginId = Auth::user()->login_id;

        $getData = $buy
            ->where("login_id", "=", $loginId)
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->jsonResponse($getData);
    }
}
