<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /* -------------------------------------------------
     | 📋 List agents / clients (filter)
     * -------------------------------------------------*/
    public function index(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $type = $request->query('type', 'agent');
        if (!in_array($type, ['agent', 'client'])) {
            $type = 'agent';
        }

        $q = $request->query('q');

        $users = User::query()
            ->where('company_id', $companyId)
            ->where('deleted', 0)
            ->whereHas('roles', function ($r) use ($companyId, $type) {
                $r->where('company_id', $companyId)
                  ->where('role_type', $type);
            })
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                       ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users', 'type'));
    }

    /* -------------------------------------------------
     | ➕ Create user (agent/client only)
     * -------------------------------------------------*/
    public function create(Request $request)
    {
        $type = $request->query('type', 'agent');

        if (!in_array($type, ['agent', 'client'])) {
            $type = 'agent';
        }

        return view('admin.users.create', compact('type'));
    }

    public function store(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $data = $request->validate([
            'type'      => ['required', Rule::in(['agent', 'client'])],
            'name'      => ['required', 'string', 'max:100'],
            'email'     => ['required', 'email', 'max:100', Rule::unique('user', 'email')],
            'password'  => ['required', 'min:6'],
        ]);

        $user = User::create([
            'company_id' => $companyId,
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'deleted'    => 0,
        ]);

        // 🔒 Force role based on type
        Role::create([
            'user_id'          => $user->id,
            'company_id'       => $companyId,
            'role_type'        => $data['type'], // agent OR client
            'created_by'       => auth()->id(),
            'is_primary_admin' => 0,
        ]);

        return redirect()
            ->route('admin.users.index', ['type' => $data['type']])
            ->with('success', ucfirst($data['type']) . ' created.');
    }

    /* -------------------------------------------------
     | ✏️ Edit user (no role changes)
     * -------------------------------------------------*/
    public function edit(User $user)
    {
        $this->authorizeUser($user);

        $type = $this->getManagedType($user);

        return view('admin.users.edit', compact('user', 'type'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeUser($user);

        $type = $this->getManagedType($user); // locked from DB

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:100', Rule::unique('user', 'email')->ignore($user->id)],
            'password' => ['nullable', 'min:6'],
        ]);

        $payload = [
            'name'  => $data['name'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        // ❌ DO NOT UPDATE ROLE TABLE
        // Admin is not allowed to change role.

        return redirect()
            ->route('admin.users.index', ['type' => $type])
            ->with('success', ucfirst($type) . ' updated successfully.');
    }

    /* -------------------------------------------------
     | 🗑 Soft delete user
     * -------------------------------------------------*/
    public function destroy(User $user)
    {
        $this->authorizeUser($user);

        $type = $this->getManagedType($user);

        // Soft delete only
        $user->deleted = 1;
        $user->save();

        return redirect()
            ->route('admin.users.index', ['type' => $type])
            ->with('success', ucfirst($type) . ' deleted (soft delete).');
    }

    /* -------------------------------------------------
     | 🔐 Authorization helpers
     * -------------------------------------------------*/
    protected function authorizeUser(User $user)
    {
        $companyId = auth()->user()->company_id;

        if ($user->company_id !== $companyId || $user->deleted) {
            abort(404);
        }

        // Admin can ONLY manage agents and clients
        if (
            !$user->hasRole('agent', $companyId) &&
            !$user->hasRole('client', $companyId)
        ) {
            abort(403, 'Admin can only manage agents and clients.');
        }
    }

    /**
     * Determine whether the user is agent or client
     * (locked from DB)
     */
    protected function getManagedType(User $user): string
    {
        $companyId = auth()->user()->company_id;

        if ($user->hasRole('agent', $companyId)) {
            return 'agent';
        }

        if ($user->hasRole('client', $companyId)) {
            return 'client';
        }

        abort(403);
    }
}
