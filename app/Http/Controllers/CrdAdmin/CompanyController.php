<?php

namespace App\Http\Controllers\CrdAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Contact;
use App\Models\Role;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        return view('crd_admin.companies.index', compact('companies'));
    }

    public function create()
    {
        $users = User::all();
        return view('crd_admin.companies.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:company,domain',
        ]);

        $company = Company::create([
            'name'       => $request->name,
            'domain'     => $request->domain,
            'notes'      => $request->notes,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($request->filled('customer_admin_id')) {
            $admin = User::find($request->customer_admin_id);
            if ($admin) {
                $admin->company_id = $company->id;
                $admin->save();
            }
        }

        return redirect()
            ->route('crd-admin.companies.index')
            ->with('success', 'Company created successfully!');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()
            ->route('crd-admin.companies.index')
            ->with('success', 'Company deleted successfully!');
    }

    public function createUser(Company $company)
    {
        return view('crd_admin.companies.create_user', compact('company'));
    }

    public function storeUser(Request $request, Company $company)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:user,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,agent,viewer,customer_admin',
        ]);

        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => bcrypt($request->password),
            'company_id' => $company->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Role::create([
            'user_id'         => $user->id,
            'company_id'      => $company->id,
            'role_type'       => $request->role,
            'created_by'      => auth('crd_admin')->id(),
            'is_primary_admin'=> $request->role === 'admin' ? 1 : 0,
            'created_at'      => now(),
        ]);

        return redirect()
            ->route('crd-admin.companies.index')
            ->with('success', "User '{$user->name}' created for {$company->name}.");
    }

    public function listUsers(Company $company)
    {
        $users = $company->users()
            ->with(['roles' => function ($q) {
                $q->select('user_id', 'role_type');
            }])
            ->get();

        return view('crd_admin.companies.users_index', compact('company', 'users'));
    }

public function editUser(Company $company, User $user)
{
    return view('crd_admin.companies.edit_user', compact('company', 'user'));
}

public function updateUser(Request $request, Company $company, User $user)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => "required|email|unique:user,email,{$user->id}",
        'role' => 'required|in:admin,agent,viewer,customer_admin',
    ]);

    $user->update([
        'name'  => $request->name,
        'email' => $request->email,
    ]);

    $role = $user->roles()->first();
    if ($role) {
        $role->update([
            'role_type' => $request->role,
            'is_primary_admin' => $request->role === 'admin' ? 1 : 0,
        ]);
    }

    return redirect()
        ->route('crd-admin.company-users.index', $company->id)
        ->with('success', "User {$user->name} updated successfully.");
}

public function deleteUser(Company $company, User $user)
{
    $user->roles()->delete();
    $user->delete();

    return redirect()
        ->route('crd-admin.company-users.index', $company->id)
        ->with('success', "User deleted successfully.");
}

public function companyTickets(Company $company, Request $request)
{
    $query = Ticket::where('company_id', $company->id)
                    ->with(['contact', 'assignee'])
                    ->orderBy('created_at', 'desc');

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $tickets = $query->paginate(15)->withQueryString();

    return view('crd_admin.companies.tickets.index', compact('company', 'tickets'));
}

public function companyTicketShow(Company $company, Ticket $ticket)
{
    if ($ticket->company_id !== $company->id) {
        abort(404);
    }

    $ticket->load(['contact', 'assignee', 'comments.user']); // load extras if available

    return view('crd_admin.companies.tickets.show', compact('company', 'ticket'));
}

public function companyContacts(Company $company, Request $request)
{
    $query = Contact::where('company_id', $company->id)
                    ->orderBy('name', 'asc');

    if ($request->filled('q')) {
        $q = $request->q;
        $query->where(function($w) use ($q) {
            $w->where('name', 'like', "%{$q}%")
              ->orWhere('email', 'like', "%{$q}%")
              ->orWhere('phone', 'like', "%{$q}%");
        });
    }

    $contacts = $query->paginate(20)->withQueryString();

    return view('crd_admin.companies.contacts.index', compact('company', 'contacts'));
}

public function listJson(Request $request)
{
    $q = $request->query('q', null);

    $query = Company::select('id', 'name')->orderBy('name', 'asc');

    if ($q) {
        $query->where('name', 'like', "%{$q}%");
    }

    $companies = $query->get();

    return response()->json($companies);
}
}
