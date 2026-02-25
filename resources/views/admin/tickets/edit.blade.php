@extends('layouts.app')

@section('header')
  Edit Ticket #{{ $ticket->id }}
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4">
    <div class="flex items-start justify-between">
      <div>
        <h2 class="text-lg font-semibold">Edit ticket</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Update fields and save changes.</p>
      </div>

      <div class="flex items-center gap-2">
        <form action="{{ route('admin.tickets.destroy', $ticket) }}"
              method="POST"
              onsubmit="return confirm('Soft delete this ticket?')">
          @csrf
          @method('DELETE')
          <button class="px-3 py-1 rounded bg-red-600 text-white text-sm hover:bg-red-700">
            Delete
          </button>
        </form>
      </div>
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

    <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST">
      @csrf
      @method('PUT')

      {{-- Subject --}}
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Subject</label>
        <input name="subject" value="{{ old('subject', $ticket->subject) }}"
               class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
               placeholder="Short, clear summary" required />
      </div>

      {{-- Assignee --}}
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Assignee</label>
        <select name="assignee_id"
                class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700">
          <option value="">Unassigned</option>

          @foreach($agents as $a)
            <option value="{{ $a->id }}" {{ old('assignee_id', $ticket->assignee_id) == $a->id ? 'selected' : '' }}>
              {{ $a->name }}{{ isset($a->email) ? ' • '.$a->email : '' }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Priority + Status --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block text-sm font-medium mb-1">Priority</label>
          <div class="flex items-center gap-2">
            <label class="inline-flex items-center gap-2 text-sm">
              <input type="radio" name="priority" value="normal"
                     {{ old('priority', $ticket->priority) === 'normal' ? 'checked' : '' }} />
              <span class="px-2 py-1 rounded text-xs font-semibold bg-yellow-50 text-yellow-800">
                Normal
              </span>
            </label>

            <label class="inline-flex items-center gap-2 text-sm">
              <input type="radio" name="priority" value="high"
                     {{ old('priority', $ticket->priority) === 'high' ? 'checked' : '' }} />
              <span class="px-2 py-1 rounded text-xs font-semibold bg-red-50 text-red-700">
                High
              </span>
            </label>

            <label class="inline-flex items-center gap-2 text-sm">
              <input type="radio" name="priority" value="low"
                     {{ old('priority', $ticket->priority) === 'low' ? 'checked' : '' }} />
              <span class="px-2 py-1 rounded text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                Low
              </span>
            </label>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Status</label>
          <select name="status"
                  class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700">
            <option value="open" {{ old('status', $ticket->status) === 'open' ? 'selected' : '' }}>Open</option>
            <option value="pending" {{ old('status', $ticket->status) === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="closed" {{ old('status', $ticket->status) === 'closed' ? 'selected' : '' }}>Closed</option>
          </select>
        </div>
      </div>

      {{-- Body --}}
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Details</label>
        <textarea id="ticketBody"
                  name="body"
                  rows="8"
                  class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 resize-y">{{ old('body', $ticket->body) }}</textarea>
      </div>

      {{-- Actions --}}
      <div class="flex items-center gap-3">
        <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
          Save changes
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
