<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company_id;

        $tickets = Ticket::where('company_id', $companyId)
            ->where('deleted', 0)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $companyId = Auth::user()->company_id;

        $agents = User::where('company_id', $companyId)
            ->where('deleted', 0)
            ->whereHas('roles', function ($q) use ($companyId) {
                $q->where('role_type', 'agent')
                  ->where('company_id', $companyId);
            })
            ->get();

        return view('admin.tickets.create', compact('agents'));
    }

    public function store(Request $request)
    {
        $companyId = Auth::user()->company_id;

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'priority' => 'required|in:low,normal,high',
            'status' => 'required|in:open,pending,closed',
            'assignee_id' => 'nullable|integer',
        ]);

        $ticket = Ticket::create([
            'company_id' => $companyId,
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'priority' => $validated['priority'],
            'status' => $validated['status'],
            'assignee_id' => $validated['assignee_id'] ?? null,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        $companyId = Auth::user()->company_id;

        if ($ticket->company_id != $companyId || $ticket->deleted) {
            abort(403);
        }

        $ticket->load(['comments.user']);

        $agents = User::where('company_id', $companyId)
            ->where('deleted', 0)
            ->whereHas('roles', function ($q) use ($companyId) {
                $q->where('role_type', 'agent')
                  ->where('company_id', $companyId);
            })
            ->get();

        return view('admin.tickets.show', compact('ticket', 'agents'));
    }

    public function edit(Ticket $ticket)
    {
        $companyId = Auth::user()->company_id;

        if ($ticket->company_id != $companyId || $ticket->deleted) {
            abort(403);
        }

        $agents = User::where('company_id', $companyId)
            ->where('deleted', 0)
            ->whereHas('roles', function ($q) use ($companyId) {
                $q->where('role_type', 'agent')
                  ->where('company_id', $companyId);
            })
            ->get();

        return view('admin.tickets.edit', compact('ticket', 'agents'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $companyId = Auth::user()->company_id;

        if ($ticket->company_id != $companyId || $ticket->deleted) {
            abort(403);
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'priority' => 'required|in:low,normal,high',
            'status' => 'required|in:open,pending,closed',
            'assignee_id' => 'nullable|integer',
        ]);

        $ticket->subject = $validated['subject'];
        $ticket->body = $validated['body'];
        $ticket->priority = $validated['priority'];
        $ticket->status = $validated['status'];
        $ticket->assignee_id = $validated['assignee_id'] ?? null;
        $ticket->updated_by = Auth::id();
        $ticket->save();

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        $companyId = Auth::user()->company_id;

        if ($ticket->company_id != $companyId) {
            abort(403);
        }

        $ticket->deleted = 1;
        $ticket->save();

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket soft deleted.');
    }

    /**
     * Assign ticket to an agent
     */
    public function assign(Request $request, Ticket $ticket)
    {
        $companyId = Auth::user()->company_id;

        if ($ticket->company_id != $companyId || $ticket->deleted) {
            abort(403);
        }

        $validated = $request->validate([
            'assignee_id' => 'required|integer',
        ]);

        // Ensure the user is an agent under the same company
        $agent = User::where('id', $validated['assignee_id'])
            ->where('company_id', $companyId)
            ->where('deleted', 0)
            ->whereHas('roles', function ($q) use ($companyId) {
                $q->where('role_type', 'agent')
                  ->where('company_id', $companyId);
            })
            ->first();

        if (!$agent) {
            return back()->withErrors(['assignee_id' => 'Invalid agent selected.']);
        }

        $ticket->assignee_id = $agent->id;
        $ticket->updated_by = Auth::id();
        $ticket->save();

        // Log activity
        Activity::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'type' => 'assigned',
            'payload' => json_encode([
                'to' => $agent->name
            ]),
        ]);

        return back()->with('success', 'Ticket assigned successfully.');
    }
}
