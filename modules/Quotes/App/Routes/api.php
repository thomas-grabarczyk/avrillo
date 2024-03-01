<?php

use Avrillo\Quotes\Inerfaces\Api\GetQuotes;
use Avrillo\Quotes\Inerfaces\Api\RefreshQuotes;
use Illuminate\Support\Facades\Route;

Route::get('quotes', GetQuotes::class)->name('quotes');
Route::get('quotes/refresh', RefreshQuotes::class)->name('quotes.refresh');
