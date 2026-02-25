@extends('layouts.app')

@section('header')
Ticket #{{ $ticket->id }}
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

  @if(session('success'))
    <div class="rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm">
      {{ session('success') }}
    </div>
  @endif

  {{-- Header --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-semibold">{{ $ticket->subject }}</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
        Created {{ optional($ticket->created_at)->diffForHumans() }}
        @if($ticket->contact)
          • Contact: {{ $ticket->contact->name }}
        @endif
      </p>
    </div>

    <div class="flex items-center gap-2">
      <a href="{{ route('agent.tickets.edit', $ticket->id) }}"
         class="px-3 py-2 rounded bg-yellow-500 text-white text-sm hover:bg-yellow-600">
        Edit
      </a>

      <a href="{{ route('agent.tickets.index') }}"
         class="px-3 py-2 rounded bg-white dark:bg-gray-800 border text-sm">
        ← Back
      </a>
    </div>
  </div>

  {{-- Badges --}}
  <div class="flex gap-2">
    <span class="px-3 py-1 rounded-full text-xs font-semibold
      {{ $ticket->status === 'open' ? 'bg-green-100 text-green-800' :
         ($ticket->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-200 text-gray-800') }}">
      {{ strtoupper($ticket->status) }}
    </span>

    <span class="px-3 py-1 rounded-full text-xs font-semibold
      {{ $ticket->priority === 'high' ? 'bg-red-100 text-red-800' :
         ($ticket->priority === 'normal' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700') }}">
      {{ strtoupper($ticket->priority) }}
    </span>
  </div>

  {{-- Info --}}
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white dark:bg-gray-800 p-4 rounded shadow">
      <div class="text-xs text-gray-500">Assignee</div>
      <div class="font-semibold mt-1">
        {{ optional($ticket->assignee)->name ?? 'Unassigned' }}
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 p-4 rounded shadow">
      <div class="text-xs text-gray-500">Company</div>
      <div class="font-semibold mt-1">
        {{ optional($ticket->company)->name ?? '—' }}
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 p-4 rounded shadow">
      <div class="text-xs text-gray-500">Created</div>
      <div class="font-semibold mt-1">
        {{ optional($ticket->created_at)->format('d M Y H:i') }}
      </div>
    </div>
  </div>

  {{-- Description --}}
  <div class="bg-white dark:bg-gray-800 p-5 rounded shadow">
    <h2 class="font-semibold mb-2">Description</h2>
    <p class="text-sm whitespace-pre-line">
      {{ $ticket->body ?? '—' }}
    </p>
  </div>

  {{-- Comments --}}
  <div class="bg-white dark:bg-gray-800 p-5 rounded shadow">
    <h2 class="font-semibold mb-4">Comments</h2>

    <div class="space-y-4 mb-6">
      @forelse($ticket->comments as $c)
        <div class="flex gap-3">
          <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
            {{ strtoupper(substr(optional($c->user)->name ?? 'U', 0, 1)) }}
          </div>

          <div class="flex-1 bg-gray-50 dark:bg-gray-900 rounded p-3">
            <div class="flex justify-between mb-1">
              <div class="font-semibold text-sm">
                {{ optional($c->user)->name ?? 'User' }}
              </div>
              <div class="text-xs text-gray-400">
                {{ optional($c->created_at)->diffForHumans() }}
              </div>
            </div>

            <div class="text-sm whitespace-pre-line">
              {{ $c->body }}
            </div>
          </div>
        </div>
      @empty
        <div class="text-sm text-gray-500">No comments yet.</div>
      @endforelse
    </div>

    {{-- Add comment --}}
    <form method="POST" action="{{ route('agent.tickets.comment', $ticket->id) }}" class="space-y-3">
      @csrf
      <textarea name="body"
                rows="3"
                required
                placeholder="Write a reply..."
                class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-sm"></textarea>

      <div class="flex justify-end">
        <button type="submit"
                class="px-4 py-2 rounded bg-green-600 text-white text-sm hover:bg-green-700">
          Send Reply
        </button>
      </div>
    </form>
  </div>

</div>
@endsection
