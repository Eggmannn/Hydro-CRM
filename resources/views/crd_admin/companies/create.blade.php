@extends('layouts.app')

@section('title', 'Create New Company')
@section('header', 'Create New Company')

@section('content')

<div class="max-w-3xl mx-auto px-6 py-12">

    {{-- ✅ Page Header --}}
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-bold text-gray-800 dark:text-gray-100">
            Create New Company
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2 text-lg">
            Add a new company to the CRM system.
        </p>
    </div>

    {{-- ✅ Validation Errors --}}
    @if ($errors->any())
        <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700
                    text-red-700 dark:text-red-200 px-5 py-4 rounded-lg mb-8 animate-fadeIn">
            <strong>Whoops!</strong> There were some problems with your input.
            <ul class="mt-3 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ✅ Success Message --}}
    @if (session('success'))
        <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700
                    text-green-700 dark:text-green-200 px-5 py-4 rounded-lg mb-8 animate-fadeIn">
            {{ session('success') }}
        </div>
    @endif

    {{-- ✅ Main Form Card --}}
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-10 animate-fadeIn">
        <form method="POST" action="{{ route('crd-admin.companies.store') }}" class="space-y-8">
            @csrf

            {{-- Company Name --}}
            <div>
                <label class="block font-medium text-gray-700 dark:text-gray-200 mb-2">
                    Company Name
                </label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="block w-full rounded-md border border-gray-300 dark:border-gray-700 
                    dark:bg-gray-900 dark:text-gray-100 shadow-sm
                    focus:border-blue-500 focus:ring focus:ring-blue-300 px-3 py-2 text-sm placeholder-gray-400"
                    placeholder="e.g., Tech Solutions Ltd" required>
            </div>

            {{-- Domain --}}
            <div>
                <label class="block font-medium text-gray-700 dark:text-gray-200 mb-2">
                    Company Domain
                </label>
                <input type="text" name="domain" value="{{ old('domain') }}"
                    class="block w-full rounded-md border border-gray-300 dark:border-gray-700 
                    dark:bg-gray-900 dark:text-gray-100 shadow-sm
                    focus:border-blue-500 focus:ring focus:ring-blue-300 px-3 py-2 text-sm placeholder-gray-400"
                    placeholder="example.com" required>
            </div>

            {{-- Notes --}}
            <div>
                <label class="block font-medium text-gray-700 dark:text-gray-200 mb-2">
                    Notes (Optional)
                </label>
                <textarea name="notes" rows="4"
                    class="block w-full rounded-md border border-gray-300 dark:border-gray-700 
                    dark:bg-gray-900 dark:text-gray-100 shadow-sm
                    focus:border-blue-500 focus:ring focus:ring-blue-300 px-3 py-2 text-sm placeholder-gray-400"
                    placeholder="Any additional information...">{{ old('notes') }}</textarea>
            </div>

            {{-- Customer Admin --}}
            <div>
                <label class="block font-medium text-gray-700 dark:text-gray-200 mb-2">
                    Assign Customer Admin
                </label>
                <select name="customer_admin_id"
                    class="block w-full rounded-md border border-gray-300 dark:border-gray-700 
                    dark:bg-gray-900 dark:text-gray-100 shadow-sm
                    focus:border-blue-500 focus:ring focus:ring-blue-300 px-3 py-2 text-sm">
                    <option value="">-- Select Customer Admin --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('customer_admin_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>

                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    Only existing users can be assigned as Customer Admins.
                </p>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end items-center gap-4 pt-4">
                <a href="{{ route('crd-admin.companies.index') }}"
                    class="px-5 py-2 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition">
                    Cancel
                </a>

                <button type="submit"
                    class="px-7 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow transition">
                    Create Company
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Fade animation --}}
<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
    animation: fadeIn 0.4s ease-out;
}
</style>

@endsection
