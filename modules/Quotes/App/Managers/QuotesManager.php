<?php

namespace Avrillo\Quotes\App\Managers;

class QuotesManager
{
    protected array $drivers;

    public function register(string $driverName, $driverClass): void
    {
        $this->drivers[$driverName] = $driverClass;
    }

    public function get(string $driverName = null): ProvidesQuotes
    {
        if (is_null($driverName)) {
            $class = $this->drivers[
                env('QUOTES_DRIVER', config('quotes.default'))
            ];

            return app($class);
        }

        if (!array_key_exists($driverName, $this->drivers)) {
            throw new \InvalidArgumentException(sprintf('Driver "%s" is not registered', $driverName));
        }

        return app($this->drivers[$driverName]);
    }
}
