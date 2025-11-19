@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6">
    <h2 class="text-2xl font-semibold">Assume authorization for: {{ $company->name }}</h2>
    <p class="mt-2">By assuming authorization you will be able to view and manage tickets for this company. Your action will be logged.</p>

    <form method="POST" action="{{ route('crd-admin.authorization.assume', ['company' => $company->id]) }}" class="mt-4">
        @csrf
        <div>
            <label class="block text-sm">Reason (optional)</label>
            <input name="reason" class="mt-1 w-full border rounded p-2" placeholder="e.g. investigating support SLA issue">
        </div>

        <div class="mt-4 flex items-center space-x-3">
            <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white">Yes — assume authorization</button>

            <a href="{{ url()->previous() }}" class="px-4 py-2 rounded border">Cancel</a>
        </div>
    </form>
</div>
@endsection
