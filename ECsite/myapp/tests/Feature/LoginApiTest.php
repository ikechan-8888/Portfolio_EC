<?php

namespace Tests\Feature;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginApiTest extends TestCase
{
    // RefreshDatabaseトレイトを利用するとテスト後にデータを破棄してくれる
    // use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        //テストユーザー作成
        $this->user = factory(Account::class)->create();
    }

    /**
     * @test
     */
    public function should_登録済みのユーザーを認証して返却する()
    {
        $response = $this->json('POST', route('login'), [
            'account_name' => $this->user->account_name,
            'password' => 'password',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(['account_name' => $this->user->account_name]);

        $this->assertAuthenticatedAs($this->user);
    }
}
