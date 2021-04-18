<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buyproduct;
use Illuminate\Support\Facades\Auth;

class BuyproductController extends Controller
{
    public function buyproduct(Request $request, Buyproduct $buyproduct){
        if(Auth::check()){
            $loginId = null;
            if(Auth::check()){
                $loginId = Auth::user()->login_id;
            }else{
                $loginId = session('_token');
            }
            $list = $request['products'];
            $id = $request['buyId'];
            foreach($list as $data){
                $buyproduct::create(
                    [
                        'login_id' => $loginId,
                        'product_id' => $data['product_id'],
                        'buy_id' => $id,
                        'item_size' => $data['item_size'],
                        'item_number' => $data['item_number'],
                    ]
                );
            }
        }

        return $this->jsonResponse(['loginId' => null]);
    }

    public function GetBoughtProductList(string $id , Buyproduct $buyproduct){
        $loginId = Auth::user()->login_id;
        $getData = $buyproduct
            ->leftjoin('product', 'buyproduct.product_id', '=', 'product.id')
            ->select('buyproduct.*','product.price','product.color','product.name','product.img_name')
            ->where([["login_id", $loginId] ,["buy_id", $id]])
            ->get();

        return $this->jsonResponse($getData);
    }
}
