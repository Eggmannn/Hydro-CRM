@extends('layouts.app')

@section('header')
Create Client User
@endsection

@section('content')
<div class="max-w-xl mx-auto px-4 py-10">

    <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-lg p-8">

        {{-- Header --}}
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                Create Client User
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Client users can only create and view their own tickets.
            </p>
        </div>

        {{-- Form --}}
        <form method="POST"
              action="{{ route('customer-admin.users.client.store') }}"
              autocomplete="off"
              class="space-y-5">
            @csrf

            {{-- Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Name
                </label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    class="w-full rounded-md border border-gray-200 dark:border-gray-700
                           bg-gray-50 dark:bg-gray-900
                           text-gray-900 dark:text-gray-100
                           px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-300"
                >
                @error('name')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Email
                </label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full rounded-md border border-gray-200 dark:border-gray-700
                           bg-gray-50 dark:bg-gray-900
                           text-gray-900 dark:text-gray-100
                           px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-300"
                >
                @error('email')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Password
                </label>
                <input
                    type="password"
                    name="password"
                    required
                    class="w-full rounded-md border border-gray-200 dark:border-gray-700
                           bg-gray-50 dark:bg-gray-900
                           text-gray-900 dark:text-gray-100
                           px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-300"
                >
                @error('password')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Confirm Password
                </label>
                <input
                    type="password"
                    name="password_confirmation"
                    required
                    class="w-full rounded-md border border-gray-200 dark:border-gray-700
                           bg-gray-50 dark:bg-gray-900
                           text-gray-900 dark:text-gray-100
                           px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-300"
                >
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('customer-admin.users.index') }}"
                   class="text-sm px-4 py-2 rounded-md
                          border border-gray-200 dark:border-gray-600
                          text-gray-700 dark:text-gray-300
                          hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    Cancel
                </a>

                <button type="submit"
                        class="inline-flex items-center gap-2
                               bg-blue-600 hover:bg-blue-700
                               text-white text-sm font-medium
                               px-5 py-2 rounded-md shadow-sm transition">
                    Create Client
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
