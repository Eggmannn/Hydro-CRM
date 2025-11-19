<?php

namespace App\Http\Controllers\CrdAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanyAuthorization;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthorizationController extends Controller
{
    public function prompt(Company $company)
    {
        return view('crd_admin.authorization.confirm', compact('company'));
    }

    public function assume(Request $request, Company $company)
    {
        $user = Auth::user();

        $expires = Carbon::now()->addHours(8);

        $auth = CompanyAuthorization::create([
            'crd_admin_id' => $user->id,
            'company_id'   => $company->id,
            'granted_by'   => $user->id,
            'granted_at'   => Carbon::now(),
            'expires_at'   => $expires,
            'reason'       => $request->input('reason', null),
        ]);

        session()->put('assumed_company', [
            'company_id'       => $company->id,
            'authorization_id' => $auth->id,
            'expires_at'       => $expires->toDateTimeString(),
        ]);

        return redirect()->route('crd-admin.companies.tickets.index', ['company' => $company->id])
                         ->with('success', 'Assumed authorization for ' . $company->name);
    }

    public function release(Request $request)
    {
        $session = session()->get('assumed_company');
        if ($session && isset($session['authorization_id'])) {
            CompanyAuthorization::where('id', $session['authorization_id'])->update(['expires_at' => Carbon::now()]);
        }
        session()->forget('assumed_company');

        return back()->with('success', 'Authorization released.');
    }
}
