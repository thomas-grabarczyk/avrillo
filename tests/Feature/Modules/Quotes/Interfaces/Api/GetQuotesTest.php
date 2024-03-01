<?php

namespace Tests\Feature\Modules\Quotes\Interfaces\Api;

use Avrillo\Quotes\Infrastructure\Mysql\Models\Eloquent\TokenModel;
use Illuminate\Support\Str;
use Tests\TestCase;

class GetQuotesTest extends TestCase
{
    public function test_it_fails_if_the_token_is_missing(): void
    {
        $response = $this->json('GET', route('quotes'));
        $response->assertStatus(401);
        $this->assertEquals('Token is required', $response->json()['error']);
    }

    public function test_it_fails_if_the_token_is_invalid(): void
    {
        $response = $this->json('GET', route('quotes', ['token' => 'invalid']));
        $response->assertStatus(401);
        $this->assertEquals('Token is invalid', $response->json()['error']);
    }

    public function test_it_fails_if_the_token_is_expired(): void
    {
        $token = Str::random(64);
        TokenModel::create(['token' => $token, 'expires_at' => now()->subMinute()]);

        $response = $this->json('GET', route('quotes', ['token' => $token]));
        $response->assertStatus(401);
        $this->assertEquals('Token has expired', $response->json('error'));
    }

    public function test_it_can_return_a_list_of_quotes(): void
    {
        $token = Str::random(64);
        TokenModel::create(['token' => $token, 'expires_at' => now()->addMinutes(60)]);

        $response = $this->json('GET', route('quotes', ['token' => $token]));
        $response->assertStatus(200);
        $this->assertCount(5, $response->json());
    }
}
