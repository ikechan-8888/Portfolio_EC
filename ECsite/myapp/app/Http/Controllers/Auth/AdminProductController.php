<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Cart;
use App\Models\Top;
use App\Models\Bottom;
use App\Models\Buy;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Validator;
use App\Rules\size;
use App\Rules\img;

class AdminProductController extends Controller
{

    public function GetProductNumber(Product $product){
        $getData = $product
        ->select('id', 'display_status')
        ->where("delete_flag", 0)
        ->get();
        return $this->jsonResponse($getData);
    }

    public function GetBuyToday(Buy $buy){
        date_default_timezone_set('Asia/Tokyo');
        $getToday = date("Y-m-d");
        $today = new DateTime($getToday);
        $today->setTimeZone( new DateTimeZone('UTC'));
        $today->format(DateTime::ISO8601);

        $getData = $buy
        ->leftjoin('buyproduct', 'buy.id', '=', 'buyproduct.buy_id')
        ->select('buy.id', 'buy.created_at', 'buy.buy_price', 'buy.shipping_flag', DB::raw('group_concat(buyproduct.item_number) as item_numbers'))
        //DB::raw('group_concat(buyproduct.item_number) as item_numbers')buy.idが一緒のitem_numberは1つにする
        ->where("buy.created_at", '>=' , $today)
        ->groupBy('buy.id')//何を基準に1つのレコードとしてまとめるか(今回は購入IDが一緒のものは1つにする)
        ->orderBy('buy.created_at', 'desc')
        ->get();
        return $this->jsonResponse($getData);
    }

    public function GetNotShipping(Buy $buy){
        $getData = $buy
        ->leftjoin('buyproduct', 'buy.id', '=', 'buyproduct.buy_id')
        ->select('buy.id', DB::raw('group_concat(buyproduct.item_number) as item_numbers'))
        ->where("shipping_flag", 0)
        ->groupBy('buy.id')
        ->get();
        return $this->jsonResponse($getData);
    }

    public function GetChartLinedata(Buy $buy){
        date_default_timezone_set('Asia/Tokyo');
        $getDaysAgo = date("Y-m-d",strtotime("-6 day"));
        $daysAgo = new DateTime($getDaysAgo);
        $daysAgo->setTimeZone( new DateTimeZone('UTC'));
        $daysAgo->format(DateTime::ISO8601);

        $getData = $buy
        ->leftjoin('buyproduct', 'buy.id', '=', 'buyproduct.buy_id')
        ->select('buy.id', 'buy.created_at', 'buy.buy_price', DB::raw('group_concat(buyproduct.item_number) as item_numbers'))
        //DB::raw('group_concat(buyproduct.item_number) as item_numbers')buy.idが一緒のitem_numberは1つにする
        ->where("buy.created_at", '>=' , $daysAgo)
        ->groupBy('buy.id')//何を基準に1つのレコードとしてまとめるか(今回は購入IDが一緒のものは1つにする)
        ->orderBy('buy.created_at', 'desc')
        ->get();
        return $this->jsonResponse($getData);
    }

    public function GetProductList(Product $product){
        $getData = $product
        ->leftjoin('stock', function($join){
            $join->on('product.id', '=', 'stock.product_id');
        })
        ->select('product.*', 'stock.ssize_register_items', 'stock.msize_register_items', 'stock.lsize_register_items', 'stock.xlsize_register_items', 'stock.ssize_items', 'stock.msize_items', 'stock.lsize_items', 'stock.xlsize_items')
        ->where("delete_flag", 0)
        ->get();
        return $this->jsonResponse($getData);
    }

    public function GetShippingList(Buy $buy){
        $getData = $buy
        ->leftjoin('buyproduct', 'buy.id', '=', 'buyproduct.buy_id')
        ->select('buy.id', 'buy.created_at', 'buy.updated_at', 'buy.login_id', 'buy.delivery_address', 'buy.shipping_flag', DB::raw('group_concat(buyproduct.item_number) as item_numbers'))
        ->groupBy('buy.id')
        ->get();
        return $this->jsonResponse($getData);
    }

