<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    //RedirectIfAuthenticatedクラスの処理はguestという名前でApp\Http\Kernelクラスに登録されていて下記を記述するとRedirectIfAuthenticatedクラスの処理が呼ばれてしまうので注意
    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     //guestは認証がなくてもアクセスできる
    //     $this->middleware('guest');
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function GetProductList(Product $product)
    {
        $getData = null;
        if(Auth::check()){
            $getData = $product
                ->leftjoin('favorites', function($join){
                    $join->on('product.id', '=', 'favorites.product_id')
                        ->where('favorites.login_id', '=', Auth::user()->login_id);
                })
                ->select('product.*','favorites.login_id')
                ->where('product.display_status', 1)
                ->orderBy('product.created_at', 'desc')
                ->paginate(12);
        }else{
            $getData = $product->orderBy('created_at', 'desc')
                ->where([['product.display_status', 1], ['product.delete_flag', 0]])
                ->paginate(12);
        }
        return $this->jsonResponse($getData);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @param  int  $id
     */
    public function GetProductDetail(string $id, Product $product){
        $getData = null;
        if(Auth::check()){
            $getData = $product
                ->leftjoin('tops', 'product.id', '=', 'tops.product_id')
                ->leftjoin('bottoms', 'product.id', '=', 'bottoms.product_id')
                ->leftjoin('stock', 'product.id', '=', 'stock.product_id')
                ->leftjoin('favorites', function($join){
                    $join->on('product.id', '=', 'favorites.product_id')
                        ->where('favorites.login_id', '=', Auth::user()->login_id);
                })
                ->select('product.*','favorites.login_id','tops.genre_status as tops_genre_status','tops.ssize_body','tops.ssize_shoulder','tops.ssize_length','tops.ssize_sleeve','tops.msize_body','tops.msize_shoulder','tops.msize_length','tops.msize_sleeve','tops.lsize_body','tops.lsize_shoulder','tops.lsize_length','tops.lsize_sleeve','tops.xlsize_body','tops.xlsize_shoulder','tops.xlsize_length','tops.xlsize_sleeve','bottoms.genre_status as bottoms_genre_status','bottoms.ssize_waist','bottoms.ssize_hips','bottoms.ssize_rise','bottoms.ssize_inseam','bottoms.ssize_thigh','bottoms.ssize_hem','bottoms.msize_waist','bottoms.msize_hips','bottoms.msize_rise','bottoms.msize_inseam','bottoms.msize_thigh','bottoms.msize_hem','bottoms.lsize_waist','bottoms.lsize_hips','bottoms.lsize_rise','bottoms.lsize_inseam','bottoms.lsize_thigh','bottoms.lsize_hem','bottoms.xlsize_waist','bottoms.xlsize_hips','bottoms.xlsize_rise','bottoms.xlsize_inseam','bottoms.xlsize_thigh','bottoms.xlsize_hem','stock.ssize_items','stock.msize_items','stock.lsize_items','stock.xlsize_items')
                ->where([['product.id', $id], ['product.display_status', 1], ['product.delete_flag', 0]])
                ->get();
        }else{
            $getData = $product
                ->leftjoin('tops', 'product.id', '=', 'tops.product_id')
                ->leftjoin('bottoms', 'product.id', '=', 'bottoms.product_id')
                ->leftjoin('stock', 'product.id', '=', 'stock.product_id')
                ->select('product.*','tops.genre_status as tops_genre_status','tops.ssize_body','tops.ssize_shoulder','tops.ssize_length','tops.ssize_sleeve','tops.msize_body','tops.msize_shoulder','tops.msize_length','tops.msize_sleeve','tops.lsize_body','tops.lsize_shoulder','tops.lsize_length','tops.lsize_sleeve','tops.xlsize_body','tops.xlsize_shoulder','tops.xlsize_length','tops.xlsize_sleeve','bottoms.genre_status as bottoms_genre_status','bottoms.ssize_waist','bottoms.ssize_hips','bottoms.ssize_rise','bottoms.ssize_inseam','bottoms.ssize_thigh','bottoms.ssize_hem','bottoms.msize_waist','bottoms.msize_hips','bottoms.msize_rise','bottoms.msize_inseam','bottoms.msize_thigh','bottoms.msize_hem','bottoms.lsize_waist','bottoms.lsize_hips','bottoms.lsize_rise','bottoms.lsize_inseam','bottoms.lsize_thigh','bottoms.lsize_hem','bottoms.xlsize_waist','bottoms.xlsize_hips','bottoms.xlsize_rise','bottoms.xlsize_inseam','bottoms.xlsize_thigh','bottoms.xlsize_hem','stock.ssize_items','stock.msize_items','stock.lsize_items','stock.xlsize_items')
                ->where([['product.id', $id], ['product.display_status', 1], ['product.delete_flag', 0]])
                ->get();
        }
        return $this->jsonResponse($getData);
    }
}
