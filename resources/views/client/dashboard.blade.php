@extends('layouts.app')

@section('header')
My Dashboard
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 space-y-8">

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Total Tickets
            </div>
            <div class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">
                {{ $stats['total'] }}
            </div>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg shadow p-5">
            <div class="text-sm text-blue-700 dark:text-blue-300">
                Open
            </div>
            <div class="mt-2 text-3xl font-semibold text-blue-900 dark:text-blue-100">
                {{ $stats['open'] }}
            </div>
        </div>

        <div class="bg-yellow-50 dark:bg-yellow-900/30 rounded-lg shadow p-5">
            <div class="text-sm text-yellow-700 dark:text-yellow-300">
                Pending
            </div>
            <div class="mt-2 text-3xl font-semibold text-yellow-900 dark:text-yellow-100">
                {{ $stats['pending'] }}
            </div>
        </div>

        <div class="bg-green-50 dark:bg-green-900/30 rounded-lg shadow p-5">
            <div class="text-sm text-green-700 dark:text-green-300">
                Closed
            </div>
            <div class="mt-2 text-3xl font-semibold text-green-900 dark:text-green-100">
                {{ $stats['closed'] }}
            </div>
        </div>

    </div>

    {{-- Quick Actions --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('client.tickets.create') }}"
           class="inline-flex items-center justify-center px-4 py-2 rounded-lg
                  bg-blue-600 hover:bg-blue-700
                  text-white font-medium transition">
            + Create New Ticket
        </a>

        <a href="{{ route('client.tickets.index') }}"
           class="inline-flex items-center justify-center px-4 py-2 rounded-lg
                  bg-white dark:bg-gray-800
                  border border-gray-300 dark:border-gray-700
                  text-gray-700 dark:text-gray-200
                  hover:bg-gray-50 dark:hover:bg-gray-700
                  transition">
            View All My Tickets
        </a>
    </div>

    {{-- Recent Tickets --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">

        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                Recent Tickets
            </h3>
        </div>

        @if($recentTickets->isEmpty())
            <div class="p-6 text-sm text-gray-500 dark:text-gray-400">
                You haven’t created any tickets yet.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-5 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">
                                Subject
                            </th>
                            <th class="px-5 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">
                                Status
                            </th>
                            <th class="px-5 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">
                                Priority
                            </th>
                            <th class="px-5 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">
                                Created
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($recentTickets as $ticket)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                                <td class="px-5 py-3">
                                    <a href="{{ route('client.tickets.show', $ticket->id) }}"
                                       class="font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ $ticket->subject }}
                                    </a>
                                </td>

                                <td class="px-5 py-3 text-sm">
                                    <span class="px-2 py-1 rounded text-xs font-semibold
                                        @if($ticket->status === 'open')
                                            bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300
                                        @elseif($ticket->status === 'pending')
                                            bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300
                                        @else
                                            bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300
                                        @endif">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </td>

                                <td class="px-5 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ ucfirst($ticket->priority) }}
                                </td>

                                <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400">
                                    {{ optional($ticket->created_at)->format('d M Y') ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>

</div>
@endsection
