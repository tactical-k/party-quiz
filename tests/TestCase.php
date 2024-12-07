<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;
abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create(); // ユーザーを作成
        $this->actingAs($user); // ユーザーとしてログイン
    }
}
