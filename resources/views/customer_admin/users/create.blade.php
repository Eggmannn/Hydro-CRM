@extends('layouts.app')

@section('header')
  Create User
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <div class="mb-4">
      <h1 class="text-xl font-semibold">Create User</h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Add a new user to your company and assign a role.</p>
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

    <form action="{{ route('customer-admin.users.store') }}" method="POST" class="space-y-4">
      @csrf

      <div>
        <label class="block text-sm font-medium mb-1">Full name</label>
        <input name="name" value="{{ old('name') }}" required
               class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        @error('name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Email address</label>
        <input name="email" type="email" value="{{ old('email') }}" required
               class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        @error('email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Password</label>
        <div class="relative">
          <input id="passwordInput" name="password" type="password" required
                 class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
          <button type="button" onclick="togglePassword()" class="absolute right-2 top-2.5 text-sm text-gray-500 dark:text-gray-300">
            Show
          </button>
        </div>
        @error('password') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        <div class="text-xs text-gray-400 mt-1">Minimum 8 characters recommended.</div>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Role</label>
        <select name="role_type" required
                class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option value="admin" {{ old('role_type')==='admin' ? 'selected' : '' }}>Admin</option>
          <option value="agent" {{ old('role_type')==='agent' ? 'selected' : '' }}>Agent</option>
          <option value="viewer" {{ old('role_type')==='viewer' ? 'selected' : '' }}>Viewer</option>
          <option value="customer_admin" {{ old('role_type')==='customer_admin' ? 'selected' : '' }}>Customer Admin</option>
        </select>
        @error('role_type') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      <div class="flex items-center gap-3">
        <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Create</button>
        <a href="{{ route('customer-admin.users.index') }}" class="px-4 py-2 rounded bg-gray-100 dark:bg-gray-700 text-sm">Cancel</a>
      </div>
    </form>
  </div>
</div>

<script>
function togglePassword(){
  const inp = document.getElementById('passwordInput');
  if (!inp) return;
  if (inp.type === 'password') {
    inp.type = 'text';
    event.target.textContent = 'Hide';
  } else {
    inp.type = 'password';
    event.target.textContent = 'Show';
  }
}
</script>
@endsection
