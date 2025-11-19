@extends('layouts.app')

@section('header')
    Tickets — {{ $company->name }}
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
  <div class="mb-4 flex justify-between items-center">
    <div>
      <h2 class="text-lg font-semibold">Tickets for {{ $company->name }}</h2>
      <p class="text-sm text-gray-500">View and filter tickets created for this company.</p>
    </div>

    <form method="GET" class="flex gap-2">
      <select name="status" class="rounded border px-3 py-2">
        <option value="">All status</option>
        <option value="open" {{ request('status')==='open' ? 'selected' : '' }}>Open</option>
        <option value="pending" {{ request('status')==='pending' ? 'selected' : '' }}>Pending</option>
        <option value="closed" {{ request('status')==='closed' ? 'selected' : '' }}>Closed</option>
      </select>
      <button type="submit" class="px-3 py-2 rounded bg-blue-600 text-white">Filter</button>
    </form>
  </div>

  <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y">
      <thead class="bg-gray-50 dark:bg-gray-700">
        <tr>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">#</th>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Subject</th>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Contact</th>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Assignee</th>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Priority</th>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Status</th>
          <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">Created</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        @forelse($tickets as $t)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
          <td class="px-4 py-3 text-sm">{{ $t->id }}</td>
          <td class="px-4 py-3">
            <a href="{{ route('crd-admin.companies.tickets.show', [$company, $t]) }}" class="font-semibold text-blue-600 dark:text-blue-400">
              {{ \Illuminate\Support\Str::limit($t->subject, 80) }}
            </a>
            <div class="text-xs text-gray-500">{{ \Illuminate\Support\Str::limit($t->body, 120) }}</div>
          </td>
          <td class="px-4 py-3 text-sm">{{ optional($t->contact)->name ?? '-' }}</td>
          <td class="px-4 py-3 text-sm">{{ optional($t->assignee)->name ?? '-' }}</td>
          <td class="px-4 py-3 text-sm">{{ ucfirst($t->priority) }}</td>
          <td class="px-4 py-3 text-sm">
            <span class="px-2 py-1 rounded text-xs {{ $t->status==='open' ? 'bg-green-100 text-green-800' : ($t->status==='pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700') }}">
              {{ ucfirst($t->status) }}
            </span>
          </td>
          <td class="px-4 py-3 text-right text-sm text-gray-500">{{ $t->created_at->format('Y-m-d H:i') }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="px-4 py-6 text-center text-gray-500">No tickets found.</td>
        </tr>
        @endforelse
      </tbody>
    </table>

    <div class="p-4">
      {{ $tickets->links() }}
    </div>
  </div>
</div>
@endsection
