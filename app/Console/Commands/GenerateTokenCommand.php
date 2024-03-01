<?php

namespace App\Console\Commands;

use Avrillo\Quotes\Infrastructure\Mysql\Models\Eloquent\TokenModel;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateTokenCommand extends Command
{
    protected $signature = 'quotes:generate-token';
    protected $description = 'Generate a new token that you can immediately use to access the API. Tokens expire after 1 hour';

    public function handle(): void
    {
        $token = $this->generateToken();

        while ($this->checkIfExists($token) === true) {
            $token = $this->generateToken();
        }

        $this->storeToken($token);

        echo 'Token generated: '. $token . PHP_EOL;
    }

    private function storeToken(string $token): void
    {
        TokenModel::create([
            'token' => $token,
            'expires_at' => now()->addHour(),
        ]);
    }

    private function generateToken(): string
    {
        return Str::random(64);
    }

    private function checkIfExists(string $token): bool
    {
        return TokenModel::where('token', $token)->exists();
    }
}
