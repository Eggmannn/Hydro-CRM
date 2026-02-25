<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Http\Request;

class AgentTicketController extends Controller
{
    // helper
    protected function companyId()
    {
        return auth()->user()->company_id;
    }

    // ✅ All tickets in company (with search & filter)
public function index(Request $request)
{
    $companyId = auth()->user()->company_id;

    $tickets = Ticket::where('company_id', $companyId)
        ->where('deleted', 0)
        ->when($request->q, function ($query) use ($request) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('subject', 'like', "%{$q}%")
                    ->orWhereHas('contact', function ($c) use ($q) {
                        $c->where('name', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        })
        ->when($request->status, function ($query) use ($request) {
            $query->where('status', $request->status);
        })
        ->when($request->priority, function ($query) use ($request) {
            $query->where('priority', $request->priority);
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('agent.tickets.index', compact('tickets'));
}

// ✅ Only my tickets (with search & filter)
public function my(Request $request)
{
    $companyId = auth()->user()->company_id;

    $tickets = Ticket::where('company_id', $companyId)
        ->where('assignee_id', auth()->id())
        ->where('deleted', 0)
        ->when($request->q, function ($query) use ($request) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('subject', 'like', "%{$q}%")
                    ->orWhereHas('contact', function ($c) use ($q) {
                        $c->where('name', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        })
        ->when($request->status, function ($query) use ($request) {
            $query->where('status', $request->status);
        })
        ->when($request->priority, function ($query) use ($request) {
            $query->where('priority', $request->priority);
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('agent.tickets.my', compact('tickets'));
}


    public function show($id)
    {
        $ticket = Ticket::where('id', $id)
            ->where('company_id', $this->companyId())
            ->where('deleted', 0)
            ->firstOrFail();

        $agents = User::where('company_id', $this->companyId())
            ->whereHas('roles', function ($q) {
                $q->where('role_type', 'agent');
            })
            ->get();

        return view('agent.tickets.show', compact('ticket', 'agents'));
    }

    public function create()
    {
        $contacts = Contact::where('company_id', $this->companyId())
            ->where('deleted', 0)
            ->get();

        return view('agent.tickets.create', compact('contacts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject'    => 'required|string|max:255',
            'body'       => 'nullable|string',
            'priority'   => 'required|in:low,normal,high',
            'contact_id' => 'nullable|exists:contacts,id',
        ]);

        $data['company_id'] = $this->companyId();
        $data['assignee_id'] = auth()->id(); // default self
        $data['created_by'] = auth()->id();
        $data['status'] = 'open';

        Ticket::create($data);

        return redirect()
            ->route('agent.tickets.index')
            ->with('success', 'Ticket created.');
    }

    public function edit($id)
    {
        $ticket = Ticket::where('id', $id)
            ->where('company_id', $this->companyId())
            ->where('deleted', 0)
            ->firstOrFail();

        $contacts = Contact::where('company_id', $this->companyId())
            ->where('deleted', 0)
            ->get();

        $agents = User::where('company_id', $this->companyId())
            ->whereHas('roles', function ($q) {
                $q->where('role_type', 'agent');
            })
            ->get();

        return view('agent.tickets.edit', compact('ticket', 'contacts', 'agents'));
    }

    public function update(Request $request, $id)
{
    $ticket = Ticket::where('id', $id)
        ->where('company_id', auth()->user()->company_id)
        ->firstOrFail();

    $data = $request->validate([
        'subject'     => 'required|string',
        'body'        => 'nullable|string',
        'priority'    => 'required|in:low,normal,high',
        'status'      => 'required|in:open,pending,closed',
        'contact_id'  => 'nullable|exists:contact,id',
        'assignee_id' => 'nullable|exists:user,id',
    ]);

    $data['updated_by'] = auth()->id();

    $ticket->update($data);

    return redirect()
        ->route('agent.tickets.show', $ticket->id)
        ->with('success', 'Ticket updated.');
}

    public function destroy($id)
    {
        $ticket = Ticket::where('id', $id)
            ->where('company_id', $this->companyId())
            ->where('deleted', 0)
            ->firstOrFail();

        $ticket->update(['deleted' => 1]);

        return redirect()
            ->route('agent.tickets.index')
            ->with('success', 'Ticket deleted.');
    }

    public function comment(Request $request, $id)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $ticket = Ticket::where('id', $id)
            ->where('company_id', $this->companyId())
            ->where('deleted', 0)
            ->firstOrFail();

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id'   => auth()->id(),
            'body'      => $request->body,
        ]);

        return back()->with('success', 'Comment added.');
    }

    public function deleteComment($ticketId, $commentId)
    {
        $ticket = Ticket::where('id', $ticketId)
            ->where('company_id', $this->companyId())
            ->firstOrFail();

        TicketComment::where('id', $commentId)
            ->where('ticket_id', $ticket->id)
            ->where('user_id', auth()->id())
            ->delete();

        return back()->with('success', 'Comment deleted.');
    }

    public function unassignedJson()
{
    $tickets = Ticket::where('company_id', auth()->user()->company_id)
        ->whereNull('assignee_id')
        ->where('deleted', 0)
        ->with('contact')
        ->latest()
        ->take(10)
        ->get()
        ->map(function ($t) {
            return [
                'id' => $t->id,
                'subject' => $t->subject,
                'created_at_human' => optional($t->created_at)->diffForHumans(),
                'contact' => $t->contact ? ['name' => $t->contact->name] : null,
            ];
        });

    return response()->json($tickets);
}

}

