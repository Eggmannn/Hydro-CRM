<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\CompanyAuthorization;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EnsureCompanyAssumed
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if (! $user) {
            return $next($request);
        }

        $companyParam = $request->route('company');
        $companyId = is_object($companyParam) ? $companyParam->id : $companyParam;

        if (! $companyId) {
            return $next($request);
        }

        $assumed = session('assumed_company');

        // ✅ Already assumed for THIS company and still valid
        if (
            $assumed &&
            intval($assumed['company_id']) === intval($companyId) &&
            (
                empty($assumed['expires_at']) ||
                Carbon::parse($assumed['expires_at'])->isFuture()
            )
        ) {
            return $next($request);
        }

        // ❗ Store intended URL BEFORE redirecting
        session()->put('assume_intended_url', $request->fullUrl());

        // ❗ Clear stale assumption
        session()->forget('assumed_company');

        return redirect()
            ->route('crd-admin.authorization.prompt', ['company' => $companyId])
            ->with('info', 'You must assume authorization to access this company.');
    }
}
