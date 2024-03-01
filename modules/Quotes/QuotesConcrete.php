<?php

namespace Avrillo\Quotes;

use Avrillo\Quotes\App\Managers\ProvidesQuotes;
use Avrillo\Quotes\App\Managers\QuotesManager;
use Illuminate\Support\Facades\Cache;

class QuotesConcrete
{
    private ProvidesQuotes $manager;
    private QuotesManager $baseManager;

    private const QUOTES_LIMIT = 5;

    public function __construct(QuotesManager $baseManager)
    {
        $this->baseManager = $baseManager;
        $this->manager = $this->baseManager->get();
    }

    public function get(): array
    {
        if (Cache::has('quotes')) {
            return Cache::get('quotes');
        }

        $quotes = [];

        for ($i = 0; $i < static::QUOTES_LIMIT; ++$i) {
            $quotes[] = $this->manager->get();
        }

        Cache::put('quotes', $quotes, now()->addMinutes(10));
        return $quotes;
    }

    public function refresh(): array
    {
        Cache::forget('quotes');

        return $this->get();
    }

    public function using(string $driverName): static
    {
        $this->manager = $this->baseManager->get($driverName);

        return $this;
    }
}
