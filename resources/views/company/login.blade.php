{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.auth')

@section('title', 'Login — CRM')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-10 bg-gray-100 dark:bg-gray-900">

    {{-- background decoration --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -left-40 w-[520px] h-[520px] bg-blue-500/20 blur-3xl rounded-full"></div>
        <div class="absolute -bottom-40 -right-40 w-[520px] h-[520px] bg-indigo-500/20 blur-3xl rounded-full"></div>
    </div>

    <div class="relative w-full max-w-5xl">
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur rounded-2xl shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-2 border border-gray-200/60 dark:border-gray-700/60">

            {{-- LEFT / visual --}}
            <div class="hidden md:flex flex-col justify-between p-10 bg-gradient-to-br from-blue-600 to-indigo-700 text-white">
                <div>
                    <img src="{{ asset('images/logo.png') }}" alt="CRM Logo" class="h-14 w-auto mb-6">

                    <h2 class="text-3xl font-semibold leading-tight">
                        Welcome back
                    </h2>

                    <p class="mt-3 text-sm text-blue-100 max-w-sm leading-relaxed">
                        Sign in to manage your tickets, users, and company operations securely.
                    </p>
                </div>

                <div class="mt-10 space-y-3 text-sm text-blue-100/95">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M12 3l9 4.5-9 4.5-9-4.5L12 3z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M3 10.5V16.5L12 21l9-4.5v-6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-white">Company roles supported</div>
                            <div class="text-blue-100/90">Customer Admin • Admin • Agent • Client</div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="mt-0.5">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M12 6v6l4 2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-white">Fast workflow</div>
                            <div class="text-blue-100/90">Create, assign, comment, and resolve tickets.</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT / form --}}
            <div class="p-8 sm:p-10">
                <div class="mb-7">
                    <img src="{{ asset('images/logo.png') }}" alt="CRM Logo" class="h-10 md:hidden mx-auto mb-4">

                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                        Sign in
                    </h3>

                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Enter your credentials to continue.
                    </p>

                    {{-- small hint --}}
                    <div class="mt-4 text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/40 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 leading-relaxed">
                        <span class="font-medium text-gray-700 dark:text-gray-200">Note:</span>
                        This login is for <span class="font-medium">Company Users</span>.
                    </div>
                </div>

                {{-- Validation / status --}}
                @if(session('status'))
                    <div class="mb-4 p-3 rounded-xl bg-green-50 text-green-800 border border-green-100 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-3 rounded-xl bg-red-50 text-red-800 border border-red-100 text-sm">
                        <div class="font-semibold mb-1">Please fix the following:</div>
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                            Email
                        </label>

                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M4 4h16v16H4V4z" stroke-width="0" />
                                    <path d="M4 6l8 6 8-6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M4 6v12h16V6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>

                            <input type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus
                                   placeholder="you@company.com"
                                   class="w-full rounded-xl border pl-10 pr-3 py-2.5 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700
                                          text-gray-900 dark:text-gray-100
                                          placeholder:text-gray-400
                                          focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                            Password
                        </label>

                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M7 11V8a5 5 0 0110 0v3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M6 11h12v10H6V11z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>

                            <input type="password"
                                   name="password"
                                   required
                                   placeholder="••••••••"
                                   class="w-full rounded-xl border pl-10 pr-3 py-2.5 bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700
                                          text-gray-900 dark:text-gray-100
                                          placeholder:text-gray-400
                                          focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                    </div>

                    {{-- Remember --}}
                    <div class="flex items-center justify-between text-sm">
                        <label class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-300">
                            <input type="checkbox"
                                   name="remember"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                   {{ old('remember') ? 'checked' : '' }}>
                            Remember me
                        </label>

                        {{-- keep empty if you don't have password reset --}}
                        <a href="#"
                           class="text-blue-600 dark:text-blue-400 hover:underline hidden">
                            Forgot password?
                        </a>
                    </div>

                    {{-- Submit --}}
                    <div class="pt-1">
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl
                                       bg-blue-600 text-white font-semibold
                                       hover:bg-blue-700
                                       focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-800
                                       transition">
                            Sign in
                        </button>
                    </div>
                </form>

                <div class="mt-7 text-center text-sm text-gray-500 dark:text-gray-400">
                    Don’t have an account?
                    <span class="text-gray-700 dark:text-gray-200 font-medium">Contact your Company.</span>
                </div>
            </div>
        </div>

        {{-- footer --}}
        <div class="mt-6 text-center text-xs text-gray-400">
            © {{ date('Y') }} CRM — Secure Company Ticketing
        </div>
    </div>
</div>
@endsection
