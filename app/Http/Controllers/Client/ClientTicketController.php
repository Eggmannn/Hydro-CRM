<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketComment;
use Illuminate\Http\Request;

class ClientTicketController extends Controller
{
    /**
     * Display a listing of the client's tickets
     * (ONLY tickets created by this client)
     */
    public function index()
    {
        $user = auth()->user();

        $tickets = Ticket::where('company_id', $user->company_id)
            ->where('created_by', $user->id)
            ->where('deleted', 0)
            ->latest()
            ->paginate(10);


        return view('client.tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new ticket
     */
    public function create()
    {
        return view('client.tickets.create');
    }

    /**
     * Store a newly created ticket
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        Ticket::create([
            'company_id' => auth()->user()->company_id,
            'subject' => $request->subject,
            'body' => $request->body,
            'status' => 'open',
            'priority' => 'normal',
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('client.tickets.index')
            ->with('success', 'Ticket submitted successfully.');
    }

    /**
     * Display a specific ticket
     * (IDOR-safe)
     */
    public function show(Ticket $ticket)
    {
        $user = auth()->user();

        // 🔐 Security check
        if (!$ticket->isOwnedBy($user) || $ticket->isDeleted()) {
            abort(403);
        }

        $comments = $ticket->comments()
            ->where('deleted', 0)
            ->with('user')
            ->oldest()
            ->get();

        return view('client.tickets.show', compact('ticket', 'comments'));
    }

    /**
     * Add a comment to a ticket
     * (client can only comment on their own tickets)
     */
    public function comment(Request $request, Ticket $ticket)
    {
        $user = auth()->user();

        if (!$ticket->isOwnedBy($user) || $ticket->isDeleted()) {
            abort(403);
        }

        $request->validate([
            'body' => 'required|string',
        ]);

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'body' => $request->body,
        ]);

        return back()->with('success', 'Comment added.');
    }
}
