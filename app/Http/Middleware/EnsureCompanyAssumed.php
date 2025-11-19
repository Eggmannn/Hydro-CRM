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
        $companyId = null;
        if (is_object($companyParam) && isset($companyParam->id)) {
            $companyId = $companyParam->id;
        } else {
            $companyId = $companyParam;
        }
        if (! $companyId) {
            return $next($request);
        }
        $assumed = session('assumed_company');
        if ($assumed && isset($assumed['company_id']) && intval($assumed['company_id']) === intval($companyId)) {
            if (empty($assumed['expires_at']) || Carbon::parse($assumed['expires_at'])->isFuture()) {
                return $next($request);
            }
            session()->forget('assumed_company');
        }
        $auth = CompanyAuthorization::where('crd_admin_id', $user->id)
            ->where('company_id', $companyId)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', Carbon::now());
            })
            ->latest('granted_at')
            ->first();
        if ($auth) {
            session()->put('assumed_company', [
                'company_id' => $companyId,
                'authorization_id' => $auth->id,
                'expires_at' => optional($auth->expires_at)->toDateTimeString(),
            ]);
            return $next($request);
        }
        return redirect()->route('crd-admin.authorization.prompt', ['company' => $companyId])
                         ->with('info', 'You must explicitly assume authorization for this company to view its tickets.');
    }
}
