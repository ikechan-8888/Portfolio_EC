<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

//Authenticatableを継承すればModelも継承したことになる
class Product extends Authenticatable
{
    //基本的には、Laravelがクラス名に紐づくテーブル名を自動的に引いてきてくれるので、クラス名とテーブル名が同じ名前の場合は指定しなくてもOK。
    //今回はテーブル名を複数形に指定していないのでテーブル名を指定してあげる
    // テーブル名の指定
    protected $table = 'product';

    //デフォルトでは作成時間と更新時間は自動で更新するがcreated_atとupdated_atでカラム名を設定していない場合は自分でカスタマイズする
    // const CREATED_AT = 'created_date';
    // const UPDATED_AT = 'updated_date';

    //複数代入の許可　今回は全てのカラムを登録するので登録を限定するカラムの指定はなし
    protected $guarded = [];

    //1パージあたりに表示させる件数(Pagenation)
    protected $perPage = 3;

    //アクセサをレスポンスに追加する(追加したカラムがある場合に行う、既存のカラムの場合は追加しなくていい)
    // protected $appends = [];

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

    /**
     * １対１関係 = hasOne
     * hasOneは主テーブルのあるレコードに対して、従テーブルの1つのレコードが紐付けられるときに用いられる
     * 例)主テーブルのレコード1つに対して従テーブルのレコード1つ
     * １対多関係 = hasMany
     * hasManyは主テーブルのあるレコードに対して、従テーブルの複数のレコードが紐付けるときに用いられる
     * 例)主テーブルのレコード1つに対して従テーブルのレコードを複数
     * belongsToMany('関係するモデル', '中間テーブルのテーブル名', '中間テーブル内で対応しているID名', '関係するモデルで対応しているID名');
     */

    // //リレーションシップ - favoritesテーブル
    // public function favorites(){
    //     //Eloquentはリレーションの外部キーがモデル名に基づいていると仮定する
    //     return $this->hasOne('App\Models\Favorite');
    // }
    // //リレーションシップ - topsテーブル
    // public function top(){
    //     //Eloquentはリレーションの外部キーがモデル名に基づいていると仮定する
    //     return $this->hasOne('App\Models\Top');
    // }
    // //リレーションシップ - bottomsテーブル
    // public function bottom(){
    //     //Eloquentはリレーションの外部キーがモデル名に基づいていると仮定する
    //     return $this->hasOne('App\Models\Bottom');
    // }
    // //リレーションシップ - stockテーブル
    // public function stock(){
    //     //Eloquentはリレーションの外部キーがモデル名に基づいていると仮定する
    //     return $this->hasOne('App\Models\Stock');
    // }
}
