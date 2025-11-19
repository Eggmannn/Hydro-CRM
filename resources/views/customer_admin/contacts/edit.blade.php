@extends('layouts.app')

@section('header')
  Edit Contact
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <div class="mb-4">
      <h1 class="text-xl font-semibold">Edit Contact</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update contact information for your company.</p>
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

    <form action="{{ route('customer-admin.contacts.update', $contact) }}" method="POST" class="space-y-4">
      @csrf
      @method('PUT')

      <div>
        <label class="block text-sm font-medium mb-1">Full name</label>
        <input name="name" value="{{ old('name', $contact->name) }}" required
               class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
               placeholder="Jane Doe" />
        @error('name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Email</label>
          <input name="email" type="email" value="{{ old('email', $contact->email) }}"
                 class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                 placeholder="jane@example.com" />
          @error('email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Phone</label>
          <input name="phone" value="{{ old('phone', $contact->phone) }}"
                 class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                 placeholder="+62 812 3456 7890" />
          @error('phone') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Title</label>
        <input name="title" value="{{ old('title', $contact->title) }}"
               class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
               placeholder="Head of Support" />
        @error('title') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      @if(isset($companies) && $companies->count())
      <div>
        <label class="block text-sm font-medium mb-1">Company</label>
        <select name="company_id" class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option value="">— Select company —</option>
          @foreach($companies as $co)
            <option value="{{ $co->id }}" {{ (old('company_id', $contact->company_id) == $co->id) ? 'selected' : '' }}>{{ $co->name }}</option>
          @endforeach
        </select>
        @error('company_id') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>
      @endif

      <div>
        <label class="block text-sm font-medium mb-1">Notes</label>
        <textarea id="notes" name="notes" rows="5"
                  class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 resize-y focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Optional notes about the contact…">{{ old('notes', $contact->notes) }}</textarea>
        @error('notes') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      <div class="flex items-center gap-3">
        <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save changes</button>
        <a href="{{ route('customer-admin.contacts.index') }}" class="px-4 py-2 rounded bg-gray-100 dark:bg-gray-700 text-sm">Cancel</a>

        <form action="{{ route('customer-admin.contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Delete contact? This action cannot be undone.')" class="ml-auto">
          @csrf
          @method('DELETE')
          <button type="submit" class="px-3 py-1 rounded bg-red-600 text-white text-sm hover:bg-red-700">Delete</button>
        </form>
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
