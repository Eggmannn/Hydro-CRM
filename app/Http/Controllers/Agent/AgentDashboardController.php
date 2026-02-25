<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Ticket;

class AgentDashboardController extends Controller
{
    protected function companyId()
    {
        return auth()->user()->company_id;
    }

    public function index()
    {
        $userId    = auth()->id();
        $companyId = $this->companyId();

        // 🎯 Base: tickets assigned to this agent
        $baseQuery = Ticket::where('company_id', $companyId)
            ->where('assignee_id', $userId)
            ->where('deleted', 0);

        // 📊 Stats
        $total   = (clone $baseQuery)->count();
        $open    = (clone $baseQuery)->where('status', 'open')->count();
        $pending = (clone $baseQuery)->where('status', 'pending')->count();

        // 🕒 Recent tickets
        $recentTickets = (clone $baseQuery)
            ->with('contact')
            ->latest()
            ->take(10)
            ->get();

        // 🆕 Unassigned tickets
        $unassignedTickets = Ticket::where('company_id', $companyId)
            ->whereNull('assignee_id')
            ->where('deleted', 0)
            ->with('contact')
            ->latest()
            ->take(10)
            ->get();

        return view('agent.dashboard', compact(
            'total',
            'open',
            'pending',
            'recentTickets',
            'unassignedTickets'
        ));
    }

    // ⚡ Assign ticket to current agent
    public function assignToMe($id)
    {
        $ticket = Ticket::where('id', $id)
            ->where('company_id', $this->companyId())
            ->whereNull('assignee_id')
            ->where('deleted', 0)
            ->firstOrFail();

        $ticket->update([
            'assignee_id' => auth()->id(),
            'updated_by'  => auth()->id(),
        ]);

        return back()->with('success', 'Ticket assigned to you.');
    }

    // 🔄 AJAX: fetch unassigned tickets
    public function unassigned()
    {
        $tickets = Ticket::where('company_id', $this->companyId())
            ->whereNull('assignee_id')
            ->where('deleted', 0)
            ->with('contact')
            ->latest()
            ->take(10)
            ->get();

        return response()->json($tickets);
    }
}
