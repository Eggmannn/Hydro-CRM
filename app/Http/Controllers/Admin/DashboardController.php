<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()->company_id;

        // -------------------------------------------------
        // 📊 Ticket stats
        // -------------------------------------------------
        $stats = [
            'total'      => Ticket::where('company_id', $companyId)->where('deleted', 0)->count(),
            'open'       => Ticket::where('company_id', $companyId)->where('deleted', 0)->where('status', 'open')->count(),
            'pending'    => Ticket::where('company_id', $companyId)->where('deleted', 0)->where('status', 'pending')->count(),
            'closed'     => Ticket::where('company_id', $companyId)->where('deleted', 0)->where('status', 'closed')->count(),
            'unassigned' => Ticket::where('company_id', $companyId)->where('deleted', 0)->whereNull('assignee_id')->count(),
        ];

        // -------------------------------------------------
        // 🚨 Unassigned tickets (top 6)
        // -------------------------------------------------
        $unassignedTickets = Ticket::with(['contact', 'assignee'])
            ->where('company_id', $companyId)
            ->where('deleted', 0)
            ->whereNull('assignee_id')
            ->orderByRaw("FIELD(priority, 'high','normal','low')")
            ->latest()
            ->take(6)
            ->get();

        // -------------------------------------------------
        // 🕒 Recent tickets (top 8)
        // -------------------------------------------------
        $recentTickets = Ticket::with(['contact', 'assignee'])
            ->where('company_id', $companyId)
            ->where('deleted', 0)
            ->latest()
            ->take(8)
            ->get();

        // -------------------------------------------------
        // 👷 Agent workload (active tickets per agent)
        // Only counts tickets that are not closed
        // -------------------------------------------------
        $agentWorkload = DB::table('user as u')
            ->join('role as r', 'r.user_id', '=', 'u.id')
            ->where('u.company_id', $companyId)
            ->where('u.deleted', 0)
            ->where('r.company_id', $companyId)
            ->where('r.role_type', 'agent')
            ->select(
                'u.id',
                'u.name',
                DB::raw('(
                    SELECT COUNT(*)
                    FROM ticket t
                    WHERE t.assignee_id = u.id
                      AND t.company_id = ' . intval($companyId) . '
                      AND t.deleted = 0
                      AND t.status != "closed"
                ) as ticket_count')
            )
            ->orderByDesc('ticket_count')
            ->get()
            ->map(function ($row) {
                return [
                    'id'    => $row->id,
                    'name'  => $row->name,
                    'count' => (int) $row->ticket_count,
                ];
            });

        return view('admin.dashboard', compact(
            'stats',
            'unassignedTickets',
            'recentTickets',
            'agentWorkload'
        ));
    }
}
