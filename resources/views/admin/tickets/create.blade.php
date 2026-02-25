@extends('layouts.app')

@section('header')
  New Ticket (Admin)
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold mb-2">Create a new ticket</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
      Provide details so your team can triage the issue quickly.
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

    <form action="{{ route('admin.tickets.store') }}" method="POST">
      @csrf

      {{-- Subject --}}
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Subject</label>
        <input name="subject" value="{{ old('subject') }}"
               class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
               placeholder="Short, clear summary" required />
        @error('subject') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      {{-- Assignee --}}
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Assignee (optional)</label>
        <select name="assignee_id"
                class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700">
          <option value="">— Unassigned —</option>

          @foreach($agents as $a)
            <option value="{{ $a->id }}" {{ old('assignee_id') == $a->id ? 'selected' : '' }}>
              {{ $a->name }}{{ isset($a->email) ? ' • '.$a->email : '' }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Priority --}}
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Priority</label>
        <select name="priority"
                class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700"
                required>
          <option value="normal" {{ old('priority','normal')==='normal' ? 'selected' : '' }}>Normal</option>
          <option value="low" {{ old('priority')==='low' ? 'selected' : '' }}>Low</option>
          <option value="high" {{ old('priority')==='high' ? 'selected' : '' }}>High</option>
        </select>
      </div>

      {{-- Status --}}
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Status</label>
        <select name="status"
                class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700"
                required>
          <option value="open" {{ old('status','open')==='open' ? 'selected' : '' }}>Open</option>
          <option value="pending" {{ old('status')==='pending' ? 'selected' : '' }}>Pending</option>
          <option value="closed" {{ old('status')==='closed' ? 'selected' : '' }}>Closed</option>
        </select>
      </div>

      {{-- Body --}}
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Body / Description</label>
        <textarea id="ticketBody"
                  name="body"
                  class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 resize-y"
                  rows="6">{{ old('body') }}</textarea>
      </div>

      <div class="flex items-center gap-3">
        <button class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
          Create Ticket
        </button>

        <a href="{{ route('admin.tickets.index') }}"
           class="px-4 py-2 rounded bg-gray-100 dark:bg-gray-700 text-sm">
          Cancel
        </a>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const ta = document.getElementById('ticketBody');
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
