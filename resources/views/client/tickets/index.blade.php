@extends('layouts.app')

@section('header')
My Tickets
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
            My Tickets
        </h2>

        <a href="{{ route('client.tickets.create') }}"
           class="inline-flex items-center justify-center px-4 py-2 rounded-lg
                  bg-blue-600 text-white font-medium
                  hover:bg-blue-700 transition">
            + New Ticket
        </a>
    </div>

    {{-- Empty State --}}
    @if($tickets->isEmpty())
        <div class="bg-white dark:bg-gray-800
                    rounded-lg shadow p-6 text-center
                    text-gray-500 dark:text-gray-400">
            You haven’t created any tickets yet.
        </div>
    @else
        {{-- Tickets Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-5 py-3 text-left text-sm font-medium
                                       text-gray-600 dark:text-gray-300">
                                Subject
                            </th>
                            <th class="px-5 py-3 text-left text-sm font-medium
                                       text-gray-600 dark:text-gray-300">
                                Status
                            </th>
                            <th class="px-5 py-3 text-left text-sm font-medium
                                       text-gray-600 dark:text-gray-300">
                                Created
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($tickets as $ticket)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                                <td class="px-5 py-4">
                                    <a href="{{ route('client.tickets.show', $ticket->id) }}"
                                       class="font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ $ticket->subject }}
                                    </a>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="px-2 py-1 rounded text-xs font-semibold
                                        @if($ticket->status === 'open')
                                            bg-blue-100 text-blue-800
                                            dark:bg-blue-900/40 dark:text-blue-300
                                        @elseif($ticket->status === 'pending')
                                            bg-yellow-100 text-yellow-800
                                            dark:bg-yellow-900/40 dark:text-yellow-300
                                        @else
                                            bg-green-100 text-green-800
                                            dark:bg-green-900/40 dark:text-green-300
                                        @endif">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-sm
                                           text-gray-500 dark:text-gray-400">
                                    {{ optional($ticket->created_at)->format('d M Y') ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="pt-2 text-gray-700 dark:text-gray-300">
            {{ $tickets->links() }}
        </div>
    @endif

</div>
@endsection
