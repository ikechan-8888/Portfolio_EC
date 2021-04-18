<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

//Authenticatableを継承すればModelも継承したことになる
class Favorite extends Authenticatable
{
    //デフォルトでは作成時間と更新時間は自動で更新するがcreated_atとupdated_atでカラム名を設定していない場合は自分でカスタマイズする
    // const CREATED_AT = 'created_date';
    // const UPDATED_AT = 'updated_date';

    //複数代入の許可　今回は全てのカラムを登録するので登録を限定するカラムの指定はなし
    protected $guarded = [];

    //金額を,付きにする(priceカラム)
    public function getPriceAttribute($value){
        return number_format($value);
    }

    //商品の色を数字から文字にする(colorカラム)
    public function getColorAttribute($value){
        if($value === 0){
            return "BLACK";
        }else if($value === 1){
            return "WHITE";
        }else if($value === 2){
            return "GRAY";
        }else if($value === 3){
            return "RED";
        }else if($value === 4){
            return "BLUE";
        }else if($value === 5){
            return "GREEN";
        }else if($value === 6){
            return "YELLOW";
        }
    }
}
