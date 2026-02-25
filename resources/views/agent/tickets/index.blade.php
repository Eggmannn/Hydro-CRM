@extends('layouts.app')

@section('header')
All Tickets
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-6 px-4 sm:px-0">

  {{-- Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
        All Tickets
      </h1>
      <p class="text-sm text-gray-500 dark:text-gray-400">
        All tickets in your company. You can assign any to yourself or other agents.
      </p>
    </div>

    <a href="{{ route('agent.tickets.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
              bg-blue-600 text-white text-sm hover:bg-blue-700">
      + New Ticket
    </a>
  </div>

  {{-- Filters --}}
  <form method="GET"
        class="flex flex-wrap items-center gap-2">

    <input
      name="q"
      value="{{ request('q') }}"
      placeholder="Search subject or contact..."
      class="rounded-lg border px-3 py-2 text-sm
             bg-white dark:bg-gray-800
             text-gray-900 dark:text-gray-100
             border-gray-300 dark:border-gray-600
             placeholder-gray-400 dark:placeholder-gray-500"
    />

    <select name="status"
            class="rounded-lg border px-3 py-2 text-sm
                   bg-white dark:bg-gray-800
                   text-gray-900 dark:text-gray-100
                   border-gray-300 dark:border-gray-600">
      <option value="">All status</option>
      <option value="open" {{ request('status')=='open'?'selected':'' }}>Open</option>
      <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
      <option value="closed" {{ request('status')=='closed'?'selected':'' }}>Closed</option>
    </select>

    <select name="priority"
            class="rounded-lg border px-3 py-2 text-sm
                   bg-white dark:bg-gray-800
                   text-gray-900 dark:text-gray-100
                   border-gray-300 dark:border-gray-600">
      <option value="">All priority</option>
      <option value="low" {{ request('priority')=='low'?'selected':'' }}>Low</option>
      <option value="normal" {{ request('priority')=='normal'?'selected':'' }}>Normal</option>
      <option value="high" {{ request('priority')=='high'?'selected':'' }}>High</option>
    </select>

    <button type="submit"
            class="px-3 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
      Filter
    </button>
  </form>

  {{-- Desktop Table --}}
  <div class="hidden md:block bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
      <thead class="bg-gray-50 dark:bg-gray-700">
        <tr>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Subject</th>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Contact</th>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Assignee</th>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Priority</th>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Status</th>
          <th class="px-4 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-200">Action</th>
        </tr>
      </thead>

      <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
        @forelse($tickets as $t)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
          <td class="px-4 py-3">
            <a href="{{ route('agent.tickets.show',$t->id) }}"
               class="font-semibold text-blue-600 dark:text-blue-400 hover:underline">
              {{ \Str::limit($t->subject,60) }}
            </a>
          </td>

          <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
            {{ optional($t->contact)->name ?? '—' }}
          </td>

          <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
            {{ optional($t->assignee)->name ?? 'Unassigned' }}
          </td>

          <td class="px-4 py-3 text-sm">
            @if($t->priority==='high')
              <span class="px-2 py-1 rounded bg-red-100 dark:bg-red-900/40
                           text-red-700 dark:text-red-300 text-xs font-semibold">HIGH</span>
            @elseif($t->priority==='normal')
              <span class="px-2 py-1 rounded bg-yellow-100 dark:bg-yellow-900/40
                           text-yellow-700 dark:text-yellow-300 text-xs font-semibold">NORMAL</span>
            @else
              <span class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-700
                           text-gray-700 dark:text-gray-300 text-xs font-semibold">LOW</span>
            @endif
          </td>

          <td class="px-4 py-3 text-sm">
            @if($t->status==='open')
              <span class="px-2 py-1 rounded bg-green-100 dark:bg-green-900/40
                           text-green-700 dark:text-green-300 text-xs font-semibold">OPEN</span>
            @elseif($t->status==='pending')
              <span class="px-2 py-1 rounded bg-yellow-100 dark:bg-yellow-900/40
                           text-yellow-700 dark:text-yellow-300 text-xs font-semibold">PENDING</span>
            @else
              <span class="px-2 py-1 rounded bg-gray-200 dark:bg-gray-700
                           text-gray-700 dark:text-gray-300 text-xs font-semibold">CLOSED</span>
            @endif
          </td>

          <td class="px-4 py-3 text-right">
            <a href="{{ route('agent.tickets.show',$t->id) }}"
               class="px-3 py-1 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
              View
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
            No tickets found.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Mobile Cards --}}
  <div class="md:hidden space-y-3">
    @foreach($tickets as $t)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 space-y-2">
      <a href="{{ route('agent.tickets.show',$t->id) }}"
         class="font-semibold text-blue-600 dark:text-blue-400 block">
        {{ \Str::limit($t->subject,80) }}
      </a>

      <div class="text-xs text-gray-500 dark:text-gray-400">
        {{ optional($t->contact)->name ?? '—' }} •
        {{ optional($t->assignee)->name ?? 'Unassigned' }}
      </div>

      <div class="flex gap-2 flex-wrap">
        <span class="px-2 py-0.5 rounded text-xs
                     bg-gray-100 dark:bg-gray-700
                     text-gray-700 dark:text-gray-300">
          {{ strtoupper($t->priority) }}
        </span>

        <span class="px-2 py-0.5 rounded text-xs
                     bg-gray-200 dark:bg-gray-600
                     text-gray-700 dark:text-gray-300">
          {{ strtoupper($t->status) }}
        </span>
      </div>
    </div>
    @endforeach
  </div>

  {{-- Pagination --}}
  <div>
    {{ $tickets->withQueryString()->links() }}
  </div>

</div>
@endsection
