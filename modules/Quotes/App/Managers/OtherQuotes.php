<?php

namespace Avrillo\Quotes\App\Managers;

class OtherQuotes implements ProvidesQuotes
{

    public function get(): ?string
    {
        return 'Some test quote';
    }
}
