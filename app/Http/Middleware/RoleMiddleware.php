public function handle($request, Closure $next, ...$roles)
{
    $user = auth()->user();

    if (!$user) {
        abort(403);
    }

    foreach ($roles as $role) {
        if ($user->hasRole($role, $user->company_id)) {
            return $next($request);
        }
    }

    abort(403, 'Unauthorized');
}
