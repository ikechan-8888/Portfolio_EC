<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

////ユーザー側
//会員登録
Route::post('/register', 'Auth\RegisterController@register')->name('register');
//ログアウト
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
//ログイン
Route::post('/login', 'Auth\LoginController@login')->name('login');
//ログインユーザー
Route::get('/user', function(){ return Auth::user();})->name('user');
//トップページ(商品一覧)
Route::get('/product', 'Auth\ProductController@GetProductList')->name('product');
//商品詳細
Route::get('/productDetail/{id}', 'Auth\ProductController@GetProductDetail')->name('productDetail');
//お気に入り
Route::put('/product/{id}/like', 'Auth\FavoriteController@like')->name('productLike');
//お気に入り一覧
Route::get('/favorite', 'Auth\FavoriteController@GetFavoriteList')->name('favorite');
//カート追加
Route::post('/buyCart', 'Auth\CartController@buyCart')->name('buyCart');
//カートログイン情報変更(ゲスト→ログイン時)
Route::put('/buyCartLoginChange', 'Auth\CartController@buyCartLoginChange')->name('buyCartLoginChange');
//カート一覧
Route::get('/buyCartList', 'Auth\CartController@GetBuyList')->name('buyCartList');
//カート品数増減
Route::put('/buyCartChange', 'Auth\CartController@buyCartChange')->name('buyCartChange');
//カート品購入済み
Route::put('/boughtCartChange', 'Auth\CartController@boughtCartChange')->name('boughtCartChange');
//カート品数削除
Route::put('/buyCartDelete', 'Auth\CartController@buyCartDelete')->name('buyCartDelete');
//カート購入数自動変更
Route::put('/putCart', 'Auth\CartController@putCart')->name('putCart');
//カート購入数自動削除
Route::put('/deleteCart', 'Auth\CartController@deleteCart')->name('deleteCart');
//住所登録
Route::post('/delivery', 'Auth\DeliveryController@delivery')->name('delivery');
//住所更新
Route::put('/deliveryChange', 'Auth\DeliveryController@deliveryChange')->name('deliveryChange');
//住所一覧
Route::get('/deliveryList', 'Auth\DeliveryController@getDeliveryList')->name('deliveryList');
//届け先住所
Route::get('/actuallyDelivery', 'Auth\DeliveryController@getActuallyDelivery')->name('actuallyDelivery');
//商品購入
Route::post('/buy', 'Auth\BuyController@buy')->name('buy');
//商品購入チェック
Route::post('/buyCheck', 'Auth\StockController@buyCheck')->name('buyCheck');
//購入在庫一覧
Route::get('/buyStockList', 'Auth\StockController@getBuyStockList')->name('buyStockList');
//購入商品登録
Route::post('/buyStock', 'Auth\StockController@buyStock')->name('buyStock');
//購入商品登録
Route::post('/buyproduct', 'Auth\BuyproductController@buyproduct')->name('buyproduct');
//購入履歴一覧
Route::get('/boughtList', 'Auth\BuyController@GetBoughtList')->name('boughtList');
//購入履歴商品一覧
Route::get('/boughtProductList/{id}', 'Auth\BuyproductController@GetBoughtProductList')->name('boughtProductList');

////管理側
//ログイン管理者
Route::get('/adminUser', function(){ return Auth::guard('admin')->user();})->name('adminUser');
//ログイン
Route::post('/adminLogin', 'Auth\AdminLoginController@login')->name('adminLogin');
//ログアウト
Route::post('/adminLogout', 'Auth\AdminLoginController@logout')->name('adminLogout');
//会員登録
Route::post('/adminRegister', 'Auth\AdminRegisterController@register')->name('adminRegister');
//商品数
Route::get('/productNumber', 'Auth\AdminProductController@GetProductNumber')->name('productNumber');
//購入通知(当日)
Route::get('/buyToday', 'Auth\AdminProductController@GetBuyToday')->name('buyToday');
//未発送件数
Route::get('/notShipping', 'Auth\AdminProductController@GetNotShipping')->name('notShipping');
//グラフデータ
Route::get('/chartLinedata', 'Auth\AdminProductController@GetChartLinedata')->name('chartLinedata');
//商品一覧
Route::get('/productList', 'Auth\AdminProductController@GetProductList')->name('productList');
//発送一覧
Route::get('/shippingList', 'Auth\AdminProductController@GetShippingList')->name('shippingList');
//発送詳細情報取得
Route::get('/editShipping/{id}', 'Auth\AdminProductController@GetEditShipping')->name('editShipping');
//発送完了更新
Route::put('/shippingDoneEdit', 'Auth\AdminProductController@shippingDoneEdit')->name('shippingDoneEdit');
//商品登録チェック
Route::post('/productRegisterValidate', 'Auth\AdminProductController@validatecheck')->name('productRegisterValidate');
//商品登録
Route::post('/productregister', 'Auth\AdminProductController@register')->name('productregister');
//商品更新
Route::post('/producteditregister', 'Auth\AdminProductController@editRegister')->name('producteditregister');
//画像登録
Route::post('/imgregister', 'Auth\AdminProductController@imgRegister')->name('imgregister');
//商品削除
Route::delete('/deleteProduct', 'Auth\AdminProductController@deleteProduct')->name('deleteProduct');
//編集商品取得
Route::get('/editProduct/{id}', 'Auth\AdminProductController@GetEditProduct')->name('editProduct');