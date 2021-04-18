<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

//Authenticatableを継承すればModelも継承したことになる
class Cart extends Authenticatable
{
    //基本的には、Laravelがクラス名に紐づくテーブル名を自動的に引いてきてくれるので、クラス名とテーブル名が同じ名前の場合は指定しなくてもOK。
    //今回はテーブル名を複数形に指定していないのでテーブル名を指定してあげる
    // テーブル名の指定
    protected $table = 'cart';

    //デフォルトでは作成時間と更新時間は自動で更新するがcreated_atとupdated_atでカラム名を設定していない場合は自分でカスタマイズする
    // const CREATED_AT = 'created_date';
    // const UPDATED_AT = 'updated_date';

    //複数代入の許可　今回は全てのカラムを登録するので登録を限定するカラムの指定はなし
    protected $guarded = [];
}
