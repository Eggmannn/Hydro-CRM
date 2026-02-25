@extends('layouts.app')

@section('header')
Ticket Details
@endsection

@section('content')
<div class="max-w-5xl mx-auto px-4 space-y-8">

    {{-- Ticket Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ $ticket->subject }}
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    Created {{ optional($ticket->created_at)->format('d M Y') ?? '-' }}
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    @if($ticket->status === 'open')
                        bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300
                    @elseif($ticket->status === 'pending')
                        bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300
                    @else
                        bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300
                    @endif">
                    {{ ucfirst($ticket->status) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Ticket Body --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-gray-100">
            Description
        </h3>

        <p class="whitespace-pre-line text-gray-800 dark:text-gray-300 leading-relaxed">
            {{ $ticket->body }}
        </p>
    </div>

    {{-- Comments --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">
            Conversation
        </h3>

        @forelse($comments as $comment)
            <div class="border-b border-gray-200 dark:border-gray-700 last:border-b-0 pb-4 mb-4 last:mb-0">
                <div class="flex items-center justify-between mb-1">
                    <div class="text-sm font-medium text-gray-800 dark:text-gray-200">
                        {{ optional($comment->user)->name ?? 'Unknown User' }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        {{ optional($comment->created_at)->diffForHumans() ?? '' }}
                    </div>
                </div>

                <div class="text-gray-700 dark:text-gray-300 text-sm whitespace-pre-line">
                    {{ $comment->body }}
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-500 dark:text-gray-400">
                No comments yet. Support will reply here once available.
            </p>
        @endforelse
    </div>

    {{-- Footer Actions --}}
    <div class="flex justify-between items-center">
        <a href="{{ route('client.tickets.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                  border border-gray-300 dark:border-gray-600
                  text-gray-700 dark:text-gray-300
                  hover:bg-gray-100 dark:hover:bg-gray-700 transition">
            ← Back to Tickets
        </a>
    </div>

</div>
@endsection
