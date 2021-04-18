<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

//Authenticatableを継承すればModelも継承したことになる
class Bottom extends Authenticatable
{
    //デフォルトでは作成時間と更新時間は自動で更新するがcreated_atとupdated_atでカラム名を設定していない場合は自分でカスタマイズする
    // const CREATED_AT = 'created_date';
    // const UPDATED_AT = 'updated_date';

    //複数代入の許可　今回は全てのカラムを登録するので登録を限定するカラムの指定はなし
    protected $guarded = [];
}
