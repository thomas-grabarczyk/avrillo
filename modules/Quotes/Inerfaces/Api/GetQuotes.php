<?php

namespace Avrillo\Quotes\Inerfaces\Api;

use Avrillo\Quotes\Quotes;
use Illuminate\Http\JsonResponse;

class GetQuotes
{
    public function __invoke(): JsonResponse
    {
        return response()->json(Quotes::get());
    }
}
