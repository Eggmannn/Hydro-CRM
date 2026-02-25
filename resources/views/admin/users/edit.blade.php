@extends('layouts.app')

@section('header')
  Edit {{ $type === 'client' ? 'Client' : 'Agent' }}
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4">

    <div class="flex items-start justify-between">
      <div>
        <h2 class="text-lg font-semibold">
          Edit {{ $type === 'client' ? 'client' : 'agent' }}
        </h2>

        <p class="text-sm text-gray-500 dark:text-gray-400">
          Roles are locked. Admin cannot convert {{ $type }} to another type.
        </p>
      </div>

      <form action="{{ route('admin.users.destroy', $user) }}"
            method="POST"
            onsubmit="return confirm('Soft delete this user?')">
        @csrf
        @method('DELETE')
        <button class="px-3 py-1 rounded bg-red-600 text-white text-sm hover:bg-red-700">
          Delete
        </button>
      </form>
    </div>

    @if ($errors->any())
      <div class="p-3 rounded border bg-red-50 border-red-200 text-red-700">
        <div class="font-semibold">Please fix the following:</div>
        <ul class="mt-2 list-disc list-inside text-sm">
          @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
      @csrf
      @method('PUT')

      {{-- Name --}}
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Name</label>
        <input name="name" value="{{ old('name', $user->name) }}"
               class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
               required />
      </div>

      {{-- Email --}}
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Email</label>
        <input name="email" value="{{ old('email', $user->email) }}"
               type="email"
               class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
               required />
      </div>

      {{-- Password --}}
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">New Password (optional)</label>
        <input name="password"
               type="password"
               class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        <p class="text-xs text-gray-500 mt-1">
          Leave blank to keep current password.
        </p>
      </div>

      {{-- Actions --}}
      <div class="flex items-center gap-3">
        <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
          Save changes
        </button>

        <a href="{{ route('admin.users.index', ['type' => $type]) }}"
           class="px-4 py-2 rounded bg-gray-100 dark:bg-gray-700 text-sm">
          Cancel
        </a>
      </div>
    </form>
  </div>
</div>
@endsection
