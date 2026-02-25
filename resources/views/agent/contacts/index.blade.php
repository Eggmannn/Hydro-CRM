@extends('layouts.app')

@section('header')
  Contacts
@endsection

@section('content')
<div class="max-w-6xl mx-auto">

  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
      <h1 class="text-2xl font-semibold">Contacts</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
        All contacts for {{ auth()->user()->company->name ?? 'your company' }}. Manage and search quickly.
      </p>
    </div>

    <div class="flex items-center gap-3">
      <form method="GET" class="flex items-center gap-2" aria-label="Search contacts">
        <input
          name="q"
          value="{{ request('q') }}"
          placeholder="Search name / email / phone..."
          class="rounded-lg border px-3 py-2 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        />

        <button type="submit"
                class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700">
          Search
        </button>
      </form>

      <a href="{{ route('agent.contacts.create') }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md text-sm font-medium">
        + New Contact
      </a>
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    {{-- Table for md+ --}}
    <div class="hidden md:block">
      <table class="min-w-full divide-y">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Name</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Email</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Phone</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Title</th>
            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-200">Actions</th>
          </tr>
        </thead>

        <tbody class="divide-y">
          @forelse($contacts as $c)
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
            <td class="px-4 py-4 text-sm">
              <div class="font-medium text-gray-900 dark:text-gray-100">{{ $c->name }}</div>
            </td>

            <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">
              {{ $c->email ?? '—' }}
            </td>

            <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">
              {{ $c->phone ?? '—' }}
            </td>

            <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">
              {{ $c->title ?? '—' }}
            </td>

            <td class="px-4 py-4 text-right">
              <div class="inline-flex items-center gap-2">
                <a href="{{ route('agent.contacts.edit', $c->id) }}"
                   class="px-3 py-1 rounded bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-sm hover:bg-gray-100">
                  Edit
                </a>

                <form action="{{ route('agent.contacts.destroy', $c->id) }}"
                      method="POST"
                      onsubmit="return confirmDelete(event)"
                      class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                          class="px-3 py-1 rounded bg-red-600 text-white text-sm hover:bg-red-700">
                    Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
              No contacts found.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Mobile list --}}
    <div class="md:hidden divide-y">
      @forelse($contacts as $c)
      <div class="px-4 py-3">
        <div class="flex items-start justify-between gap-3">
          <div class="flex-1 min-w-0">
            <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $c->name }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
              {{ $c->email ?? '—' }} • {{ $c->phone ?? '—' }}
            </div>
            @if($c->title)
              <div class="text-xs text-gray-400 mt-1">{{ $c->title }}</div>
            @endif
          </div>

          <div class="flex-shrink-0 flex flex-col items-end gap-2">
            <a href="{{ route('agent.contacts.edit', $c->id) }}"
               class="inline-block px-3 py-1 rounded bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-sm">
              Edit
            </a>

            <form action="{{ route('agent.contacts.destroy', $c->id) }}"
                  method="POST"
                  onsubmit="return confirmDelete(event)"
                  class="inline">
              @csrf
              @method('DELETE')
              <button type="submit"
                      class="px-3 py-1 rounded bg-red-600 text-white text-sm">
                Delete
              </button>
            </form>
          </div>
        </div>
      </div>
      @empty
      <div class="px-4 py-6 text-center text-gray-500">
        No contacts found.
      </div>
      @endforelse
    </div>

    {{-- Pagination --}}
    <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
      <div class="flex items-center justify-between">
        <div class="text-sm text-gray-500">
          Showing <strong>{{ $contacts->firstItem() ?? 0 }}</strong>
          to <strong>{{ $contacts->lastItem() ?? 0 }}</strong>
          of <strong>{{ $contacts->total() }}</strong>
        </div>

        <div>
          {{ $contacts->withQueryString()->links() }}
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function confirmDelete(e) {
  if (!confirm('Are you sure you want to delete this contact? This action cannot be undone.')) {
    e.preventDefault();
    return false;
  }
  return true;
}
</script>
@endsection
