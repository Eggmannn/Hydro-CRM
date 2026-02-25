{{-- Client sidebar --}}

@php
    $btn = "flex items-center gap-4 px-5 py-4 rounded-xl text-base font-medium transition-all duration-200 
            hover:bg-blue-100 dark:hover:bg-gray-700 hover:scale-[1.02]";
@endphp

{{-- ---------- Desktop sidebar (md+) ---------- --}}
<aside class="hidden md:flex w-64 bg-white dark:bg-gray-800 shadow-lg h-screen fixed top-0 left-0 z-40 transition-all flex-col">
    <div class="p-6 border-b dark:border-gray-700 flex items-center justify-center">
        <img src="{{ asset('images/logo.png') }}" alt="CRM Logo" class="h-14 w-auto object-contain">
    </div>

    <nav class="p-4 space-y-3 text-gray-700 dark:text-gray-200 overflow-y-auto">

        {{-- Dashboard --}}
        <a href="{{ route('client.dashboard') }}"
           class="{{ $btn }} {{ request()->routeIs('client.dashboard') ? 'bg-blue-100 dark:bg-gray-700 font-semibold' : '' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M3 12l9-9 9 9v9H3z"
                      stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Dashboard
        </a>

        {{-- My Tickets --}}
        <a href="{{ route('client.tickets.index') }}"
           class="{{ $btn }} {{ request()->routeIs('client.tickets.*') ? 'bg-blue-100 dark:bg-gray-700 font-semibold' : '' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M4 6h16M4 12h16M4 18h16"
                      stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            My Tickets
        </a>
    </nav>
    <div class="pt-4 mt-4 border-t dark:border-gray-700">
    <div class="text-xs uppercase tracking-wider text-gray-400 px-5 mb-2">
        Client Tools
    </div>

    <div class="px-5 text-sm text-gray-500 dark:text-gray-300 leading-relaxed">
        You can create and view your support tickets here.
    </div>
</div>

</aside>

{{-- ---------- Mobile floating drawer (md:hidden) ---------- --}}
<div id="sidebarBackdrop"
     class="fixed inset-0 z-40 bg-black/40 hidden md:hidden"
     aria-hidden="true"
     onclick="closeSidebarMobile()"></div>

<aside id="mobileSidebar"
       class="fixed inset-y-0 left-0 z-50 w-72 max-w-[85vw] transform -translate-x-full
              transition-transform duration-300 ease-in-out bg-white dark:bg-gray-800 shadow-lg md:hidden"
       role="dialog" aria-modal="true" aria-hidden="true">

    <div class="p-6 border-b dark:border-gray-700 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="CRM Logo" class="h-10 w-auto object-contain">
            <span class="font-semibold text-lg">Client</span>
        </div>

        <button aria-label="Close sidebar" onclick="closeSidebarMobile()"
                class="p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12"
                      stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>

    <nav class="p-4 space-y-3 text-gray-700 dark:text-gray-200 overflow-y-auto">

        {{-- Dashboard --}}
        <a href="{{ route('client.dashboard') }}"
           class="{{ $btn }} block {{ request()->routeIs('client.dashboard') ? 'bg-blue-100 dark:bg-gray-700 font-semibold' : '' }}"
           onclick="closeSidebarMobile()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M3 12l9-9 9 9v9H3z"
                      stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Dashboard
        </a>

        {{-- My Tickets --}}
        <a href="{{ route('client.tickets.index') }}"
           class="{{ $btn }} block {{ request()->routeIs('client.tickets.*') ? 'bg-blue-100 dark:bg-gray-700 font-semibold' : '' }}"
           onclick="closeSidebarMobile()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M4 6h16M4 12h16M4 18h16"
                      stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            My Tickets
        </a>
    </nav>
    <div class="pt-4 mt-4 border-t dark:border-gray-700">
    <div class="text-xs uppercase tracking-wider text-gray-400 px-5 mb-2">
        Client Tools
    </div>

    <div class="px-5 text-sm text-gray-500 dark:text-gray-300 leading-relaxed">
        You can create and view your support tickets here.
    </div>
</div>

</aside>

{{-- ---------- Mobile drawer JS ---------- --}}
<script>
window.openSidebarMobile = function () {
    const s = document.getElementById('mobileSidebar');
    const b = document.getElementById('sidebarBackdrop');
    if (!s || !b) return;
    s.classList.remove('-translate-x-full');
    s.classList.add('translate-x-0');
    b.classList.remove('hidden');
    document.documentElement.classList.add('overflow-hidden');
    s.setAttribute('aria-hidden','false');
}

window.closeSidebarMobile = function () {
    const s = document.getElementById('mobileSidebar');
    const b = document.getElementById('sidebarBackdrop');
    if (!s || !b) return;
    s.classList.add('-translate-x-full');
    s.classList.remove('translate-x-0');
    b.classList.add('hidden');
    document.documentElement.classList.remove('overflow-hidden');
    s.setAttribute('aria-hidden','true');
}
</script>