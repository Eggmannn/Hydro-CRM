@extends('layouts.app')

@section('header')
  Admin - Users
@endsection

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

  {{-- Success --}}
  @if(session('success'))
    <div class="mb-4 p-3 rounded-lg border bg-green-50 border-green-200 text-green-800">
      {{ session('success') }}
    </div>
  @endif

  {{-- Header + Tabs --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div class="min-w-0">
      <h1 class="text-2xl font-semibold truncate">Users</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 truncate">
        Manage agents and clients for {{ auth()->user()->company->name ?? 'your company' }}.
      </p>

      {{-- Tabs --}}
      <div class="mt-4 inline-flex rounded-xl bg-gray-100 dark:bg-gray-800 p-1">
        <a href="{{ route('admin.users.index', ['type' => 'agent']) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition
                  {{ ($type ?? 'agent') === 'agent'
                      ? 'bg-white dark:bg-gray-700 shadow text-gray-900 dark:text-white'
                      : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
          Agents
        </a>

        <a href="{{ route('admin.users.index', ['type' => 'client']) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition
                  {{ ($type ?? 'agent') === 'client'
                      ? 'bg-white dark:bg-gray-700 shadow text-gray-900 dark:text-white'
                      : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
          Clients
        </a>
      </div>
    </div>

    {{-- Search + Create --}}
    <div class="flex w-full sm:w-auto flex-col sm:flex-row sm:items-center gap-3">
      <form method="GET"
            class="flex flex-col sm:flex-row sm:items-center gap-2 w-full sm:w-auto"
            aria-label="Filter users">

        <input type="hidden" name="type" value="{{ $type ?? 'agent' }}">

        <input
          name="q"
          value="{{ request('q') }}"
          placeholder="Search name or email..."
          class="w-full sm:w-auto flex-1 rounded-lg border px-3 py-2 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        />

        <button type="submit"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
          Filter
        </button>
      </form>

      <a href="{{ route('admin.users.create', ['type' => $type ?? 'agent']) }}"
         class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-400">
        + New {{ ($type ?? 'agent') === 'client' ? 'Client' : 'Agent' }}
      </a>
    </div>
  </div>

  {{-- Table --}}
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">

    <div class="hidden md:block w-full overflow-x-auto">
      <table class="min-w-full divide-y">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Name</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Email</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Created</th>
            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-200">Actions</th>
          </tr>
        </thead>

        <tbody class="divide-y">
          @forelse($users as $u)
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
            <td class="px-4 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
              {{ $u->name }}
            </td>

            <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">
              {{ $u->email }}
            </td>

            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
              {{ optional($u->created_at)->diffForHumans() }}
            </td>

            <td class="px-4 py-4 text-right">
              <div class="inline-flex items-center gap-2">
                <a href="{{ route('admin.users.edit', $u) }}"
                   class="px-3 py-1 rounded bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-300">
                  Edit
                </a>

                <form action="{{ route('admin.users.destroy', $u) }}"
                      method="POST"
                      onsubmit="return confirmDelete(event)"
                      class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                          class="px-3 py-1 rounded bg-red-600 text-white text-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                    Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
              No {{ ($type ?? 'agent') }} users found.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Mobile --}}
    <div class="md:hidden divide-y">
      @forelse($users as $u)
      <div class="px-4 py-4">
        <div class="flex items-start justify-between gap-3">
          <div class="flex-1 min-w-0">
            <div class="font-semibold truncate">{{ $u->name }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $u->email }}</div>
            <div class="text-xs text-gray-400 mt-2">
              Created {{ optional($u->created_at)->diffForHumans() }}
            </div>
          </div>

          <div class="flex-shrink-0 flex flex-col items-end gap-2">
            <a href="{{ route('admin.users.edit', $u) }}"
               class="w-full inline-block px-3 py-2 rounded bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-300">
              Edit
            </a>

            <form action="{{ route('admin.users.destroy', $u) }}"
                  method="POST"
                  onsubmit="return confirmDelete(event)"
                  class="inline w-full">
              @csrf
              @method('DELETE')
              <button type="submit"
                      class="w-full px-3 py-2 rounded bg-red-600 text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
                Delete
              </button>
            </form>
          </div>
        </div>
      </div>
      @empty
      <div class="px-4 py-8 text-center text-gray-500">
        No {{ ($type ?? 'agent') }} users found.
      </div>
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
  if (!confirm('Soft delete this user?')) {
    e.preventDefault();
    return false;
  }
  return true;
}
</script>
@endsection
