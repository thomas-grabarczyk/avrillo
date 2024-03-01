<?php

namespace Avrillo\Quotes\Inerfaces\Api;

use Avrillo\Quotes\Quotes;
use Illuminate\Http\JsonResponse;

class RefreshQuotes
{
    public function __invoke(): JsonResponse
    {
        return response()->json(Quotes::refresh());
    }
}
