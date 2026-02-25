<?php

namespace App\Http\Middleware;

use Closure;

class ClientMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if (!$user || !$user->isClient()) {
            abort(403, 'Client access only');
        }

        return $next($request);
    }
}
