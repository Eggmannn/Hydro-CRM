<?php

namespace App\Http\Controllers\CrdAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;

class CrdAdminDashboardController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        $users = User::with('company')->get();

        $months = [];
        $companyCounts = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);

            $months[] = $month->format('M Y');

            $companyCounts[] = Company::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return view('crd_admin.dashboard', compact(
            'companies',
            'users',
            'months',
            'companyCounts'
        ));
    }
}
