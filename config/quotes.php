<?php

use Avrillo\Quotes\App\Managers\KanyeQuotes;
use Avrillo\Quotes\App\Managers\OtherQuotes;

return [
    'drivers' => [
        'kanye' => KanyeQuotes::class,
        'other' => OtherQuotes::class,
        // add more implementations here
    ],
    'default' => 'kanye',
];
