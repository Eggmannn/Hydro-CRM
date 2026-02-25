<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CRM')</title>

    {{-- Tailwind + dark mode setup --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.documentElement.classList.add('dark');
        }
        tailwind.config = { darkMode: 'class' }
    </script>

    {{-- Smooth transitions --}}
    <style>
        body { transition: background 0.25s, color 0.25s; }
    </style>

    {{-- Heroicons (used inline) --}}
    <script src="https://unpkg.com/heroicons@2.0.18/dist/outline.js"></script>
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex transition">

    {{-- Role-aware Sidebar: include small partials for each role to keep logic simple --}}
    @if (auth('crd_admin')->check())
    @include('layouts.partials.sidebar_crd')

@elseif (auth()->check() && auth()->user()->isClient())
    @include('layouts.partials.sidebar_client')

@elseif (auth()->check() && auth()->user()->isAgent())
    @include('layouts.partials.sidebar_agent')

@elseif (auth()->check() && auth()->user()->isCompanyAdmin())
    @include('layouts.partials.sidebar_admin')

@elseif (auth()->check())
    @include('layouts.partials.sidebar_company')

@else

        {{-- Guest sidebar (minimal) --}}
        <aside class="w-64 bg-white dark:bg-gray-800 shadow-lg h-screen fixed top-0 left-0 z-40 transition-all">
            <div class="p-6 border-b dark:border-gray-700 flex items-center justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="CRM Logo" class="h-14 w-auto object-contain transition duration-300 dark:brightness-95">
            </div>

            <nav class="p-4 space-y-3 text-gray-700 dark:text-gray-200">
                @php
                    $btn = "flex items-center gap-4 px-5 py-4 rounded-xl 
                            text-base font-medium
                            transition-all duration-200 
                            hover:bg-blue-100 dark:hover:bg-gray-700
                            hover:scale-[1.02]";
                @endphp

                <a href="{{ route('login') }}" class="{{ $btn }}">Login</a>
                <a href="#" class="{{ $btn }}">About</a>
            </nav>
        </aside>
    @endif

    {{-- Main content area: responsive left margin (only on md+ screens) --}}
    <div id="mainContent" class="w-full transition-all md:ml-64 ml-0">

        {{-- Top Navbar --}}
        <header class="bg-white dark:bg-gray-800 shadow px-6 py-4 flex justify-between items-center transition">

            {{-- mobile hamburger (valid HTML; visible on md:hidden) --}}
            <div class="flex items-center">
                <button id="mobileHamburger" onclick="openSidebar()" aria-label="Open sidebar"
                        class="md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 mr-3">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M4 6h16M4 12h16M4 18h16" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <h2 class="text-xl font-semibold">@yield('header', '')</h2>
            </div>

            <div class="flex items-center gap-4">

                {{-- Dark Mode Toggle --}}
                <button onclick="toggleDarkMode()"
                        class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition flex items-center justify-center">

                    <svg id="iconSun" xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor"
                         class="w-6 h-6 hidden dark:block">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25M17.964 17.964l-1.591-1.591M12 21v-2.25M7.627 17.964l1.591-1.591M3 12h2.25M7.627 7.636l1.591 1.591M15 12a3 3 0 11-6 0 3 3 0 0 1 6 0z"/>
                    </svg>

                    <svg id="iconMoon" xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor"
                         class="w-6 h-6 dark:hidden">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 12.79A9 9 0 1111.21 3c.29 0 .58.02.86.05a7.5 7.5 0 009.79 9.79c.03.28.05.57.05.86z"/>
                    </svg>
                </button>

                {{-- Profile area (prefers crd_admin guard) --}}
                <div class="relative">
                    @if (auth('crd_admin')->check())
                        @php $profileName = auth('crd_admin')->user()->name ?? 'Super Admin'; @endphp
                        <button onclick="toggleProfileMenu()" class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center text-lg">
                                {{ strtoupper(substr($profileName, 0, 1)) }}
                            </div>
                            <span class="font-medium">{{ $profileName }}</span>
                        </button>
                    @elseif(auth()->check())
                        @php $profileName = auth()->user()->name ?? 'User'; @endphp
                        <button onclick="toggleProfileMenu()" class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center text-lg">
                                {{ strtoupper(substr($profileName, 0, 1)) }}
                            </div>
                            <span class="font-medium">{{ $profileName }}</span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-2 rounded bg-blue-600 text-white">Login</a>
                    @endif

                    <div id="profileMenu"
                         class="hidden absolute right-0 mt-3 bg-white dark:bg-gray-700 shadow rounded-lg w-48 border dark:border-gray-600 transition">

                        @if (auth('crd_admin')->check())
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Profile Settings</a>
                            <form method="POST" action="{{ route('crd-admin.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Logout</button>
                            </form>
                        @elseif (auth()->check())
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Profile Settings</a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Login</a>
                        @endif

                    </div>
                </div>
            </div>

        </header>

        <main class="p-6 animate-fadeIn">
            @yield('content')
        </main>

    </div>

    {{-- Small page animations --}}
    <style>
        @keyframes fadeIn { from { opacity:0; transform:translateY(8px);} to { opacity:1; transform:translateY(0);} }
        .animate-fadeIn { animation: fadeIn .35s ease-out; }
    </style>

    <script>
        function toggleDarkMode() {
            const html = document.documentElement;

            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('darkMode', 'disabled');
            } else {
                html.classList.add('dark');
                localStorage.setItem('darkMode', 'enabled');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('darkMode') === 'enabled') {
                document.documentElement.classList.add('dark');
            }
        });

        function toggleProfileMenu() {
            document.getElementById('profileMenu').classList.toggle('hidden');
        }

        function openSidebar() {
            if (typeof window.openSidebarMobile === 'function') {
            return window.openSidebarMobile();
            }
            document.querySelectorAll('aside').forEach(a => a.classList.toggle('hidden'));
        }

        function closeSidebar() {
            if (typeof window.closeSidebarMobile === 'function') {
            return window.closeSidebarMobile();
            }
            document.querySelectorAll('aside').forEach(a => a.classList.add('hidden'));
        }

        document.addEventListener('DOMContentLoaded', function () {
            const hamburger = document.getElementById('mobileHamburger');
            if (hamburger) hamburger.addEventListener('click', openSidebar);
        });
    </script>

</body>
</html>