    public function GetEditShipping(string $id, Buy $buy){
        $getData = $buy
        ->leftjoin('buyproduct', 'buy.id', '=', 'buyproduct.buy_id')
        ->leftjoin('product', 'buyproduct.product_id', '=', 'product.id')
        ->select('buy.*', 'product.name', 'product.img_name' , 'buyproduct.product_id', 'buyproduct.item_size', 'buyproduct.item_number')
        ->where("buy.id", $id)
        ->get();
        return $this->jsonResponse($getData);
    }

    public function shippingDoneEdit(Request $request, Buy $buy){
        $id = $request->id;
        $getData = $buy
        ->where("id", $id)
        ->update(["shipping_flag" => 1]);
        return $this->jsonResponse($getData);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Validation\Validator
    */
    public function validatecheck(Request $request){
        $data = $request->all();

        $rulus = [
            'name' => ['required', 'string'],
            'price' => ['required', 'integer', 'numeric'],
            'color' => ['required'],
            'content' => ['required', 'string'],
            'category' => ['required'],
            'ssizeitems' => ['required', 'integer', 'numeric', 'between:0,999999'],
            'msizeitems' => ['required', 'integer', 'numeric', 'between:0,999999'],
            'lsizeitems' => ['required', 'integer', 'numeric', 'between:0,999999'],
            'xlsizeitems' => ['required', 'integer', 'numeric', 'between:0,999999'],
        ];

        if(!$data["imgName"]){
            $rulus['img'] = ['required', 'unique:product,img_name', new img];
        }else if($data["imgName"] && $data["img"]){
            $rulus['img'] = ['unique:product,img_name', new img];
        }

        if($data["category"] !== ""){
            if($data["category"] === "0"){
                $rulus += array(
                    'topscategory' => ['required'],
                    'ssizebody' => ['required', new size],
                    'ssizeshoulder' => ['required', new size],
                    'ssizelength' => ['required', new size],
                    'ssizesleeve' => ['required', new size],
                    'msizebody' => ['required', new size],
                    'msizeshoulder' => ['required', new size],
                    'msizelength' => ['required', new size],
                    'msizesleeve' => ['required', new size],
                    'lsizebody' => ['required', new size],
                    'lsizeshoulder' => ['required', new size],
                    'lsizelength' => ['required', new size],
                    'lsizesleeve' => ['required', new size],
                    'xlsizebody' => ['required', new size],
                    'xlsizeshoulder' => ['required', new size],
                    'xlsizelength' => ['required', new size],
                    'xlsizesleeve' => ['required', new size]
                );
            }else if($data["category"] === "1"){
                $rulus += array(
                    'bottomscategory' => ['required'],
                    'ssizewaist' => ['required', new size],
                    'ssizehips' => ['required', new size],
                    'ssizerise' => ['required', new size],
                    'ssizeinseam' => ['required', new size],
                    'ssizethigh' => ['required', new size],
                    'ssizehem' => ['required', new size],
                    'msizewaist' => ['required', new size],
                    'msizehips' => ['required', new size],
                    'msizerise' => ['required', new size],
                    'msizeinseam' => ['required', new size],
                    'msizethigh' => ['required', new size],
                    'msizehem' => ['required', new size],
                    'lsizewaist' => ['required', new size],
                    'lsizehips' => ['required', new size],
                    'lsizerise' => ['required', new size],
                    'lsizeinseam' => ['required', new size],
                    'lsizethigh' => ['required', new size],
                    'lsizehem' => ['required', new size],
                    'xlsizewaist' => ['required', new size],
                    'xlsizehips' => ['required', new size],
                    'xlsizerise' => ['required', new size],
                    'xlsizeinseam' => ['required', new size],
                    'xlsizethigh' => ['required', new size],
                    'xlsizehem' => ['required', new size]
                );
            }
        }

        $validator = Validator::make($data, $rulus)->validate();

        return $this->jsonResponse($data);
    }

    public function register(Request $request, Product $product, Stock $stock, Top $top, Bottom $bottom){
        $data = $request->all();

        $productrecord = $product::create(
            [
                'name' => $data['name'],
                'price' => (int) $data['price'],
                'content' => $data['content'],
                'img_name' => $data['img'],
                'category_status' => (int) $data['category'],
                'delete_flag' => 0,
                'display_status' => 0,
                'color' => (int) $data['color'],
            ]
        );

        $stockrecord = $stock::create(
            [
                'product_id' => $productrecord['id'],
                'ssize_register_items' => (int) $data['ssizeitems'],
                'msize_register_items' => (int) $data['msizeitems'],
                'lsize_register_items' => (int) $data['lsizeitems'],
                'xlsize_register_items' => (int) $data['xlsizeitems'],
                'ssize_items' => (int) $data['ssizeitems'],
                'msize_items' => (int) $data['msizeitems'],
                'lsize_items' => (int) $data['lsizeitems'],
                'xlsize_items' => (int) $data['xlsizeitems'],
            ]
        );

        if($data["category"] === "0"){
            $toprecord = $top::create(
                [
                    'product_id' => $productrecord['id'],
                    'genre_status' => (int) $data['topscategory'],
                    'ssize_body' => (int) $data['ssizebody'],
                    'ssize_shoulder' => (int) $data['ssizeshoulder'],
                    'ssize_length' => (int) $data['ssizelength'],
                    'ssize_sleeve' => (int) $data['ssizesleeve'],
                    'msize_body' => (int) $data['msizebody'],
                    'msize_shoulder' => (int) $data['msizeshoulder'],
                    'msize_length' => (int) $data['msizelength'],
                    'msize_sleeve' => (int) $data['msizesleeve'],
                    'lsize_body' => (int) $data['lsizebody'],
                    'lsize_shoulder' => (int) $data['lsizeshoulder'],
                    'lsize_length' => (int) $data['lsizelength'],
                    'lsize_sleeve' => (int) $data['lsizesleeve'],
                    'xlsize_body' => (int) $data['xlsizebody'],
                    'xlsize_shoulder' => (int) $data['xlsizeshoulder'],
                    'xlsize_length' => (int) $data['xlsizelength'],
                    'xlsize_sleeve' => (int) $data['xlsizesleeve'],
                ]
            );
        }else if($data["category"] === "1"){
            $bottomrecord = $bottom::create(
                [
                    'product_id' => $productrecord['id'],
                    'genre_status' => (int) $data['bottomscategory'],
                    'ssize_waist' => (int) $data['ssizewaist'],
                    'ssize_hips' => (int) $data['ssizehips'],
                    'ssize_rise' => (int) $data['ssizerise'],
                    'ssize_inseam' => (int) $data['ssizeinseam'],
                    'ssize_thigh' => (int) $data['ssizethigh'],
                    'ssize_hem' => (int) $data['ssizehem'],
                    'msize_waist' => (int) $data['msizewaist'],
                    'msize_hips' => (int) $data['msizehips'],
                    'msize_rise' => (int) $data['msizerise'],
                    'msize_inseam' => (int) $data['msizeinseam'],
                    'msize_thigh' => (int) $data['msizethigh'],
                    'msize_hem' => (int) $data['msizehem'],
                    'lsize_waist' => (int) $data['lsizewaist'],
                    'lsize_hips' => (int) $data['lsizehips'],
                    'lsize_rise' => (int) $data['lsizerise'],
                    'lsize_inseam' => (int) $data['lsizeinseam'],
                    'lsize_thigh' => (int) $data['lsizethigh'],
                    'lsize_hem' => (int) $data['lsizehem'],
                    'xlsize_waist' => (int) $data['xlsizewaist'],
                    'xlsize_hips' => (int) $data['xlsizehips'],
                    'xlsize_rise' => (int) $data['xlsizerise'],
                    'xlsize_inseam' => (int) $data['xlsizeinseam'],
                    'xlsize_thigh' => (int) $data['xlsizethigh'],
                    'xlsize_hem' => (int) $data['xlsizehem'],
                ]
            );
        }

        return $this->jsonResponse($productrecord['id']);
    }

    public function editRegister(Request $request, Product $product, Stock $stock, Top $top, Bottom $bottom, Cart $cart){
        $data = $request->all();

        //商品更新
        $productupdate = [
            'name' => $data['name'],
            'price' => (int) $data['price'],
            'content' => $data['content'],
            'category_status' => (int) $data['category'],
            'display_status' => (int) $data['displaystatus'],
            'color' => (int) $data['color'],
        ];
        if($data['img']){
            $productupdate['img_name'] = $data['img'];
            Storage::delete('public/'.$data['imgName']);
        }
        $productrecord = $product
            ->where([["id", (int) $data['productId']] ,["delete_flag", 0]])
            ->update($productupdate);

        //販売停止の場合はカートに入っている該当商品を削除する
        // if((int) $data['displaystatus'] === 0){
        //     $cart
        //         ->where([["product_id", (int) $data['productId']] ,["status", 0]])
        //         ->delete();
        // }

        //在庫数更新
        $addssize = null;
        $addmsize = null;
        $addlsize = null;
        $addxlsize = null;
        $removessize = null;
        $removemsize = null;
        $removelsize = null;
        $removexlsize = null;
        if((int) $data['ssizeitems'] > (int) $data['ssizecheckitems']){
            $addssize = (int) $data['ssizeitems'] - (int) $data['ssizecheckitems'];
        }else if((int) $data['ssizecheckitems'] > (int) $data['ssizeitems']){
            $removessize = (int) $data['ssizecheckitems'] - (int) $data['ssizeitems'];
        }
        if((int) $data['msizeitems'] > (int) $data['msizecheckitems']){
            $addmsize = (int) $data['msizeitems'] - (int) $data['msizecheckitems'];
        }else if((int) $data['msizecheckitems'] > (int) $data['msizeitems']){
            $removemsize = (int) $data['msizecheckitems'] - (int) $data['msizeitems'];
        }
        if((int) $data['lsizeitems'] > (int) $data['lsizecheckitems']){
            $addlsize = (int) $data['lsizeitems'] - (int) $data['lsizecheckitems'];
        }else if((int) $data['lsizecheckitems'] > (int) $data['lsizeitems']){
            $removelsize = (int) $data['lsizecheckitems'] - (int) $data['lsizeitems'];
        }
        if((int) $data['xlsizeitems'] > (int) $data['xlsizecheckitems']){
            $addxlsize = (int) $data['xlsizeitems'] - (int) $data['xlsizecheckitems'];
        }else if((int) $data['xlsizecheckitems'] > (int) $data['xlsizeitems']){
            $removexlsize = (int) $data['xlsizecheckitems'] - (int) $data['xlsizeitems'];
        }
        $stockssize = null;
        $stockmsize = null;
        $stocklsize = null;
        $stockxlsize = null;
        if($addssize){
            $stockssize = (int) $data['ssizeregisteritems'] + $addssize;
        }else if($removessize){
            $stockssize = (int) $data['ssizeregisteritems'] - $removessize;
        }else if(!$addssize && !$removessize){
            $stockssize = (int) $data['ssizeregisteritems'];
        }
        if($addmsize){
            $stockmsize = (int) $data['msizeregisteritems'] + $addmsize;
        }else if($removemsize){
            $stockmsize = (int) $data['msizeregisteritems'] - $removemsize;
        }else if(!$addmsize && !$removemsize){
            $stockmsize = (int) $data['msizeregisteritems'];
        }
        if($addlsize){
            $stocklsize = (int) $data['lsizeregisteritems'] + $addlsize;
        }else if($removelsize){
            $stocklsize = (int) $data['lsizeregisteritems'] - $removelsize;
        }else if(!$addlsize && !$removelsize){
            $stocklsize = (int) $data['lsizeregisteritems'];
        }
        if($addxlsize){
            $stockxlsize = (int) $data['xlsizeregisteritems'] + $addxlsize;
        }else if($removexlsize){
            $stockxlsize = (int) $data['xlsizeregisteritems'] - $removexlsize;
        }else if(!$addxlsize && !$removexlsize){
            $stockxlsize = (int) $data['xlsizeregisteritems'];
        }

        $stockupdate = [
            'ssize_register_items' => $stockssize,
            'msize_register_items' => $stockmsize,
            'lsize_register_items' => $stocklsize,
            'xlsize_register_items' => $stockxlsize,
            'ssize_items' => (int) $data['ssizeitems'],
            'msize_items' => (int) $data['msizeitems'],
            'lsize_items' => (int) $data['lsizeitems'],
            'xlsize_items' => (int) $data['xlsizeitems'],
        ];
        $stockrecord = $stock
            ->where("product_id", (int) $data['productId'])
            ->update($stockupdate);

        //トップス更新
        if($data["category"] === "0"){
            $topupdate = [
                'genre_status' => (int) $data['topscategory'],
                'ssize_body' => (int) $data['ssizebody'],
                'ssize_shoulder' => (int) $data['ssizeshoulder'],
                'ssize_length' => (int) $data['ssizelength'],
                'ssize_sleeve' => (int) $data['ssizesleeve'],
                'msize_body' => (int) $data['msizebody'],
                'msize_shoulder' => (int) $data['msizeshoulder'],
                'msize_length' => (int) $data['msizelength'],
                'msize_sleeve' => (int) $data['msizesleeve'],
                'lsize_body' => (int) $data['lsizebody'],
                'lsize_shoulder' => (int) $data['lsizeshoulder'],
                'lsize_length' => (int) $data['lsizelength'],
                'lsize_sleeve' => (int) $data['lsizesleeve'],
                'xlsize_body' => (int) $data['xlsizebody'],
                'xlsize_shoulder' => (int) $data['xlsizeshoulder'],
                'xlsize_length' => (int) $data['xlsizelength'],
                'xlsize_sleeve' => (int) $data['xlsizesleeve'],
            ];
            $toprecord = $top
                ->where("product_id", (int) $data['productId'])
                ->update($topupdate);

        //ボトムス更新
        }else if($data["category"] === "1"){
            $bottomupdate = [
                'genre_status' => (int) $data['bottomscategory'],
                'ssize_waist' => (int) $data['ssizewaist'],
                'ssize_hips' => (int) $data['ssizehips'],
                'ssize_rise' => (int) $data['ssizerise'],
                'ssize_inseam' => (int) $data['ssizeinseam'],
                'ssize_thigh' => (int) $data['ssizethigh'],
                'ssize_hem' => (int) $data['ssizehem'],
                'msize_waist' => (int) $data['msizewaist'],
                'msize_hips' => (int) $data['msizehips'],
                'msize_rise' => (int) $data['msizerise'],
                'msize_inseam' => (int) $data['msizeinseam'],
                'msize_thigh' => (int) $data['msizethigh'],
                'msize_hem' => (int) $data['msizehem'],
                'lsize_waist' => (int) $data['lsizewaist'],
                'lsize_hips' => (int) $data['lsizehips'],
                'lsize_rise' => (int) $data['lsizerise'],
                'lsize_inseam' => (int) $data['lsizeinseam'],
                'lsize_thigh' => (int) $data['lsizethigh'],
                'lsize_hem' => (int) $data['lsizehem'],
                'xlsize_waist' => (int) $data['xlsizewaist'],
                'xlsize_hips' => (int) $data['xlsizehips'],
                'xlsize_rise' => (int) $data['xlsizerise'],
                'xlsize_inseam' => (int) $data['xlsizeinseam'],
                'xlsize_thigh' => (int) $data['xlsizethigh'],
                'xlsize_hem' => (int) $data['xlsizehem'],
            ];
            $bottomrecord = $bottom
                ->where("product_id", (int) $data['productId'])
                ->update($bottomupdate);
        }

        return $this->jsonResponse($productrecord['id']);
    }

    public function imgRegister(Request $request){
        $file_name = $request->file('file')->getClientOriginalName();
        $request->file('file')->storeAs('/public',$file_name);
        return $this->jsonResponse($file_name);
    }

    public function deleteProduct(Request $request, Product $product){
        $data = $request->all();

        $getData = $product
            ->where([["id", $data['productId']] ,["name", $data['name']]])
            ->update(["delete_flag" => 1]);

        Storage::delete('public/'.$data['img']);

        return $this->jsonResponse($getData);
    }

    public function GetEditProduct(string $id, Product $product){
        $getrecord = $product
            ->where([["id", $id] ,["delete_flag", 0]])
            ->get();

        $record = $getrecord->toArray();
        $record = $record[0];

        $getData = null;

        if($record['category_status'] === 0){
            $getData = $product
                ->leftjoin('tops', 'product.id', '=', 'tops.product_id')
                ->leftjoin('stock', 'product.id', '=', 'stock.product_id')
                ->select('product.*','tops.genre_status as tops_genre_status','tops.ssize_body','tops.ssize_shoulder','tops.ssize_length','tops.ssize_sleeve','tops.msize_body','tops.msize_shoulder','tops.msize_length','tops.msize_sleeve','tops.lsize_body','tops.lsize_shoulder','tops.lsize_length','tops.lsize_sleeve','tops.xlsize_body','tops.xlsize_shoulder','tops.xlsize_length','tops.xlsize_sleeve','stock.ssize_items','stock.msize_items','stock.lsize_items','stock.xlsize_items','stock.ssize_register_items','stock.msize_register_items','stock.lsize_register_items','stock.xlsize_register_items')
                ->where([['product.id', $id], ['product.delete_flag', 0]])
                ->get();
        }else if($record['category_status'] === 1){
            $getData = $product
                ->leftjoin('bottoms', 'product.id', '=', 'bottoms.product_id')
                ->leftjoin('stock', 'product.id', '=', 'stock.product_id')
                ->select('product.*','bottoms.genre_status as bottoms_genre_status','bottoms.ssize_waist','bottoms.ssize_hips','bottoms.ssize_rise','bottoms.ssize_inseam','bottoms.ssize_thigh','bottoms.ssize_hem','bottoms.msize_waist','bottoms.msize_hips','bottoms.msize_rise','bottoms.msize_inseam','bottoms.msize_thigh','bottoms.msize_hem','bottoms.lsize_waist','bottoms.lsize_hips','bottoms.lsize_rise','bottoms.lsize_inseam','bottoms.lsize_thigh','bottoms.lsize_hem','bottoms.xlsize_waist','bottoms.xlsize_hips','bottoms.xlsize_rise','bottoms.xlsize_inseam','bottoms.xlsize_thigh','bottoms.xlsize_hem','stock.ssize_items','stock.msize_items','stock.lsize_items','stock.xlsize_items','stock.ssize_register_items','stock.msize_register_items','stock.lsize_register_items','stock.xlsize_register_items')
                ->where([['product.id', $id], ['product.delete_flag', 0]])
                ->get();
        }

        $getData = $getData[0];
        return $this->jsonResponse($getData);
    }

}
