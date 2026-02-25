<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Ticket;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Only tickets created by this client
        $ticketsQuery = Ticket::where('company_id', $user->company_id)
            ->where('created_by', $user->id)
            ->where('deleted', 0);

        $stats = [
            'total'   => (clone $ticketsQuery)->count(),
            'open'    => (clone $ticketsQuery)->where('status', 'open')->count(),
            'pending' => (clone $ticketsQuery)->where('status', 'pending')->count(),
            'closed'  => (clone $ticketsQuery)->where('status', 'closed')->count(),
        ];

        $recentTickets = $ticketsQuery
            ->latest()
            ->limit(5)
            ->get();

        return view('client.dashboard', compact('stats', 'recentTickets'));
    }
}
