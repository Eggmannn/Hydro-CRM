@extends('layouts.app')

@section('content')
@php
    $currentRole = optional($user->primaryRole())->role_type;
    $isClient = $user->hasRole('client', $user->company_id);
@endphp

<div class="max-w-2xl mx-auto px-4 py-10">
  <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-lg p-8">

    {{-- Header --}}
    <div class="mb-6">
      <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Edit user</h2>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
        Update profile details — changes are saved after pressing
        <span class="font-medium">Save</span>.
      </p>
    </div>

    {{-- Main form --}}
    <form action="{{ route('customer-admin.users.update', $user) }}"
          method="POST"
          autocomplete="off"
          class="space-y-5">
      @csrf
      @method('PUT')

      {{-- Name --}}
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Name
        </label>
        <input
          name="name"
          value="{{ old('name', $user->name) }}"
          required
          class="w-full rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-300"
        />
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
          name="email"
          value="{{ old('email', $user->email) }}"
          required
          class="w-full rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-300"
        />
        @error('email')
          <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
        @enderror
      </div>

      {{-- Password --}}
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          New password
          <span class="text-xs text-gray-400">(leave blank to keep)</span>
        </label>
        <input
          name="password"
          type="password"
          class="w-full rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-300"
        />
        @error('password')
          <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
        @enderror
      </div>

      {{-- Role (only for non-client users) --}}
      @if(!$isClient)
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Role
        </label>
        <select
          name="role_type"
          class="w-full rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-300"
        >
          <option value="admin" {{ $currentRole === 'admin' ? 'selected' : '' }}>Admin</option>
          <option value="agent" {{ $currentRole === 'agent' ? 'selected' : '' }}>Agent</option>
          <option value="viewer" {{ $currentRole === 'viewer' ? 'selected' : '' }}>Viewer</option>
          <option value="customer_admin" {{ $currentRole === 'customer_admin' ? 'selected' : '' }}>
            Customer Admin
          </option>
        </select>
      </div>
      @else
      <div class="rounded-md bg-yellow-50 border border-yellow-200 p-3 text-sm text-yellow-800">
        This user is a <strong>Client</strong>. Client roles are fixed and cannot be changed.
      </div>
      @endif

      {{-- Actions --}}
      <div class="flex items-center justify-end gap-3 pt-2">
        <a href="{{ route('customer-admin.users.index') }}"
           class="text-sm text-gray-600 dark:text-gray-300 hover:underline">
          Cancel
        </a>
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md">
          Save
        </button>
      </div>
    </form>

  </div>
</div>
@endsection
