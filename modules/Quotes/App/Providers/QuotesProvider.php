<?php

namespace Avrillo\Quotes\App\Providers;

use Avrillo\Quotes\App\Managers\QuotesManager;
use Avrillo\Quotes\QuotesConcrete;
use Illuminate\Support\ServiceProvider;

class QuotesProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerManagers();
        $this->registerFacades();
        $this->registerRoutes();
    }

    private function registerManagers(): void
    {
        $this->app->singleton(QuotesManager::class, function (): QuotesManager {
            $manager = new QuotesManager();
            foreach (config('quotes.drivers') as $driver => $class) {
                $manager->register($driver, $class);
            }

            return $manager;
        });
    }

    private function registerFacades(): void
    {
        $this->app->bind('quotes', fn(): QuotesConcrete => new QuotesConcrete(
            app(QuotesManager::class)
        ));
    }

    private function registerRoutes(): void
    {
        $this->app->register(QuotesRouteProvider::class);
    }
}
