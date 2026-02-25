<?php

namespace App\Http\Controllers\CustomerAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserController extends Controller
{
    /* -------------------------------------------------
     | 📋 List users
     * -------------------------------------------------*/
    public function index()
{
    $companyId = auth()->user()->company_id;

    $type = request('type', 'internal'); // internal | external
    $q    = request('q');
    $role = request('role');

    $users = User::where('company_id', $companyId)
        ->where('deleted', 0)

        // 🔎 Search by name/email
        ->when($q, function ($query) use ($q) {
            $query->where(function ($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%");
            });
        })

        // 👤 External users = clients only
        ->when($type === 'external', function ($query) use ($companyId) {
            $query->whereHas('roles', function ($r) use ($companyId) {
                $r->where('company_id', $companyId)
                  ->where('role_type', 'client');
            });
        })

        // 🧑‍💼 Internal users = everyone EXCEPT clients
        ->when($type === 'internal', function ($query) use ($companyId) {
            $query->whereDoesntHave('roles', function ($r) use ($companyId) {
                $r->where('company_id', $companyId)
                  ->where('role_type', 'client');
            });
        })

        // 🎭 Internal role filter
        ->when($type === 'internal' && $role, function ($query) use ($companyId, $role) {
            $query->whereHas('roles', function ($r) use ($companyId, $role) {
                $r->where('company_id', $companyId)
                  ->where('role_type', $role);
            });
        })

        ->latest()
        ->paginate(15);

    return view('customer_admin.users.index', compact('users'));
}


    /* -------------------------------------------------
     | ➕ Create internal user
     * -------------------------------------------------*/
    public function create()
    {
        return view('customer_admin.users.create');
    }

    public function store(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $data = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'max:100', Rule::unique('user', 'email')],
            'password'   => ['required', 'min:6'],
            'role_type'  => ['required', Rule::in(['admin', 'agent', 'viewer', 'customer_admin'])],
        ]);

        $user = User::create([
            'company_id' => $companyId,
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'deleted'    => 0,
        ]);

        Role::create([
            'user_id'    => $user->id,
            'company_id' => $companyId,
            'role_type'  => $data['role_type'],
            'created_by'=> auth()->id(),
            'is_primary_admin' => $data['role_type'] === 'admin' ? 1 : 0,
        ]);

        return redirect()
            ->route('customer-admin.users.index')
            ->with('success', 'User created.');
    }

    /* -------------------------------------------------
     | ✏️ Edit user
     * -------------------------------------------------*/
    public function edit(User $user)
    {
        $this->authorizeUser($user);

        return view('customer_admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
{
    $this->authorizeUser($user);

    $isClient = $user->hasRole('client', $user->company_id);

    // Base validation
    $rules = [
        'name'     => ['required', 'string', 'max:100'],
        'email'    => ['required', 'email', 'max:100', Rule::unique('user', 'email')->ignore($user->id)],
        'password' => ['nullable', 'min:6'],
    ];

    // ✅ Only allow role change if NOT client
    if (!$isClient) {
        $rules['role_type'] = [
            'required',
            Rule::in(['admin', 'agent', 'viewer', 'customer_admin'])
        ];
    }

    $data = $request->validate($rules);

    // Update user profile
    $payload = [
        'name'  => $data['name'],
        'email' => $data['email'],
    ];

    if (!empty($data['password'])) {
        $payload['password'] = Hash::make($data['password']);
    }

    $user->update($payload);

    // ✅ Update role (internal users only)
    if (!$isClient && isset($data['role_type'])) {
        Role::updateOrCreate(
            [
                'user_id'    => $user->id,
                'company_id' => $user->company_id,
            ],
            [
                'role_type'        => $data['role_type'],
                'created_by'       => auth()->id(),
                'is_primary_admin' => $data['role_type'] === 'admin' ? 1 : 0,
            ]
        );
    }

    return redirect()
        ->route('customer-admin.users.index')
        ->with('success', 'User updated successfully.');
}


    /* -------------------------------------------------
     | 🗑 Delete user
     * -------------------------------------------------*/
    public function destroy(User $user)
    {
        $this->authorizeUser($user);

        DB::transaction(function () use ($user) {

            Role::where('user_id', $user->id)->delete();

            if (Schema::hasColumn('tickets', 'created_by')) {
                \App\Models\Ticket::where('created_by', $user->id)->delete();
            }

            if (Schema::hasColumn('tickets', 'assignee_id')) {
                \App\Models\Ticket::where('assignee_id', $user->id)
                    ->update(['assignee_id' => null]);
            }

            if (Schema::hasTable('ticket_comments') && Schema::hasColumn('ticket_comments', 'user_id')) {
                DB::table('ticket_comments')->where('user_id', $user->id)->delete();
            } elseif (Schema::hasTable('comments') && Schema::hasColumn('comments', 'user_id')) {
                DB::table('comments')->where('user_id', $user->id)->delete();
            }

            $user->delete();
        });

        return back()->with('success', 'User permanently deleted.');
    }

    /* -------------------------------------------------
     | 🔑 Assign role (internal users only)
     * -------------------------------------------------*/
    public function assignRole(Request $request, User $user)
    {
        $this->authorizeUser($user);

        // 🔒 Prevent client privilege escalation
        $this->preventClientRoleModification($user);

        $data = $request->validate([
            'role_type' => ['required', Rule::in(['admin', 'agent', 'viewer', 'customer_admin'])],
        ]);

        Role::updateOrCreate(
            ['user_id' => $user->id, 'company_id' => $user->company_id],
            [
                'role_type' => $data['role_type'],
                'created_by'=> auth()->id(),
                'is_primary_admin' => $data['role_type'] === 'admin' ? 1 : 0,
            ]
        );

        return back()->with('success', 'Role updated.');
    }

    /* -------------------------------------------------
     | 👤 Client creation
     * -------------------------------------------------*/
    public function createClient()
    {
        return view('customer_admin.users.create-client');
    }

    public function storeClient(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:user,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = auth()->user();

        $user = User::create([
            'company_id' => $admin->company_id,
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'deleted'    => 0,
        ]);

        Role::create([
            'user_id'    => $user->id,
            'company_id' => $admin->company_id,
            'role_type'  => 'client',
            'created_by'=> $admin->id,
            'is_primary_admin' => 0,
        ]);

        return redirect()
            ->route('customer-admin.users.index', ['type' => 'external'])
            ->with('success', 'Client user created successfully.');
    }

    /* -------------------------------------------------
     | 🔐 Authorization helpers
     * -------------------------------------------------*/
    protected function authorizeUser(User $user)
    {
        if ($user->company_id !== auth()->user()->company_id || $user->deleted) {
            abort(404);
        }
    }

    protected function preventClientRoleModification(User $user)
    {
        if ($user->hasRole('client', $user->company_id)) {
            abort(403, 'Client roles cannot be modified.');
        }
    }
}
