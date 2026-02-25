@extends('layouts.app')

@section('header')
    Tickets — {{ $company->name }}
@endsection

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-0">

  {{-- Header + Filter --}}
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
        Tickets for {{ $company->name }}
      </h2>
      <p class="text-sm text-gray-500 dark:text-gray-400">
        View and filter tickets created for this company.
      </p>
    </div>

    <form method="GET" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">

      <select name="status"
        class="w-full sm:w-auto rounded-md border
               bg-white dark:bg-gray-800
               text-gray-900 dark:text-gray-100
               border-gray-300 dark:border-gray-600
               px-3 py-2 text-sm
               focus:outline-none focus:ring-2 focus:ring-blue-500
               transition">

        <option value="">All status</option>
        <option value="open" {{ request('status')==='open' ? 'selected' : '' }}>Open</option>
        <option value="pending" {{ request('status')==='pending' ? 'selected' : '' }}>Pending</option>
        <option value="closed" {{ request('status')==='closed' ? 'selected' : '' }}>Closed</option>
      </select>

      <button type="submit"
        class="w-full sm:w-auto px-4 py-2 rounded-md
               bg-blue-600 hover:bg-blue-700
               text-white text-sm font-medium transition">
        Filter
      </button>
    </form>
  </div>

  <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">

    {{-- ================= DESKTOP TABLE ================= --}}
    <div class="hidden md:block overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">#</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Subject</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Contact</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Assignee</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Priority</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Status</th>
            <th class="px-4 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-200">Created</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          @forelse($tickets as $t)
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
            <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">{{ $t->id }}</td>

            <td class="px-4 py-3">
              <a href="{{ route('crd-admin.companies.tickets.show', [$company, $t]) }}"
                 class="font-semibold text-blue-600 dark:text-blue-400">
                {{ \Illuminate\Support\Str::limit($t->subject, 80) }}
              </a>
              <div class="text-xs text-gray-500 dark:text-gray-400">
                {{ \Illuminate\Support\Str::limit($t->body, 120) }}
              </div>
            </td>

            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
              {{ optional($t->contact)->name ?? '-' }}
            </td>

            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
              {{ optional($t->assignee)->name ?? '-' }}
            </td>

            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
              {{ ucfirst($t->priority) }}
            </td>

            <td class="px-4 py-3 text-sm">
              <span class="px-2 py-1 rounded text-xs
                {{ $t->status==='open'
                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                    : ($t->status==='pending'
                        ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                        : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300') }}">
                {{ ucfirst($t->status) }}
              </span>
            </td>

            <td class="px-4 py-3 text-right text-sm text-gray-500 dark:text-gray-400">
              {{ $t->created_at->format('Y-m-d H:i') }}
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
              No tickets found.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- ================= MOBILE CARDS ================= --}}
    <div class="md:hidden divide-y divide-gray-200 dark:divide-gray-700">
      @forelse($tickets as $t)
        <div class="p-4 space-y-2">
          <div class="flex justify-between items-start">
            <div>
              <a href="{{ route('crd-admin.companies.tickets.show', [$company, $t]) }}"
                 class="font-semibold text-blue-600 dark:text-blue-400">
                {{ $t->subject }}
              </a>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                #{{ $t->id }} · {{ $t->created_at->format('d M Y') }}
              </p>
            </div>

            <span class="px-2 py-1 rounded text-xs
              {{ $t->status==='open'
                  ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                  : ($t->status==='pending'
                      ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                      : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300') }}">
              {{ ucfirst($t->status) }}
            </span>
          </div>

          <p class="text-sm text-gray-700 dark:text-gray-300">
            {{ \Illuminate\Support\Str::limit($t->body, 100) }}
          </p>

          <div class="flex flex-wrap gap-3 text-xs text-gray-500 dark:text-gray-400">
            <span><strong>Priority:</strong> {{ ucfirst($t->priority) }}</span>
            <span><strong>Assignee:</strong> {{ optional($t->assignee)->name ?? '-' }}</span>
          </div>
        </div>
      @empty
        <div class="p-6 text-center text-gray-500 dark:text-gray-400">
          No tickets found.
        </div>
      @endforelse
    </div>

    {{-- Pagination --}}
    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
      {{ $tickets->withQueryString()->links() }}
    </div>

  </div>
</div>
@endsection
