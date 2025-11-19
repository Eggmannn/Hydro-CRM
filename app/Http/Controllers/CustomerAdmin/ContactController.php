<?php

namespace App\Http\Controllers\CustomerAdmin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ContactController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()->company_id;
        $contacts = Contact::where('company_id',$companyId)->where('deleted',0)->latest()->paginate(15);
        return view('customer_admin.contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('customer_admin.contacts.create');
    }

    public function store(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $data = $request->validate([
            'name'  => ['required','max:100'],
            'email' => ['nullable','email','max:100'],
            'phone' => ['nullable','max:30'],
            'title' => ['nullable','max:50'],
            'notes' => ['nullable','string'],
        ]);

        Contact::create($data + ['company_id' => $companyId, 'deleted' => 0]);

        return redirect()->route('customer-admin.contacts.index')->with('success','Contact created.');
    }

    public function edit(Contact $contact)
    {
        $this->authorizeContact($contact);
        return view('customer_admin.contacts.edit', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        $this->authorizeContact($contact);

        $data = $request->validate([
            'name'  => ['required','max:100'],
            'email' => ['nullable','email','max:100'],
            'phone' => ['nullable','max:30'],
            'title' => ['nullable','max:50'],
            'notes' => ['nullable','string'],
        ]);

        $contact->update($data);

        return redirect()->route('customer-admin.contacts.index')->with('success','Contact updated.');
    }

    public function destroy(\App\Models\Contact $contact)
    {
        if (! auth('crd_admin')->check() && $contact->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        DB::transaction(function () use ($contact) {
            if (Schema::hasColumn('tickets', 'contact_id')) {
                if (Schema::hasTable('ticket_comments') && Schema::hasColumn('ticket_comments', 'ticket_id')) {
                    $ticketIds = \App\Models\Ticket::where('contact_id', $contact->id)->pluck('id');
                    if ($ticketIds->count()) {
                        DB::table('ticket_comments')->whereIn('ticket_id', $ticketIds)->delete();
                    }
                } elseif (Schema::hasTable('comments') && Schema::hasColumn('comments', 'ticket_id')) {
                    $ticketIds = \App\Models\Ticket::where('contact_id', $contact->id)->pluck('id');
                    if ($ticketIds->count()) {
                        DB::table('comments')->whereIn('ticket_id', $ticketIds)->delete();
                    }
                }

                \App\Models\Ticket::where('contact_id', $contact->id)->delete();
            }

            $contact->delete();
        });

        return redirect()->route('customer-admin.contacts.index')->with('success', 'Contact permanently deleted.');
    }

    protected function authorizeContact(Contact $contact)
    {
        if ($contact->company_id !== auth()->user()->company_id || $contact->deleted) {
            abort(403);
        }
    }
}
