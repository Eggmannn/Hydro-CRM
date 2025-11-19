<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureCustomerAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Must have customer_admin role in this user's company
        if (!method_exists($user, 'isCustomerAdmin') || !$user->isCustomerAdmin()) {
            abort(403, 'Only Customer Admins can access this area.');
        }

        return $next($request);
    }
}
