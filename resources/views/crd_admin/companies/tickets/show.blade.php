@extends('layouts.app')

@section('header')
    Ticket #{{ $ticket->id }} — {{ $company->name }}
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-4">

  {{-- Ticket card --}}
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">

      {{-- Left --}}
      <div>
        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
          {{ $ticket->subject }}
        </h3>

        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
          Created: {{ $ticket->created_at->format('Y-m-d H:i') }}
        </p>
      </div>

      {{-- Right --}}
      <div class="text-left sm:text-right space-y-2">
        <div class="text-sm text-gray-600 dark:text-gray-300">
          Priority:
          <span class="font-semibold text-gray-900 dark:text-gray-100">
            {{ ucfirst($ticket->priority) }}
          </span>
        </div>

        <span class="inline-block px-2 py-1 rounded text-sm font-medium
          {{ $ticket->status==='open'
              ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300'
              : ($ticket->status==='pending'
                  ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300'
                  : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200') }}">
          {{ ucfirst($ticket->status) }}
        </span>
      </div>
    </div>

    <hr class="my-4 border-gray-200 dark:border-gray-700">

    {{-- Body --}}
    <div class="prose max-w-none
                prose-gray dark:prose-invert
                text-gray-800 dark:text-gray-200">
      {!! nl2br(e($ticket->body)) !!}
    </div>

    <hr class="my-4 border-gray-200 dark:border-gray-700">

    {{-- Meta --}}
    <div class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
      <div>
        <strong class="text-gray-800 dark:text-gray-100">Contact:</strong>
        {{ optional($ticket->contact)->name ?? '-' }}
        ({{ optional($ticket->contact)->email ?? '-' }})
      </div>
      <div>
        <strong class="text-gray-800 dark:text-gray-100">Assignee:</strong>
        {{ optional($ticket->assignee)->name ?? '-' }}
      </div>
    </div>
  </div>

  {{-- Comments --}}
  @if(method_exists($ticket, 'comments'))
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
    <h4 class="font-semibold mb-3 text-gray-900 dark:text-gray-100">
      Comments
    </h4>

    @forelse($ticket->comments as $c)
      <div class="border-b border-gray-200 dark:border-gray-700 py-3">
        <div class="text-sm font-medium text-gray-800 dark:text-gray-100">
          {{ optional($c->user)->name ?? '—' }}
          <span class="text-xs text-gray-500 dark:text-gray-400">
            • {{ $c->created_at->diffForHumans() }}
          </span>
        </div>

        <div class="text-sm text-gray-700 dark:text-gray-300 mt-1">
          {{ $c->body }}
        </div>
      </div>
    @empty
      <div class="text-gray-500 dark:text-gray-400">
        No comments yet.
      </div>
    @endforelse
  </div>
  @endif

  {{-- Back --}}
  <div>
    <a href="{{ route('crd-admin.companies.tickets.index', $company) }}"
       class="inline-block px-4 py-2 rounded
              bg-gray-200 hover:bg-gray-300
              dark:bg-gray-700 dark:hover:bg-gray-600
              text-gray-800 dark:text-gray-100 transition">
      ← Back to tickets
    </a>
  </div>

</div>
@endsection
