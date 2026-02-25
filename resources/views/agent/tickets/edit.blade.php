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

      <form action="{{ route('agent.tickets.destroy', $ticket->id) }}"
            method="POST"
            onsubmit="return confirm('Delete this ticket? This action cannot be undone.')">
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

    <form action="{{ route('agent.tickets.update', $ticket->id) }}" method="POST">
      @csrf
      @method('PUT')

      {{-- Subject --}}
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Subject</label>
        <input name="subject"
               value="{{ old('subject', $ticket->subject) }}"
               class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-blue-500"
               required />
      </div>

      {{-- Contact --}}
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Contact</label>
        <select name="contact_id"
                class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700">
          <option value="">— Select contact —</option>
          @foreach($contacts as $c)
            <option value="{{ $c->id }}"
              {{ old('contact_id', $ticket->contact_id) == $c->id ? 'selected' : '' }}>
              {{ $c->name }}{{ $c->email ? ' • '.$c->email : '' }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- ✅ Assignee --}}
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Assignee</label>
        <select name="assignee_id"
                class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700">
          <option value="">— Unassigned —</option>
          @foreach($agents as $a)
            <option value="{{ $a->id }}"
              {{ old('assignee_id', $ticket->assignee_id) == $a->id ? 'selected' : '' }}>
              {{ $a->name }}{{ $a->email ? ' • '.$a->email : '' }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Priority + Status --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block text-sm font-medium mb-1">Priority</label>
          <div class="flex gap-3">
            @foreach(['normal'=>'Normal','high'=>'High','low'=>'Low'] as $val=>$label)
              <label class="inline-flex items-center gap-2 text-sm">
                <input type="radio" name="priority" value="{{ $val }}"
                  {{ old('priority', $ticket->priority) === $val ? 'checked' : '' }} />
                <span class="px-2 py-1 rounded text-xs font-semibold
                  {{ $val=='high'?'bg-red-50 text-red-700':($val=='normal'?'bg-yellow-50 text-yellow-800':'bg-gray-100 text-gray-800') }}">
                  {{ strtoupper($label) }}
                </span>
              </label>
            @endforeach
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Status</label>
          <select name="status"
                  class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700">
            <option value="open" {{ old('status', $ticket->status)==='open'?'selected':'' }}>Open</option>
            <option value="pending" {{ old('status', $ticket->status)==='pending'?'selected':'' }}>Pending</option>
            <option value="closed" {{ old('status', $ticket->status)==='closed'?'selected':'' }}>Closed</option>
          </select>
        </div>
      </div>

      {{-- Body --}}
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Details</label>
        <textarea id="ticketBody" name="body" rows="6"
          class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 resize-y">{{ old('body', $ticket->body) }}</textarea>
      </div>

      {{-- Actions --}}
      <div class="flex items-center gap-3">
        <button type="submit"
                class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
          Save changes
        </button>

        <a href="{{ route('agent.tickets.show', $ticket->id) }}"
           class="px-4 py-2 rounded bg-gray-100 dark:bg-gray-700 text-sm">
          Cancel
        </a>
      </div>

    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const ta = document.getElementById('ticketBody');
  if (!ta) return;
  const autoSize = () => {
    ta.style.height = 'auto';
    ta.style.height = ta.scrollHeight + 'px';
  };
  ta.addEventListener('input', autoSize);
  autoSize();
});
</script>
@endsection
