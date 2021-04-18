<?php

namespace Tests\Feature;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterApiTest extends TestCase
{
    // RefreshDatabaseトレイトを利用するとテスト後にデータを破棄してくれる
    // use RefreshDatabase;

    /**
     * @test
     */

    public function should_新しいユーザーを作成して返却する(){
        $data = [
            'login_id' => 'user',
            'password' => 'test1234',
            'account_name' => 'test1234'
        ];

        $response = $this->json('POST', route('register'), $data);//routeの/registerに$data送るPOST
        //AccountModelのインスタンス生成
        $account = new Account();
        $user = $account::first();//テーブルから1行取得
        $this->assertEquals($data['account_name'], $user->account_name);//2つの引数が一緒かどうか(登録するデータと登録されたデータが一緒かどうか)

        $response
            ->assertStatus(201)//201とはリクエストが成功してリソースの作成が完了している状態
            ->assertJson(['account_name' => $user->account_name]);//レスポンスを配列に変換する,アプリケーションへ戻ってきたJSONレスポンスの中に、指定された配列が含まれているかを確認する(今回は$userがレスポンス)
    }
}
