<?php

namespace App\Http\Controllers\CustomerAdmin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TicketController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()->company_id;

        $tickets = Ticket::where('company_id',$companyId)
            ->where('deleted',0)
            ->latest()->paginate(15);

        return view('customer_admin.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $companyId = auth()->user()->company_id;
        $contacts = Contact::where('company_id',$companyId)->where('deleted',0)->get();
        $agents   = User::where('company_id',$companyId)->where('deleted',0)->get();
        return view('customer_admin.tickets.create', compact('contacts','agents'));
    }

    public function store(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $data = $request->validate([
            'contact_id' => ['nullable','integer'],
            'assignee_id'=> ['nullable','integer'],
            'status'     => ['required', Rule::in(['open','pending','closed'])],
            'priority'   => ['required', Rule::in(['low','normal','high'])],
            'subject'    => ['required','max:255'],
            'body'       => ['nullable','string'],
        ]);

        $this->ensureContactAndAssigneeInCompany($data, $companyId);

        $ticket = Ticket::create($data + [
            'company_id' => $companyId,
            'created_by' => auth()->id(),
            'deleted'    => 0,
        ]);

        return redirect()->route('customer-admin.tickets.edit', $ticket)->with('success','Ticket created.');
    }

    public function edit(Ticket $ticket)
    {
        $this->authorizeTicket($ticket);

        $companyId = auth()->user()->company_id;
        $contacts = Contact::where('company_id',$companyId)->where('deleted',0)->get();
        $agents   = User::where('company_id',$companyId)->where('deleted',0)->get();

        return view('customer_admin.tickets.edit', compact('ticket','contacts','agents'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $this->authorizeTicket($ticket);

        $data = $request->validate([
            'contact_id' => ['nullable','integer'],
            'assignee_id'=> ['nullable','integer'],
            'status'     => ['required', Rule::in(['open','pending','closed'])],
            'priority'   => ['required', Rule::in(['low','normal','high'])],
            'subject'    => ['required','max:255'],
            'body'       => ['nullable','string'],
        ]);

        $this->ensureContactAndAssigneeInCompany($data, $ticket->company_id);

        $ticket->update($data + ['updated_by' => auth()->id()]);

        return back()->with('success','Ticket updated.');
    }

    public function destroy(\App\Models\Ticket $ticket)
    {
        if (! auth('crd_admin')->check() && $ticket->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        DB::transaction(function () use ($ticket) {
            if (Schema::hasTable('ticket_comments') && Schema::hasColumn('ticket_comments', 'ticket_id')) {
                DB::table('ticket_comments')->where('ticket_id', $ticket->id)->delete();
            } elseif (Schema::hasTable('comments') && Schema::hasColumn('comments', 'ticket_id')) {
                DB::table('comments')->where('ticket_id', $ticket->id)->delete();
            }

            if (Schema::hasTable('attachments') && Schema::hasColumn('attachments', 'ticket_id')) {
                DB::table('attachments')->where('ticket_id', $ticket->id)->delete();
            }

            $ticket->delete();
        });

        return redirect()->route('customer-admin.tickets.index')->with('success', 'Ticket permanently deleted.');
    }

    protected function authorizeTicket(Ticket $ticket)
    {
        if ($ticket->company_id !== auth()->user()->company_id || $ticket->deleted) {
            abort(403);
        }
    }

    protected function ensureContactAndAssigneeInCompany(array $data, int $companyId): void
    {
        if (!empty($data['contact_id'])) {
            $ok = Contact::where('id',$data['contact_id'])->where('company_id',$companyId)->where('deleted',0)->exists();
            abort_unless($ok, 422, 'Invalid contact for this company.');
        }
        if (!empty($data['assignee_id'])) {
            $ok = User::where('id',$data['assignee_id'])->where('company_id',$companyId)->where('deleted',0)->exists();
            abort_unless($ok, 422, 'Invalid assignee for this company.');
        }
    }
}
