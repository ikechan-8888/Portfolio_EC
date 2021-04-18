<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * いいね
     * @param string $id
     * @return array
     */
    public function like(string $id, Favorite $favorite){
        $loginId = Auth::user()->login_id;
        $like = $favorite->where([["login_id", $loginId],["product_id", $id]])->get();
        if($like->isEmpty()){
            $favorite::create(['login_id' => $loginId,'product_id' => $id]);
            return $this->jsonResponse(['loginId' => $loginId, 'id' => $id]);
        }else{
            $favorite->where([["login_id", $loginId],["product_id", $id]])->delete();
            return $this->jsonResponse(['loginId' => null,  'id' => $id]);
        }
    }

    //お気に入り一覧取得
    public function GetFavoriteList(Favorite $favorite){
        $getData = $favorite
            ->leftjoin('product', 'favorites.product_id', '=', 'product.id')
            ->select('product.*','favorites.login_id')
            ->where([['favorites.login_id', Auth::user()->login_id], ['product.delete_flag', 0]])
            ->orderBy('favorites.created_at', 'asc')
            ->get();

        return $this->jsonResponse($getData);
    }
}
