@extends('layouts.app')

@section('title', 'Edit User - ' . $user->name)
@section('header', 'Edit User')

@section('content')

<div class="max-w-3xl mx-auto px-6 py-12 space-y-10 animate-fadeIn">

    {{-- ✅ Page Header --}}
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-800 dark:text-gray-100">
            Edit User — <span class="text-blue-600 dark:text-blue-400">{{ $company->name }}</span>
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2 text-lg">
            Update user information and access permissions.
        </p>
    </div>

    {{-- ✅ Error Messages --}}
    @if ($errors->any())
        <div class="bg-red-100 dark:bg-red-900/40 border border-red-400 dark:border-red-700 
                    text-red-700 dark:text-red-300 px-5 py-4 rounded-lg mb-6">
            <strong>Whoops!</strong> Please fix the following errors:
            <ul class="mt-3 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ✅ Edit User Form --}}
    <form method="POST"
          action="{{ route('crd-admin.company-users.update', [$company->id, $user->id]) }}"
          class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-10 space-y-8 transition">

        @csrf
        @method('PUT')

        {{-- ✅ Full Name --}}
        <div>
            <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">
                Full Name
            </label>
            <input type="text"
                   name="name"
                   value="{{ old('name', $user->name) }}"
                   required
                   class="block w-full rounded-md border border-gray-300 dark:border-gray-700 
                          bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 
                          shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-300 
                          px-3 py-2 text-sm placeholder-gray-400 transition"
                   placeholder="Enter full name">
        </div>

        {{-- ✅ Email --}}
        <div>
            <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">
                Email
            </label>
            <input type="email"
                   name="email"
                   value="{{ old('email', $user->email) }}"
                   required
                   class="block w-full rounded-md border border-gray-300 dark:border-gray-700 
                          bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 
                          shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-300 
                          px-3 py-2 text-sm placeholder-gray-400 transition"
                   placeholder="Enter email address">
        </div>

        {{-- ✅ Role Dropdown --}}
        <div>
            <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">
                Role
            </label>

            @php 
                $currentRole = $user->roles->first()->role_type ?? 'viewer'; 
            @endphp

            <select name="role" required
                class="block w-full rounded-md border border-gray-300 dark:border-gray-700 
                       bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 
                       shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-300 
                       px-3 py-2 text-sm transition">
                <option value="admin"  {{ $currentRole === 'admin' ? 'selected' : '' }}>Company Admin</option>
                <option value="agent"  {{ $currentRole === 'agent' ? 'selected' : '' }}>Agent</option>
                <option value="viewer" {{ $currentRole === 'viewer' ? 'selected' : '' }}>Viewer</option>
            </select>
        </div>

        {{-- ✅ Form Buttons --}}
        <div class="flex justify-end items-center gap-4 pt-4">
            <a href="{{ route('crd-admin.company-users.index', $company->id) }}"
               class="px-5 py-2.5 rounded-lg border dark:border-gray-600 
                      text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 
                      transition">
                Cancel
            </a>

            <button type="submit"
                class="px-7 py-2.5 bg-blue-600 hover:bg-blue-700 text-white 
                       font-medium rounded-lg shadow transition">
                Update User
            </button>
        </div>

    </form>
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
