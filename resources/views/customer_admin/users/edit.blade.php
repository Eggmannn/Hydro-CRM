@extends('layouts.app')

@section('content')
@php
    $currentRole = optional($user->primaryRole())->role_type;
@endphp

<div class="max-w-2xl mx-auto px-4 py-10">
  <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-lg p-8">
    {{-- Header --}}
    <div class="mb-6">
      <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Edit user</h2>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update profile details — changes are saved immediately after pressing <span class="font-medium">Save</span>.</p>
    </div>

    {{-- Primary form --}}
    <form action="{{ route('customer-admin.users.update', $user) }}" method="POST" autocomplete="off" class="space-y-5">
      @csrf
      @method('PUT')

      {{-- Name --}}
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
        <input
          name="name"
          value="{{ old('name', $user->name) }}"
          required
          class="w-full rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
        />
        @error('name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      {{-- Email --}}
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
        <input
          name="email"
          value="{{ old('email', $user->email) }}"
          required
          class="w-full rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
        />
        @error('email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      {{-- New password --}}
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New password <span class="font-normal text-xs text-gray-400">(leave blank to keep)</span></label>
        <input
          name="password"
          type="password"
          class="w-full rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
        />
        @error('password') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      {{-- Role (primary) --}}
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
        <select
          name="role_type"
          required
          class="w-full rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
        >
          <option value="admin" {{ $currentRole==='admin' ? 'selected' : '' }}>Admin</option>
          <option value="agent" {{ $currentRole==='agent' ? 'selected' : '' }}>Agent</option>
          <option value="viewer" {{ $currentRole==='viewer' ? 'selected' : '' }}>Viewer</option>
          <option value="customer_admin" {{ $currentRole==='customer_admin' ? 'selected' : '' }}>Customer Admin</option>
        </select>
      </div>

      {{-- Actions --}}
      <div class="flex items-center justify-end gap-3 pt-1">
        <a href="{{ route('customer-admin.users.index') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Cancel</a>
        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md shadow-sm transition">
          Save
        </button>
      </div>
    </form>

    {{-- Divider --}}
    <div class="my-6 border-t border-gray-100 dark:border-gray-700"></div>

    {{-- Role assignment form (separate endpoint) --}}
    <div>
      <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100 mb-2">Update role</h3>
      <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">This uses the dedicated role-assignment endpoint.</p>

      <form action="{{ route('customer-admin.users.assign-role', $user) }}" method="POST" class="flex flex-col sm:flex-row sm:items-center sm:gap-3 gap-3">
        @csrf

        <div class="flex-1">
          <select
            name="role_type"
            required
            class="w-full rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
          >
            <option value="admin" {{ $currentRole==='admin' ? 'selected' : '' }}>Admin</option>
            <option value="agent" {{ $currentRole==='agent' ? 'selected' : '' }}>Agent</option>
            <option value="viewer" {{ $currentRole==='viewer' ? 'selected' : '' }}>Viewer</option>
            <option value="customer_admin" {{ $currentRole==='customer_admin' ? 'selected' : '' }}>Customer Admin</option>
          </select>
        </div>

        <div>
          <button type="submit" class="inline-flex items-center gap-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-sm text-gray-800 dark:text-gray-100 px-4 py-2 rounded-md border border-transparent transition">
            Assign role
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
