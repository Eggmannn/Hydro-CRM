@extends('layouts.app')

@section('header')
  Tickets
@endsection

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div class="min-w-0">
      <h1 class="text-2xl font-semibold truncate">Tickets</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 truncate">
        All tickets for {{ auth()->user()->company->name ?? 'your company' }} — manage and triage.
      </p>
    </div>

    <div class="flex w-full sm:w-auto flex-col sm:flex-row sm:items-center gap-3">
      <form method="GET" class="flex flex-col sm:flex-row sm:items-center gap-2 w-full sm:w-auto" aria-label="Filter tickets">
        <input
          name="q"
          value="{{ request('q') }}"
          placeholder="Search subject..."
          class="w-full sm:w-auto flex-1 rounded-lg border px-3 py-2 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          aria-label="Search subject"
        />

        <select name="status" class="w-full sm:w-auto rounded-lg border px-3 py-2 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-sm">
          <option value="">All status</option>
          <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
          <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
        </select>

        <button type="submit"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                aria-label="Filter">
          Filter
        </button>
      </form>

      <a href="{{ route('admin.tickets.create') }}"
         class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-400"
         aria-label="Create new ticket">
        + New Ticket
      </a>
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">

    {{-- Desktop table --}}
    <div class="hidden md:block w-full overflow-x-auto">
      <table class="min-w-full divide-y">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Subject</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Assignee</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Priority</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Status</th>
            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-200">Actions</th>
          </tr>
        </thead>

        <tbody class="divide-y">
          @forelse($tickets as $t)
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
            <td class="px-4 py-4 max-w-xl">
              <a href="{{ route('admin.tickets.show', $t) }}"
                 class="font-semibold text-blue-600 dark:text-blue-400 hover:underline truncate block">
                {{ \Illuminate\Support\Str::limit($t->subject, 80) }}
              </a>

              @if($t->body)
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                  {{ \Illuminate\Support\Str::limit($t->body, 100) }}
                </div>
              @endif
            </td>

            <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">
              {{ optional($t->assignee)->name ?? 'Unassigned' }}
            </td>

            <td class="px-4 py-4 text-sm">
              @if($t->priority === 'high')
                <span class="px-2 py-1 rounded text-xs font-semibold bg-red-50 text-red-700">HIGH</span>
              @elseif($t->priority === 'normal')
                <span class="px-2 py-1 rounded text-xs font-semibold bg-yellow-50 text-yellow-800">NORMAL</span>
              @else
                <span class="px-2 py-1 rounded text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">LOW</span>
              @endif
            </td>

            <td class="px-4 py-4 text-sm">
              @if($t->status === 'open')
                <span class="px-2 py-1 rounded text-xs font-semibold bg-green-50 text-green-800">OPEN</span>
              @elseif($t->status === 'pending')
                <span class="px-2 py-1 rounded text-xs font-semibold bg-yellow-50 text-yellow-800">PENDING</span>
              @else
                <span class="px-2 py-1 rounded text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">CLOSED</span>
              @endif
            </td>

            <td class="px-4 py-4 text-right">
              <div class="inline-flex items-center gap-2">
                <a href="{{ route('admin.tickets.show', $t) }}"
                   class="px-3 py-1 rounded bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-300">
                  View
                </a>

                <a href="{{ route('admin.tickets.edit', $t) }}"
                   class="px-3 py-1 rounded bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-300">
                  Edit
                </a>

                <form action="{{ route('admin.tickets.destroy', $t) }}"
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
            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
              No tickets found.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Mobile list --}}
    <div class="md:hidden divide-y">
      @forelse($tickets as $t)
      <div class="px-4 py-3">
        <div class="flex items-start justify-between gap-3">
          <div class="flex-1 min-w-0">
            <a href="{{ route('admin.tickets.show', $t) }}"
               class="font-semibold text-blue-600 dark:text-blue-400 block truncate">
              {{ \Illuminate\Support\Str::limit($t->subject, 80) }}
            </a>

            <div class="mt-2 flex flex-wrap items-center gap-2">
              @if($t->priority === 'high')
                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-red-50 text-red-700">HIGH</span>
              @elseif($t->priority === 'normal')
                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-yellow-50 text-yellow-800">NORMAL</span>
              @else
                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">LOW</span>
              @endif

              @if($t->status === 'open')
                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-green-50 text-green-800">OPEN</span>
              @elseif($t->status === 'pending')
                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-yellow-50 text-yellow-800">PENDING</span>
              @else
                <span class="px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">CLOSED</span>
              @endif
            </div>
          </div>

          <div class="flex-shrink-0 flex flex-col items-end gap-2">
            <a href="{{ route('admin.tickets.show', $t) }}"
               class="w-full sm:w-auto inline-block px-3 py-2 rounded bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-300">
              View
            </a>

            <a href="{{ route('admin.tickets.edit', $t) }}"
               class="w-full sm:w-auto inline-block px-3 py-2 rounded bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-300">
              Edit
            </a>

            <form action="{{ route('admin.tickets.destroy', $t) }}"
                  method="POST"
                  onsubmit="return confirmDelete(event)"
                  class="inline w-full sm:w-auto">
              @csrf
              @method('DELETE')
              <button type="submit"
                      class="w-full sm:w-auto px-3 py-2 rounded bg-red-600 text-white text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
                Delete
              </button>
            </form>
          </div>
        </div>

        <div class="mt-3 flex items-center justify-between text-xs text-gray-400">
          <div>Assigned: {{ optional($t->assignee)->name ?? 'Unassigned' }}</div>
          <div class="whitespace-nowrap">{{ optional($t->created_at)->diffForHumans() }}</div>
        </div>
      </div>
      @empty
      <div class="px-4 py-6 text-center text-gray-500">No tickets found.</div>
      @endforelse
    </div>

    {{-- Pagination --}}
    <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="text-sm text-gray-500">
          Showing <strong>{{ $tickets->firstItem() ?? 0 }}</strong>
          to <strong>{{ $tickets->lastItem() ?? 0 }}</strong>
          of <strong>{{ $tickets->total() }}</strong>
        </div>

        <div class="w-full sm:w-auto">
          {{ $tickets->withQueryString()->links() }}
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function confirmDelete(e) {
  if (!confirm('Are you sure you want to delete this ticket?')) {
    e.preventDefault();
    return false;
  }
  return true;
}
</script>
@endsection
