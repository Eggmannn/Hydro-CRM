@extends('layouts.app')

@section('header')
  Admin - Ticket #{{ $ticket->id }}
@endsection

@section('content')
<div class="max-w-6xl mx-auto">

  {{-- Success --}}
  @if(session('success'))
    <div class="mb-4 p-3 rounded-lg border bg-green-50 border-green-200 text-green-800 dark:bg-green-900/30 dark:border-green-800 dark:text-green-200">
      {{ session('success') }}
    </div>
  @endif

  {{-- Errors --}}
  @if($errors->any())
    <div class="mb-4 p-3 rounded-lg border bg-red-50 border-red-200 text-red-800 dark:bg-red-900/30 dark:border-red-800 dark:text-red-200">
      <div class="font-semibold mb-2">Please fix the following:</div>
      <ul class="list-disc list-inside text-sm space-y-1">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT: Ticket + Comments --}}
    <div class="lg:col-span-2 space-y-6">

      {{-- Ticket Card --}}
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">

          <div class="min-w-0">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
              {{ $ticket->subject }}
            </h2>

            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
              Created {{ optional($ticket->created_at)->diffForHumans() }}
              <span class="mx-2">•</span>
              {{ optional($ticket->created_at)->format('d M Y, H:i') }}
            </div>
          </div>

          <div class="flex items-center gap-2">
            <a href="{{ route('admin.tickets.edit', $ticket->id) }}"
               class="px-3 py-2 rounded-lg bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
              Edit
            </a>

            <form action="{{ route('admin.tickets.destroy', $ticket->id) }}"
                  method="POST"
                  onsubmit="return confirm('Soft delete this ticket?')">
              @csrf
              @method('DELETE')
              <button type="submit"
                      class="px-3 py-2 rounded-lg bg-red-600 text-white text-sm hover:bg-red-700">
                Delete
              </button>
            </form>
          </div>
        </div>

        {{-- Badges --}}
        <div class="mt-5 flex flex-wrap items-center gap-2">

          {{-- Status --}}
          @php
            $status = $ticket->status;
            $priority = $ticket->priority;
          @endphp

          @if($status === 'open')
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-800 dark:bg-green-900/30 dark:text-green-200">
              OPEN
            </span>
          @elseif($status === 'pending')
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200">
              PENDING
            </span>
          @else
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200">
              CLOSED
            </span>
          @endif

          {{-- Priority --}}
          @if($priority === 'high')
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-200">
              HIGH PRIORITY
            </span>
          @elseif($priority === 'normal')
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-200">
              NORMAL
            </span>
          @else
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200">
              LOW
            </span>
          @endif

          {{-- Assignee --}}
          <span class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-800 dark:bg-purple-900/30 dark:text-purple-200">
            Assignee: {{ $ticket->assignee?->name ?? 'Unassigned' }}
          </span>
        </div>

        {{-- Body --}}
        <div class="mt-6">
          <div class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
            Description
          </div>

          <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-4 text-sm leading-relaxed text-gray-800 dark:text-gray-100 whitespace-pre-line">
            {{ $ticket->body ?? '—' }}
          </div>
        </div>
      </div>

      {{-- Comments --}}
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            Comments
          </h3>

          <span class="text-sm text-gray-500 dark:text-gray-400">
            {{ $ticket->comments->count() }} total
          </span>
        </div>

        {{-- Comment list --}}
        @if($ticket->comments->count() === 0)
          <div class="text-sm text-gray-500 dark:text-gray-400">
            No comments yet.
          </div>
        @else
          <div class="space-y-4 mb-6">
            @foreach($ticket->comments as $c)
              <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-4">
                <div class="flex items-start justify-between gap-4">

                  <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                      <span class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ $c->user?->name ?? 'Unknown' }}
                      </span>

                      <span class="text-xs text-gray-500 dark:text-gray-400">
                        {{ optional($c->created_at)->diffForHumans() }}
                      </span>
                    </div>

                    <div class="mt-2 text-sm text-gray-800 dark:text-gray-100 whitespace-pre-line">
                      {{ $c->body }}
                    </div>
                  </div>

                  <form action="{{ route('admin.tickets.comments.destroy', [$ticket->id, $c->id]) }}"
                        method="POST"
                        onsubmit="return confirm('Soft delete this comment?')"
                        class="flex-shrink-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-3 py-2 rounded-lg bg-red-600 text-white text-xs hover:bg-red-700">
                      Delete
                    </button>
                  </form>
                </div>
              </div>
            @endforeach
          </div>
        @endif

        {{-- Add comment --}}
        <form action="{{ route('admin.tickets.comments.store', $ticket->id) }}" method="POST">
          @csrf

          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
            Add Comment
          </label>

          <textarea name="body"
                    rows="3"
                    required
                    class="w-full rounded-xl border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 resize-y focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Write a comment..."></textarea>

          <div class="mt-3 flex items-center justify-end">
            <button type="submit"
                    class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
              Post Comment
            </button>
          </div>
        </form>
      </div>

    </div>

    {{-- RIGHT: Assignment Panel --}}
    <div class="space-y-6">

      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 border border-gray-100 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">
          Assign Ticket
        </h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
          Choose an agent to handle this ticket.
        </p>

        <form action="{{ route('admin.tickets.assign', $ticket->id) }}" method="POST">
          @csrf

          <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
            Agent
          </label>

          <select name="assignee_id"
                  required
                  class="w-full rounded-xl border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">— Select Agent —</option>
            @foreach($agents as $a)
              <option value="{{ $a->id }}" {{ $ticket->assignee_id == $a->id ? 'selected' : '' }}>
                {{ $a->name }}
              </option>
            @endforeach
          </select>

          <button type="submit"
                  class="mt-4 w-full px-4 py-2 rounded-xl bg-gray-900 dark:bg-gray-700 text-white hover:bg-black dark:hover:bg-gray-600">
            Assign
          </button>
        </form>

        <div class="mt-5 pt-5 border-t border-gray-200 dark:border-gray-700">
          <a href="{{ route('admin.tickets.index') }}"
             class="w-full inline-flex items-center justify-center px-4 py-2 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
            Back to Tickets
          </a>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
