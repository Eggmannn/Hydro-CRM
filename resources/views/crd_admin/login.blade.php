@extends('layouts.auth')

@section('title', 'CRD Admin Login')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg overflow-hidden grid grid-cols-1 md:grid-cols-2">
      {{-- Left / visual --}}
      <div class="hidden md:flex flex-col items-center justify-center p-8 bg-gradient-to-br from-blue-600 to-indigo-600 text-white">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 mb-4">
        <h2 class="text-2xl font-bold mb-2">Welcome back</h2>
        <p class="text-sm opacity-90 text-blue-100 max-w-xs text-center">Sign in to continue.</p>
      </div>

      {{-- Right / form --}}
      <div class="p-8">
        <div class="mb-6">
          <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 md:hidden mx-auto mb-4">
          <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Sign in to your account</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Enter your credentials to continue.</p>
        </div>

        {{-- Validation / status --}}
        @if(session('status'))
          <div class="mb-4 p-3 rounded bg-green-50 text-green-800 border border-green-100 text-sm">
            {{ session('status') }}
          </div>
        @endif

        @if($errors->any())
          <div class="mb-4 p-3 rounded bg-red-50 text-red-800 border border-red-100 text-sm">
            <ul class="list-disc list-inside">
              @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('crd_admin.login.post') }}">
          @csrf

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-white-800 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Password</label>
            <input type="password" name="password" required
                   class="w-full rounded-lg border px-3 py-2 bg-white dark:bg-white-800 border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
          </div>

          <div class="flex items-center justify-between text-sm">
            <label class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-300">
              <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ old('remember') ? 'checked' : '' }}>
              Remember me
            </label>

            <div>
              <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline"></a>
            </div>
          </div>

          <div class="pt-2">
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700">
              Sign in
            </button>
          </div>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
          Don't have an account? <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">Contact your admin</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
