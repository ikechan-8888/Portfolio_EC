<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function buyStock(Request $request, Stock $stock){
        $id = $request->id;
        $size = $request->size;
        $number = $request->number;
        $count = count($id);
        if(Auth::check()){
            $loginId = Auth::user()->login_id;
            for($i = 0; $i < $count; $i++){
                $getrecord = $stock->where("product_id", "=", $id[$i])->first();
                $stockItems = null;
                $changeSize = null;
                if($size[$i] === "S"){
                    $stockItems = $getrecord->ssize_items;
                    $changeSize = "ssize_items";
                }else if($size[$i] === "M"){
                    $stockItems = $getrecord->msize_items;
                    $changeSize = "msize_items";
                }else if($size[$i] === "L"){
                    $stockItems = $getrecord->lsize_items;
                    $changeSize = "lsize_items";
                }else if($size[$i] === "XL"){
                    $stockItems = $getrecord->xlsize_items;
                    $changeSize = "xlsize_items";
                }
                $toralStock = $stockItems - $number[$i];

                $stock->where("product_id", "=", $id[$i])->update([ $changeSize => $toralStock]);
            }
        }

        return $this->jsonResponse($count);
    }

    public function buyCheck(Request $request, Product $product){
        $datas = $request->all();
        $checkId = [];
        foreach($datas as $data){
            array_push($checkId, $data['id']);
        }

        $loginId = null;
        if(Auth::check()){
            $loginId = Auth::user()->login_id;
        }else{
            $loginId = session('_token');
        }

        $getData = $product
            ->leftjoin('stock', 'product.id', '=', 'stock.product_id')
            ->select('product.id','stock.ssize_items','stock.msize_items','stock.lsize_items','stock.xlsize_items')
            ->where([["delete_flag", 0], ["display_status", 1]])
            ->whereIn("product.id", $checkId)
            ->get();

        return $this->jsonResponse($getData);
    }

}
