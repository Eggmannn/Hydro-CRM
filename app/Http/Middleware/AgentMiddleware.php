<?php

// app/Http/Middleware/AgentMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AgentMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user || !$user->isAgent()) {
            abort(403);
        }

        return $next($request);
    }
}
