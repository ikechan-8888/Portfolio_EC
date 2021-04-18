<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function buyCart(Request $request, Cart $cart){
        $loginId = null;
        if(Auth::check()){
            $loginId = Auth::user()->login_id;
        }else{
            $loginId = session('_token');
        }
        $getrecord = $cart->where([["login_id", $loginId],["product_id", $request->id],["item_size", $request->size],["status", 0]])->get();
        if($getrecord->isEmpty()){
            $cart::create(['login_id' => $loginId, 'product_id' => $request->id, 'item_size' => $request->size, 'item_number' => 1, 'status' => 0]);
            return $this->jsonResponse(['loginId' => $loginId, 'id' => $request->id]);
        }else{
            $record = $getrecord->toArray();
            $upLoginId = $record[0]['login_id'];
            $upProductId = $record[0]['product_id'];
            $upSize = $record[0]['item_size'];
            $upItemNumber = $record[0]['item_number'] + 1;
            $cart->where([["login_id", $upLoginId],["product_id", $upProductId],["item_size", $upSize],["status", 0]])->update(["item_number" => $upItemNumber]);
            return $this->jsonResponse(['loginId' => $loginId, 'id' => $request->id]);
        }
    }

    public function buyCartLoginChange(Request $request, Cart $cart){
        $loginId = Auth::user()->login_id;
        $sessionId = $request->sessionId;

        $cart->where([["login_id", $sessionId],["status", 0]])->update(["login_id" => $loginId]);
        return $this->jsonResponse(['loginId' => $loginId]);
    }

    public function GetBuyList(Cart $cart){
        $loginId = null;
        if(Auth::check()){
            $loginId = Auth::user()->login_id;
        }else{
            $loginId = session('_token');
        }
        $getData = $cart
            ->leftjoin('product', 'cart.product_id', '=', 'product.id')
            ->select('cart.*','product.price','product.color','product.name','product.img_name')
            ->where([["cart.login_id", $loginId] ,["cart.status", 0]])
            ->orderBy('created_at', 'asc')
            ->get();

        return $this->jsonResponse($getData);
    }

    public function buyCartChange(Request $request, Cart $cart){
        if($request->change === 0){
            $cart->where([["login_id", $request->loginId],["product_id", $request->productId],["item_size", $request->size],["status", 0]])->update(["item_number" => $request->number - 1]);
            return $this->jsonResponse(['loginId' => $request->loginId]);
        }else if($request->change === 1){
            $cart->where([["login_id", $request->loginId],["product_id", $request->productId],["item_size", $request->size],["status", 0]])->update(["item_number" => $request->number + 1]);
            return $this->jsonResponse(['loginId' => $request->loginId]);
        }
    }

    public function boughtCartChange(Request $request, Cart $cart){
        $data = $request->all();
        $loginId = null;
        if(Auth::check()){
            $loginId = Auth::user()->login_id;
        }else{
            $loginId = session('_token');
        }
        $cart->where("login_id", "=" , $loginId)->whereIn('id', $data)->update(["status" => 1]);
        return $this->jsonResponse(['loginId' => $request->loginId]);
    }

    public function putCart(Request $request, Cart $cart){
        $datas = $request->products;
        foreach($datas as $data){
            $cart->where("id", "=", $data['id'])->update(["item_number" => $data['stock']]);
        }
        return $this->jsonResponse(['number' => count($datas)]);
    }

    public function deleteCart(Request $request, Cart $cart){
        $datas = $request->products;
        $deleteId = [];
        foreach($datas as $data){
            array_push($deleteId, $data['id']);
        }

        $getData = $cart
            ->whereIn("id", $deleteId)
            ->delete();

        return $this->jsonResponse($getData);
    }

    public function buyCartDelete(Request $request, Cart $cart){
        $cart->where([["login_id", $request->loginId],["product_id", $request->productId],["item_size", $request->size],["status", 0]])->delete();
        return $this->jsonResponse(['loginId' => $request->loginId]);
    }
}
