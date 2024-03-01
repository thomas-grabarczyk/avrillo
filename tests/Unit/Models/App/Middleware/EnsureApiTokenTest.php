<?php

namespace Tests\Unit\Models\App\Middleware;

use Avrillo\Quotes\App\Middleware\EnsureApiToken;
use Avrillo\Quotes\Infrastructure\Mysql\Models\Eloquent\TokenModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tests\TestCase;

class EnsureApiTokenTest extends TestCase
{
    public function test_it_fails_if_the_token_is_missing(): void
    {
        $request = new Request();
        $middleware = new EnsureApiToken();

        $response = $middleware->handle($request, function () {});
        $this->assertEquals(401, $response->status());
        $this->assertEquals('Token is required', json_decode($response->getContent(), true)['error']);
    }

    public function test_it_fails_if_the_token_is_invalid(): void
    {
        $request = new Request(['token' => 'invalid']);
        $middleware = new EnsureApiToken();

        $response = $middleware->handle($request, function () {});
        $this->assertEquals(401, $response->status());
        $this->assertEquals('Token is invalid', json_decode($response->getContent(), true)['error']);
    }

    public function test_it_fails_if_the_token_is_expired(): void
    {
        $token =  Str::random(64);
        TokenModel::create(['token' => $token, 'expires_at' => now()->subMinute()]);

        $request = new Request(['token' => $token]);
        $middleware = new EnsureApiToken();

        $response = $middleware->handle($request, function () {});
        $this->assertEquals(401, $response->status());
        $this->assertEquals('Token has expired', json_decode($response->getContent(), true)['error']);
    }

    public function test_it_works_with_valid_token(): void
    {
        $token =  Str::random(64);
        TokenModel::create(['token' => $token, 'expires_at' => now()->addMinutes(60)]);

        $request = new Request(['token' => $token]);
        $nextRequest = new Request(['success' => true]);
        $middleware = new EnsureApiToken();

        $response = $middleware->handle($request, fn() => $nextRequest);
        $this->assertEquals(true, $response->query('success'));
    }
}
