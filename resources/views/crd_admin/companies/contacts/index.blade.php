@extends('layouts.app')

@section('header')
    Contacts — {{ $company->name }}
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
  <div class="mb-4 flex justify-between items-center">
    <div>
      <h2 class="text-lg font-semibold">Contacts for {{ $company->name }}</h2>
      <p class="text-sm text-gray-500">All contacts associated with this company.</p>
    </div>

    <form method="GET" class="flex gap-2">
      <input type="search" name="q" placeholder="Search name / email / phone" value="{{ request('q') }}" class="rounded border px-3 py-2">
      <button type="submit" class="px-3 py-2 rounded bg-blue-600 text-white">Search</button>
    </form>
  </div>

  <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y">
      <thead class="bg-gray-50 dark:bg-gray-700">
        <tr>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Name</th>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Email</th>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Phone</th>
          <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Title</th>
          <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">Added</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        @forelse($contacts as $c)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
          <td class="px-4 py-3 text-sm">{{ $c->name }}</td>
          <td class="px-4 py-3 text-sm">{{ $c->email }}</td>
          <td class="px-4 py-3 text-sm">{{ $c->phone }}</td>
          <td class="px-4 py-3 text-sm">{{ $c->title }}</td>
          <td class="px-4 py-3 text-right text-sm text-gray-500">{{ $c->created_at->format('Y-m-d') }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="px-4 py-6 text-center text-gray-500">No contacts found.</td>
        </tr>
        @endforelse
      </tbody>
    </table>

    <div class="p-4">
      {{ $contacts->links() }}
    </div>
  </div>
</div>
@endsection
