@extends('layouts.app')

@section('header')
Create Ticket
@endsection

@section('content')
<div class="max-w-xl mx-auto px-4 py-8">

    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700
                rounded-xl shadow p-6">

        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-6">
            Create Support Ticket
        </h2>

        <form method="POST" action="{{ route('client.tickets.store') }}" class="space-y-5">
            @csrf

            {{-- Subject --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Subject
                </label>
                <input
                    type="text"
                    name="subject"
                    required
                    class="w-full rounded-md border
                           bg-gray-50 dark:bg-gray-900
                           border-gray-300 dark:border-gray-600
                           text-gray-900 dark:text-gray-100
                           px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Description
                </label>
                <textarea
                    name="body"
                    rows="5"
                    required
                    class="w-full rounded-md border
                           bg-gray-50 dark:bg-gray-900
                           border-gray-300 dark:border-gray-600
                           text-gray-900 dark:text-gray-100
                           px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                ></textarea>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center
                           px-4 py-2 rounded-md
                           bg-blue-600 hover:bg-blue-700
                           text-white text-sm font-medium
                           transition">
                    Submit Ticket
                </button>

                <a href="{{ route('client.tickets.index') }}"
                   class="inline-flex items-center justify-center
                          px-4 py-2 rounded-md
                          border border-gray-300 dark:border-gray-600
                          text-sm text-gray-700 dark:text-gray-300
                          hover:bg-gray-100 dark:hover:bg-gray-700
                          transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
