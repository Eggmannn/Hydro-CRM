<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class AgentContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::where('company_id', auth()->user()->company_id)
            ->where('deleted', 0);

        // 🔍 Search support
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        // ✅ Use paginate instead of get()
        $contacts = $query->latest()->paginate(10);

        return view('agent.contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('agent.contacts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:30',
            'title' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $data['company_id'] = auth()->user()->company_id;

        Contact::create($data);

        return redirect()
            ->route('agent.contacts.index')
            ->with('success', 'Contact created.');
    }

    public function show($id)
    {
        $contact = Contact::where('id', $id)
            ->where('company_id', auth()->user()->company_id)
            ->where('deleted', 0)
            ->firstOrFail();

        return view('agent.contacts.show', compact('contact'));
    }

    public function edit($id)
    {
        $contact = Contact::where('id', $id)
            ->where('company_id', auth()->user()->company_id)
            ->where('deleted', 0)
            ->firstOrFail();

        return view('agent.contacts.edit', compact('contact'));
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::where('id', $id)
            ->where('company_id', auth()->user()->company_id)
            ->where('deleted', 0)
            ->firstOrFail();

        $data = $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:30',
            'title' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $contact->update($data);

        return redirect()
            ->route('agent.contacts.index')
            ->with('success', 'Contact updated.');
    }

    public function destroy($id)
    {
        $contact = Contact::where('id', $id)
            ->where('company_id', auth()->user()->company_id)
            ->where('deleted', 0)
            ->firstOrFail();

        $contact->update(['deleted' => 1]);

        return redirect()
            ->route('agent.contacts.index')
            ->with('success', 'Contact deleted.');
    }
}
