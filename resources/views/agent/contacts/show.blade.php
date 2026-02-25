@extends('layouts.app')

@section('header')
Contact Details
@endsection

@section('content')
<div class="max-w-xl mx-auto space-y-4">

  <div class="flex justify-between items-center">
    <div>
      <h1 class="text-xl font-semibold">{{ $contact->name }}</h1>
      <p class="text-sm text-gray-500">{{ $contact->title ?? '—' }}</p>
    </div>

    <a href="{{ route('agent.contacts.edit', $contact->id) }}"
       class="px-4 py-2 rounded bg-blue-600 text-white text-sm">
      Edit
    </a>
  </div>

  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 space-y-3">
    <div><strong>Email:</strong> {{ $contact->email ?? '—' }}</div>
    <div><strong>Phone:</strong> {{ $contact->phone ?? '—' }}</div>
    <div><strong>Notes:</strong>
      <div class="mt-1 text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">
        {{ $contact->notes ?? '—' }}
      </div>
    </div>
  </div>

  <a href="{{ route('agent.contacts.index') }}" class="text-sm text-gray-500">← Back to contacts</a>
</div>
@endsection
