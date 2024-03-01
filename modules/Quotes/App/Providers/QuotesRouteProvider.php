<?php

namespace Avrillo\Quotes\App\Providers;

use Avrillo\Quotes\App\Middleware\EnsureApiToken;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class QuotesRouteProvider extends RouteServiceProvider
{
    public function map()
    {
        Route::middleware(
            ['web', EnsureApiToken::class]
        )->prefix('api')->group(
            fn () => include __DIR__. '/../Routes/api.php'
        );
    }
}
