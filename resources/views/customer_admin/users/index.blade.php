@extends('layouts.app')

@section('header')
  Users
@endsection

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div class="min-w-0">
      <h1 class="text-2xl font-semibold truncate">Users</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 truncate">Manage users for {{ auth()->user()->company->name ?? 'your company' }}.</p>
    </div>

    <div class="flex w-full sm:w-auto flex-col sm:flex-row sm:items-center gap-3">
      <form method="GET"
      class="flex flex-col sm:flex-row w-full sm:w-auto items-stretch sm:items-center gap-2"
      aria-label="Search users">

    <input
      name="q"
      value="{{ request('q') }}"
      placeholder="Search name or email..."
      class="w-full sm:w-auto rounded-lg border px-3 py-2 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
    />

    <select
      name="role"
      class="w-full sm:w-auto rounded-lg border px-3 py-2 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-sm"
    >
      <option value="">All roles</option>
      <option value="customer_admin" {{ request('role')==='customer_admin' ? 'selected' : '' }}>Customer Admin</option>
      <option value="admin" {{ request('role')==='admin' ? 'selected' : '' }}>Admin</option>
      <option value="agent" {{ request('role')==='agent' ? 'selected' : '' }}>Agent</option>
      <option value="viewer" {{ request('role')==='viewer' ? 'selected' : '' }}>Viewer</option>
    </select>

    <button type="submit"
      class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
      Search
    </button>
</form>


      <a href="{{ route('customer-admin.users.create') }}"
         class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-400"
         aria-label="Create new user">
        + New User
      </a>
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">

    {{-- Desktop table (scrollable on small widths) --}}
    <div class="hidden md:block w-full overflow-x-auto">
      <table class="min-w-full divide-y">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Name</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Email</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Role</th>
            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-200">Actions</th>
          </tr>
        </thead>

        <tbody class="divide-y">
          @forelse($users as $u)
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
            <td class="px-4 py-4 text-sm">
              <div class="font-medium text-gray-900 dark:text-gray-100 truncate">{{ $u->name }}</div>
              <div class="text-xs text-gray-400 truncate">{{ $u->company->name ?? '' }}</div>
            </td>

            <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300 truncate">
              {{ $u->email }}
            </td>

            <td class="px-4 py-4 text-sm">
              @php
                $role = method_exists($u, 'primaryRole') ? optional($u->primaryRole())->role_type : ($u->role_type ?? null);
              @endphp

              @if($role === 'customer_admin')
                <span class="px-2 py-1 rounded text-xs font-semibold bg-indigo-50 text-indigo-800">Customer Admin</span>
              @elseif($role === 'admin')
                <span class="px-2 py-1 rounded text-xs font-semibold bg-blue-50 text-blue-800">Admin</span>
              @elseif($role === 'agent')
                <span class="px-2 py-1 rounded text-xs font-semibold bg-yellow-50 text-yellow-800">Agent</span>
              @elseif($role === 'viewer')
                <span class="px-2 py-1 rounded text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">Viewer</span>
              @else
                <span class="px-2 py-1 rounded text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">—</span>
              @endif
            </td>

            <td class="px-4 py-4 text-right">
              <div class="inline-flex items-center gap-2">
                <a href="{{ route('customer-admin.users.edit', $u) }}"
                   class="px-3 py-1 rounded bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-300">
                   Edit
                </a>

                <form action="{{ route('customer-admin.users.destroy', $u) }}" method="POST" class="inline" onsubmit="return confirmDelete(event)">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="px-3 py-1 rounded bg-red-600 text-white text-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">Delete</button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="px-4 py-8 text-center text-gray-500">No users found.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Mobile list --}}
    <div class="md:hidden divide-y">
      @forelse($users as $u)
      <div class="px-4 py-3">
        <div class="flex items-start justify-between gap-3">
          <div class="flex-1 min-w-0">
            <div class="font-semibold text-gray-900 dark:text-gray-100 truncate">{{ $u->name }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate">{{ $u->email }}</div>

            <div class="mt-2">
              @php $role = method_exists($u, 'primaryRole') ? optional($u->primaryRole())->role_type : ($u->role_type ?? null); @endphp
              @if($role)
                <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">{{ strtoupper($role) }}</span>
              @endif
            </div>
          </div>

          <div class="flex-shrink-0 flex flex-col items-end gap-2">
            <a href="{{ route('customer-admin.users.edit', $u) }}" class="w-full sm:w-auto inline-block px-3 py-2 rounded bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-300">
              Edit
            </a>

            <form action="{{ route('customer-admin.users.destroy', $u) }}" method="POST" onsubmit="return confirmDelete(event)" class="inline w-full sm:w-auto">
              @csrf
              @method('DELETE')
              <button type="submit" class="w-full sm:w-auto px-3 py-2 rounded bg-red-600 text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-300">Delete</button>
            </form>
          </div>
        </div>
      </div>
      @empty
      <div class="px-4 py-6 text-center text-gray-500">No users found.</div>
      @endforelse
    </div>

    {{-- Pagination --}}
    <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="text-sm text-gray-500">
          Showing <strong>{{ $users->firstItem() ?? 0 }}</strong> to <strong>{{ $users->lastItem() ?? 0 }}</strong> of <strong>{{ $users->total() }}</strong>
        </div>

        <div class="w-full sm:w-auto">
          {{ $users->withQueryString()->links() }}
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function confirmDelete(e) {
  if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
    e.preventDefault();
    return false;
  }
  return true;
}
</script>
@endsection
