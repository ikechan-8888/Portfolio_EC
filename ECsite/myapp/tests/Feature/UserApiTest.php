<?php

namespace Tests\Feature;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    public function setUp(): void{
        parent::setUp();

        //テストユーザー作成
        $this->user = factory(Account::class)->create();
    }

    /**
     * @test
     */
    public function should_ログイン中のユーザーを返却する(){
        $response = $this->actingAs($this->user)->json('GET', route('user'));
        $response
            ->assertStatus(200)
            ->assertJson([
                'account_name' => $this->user->account_name,
            ]);
    }

    public function should_ログインされていない場合は空文字を返却する(){
        $response = $this->json('GET', route('user'));
        $response->assertStatus(200);
        $this->assertEquals("", $response->content());
    }
}
