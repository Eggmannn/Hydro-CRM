@extends('layouts.auth')

@section('title', 'CRD Admin Login — CRM')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-10 bg-gray-950">

    {{-- background decoration --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-48 left-1/2 -translate-x-1/2 w-[700px] h-[700px] bg-red-500/10 blur-3xl rounded-full"></div>
        <div class="absolute -bottom-48 -left-48 w-[700px] h-[700px] bg-indigo-500/10 blur-3xl rounded-full"></div>
    </div>

    <div class="relative w-full max-w-5xl">
        <div class="bg-gray-900/80 backdrop-blur rounded-2xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2 border border-gray-800">

            {{-- LEFT / visual --}}
            <div class="hidden md:flex flex-col justify-between p-10 bg-gradient-to-br from-gray-900 via-gray-900 to-red-900/40 text-white">
                <div>
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="CRM Logo" class="h-12 w-auto">
                        <div class="leading-tight">
                            <div class="text-sm text-gray-300">CRM Platform</div>
                            <div class="text-lg font-semibold tracking-wide">CRD Admin Panel</div>
                        </div>
                    </div>

                    <h2 class="mt-10 text-3xl font-semibold leading-tight">
                        Restricted access
                    </h2>

                    <p class="mt-3 text-sm text-gray-300 max-w-sm leading-relaxed">
                        This portal is for <span class="font-semibold text-white">CRD administrators only</span>.
                        You can manage companies, authorize access, and perform platform operations.
                    </p>

                    <div class="mt-6 inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-red-500/10 border border-red-400/20 text-red-200 text-xs">
                        <span class="font-semibold">⚠️ Warning:</span>
                        High-privilege environment
                    </div>
                </div>

                <div class="mt-10 space-y-4 text-sm text-gray-300">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5">
                            <svg class="w-5 h-5 text-red-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M12 3l9 4.5-9 4.5-9-4.5L12 3z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M3 10.5V16.5L12 21l9-4.5v-6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-white">Platform management</div>
                            <div class="text-gray-300/90">Companies • Authorizations • Access control</div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="mt-0.5">
                            <svg class="w-5 h-5 text-red-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M12 6v6l4 2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-white">Audit & control</div>
                            <div class="text-gray-300/90">Assume sessions • Secure release flow</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT / form --}}
            <div class="p-8 sm:p-10 bg-gray-900">
                <div class="mb-7">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-semibold text-white">
                                CRD Admin Login
                            </h3>
                            <p class="text-sm text-gray-400 mt-1">
                                Sign in to access the platform panel.
                            </p>
                        </div>

                        {{-- mobile logo --}}
                        <img src="{{ asset('images/logo.png') }}" alt="CRM Logo" class="h-10 md:hidden">
                    </div>

                    <div class="mt-5 text-xs text-gray-400 bg-gray-950/50 border border-gray-800 rounded-xl px-4 py-3 leading-relaxed">
                        <span class="font-medium text-gray-200">Company users:</span>
                        Use the normal login page (<span class="font-mono text-gray-200">/login</span>).
                    </div>
                </div>

                {{-- Validation / status --}}
                @if(session('status'))
                    <div class="mb-4 p-3 rounded-xl bg-green-500/10 text-green-200 border border-green-500/20 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-3 rounded-xl bg-red-500/10 text-red-200 border border-red-500/20 text-sm">
                        <div class="font-semibold mb-1">Login failed:</div>
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('crd_admin.login.post') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-200 mb-1">
                            Admin Email
                        </label>

                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               placeholder="admin@crm.com"
                               class="w-full rounded-xl border px-3 py-2.5 bg-gray-950 border-gray-800
                                      text-white placeholder:text-gray-500
                                      focus:outline-none focus:ring-2 focus:ring-red-500" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-200 mb-1">
                            Password
                        </label>

                        <input type="password"
                               name="password"
                               required
                               placeholder="••••••••"
                               class="w-full rounded-xl border px-3 py-2.5 bg-gray-950 border-gray-800
                                      text-white placeholder:text-gray-500
                                      focus:outline-none focus:ring-2 focus:ring-red-500" />
                    </div>

                    {{-- Remember --}}
                    <div class="flex items-center justify-between text-sm">
                        <label class="inline-flex items-center gap-2 text-gray-300">
                            <input type="checkbox"
                                   name="remember"
                                   class="rounded border-gray-700 bg-gray-950 text-red-500 focus:ring-red-500"
                                   {{ old('remember') ? 'checked' : '' }}>
                            Remember me
                        </label>
                    </div>

                    {{-- Submit --}}
                    <div class="pt-1">
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl
                                       bg-red-600 text-white font-semibold
                                       hover:bg-red-700
                                       focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 focus:ring-offset-gray-900
                                       transition">
                            Sign in to Admin Panel
                        </button>
                    </div>
                </form>

                <div class="mt-7 text-center text-sm text-gray-400">
                    This portal is restricted to CRD administrators.
                </div>
            </div>
        </div>

        {{-- footer --}}
        <div class="mt-6 text-center text-xs text-gray-500">
            © {{ date('Y') }} CRM — CRD Admin Panel
        </div>
    </div>
</div>
@endsection
