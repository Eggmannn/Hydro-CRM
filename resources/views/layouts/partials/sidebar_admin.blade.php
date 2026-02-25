<aside class="w-64 bg-white dark:bg-gray-800 shadow-lg h-screen fixed top-0 left-0 z-40 transition-all
              md:block hidden">

    <div class="p-6 border-b dark:border-gray-700 flex items-center justify-center">
        <img src="{{ asset('images/logo.png') }}"
             alt="CRM Logo"
             class="h-14 w-auto object-contain transition duration-300 dark:brightness-95">
    </div>

    @php
        $btn = "flex items-center gap-4 px-5 py-4 rounded-xl
                text-base font-medium
                transition-all duration-200
                hover:bg-blue-100 dark:hover:bg-gray-700
                hover:scale-[1.02]";

        $active = "bg-blue-100 dark:bg-gray-700 font-semibold";
    @endphp

    <nav class="p-4 space-y-2 text-gray-700 dark:text-gray-200">

        <a href="{{ route('admin.dashboard') }}"
           class="{{ $btn }} {{ request()->routeIs('admin.dashboard') ? $active : '' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 3v18h18M7 16V9m5 7V5m5 11v-7" />
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.tickets.index') }}"
           class="{{ $btn }} {{ request()->routeIs('admin.tickets.*') ? $active : '' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15.75 5.25h-7.5A2.25 2.25 0 0 0 6 7.5v9A2.25 2.25 0 0 0 8.25 18.75h7.5A2.25 2.25 0 0 0 18 16.5v-9A2.25 2.25 0 0 0 15.75 5.25Z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 9.75h6M9 13.5h6" />
            </svg>
            <span>Tickets</span>
        </a>

        <a href="{{ route('admin.users.index') }}"
           class="{{ $btn }} {{ request()->routeIs('admin.users.*') ? $active : '' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15 19.128a9.38 9.38 0 0 0 2.625.372M4.5 19.5a9.38 9.38 0 0 1 2.625-.372M15 5.25a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M4.5 19.5a7.5 7.5 0 0 1 15 0" />
            </svg>
            <span>Users</span>
        </a>

        <div class="pt-4 mt-4 border-t dark:border-gray-700">
            <div class="text-xs uppercase tracking-wider text-gray-400 px-5 mb-2">
                Admin Tools
            </div>

            <div class="px-5 text-sm text-gray-500 dark:text-gray-300 leading-relaxed">
                You can manage agents, clients, and tickets for your company.
            </div>
        </div>

    </nav>
</aside>

{{-- Backdrop --}}
<div id="sidebarBackdrop" class="fixed inset-0 z-40 bg-black/40 hidden md:hidden"
     aria-hidden="true" onclick="closeSidebar()"></div>

{{-- MOBILE sidebar --}}
<aside id="mobileSidebar"
       class="fixed inset-y-0 left-0 z-50 w-72 max-w-[85vw] transform -translate-x-full transition-transform duration-300 ease-in-out
              bg-white dark:bg-gray-800 shadow-lg md:hidden"
       role="dialog"
       aria-modal="true"
       aria-hidden="true">

    <div class="p-6 border-b dark:border-gray-700 flex items-center justify-between">
        <img src="{{ asset('images/logo.png') }}"
             alt="CRM Logo"
             class="h-10 w-auto object-contain transition duration-300 dark:brightness-95">

        <button aria-label="Close sidebar" onclick="closeSidebar()"
                class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <nav class="p-4 space-y-2 text-gray-700 dark:text-gray-200">

        <a href="{{ route('admin.dashboard') }}"
           class="{{ $btn }} {{ request()->routeIs('admin.dashboard') ? $active : '' }}"
           onclick="closeSidebar()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 3v18h18M7 16V9m5 7V5m5 11v-7" />
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.tickets.index') }}"
           class="{{ $btn }} {{ request()->routeIs('admin.tickets.*') ? $active : '' }}"
           onclick="closeSidebar()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15.75 5.25h-7.5A2.25 2.25 0 0 0 6 7.5v9A2.25 2.25 0 0 0 8.25 18.75h7.5A2.25 2.25 0 0 0 18 16.5v-9A2.25 2.25 0 0 0 15.75 5.25Z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 9.75h6M9 13.5h6" />
            </svg>
            <span>Tickets</span>
        </a>

        <a href="{{ route('admin.users.index') }}"
           class="{{ $btn }} {{ request()->routeIs('admin.users.*') ? $active : '' }}"
           onclick="closeSidebar()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15 19.128a9.38 9.38 0 0 0 2.625.372M4.5 19.5a9.38 9.38 0 0 1 2.625-.372M15 5.25a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M4.5 19.5a7.5 7.5 0 0 1 15 0" />
            </svg>
            <span>Users</span>
        </a>

        <div class="pt-4 mt-4 border-t dark:border-gray-700">
            <div class="text-xs uppercase tracking-wider text-gray-400 px-5 mb-2">
                Admin Tools
            </div>

            <div class="px-5 text-sm text-gray-500 dark:text-gray-300 leading-relaxed">
                You can manage agents, clients, and tickets for your company.
            </div>
        </div>

    </nav>
</aside>

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
