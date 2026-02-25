@extends('layouts.app')

@section('header')
  New {{ $type === 'client' ? 'Client' : 'Agent' }}
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold mb-2">
      Create a new {{ $type === 'client' ? 'client' : 'agent' }}
    </h2>

    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
      This user will automatically be created as a <strong>{{ $type }}</strong>.
      Admin cannot change roles.
    </p>

    @if ($errors->any())
      <div class="mb-4 p-3 rounded border bg-red-50 border-red-200 text-red-700">
        <div class="font-semibold">Please fix the following:</div>
        <ul class="mt-2 list-disc list-inside text-sm">
          @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST">
      @csrf

      {{-- Required hidden type --}}
      <input type="hidden" name="type" value="{{ $type }}">

      {{-- Name --}}
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Name</label>
        <input name="name" value="{{ old('name') }}"
               class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
               required />
      </div>

      {{-- Email --}}
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Email</label>
        <input name="email" value="{{ old('email') }}"
               type="email"
               class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
               required />
      </div>

      {{-- Password --}}
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Password</label>
        <input name="password"
               type="password"
               class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
               required />
        <p class="text-xs text-gray-500 mt-1">Minimum 6 characters.</p>
      </div>

      <div class="flex items-center gap-3">
        <button class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
          Create {{ $type === 'client' ? 'Client' : 'Agent' }}
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
