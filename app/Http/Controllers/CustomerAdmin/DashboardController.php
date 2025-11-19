<?php

namespace App\Http\Controllers\CustomerAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Contact;
use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $usersCount     = User::where('company_id', $companyId)->where('deleted', 0)->count();
        $contactsCount  = Contact::where('company_id', $companyId)->where('deleted', 0)->count();
        $openCount      = Ticket::where('company_id', $companyId)->where('status','open')->where('deleted',0)->count();
        $pendingCount   = Ticket::where('company_id', $companyId)->where('status','pending')->where('deleted',0)->count();
        $closedCount    = Ticket::where('company_id', $companyId)->where('status','closed')->where('deleted',0)->count();
        $recentTickets  = Ticket::where('company_id', $companyId)->where('deleted',0)
                            ->latest()->limit(6)->get();
        $recentUsers    = User::where('company_id', $companyId)->where('deleted',0)
                            ->latest()->limit(6)->get();
        $priorityCounts = [
            'high'   => Ticket::where('company_id', $companyId)->where('priority','high')->where('deleted',0)->count(),
            'normal' => Ticket::where('company_id', $companyId)->where('priority','normal')->where('deleted',0)->count(),
            'low'    => Ticket::where('company_id', $companyId)->where('priority','low')->where('deleted',0)->count(),
        ];
        return view('customer_admin.dashboard', compact(
            'usersCount',
            'contactsCount',
            'openCount',
            'pendingCount',
            'closedCount',
            'recentTickets',
            'recentUsers',
            'priorityCounts'
        ));
    }
}
