<?php

namespace Avrillo\Quotes\App\Managers;

interface ProvidesQuotes
{
    public function get(): ?string;
}
