@extends('layouts.app')

@section('header')
    Ticket #{{ $ticket->id }} — {{ $company->name }}
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <div class="flex justify-between items-start">
      <div>
        <h3 class="text-xl font-semibold">{{ $ticket->subject }}</h3>
        <p class="text-sm text-gray-500 mt-1">Created: {{ $ticket->created_at->format('Y-m-d H:i') }}</p>
      </div>
      <div class="text-right">
        <div class="text-sm text-gray-600">Priority: <span class="font-semibold">{{ ucfirst($ticket->priority) }}</span></div>
        <div class="mt-2">
          <span class="px-2 py-1 rounded text-sm {{ $ticket->status==='open' ? 'bg-green-100 text-green-800' : ($ticket->status==='pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700') }}">
            {{ ucfirst($ticket->status) }}
          </span>
        </div>
      </div>
    </div>

    <hr class="my-4">

    <div class="prose dark:prose-invert">
      {!! nl2br(e($ticket->body)) !!}
    </div>

    <hr class="my-4">

    <div class="text-sm text-gray-600">
      <div><strong>Contact:</strong> {{ optional($ticket->contact)->name ?? '-' }} ({{ optional($ticket->contact)->email ?? '-' }})</div>
      <div class="mt-1"><strong>Assignee:</strong> {{ optional($ticket->assignee)->name ?? '-' }}</div>
    </div>
  </div>

  {{-- Comments (if you have them) --}}
  @if(method_exists($ticket, 'comments'))
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
    <h4 class="font-semibold mb-3">Comments</h4>
    @forelse($ticket->comments as $c)
      <div class="border-b py-3">
        <div class="text-sm font-medium">{{ optional($c->user)->name ?? '—' }} <span class="text-xs text-gray-500">• {{ $c->created_at->diffForHumans() }}</span></div>
        <div class="text-sm text-gray-700 mt-1">{{ $c->body }}</div>
      </div>
    @empty
      <div class="text-gray-500">No comments yet.</div>
    @endforelse
  </div>
  @endif

  <div class="flex justify-between">
    <a href="{{ route('crd-admin.companies.tickets.index', $company) }}" class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700">Back to tickets</a>
  </div>
</div>
@endsection
