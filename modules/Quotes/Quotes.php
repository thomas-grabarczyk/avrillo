<?php

namespace Avrillo\Quotes;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array get()
 * @method static array refresh()
 * @method static static using(string $driverName)
 */
class Quotes extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'quotes';
    }
}

