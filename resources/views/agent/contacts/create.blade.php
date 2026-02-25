@extends('layouts.app')

@section('header')
  Create Contact
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <div class="mb-4">
      <h1 class="text-xl font-semibold">Create Contact</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
        Add a new contact for your company.
      </p>
    </div>

    @if ($errors->any())
      <div class="mb-4 p-3 rounded border bg-red-50 border-red-200 text-red-700">
        <div class="font-semibold">Please fix the following</div>
        <ul class="mt-2 list-disc list-inside text-sm">
          @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('agent.contacts.store') }}" method="POST" class="space-y-4">
      @csrf

      <div>
        <label class="block text-sm font-medium mb-1">Full name</label>
        <input name="name" value="{{ old('name') }}" required
               class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
               placeholder="Jane Doe" />
        @error('name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Email</label>
          <input name="email" type="email" value="{{ old('email') }}"
                 class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                 placeholder="jane@example.com" />
          @error('email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Phone</label>
          <input name="phone" value="{{ old('phone') }}"
                 class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                 placeholder="+62 812 3456 7890" />
          @error('phone') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Title</label>
        <input name="title" value="{{ old('title') }}"
               class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
               placeholder="Head of Support" />
        @error('title') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      {{-- Company is fixed for agent via controller, so no selector here --}}

      <div>
        <label class="block text-sm font-medium mb-1">Notes</label>
        <textarea id="notes" name="notes" rows="5"
                  class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 resize-y focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Optional notes about the contact…">{{ old('notes') }}</textarea>
        @error('notes') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        <div class="text-xs text-gray-400 mt-1">
          Useful for internal notes (not shown to the contact).
        </div>
      </div>

      <div class="flex items-center gap-3">
        <button type="submit"
                class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
          Create Contact
        </button>
        <a href="{{ route('agent.contacts.index') }}"
           class="px-4 py-2 rounded bg-gray-100 dark:bg-gray-700 text-sm">
          Cancel
        </a>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const ta = document.getElementById('notes');
  if (ta) {
    function autoSize() {
      ta.style.height = 'auto';
      ta.style.height = (ta.scrollHeight) + 'px';
    }
    ta.addEventListener('input', autoSize);
    autoSize();
  }
});
</script>
@endsection
