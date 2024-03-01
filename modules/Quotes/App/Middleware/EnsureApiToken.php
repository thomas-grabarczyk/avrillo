<?php

namespace Avrillo\Quotes\App\Middleware;

use Avrillo\Quotes\Infrastructure\Mysql\Models\Eloquent\TokenModel;

class EnsureApiToken
{
    public function handle($request, \Closure $next)
    {
        $token = $request->query('token');

        if (!$token) {
            return response()->json(['error' => 'Token is required'], 401);
        }

        if (!TokenModel::where('token', $token)->exists()) {
            return response()->json(['error' => 'Token is invalid'], 401);
        }

        if (TokenModel::where('token', $token)->first()->expires_at < now()) {
            return response()->json(['error' => 'Token has expired'], 401);
        }

        return $next($request);
    }
}
