{{-- Company sidebar (customer admin + regular users) --}}
@php
    $btn = "flex items-center gap-4 px-5 py-4 rounded-xl text-base font-medium transition-all duration-200 hover:bg-blue-100 dark:hover:bg-gray-700 hover:scale-[1.02]";
@endphp

{{-- ---------- Desktop sidebar (md+) ---------- --}}
<aside class="hidden md:flex w-64 bg-white dark:bg-gray-800 shadow-lg h-screen fixed top-0 left-0 z-40 transition-all flex-col">
    <div class="p-6 border-b dark:border-gray-700 flex items-center justify-center">
        <img src="{{ asset('images/logo.png') }}" alt="CRM Logo" class="h-14 w-auto object-contain">
    </div>

    <nav class="p-4 space-y-3 text-gray-700 dark:text-gray-200 overflow-y-auto">
        {{-- Dashboard: route depends on whether this user is a customer_admin or regular --}}
        @if (method_exists(auth()->user(), 'isCustomerAdmin') && auth()->user()->isCustomerAdmin())
            <a href="{{ route('customer-admin.dashboard') }}" class="{{ $btn }} {{ request()->is('customer-admin/dashboard') ? 'bg-blue-100 dark:bg-gray-700 font-semibold' : '' }}">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 12l9-9 9 9v9H3z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Dashboard
            </a>
        @else
            <a href="{{ route('dashboard') }}" class="{{ $btn }} {{ request()->is('dashboard') ? 'bg-blue-100 dark:bg-gray-700 font-semibold' : '' }}">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 12l9-9 9 9v9H3z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Dashboard
            </a>
        @endif

        <a href="{{ route('customer-admin.users.index') }}" class="{{ $btn }} {{ request()->is('customer-admin/users*') ? 'bg-blue-100 dark:bg-gray-700 font-semibold' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6"><path d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.1a7.5 7.5 0 0 1 15 0A17.9 17.9 0 0 1 12 21.75a17.9 17.9 0 0 1-7.5-1.65Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
            Users
        </a>

        <a href="{{ route('customer-admin.tickets.index') }}" class="{{ $btn }} {{ request()->is('customer-admin/tickets*') ? 'bg-blue-100 dark:bg-gray-700 font-semibold' : '' }}">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 8h18M3 16h18" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Tickets
        </a>

        <a href="{{ route('customer-admin.contacts.index') }}" class="{{ $btn }} {{ request()->is('customer-admin/contacts*') ? 'bg-blue-100 dark:bg-gray-700 font-semibold' : '' }}">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M5 13l4 4L19 7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Contacts
        </a>
    </nav>
</aside>

{{-- ---------- Mobile floating drawer (md:hidden) ---------- --}}
{{-- Backdrop --}}
<div id="sidebarBackdrop" class="fixed inset-0 z-40 bg-black/40 hidden md:hidden" aria-hidden="true" onclick="closeSidebar()"></div>

<aside id="mobileSidebar"
       class="fixed inset-y-0 left-0 z-50 w-72 max-w-[85vw] transform -translate-x-full transition-transform duration-300 ease-in-out bg-white dark:bg-gray-800 shadow-lg md:hidden"
       role="dialog"
       aria-modal="true"
       aria-hidden="true">
    <div class="p-6 border-b dark:border-gray-700 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="CRM Logo" class="h-10 w-auto object-contain">
            <span class="font-semibold text-lg"></span>
        </div>

        <button aria-label="Close sidebar" onclick="closeSidebar()" class="p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 18L18 6M6 6l12 12" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
    </div>

    <nav id="mobileNav" class="p-4 space-y-3 text-gray-700 dark:text-gray-200 overflow-y-auto">
        {{-- Dashboard --}}
        @if (method_exists(auth()->user(), 'isCustomerAdmin') && auth()->user()->isCustomerAdmin())
            <a href="{{ route('customer-admin.dashboard') }}" class="{{ $btn }} block {{ request()->is('customer-admin/dashboard') ? 'bg-blue-100 dark:bg-gray-700 font-semibold' : '' }}" onclick="closeSidebar()">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 12l9-9 9 9v9H3z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Dashboard
            </a>
        @else
            <a href="{{ route('dashboard') }}" class="{{ $btn }} block {{ request()->is('dashboard') ? 'bg-blue-100 dark:bg-gray-700 font-semibold' : '' }}" onclick="closeSidebar()">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 12l9-9 9 9v9H3z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Dashboard
            </a>
        @endif

        {{-- Users --}}
        <a href="{{ route('customer-admin.users.index') }}" class="{{ $btn }} block {{ request()->is('customer-admin/users*') ? 'bg-blue-100 dark:bg-gray-700 font-semibold' : '' }}" onclick="closeSidebar()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6"><path d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.1a7.5 7.5 0 0 1 15 0A17.9 17.9 0 0 1 12 21.75a17.9 17.9 0 0 1-7.5-1.65Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
            Users
        </a>

        {{-- Tickets --}}
        <a href="{{ route('customer-admin.tickets.index') }}" class="{{ $btn }} block {{ request()->is('customer-admin/tickets*') ? 'bg-blue-100 dark:bg-gray-700 font-semibold' : '' }}" onclick="closeSidebar()">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 8h18M3 16h18" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Tickets
        </a>

        {{-- Contacts --}}
        <a href="{{ route('customer-admin.contacts.index') }}" class="{{ $btn }} block {{ request()->is('customer-admin/contacts*') ? 'bg-blue-100 dark:bg-gray-700 font-semibold' : '' }}" onclick="closeSidebar()">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M5 13l4 4L19 7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Contacts
        </a>
    </nav>
</aside>

{{-- ---------- Mobile drawer JS (namespaced, safe) ---------- --}}
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
    const focusable = s.querySelector('a, button, input');
    if (focusable) focusable.focus();
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

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        if (typeof window.closeSidebarMobile === 'function') window.closeSidebarMobile();
    }
});
</script>
