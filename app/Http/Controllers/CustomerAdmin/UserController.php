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
    public function index()
    {
        $companyId = auth()->user()->company_id;
        $users = User::where('company_id', $companyId)->where('deleted',0)->latest()->paginate(15);
        return view('customer_admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('customer_admin.users.create');
    }

    public function store(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $data = $request->validate([
            'name'  => ['required','string','max:100'],
            'email' => ['required','email','max:100', Rule::unique('user','email')],
            'password' => ['required','min:6'],
            'role_type' => ['required', Rule::in(['admin','agent','viewer','customer_admin'])],
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
            'created_by' => auth()->id(),
            'is_primary_admin' => $data['role_type'] === 'admin' ? 1 : 0,
        ]);

        return redirect()->route('customer-admin.users.index')->with('success','User created.');
    }

    public function edit(User $user)
    {
        $this->authorizeUser($user);
        return view('customer_admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeUser($user);

        $data = $request->validate([
            'name'  => ['required','string','max:100'],
            'email' => ['required','email','max:100', Rule::unique('user','email')->ignore($user->id)],
            'password' => ['nullable','min:6'],
        ]);

        $payload = [
            'name'  => $data['name'],
            'email' => $data['email'],
        ];
        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        return redirect()->route('customer-admin.users.index')->with('success','User updated.');
    }

    public function destroy(User $user)
    {
        $this->authorizeUser($user);

        DB::transaction(function () use ($user) {
            \App\Models\Role::where('user_id', $user->id)->delete();

            if (Schema::hasColumn('tickets', 'created_by')) {
                \App\Models\Ticket::where('created_by', $user->id)->delete();
            }

            if (Schema::hasColumn('tickets', 'assignee_id')) {
                \App\Models\Ticket::where('assignee_id', $user->id)->update(['assignee_id' => null]);
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

    public function assignRole(Request $request, User $user)
    {
        $this->authorizeUser($user);

        $data = $request->validate([
            'role_type' => ['required', Rule::in(['admin','agent','viewer','customer_admin'])],
        ]);

        Role::updateOrCreate(
            ['user_id' => $user->id, 'company_id' => $user->company_id],
            [
                'role_type' => $data['role_type'],
                'created_by'=> auth()->id(),
                'is_primary_admin' => $data['role_type'] === 'admin' ? 1 : 0,
            ]
        );

        return back()->with('success','Role updated.');
    }

    protected function authorizeUser(User $user)
    {
        if ($user->company_id !== auth()->user()->company_id || $user->deleted) {
            abort(403);
        }
    }
}
