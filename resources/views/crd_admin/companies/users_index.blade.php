@extends('layouts.app')

@section('title', 'Users for ' . $company->name)

@section('content')

@php
    // Clone original collection
    $filteredUsers = $users;

    // ✅ Search filter
    if(request('search')) {
        $search = strtolower(request('search'));
        $filteredUsers = $filteredUsers->filter(function ($u) use ($search) {
            return str_contains(strtolower($u->name), $search)
                || str_contains(strtolower($u->email), $search);
        });
    }

    // ✅ Role filter
    if(request('role')) {
        $role = request('role');
        $filteredUsers = $filteredUsers->filter(function ($u) use ($role) {
            return ($u->roles->first()->role_type ?? null) === $role;
        });
    }

    // ✅ Reindex filtered results
    $filteredUsers = $filteredUsers->values();
@endphp

<div class="mx-auto px-4 sm:px-6 lg:px-8 py-6 max-w-5xl">

    {{-- Page Title --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="min-w-0">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-gray-100 truncate">
                Users — {{ $company->name }}
            </h1>
        </div>

        <div class="w-full sm:w-auto">
            <a href="{{ route('crd-admin.company-users.create', $company->id) }}"
               class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow transition text-sm">
               + Add User
            </a>
        </div>
    </div>

    {{-- Search + Filter Card --}}
    <div class="bg-white dark:bg-gray-800 shadow p-4 sm:p-5 rounded-lg mb-6 border dark:border-gray-700">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                <input type="text" name="search" placeholder="Search name or email..."
                       value="{{ request('search') }}"
                       class="mt-1 w-full px-3 py-2 rounded-md border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter by Role</label>
                <select name="role"
                        class="mt-1 w-full px-3 py-2 rounded-md border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:outline-none">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="agent" {{ request('role') == 'agent' ? 'selected' : '' }}>Agent</option>
                    <option value="viewer" {{ request('role') == 'viewer' ? 'selected' : '' }}>Viewer</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md shadow focus:outline-none focus:ring-2 focus:ring-blue-400">
                    Apply
                </button>
            </div>
        </form>
    </div>

    {{-- Flash --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 dark:bg-green-800 dark:border-green-700 dark:text-green-200 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- Users container --}}
    <div class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-lg shadow overflow-hidden">

        {{-- If no users --}}
        @if($filteredUsers->isEmpty())
            <p class="text-gray-600 dark:text-gray-300 p-6 text-center">
                No users found.
            </p>

        @else

            {{-- Desktop table (md+) --}}
            <div class="hidden md:block w-full overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr class="text-left text-gray-700 dark:text-gray-300">
                            <th class="px-4 py-3 font-semibold w-12">#</th>
                            <th class="px-4 py-3 font-semibold">Name</th>
                            <th class="px-4 py-3 font-semibold">Email</th>
                            <th class="px-4 py-3 font-semibold">Role</th>
                            <th class="px-4 py-3 text-center font-semibold">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($filteredUsers as $index => $user)
                            @php $roleType = $user->roles->first()->role_type ?? '—'; @endphp
                            <tr class="border-t dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-4 py-3 align-top">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-medium align-top">{{ $user->name }}</td>
                                <td class="px-4 py-3 align-top">{{ $user->email }}</td>
                                <td class="px-4 py-3 capitalize align-top">{{ $roleType }}</td>

                                <td class="px-4 py-3 text-center align-top">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('crd-admin.company-users.edit', [$company->id, $user->id]) }}"
                                           class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded text-sm focus:outline-none focus:ring-2 focus:ring-yellow-300">
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('crd-admin.company-users.delete', [$company->id, $user->id]) }}"
                                              onsubmit="return confirmDelete(event)" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-sm focus:outline-none focus:ring-2 focus:ring-red-300">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile list (md:hidden) --}}
            <div class="md:hidden divide-y">
                @foreach($filteredUsers as $index => $user)
                    @php $roleType = $user->roles->first()->role_type ?? '—'; @endphp
                    <div class="px-4 py-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="font-medium text-gray-900 dark:text-gray-100 truncate">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</div>
                                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">Role: <span class="font-semibold">{{ strtoupper($roleType) }}</span></div>
                            </div>

                            <div class="flex-shrink-0 flex flex-col items-end gap-2">
                                <a href="{{ route('crd-admin.company-users.edit', [$company->id, $user->id]) }}" class="w-full sm:w-auto inline-block px-3 py-2 rounded bg-yellow-500 hover:bg-yellow-600 text-white text-sm text-center">Edit</a>

                                <form method="POST"
                                      action="{{ route('crd-admin.company-users.delete', [$company->id, $user->id]) }}"
                                      onsubmit="return confirmDelete(event)" class="w-full sm:w-auto">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-3 py-2 rounded bg-red-600 hover:bg-red-700 text-white text-sm">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        @endif

    </div>

    {{-- Back link --}}
    <div class="mt-6">
        <a href="{{ route('crd-admin.companies.index') }}" class="text-blue-500 hover:underline">&larr; Back to Companies</a>
    </div>

</div>

<script>
function confirmDelete(e) {
  if (!confirm('Are you sure? This action cannot be undone.')) {
    e.preventDefault();
    return false;
  }
  return true;
}
</script>

@endsection
