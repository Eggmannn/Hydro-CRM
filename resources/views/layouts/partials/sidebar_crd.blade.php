{{-- resources/views/layouts/partials/sidebar_crd.blade.php --}}

@php
  $btn = "flex items-center gap-4 px-5 py-4 rounded-xl text-base font-medium transition-all duration-200 hover:bg-blue-100 dark:hover:bg-gray-700 hover:scale-[1.02]";
@endphp

{{-- ---------- Desktop sidebar (md+) ---------- --}}
<aside class="hidden md:flex w-64 bg-white dark:bg-gray-800 shadow-lg h-screen fixed top-0 left-0 z-40 transition-all flex-col">
  <div class="p-6 border-b dark:border-gray-700 flex items-center justify-center">
    <img src="{{ asset('images/logo.png') }}" alt="CRM Logo" class="h-14 w-auto object-contain">
  </div>

  <nav class="p-4 space-y-3 text-gray-700 dark:text-gray-200 overflow-y-auto" style="height: calc(100vh - 120px);">
    {{-- Dashboard --}}
    <a href="{{ route('crd_admin.dashboard') }}" class="{{ $btn }} {{ request()->is('crd-admin/dashboard') ? 'bg-blue-100 dark:bg-gray-700 font-semibold' : '' }}">
      <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 12l9-9 9 9v9H3z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      Dashboard
    </a>

    {{-- Companies --}}
    <a href="{{ route('crd-admin.companies.index') }}" class="{{ $btn }} {{ request()->is('crd-admin/companies*') ? 'bg-blue-100 dark:bg-gray-700 font-semibold' : '' }}">
      <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 21h18M5 3v18m7-18v18" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      Companies
    </a>

    {{-- Tickets (collapsible companies list) --}}
    <div>
      <button id="crd-tickets-toggle-desktop" class="w-full flex items-center justify-between gap-4 px-5 py-3 rounded-xl text-base font-medium hover:bg-blue-100 dark:hover:bg-gray-700 transition">
        <span class="flex items-center gap-4">
          <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 8h18M3 16h18M7 4v4M7 12v8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
          Tickets
        </span>
        <svg id="crd-tickets-chevron-desktop" class="w-5 h-5 transform transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 9l6 6 6-6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>

      <div id="crd-tickets-companies-desktop" class="mt-2 hidden px-3">
        {{-- search box --}}
        <div class="mb-2">
          <input id="crd-company-search-desktop" type="search" placeholder="Search company..." class="w-full text-sm rounded border px-3 py-2 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700">
        </div>

        <div id="crd-companies-list-desktop" class="space-y-1 text-sm">
          {{-- populated by JS --}}
          <div class="text-xs text-gray-500 dark:text-gray-400 px-2 py-2">Loading...</div>
        </div>

        <div class="mt-3">
          <a id="crd-companies-view-all-desktop" href="{{ route('crd-admin.companies.index') }}" class="text-xs text-blue-600 dark:text-blue-400">View all companies</a>
        </div>
      </div>
    </div>

    {{-- Contacts placeholder --}}
    <a href="#" class="flex items-center gap-4 px-5 py-4 rounded-xl text-base font-medium hover:bg-blue-100 dark:hover:bg-gray-700 transition">
      <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M5 13l4 4L19 7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      Contacts
    </a>
  </nav>
</aside>

{{-- ---------- Mobile floating drawer (md:hidden) ---------- --}}
{{-- Backdrop --}}
<div id="crdSidebarBackdrop" class="fixed inset-0 z-40 bg-black/40 hidden md:hidden" aria-hidden="true" onclick="closeSidebarMobile()"></div>

<aside id="crdMobileSidebar"
       class="fixed inset-y-0 left-0 z-50 w-72 max-w-[85vw] transform -translate-x-full transition-transform duration-300 ease-in-out bg-white dark:bg-gray-800 shadow-lg md:hidden"
       role="dialog"
       aria-modal="true"
       aria-hidden="true">
  <div class="p-6 border-b dark:border-gray-700 flex items-center justify-between">
    <div class="flex items-center gap-3">
      <img src="{{ asset('images/logo.png') }}" alt="CRM Logo" class="h-10 w-auto object-contain">
      <span class="font-semibold text-lg">{{ config('app.name', 'CRM') }}</span>
    </div>

    <button aria-label="Close sidebar" onclick="closeSidebarMobile()" class="p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
      <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 18L18 6M6 6l12 12" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>
  </div>

  <nav id="crdMobileNav" class="p-4 space-y-3 text-gray-700 dark:text-gray-200 overflow-y-auto">
    <a href="{{ route('crd_admin.dashboard') }}" class="{{ $btn }} block {{ request()->is('crd-admin/dashboard') ? 'bg-blue-100 dark:bg-gray-700 font-semibold' : '' }}" onclick="closeSidebarMobile()">
      <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 12l9-9 9 9v9H3z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      Dashboard
    </a>

    <a href="{{ route('crd-admin.companies.index') }}" class="{{ $btn }} block {{ request()->is('crd-admin/companies*') ? 'bg-blue-100 dark:bg-gray-700 font-semibold' : '' }}" onclick="closeSidebarMobile()">
      <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 21h18M5 3v18m7-18v18" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      Companies
    </a>

    {{-- Tickets collapsible in mobile --}}
    <div>
      <button id="crd-tickets-toggle-mobile" class="w-full flex items-center justify-between gap-4 px-5 py-3 rounded-xl text-base font-medium hover:bg-blue-100 dark:hover:bg-gray-700 transition" onclick="toggleCrdTicketsMobile()">
        <span class="flex items-center gap-4">
          <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 8h18M3 16h18M7 4v4M7 12v8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
          Tickets
        </span>
        <svg id="crd-tickets-chevron-mobile" class="w-5 h-5 transform transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 9l6 6 6-6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>

      <div id="crd-tickets-companies-mobile" class="mt-2 hidden px-3">
        <div class="mb-2">
          <input id="crd-company-search-mobile" type="search" placeholder="Search company..." class="w-full text-sm rounded border px-3 py-2 bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700">
        </div>

        <div id="crd-companies-list-mobile" class="space-y-1 text-sm">
          <div class="text-xs text-gray-500 dark:text-gray-400 px-2 py-2">Loading...</div>
        </div>

        <div class="mt-3">
          <a id="crd-companies-view-all-mobile" href="{{ route('crd-admin.companies.index') }}" class="text-xs text-blue-600 dark:text-blue-400">View all companies</a>
        </div>
      </div>
    </div>

    <a href="#" class="flex items-center gap-4 px-5 py-4 rounded-xl text-base font-medium hover:bg-blue-100 dark:hover:bg-gray-700 transition" onclick="closeSidebarMobile()">
      <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M5 13l4 4L19 7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      Contacts
    </a>
  </nav>
</aside>

{{-- ---------- JS: fetch + toggle + mobile drawer ---------- --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  const toggleDesktop = document.getElementById('crd-tickets-toggle-desktop');
  const chevronDesktop = document.getElementById('crd-tickets-chevron-desktop');
  const companiesContainerDesktop = document.getElementById('crd-tickets-companies-desktop');
  const companiesListDesktop = document.getElementById('crd-companies-list-desktop');
  const companySearchDesktop = document.getElementById('crd-company-search-desktop');

  const toggleMobile = document.getElementById('crd-tickets-toggle-mobile');
  const chevronMobile = document.getElementById('crd-tickets-chevron-mobile');
  const companiesContainerMobile = document.getElementById('crd-tickets-companies-mobile');
  const companiesListMobile = document.getElementById('crd-companies-list-mobile');
  const companySearchMobile = document.getElementById('crd-company-search-mobile');

  let companiesLoaded = false;
  let companiesData = [];

  const companiesUrl = `${location.origin}/crd-admin/companies/list-json`;

  function renderCompaniesTo(container, list) {
  if (!list || !list.length) {
    container.innerHTML = '<div class="text-xs text-gray-500 dark:text-gray-400 px-2 py-2">No companies found.</div>';
    return;
  }

  container.innerHTML = '';
  list.forEach(c => {
    const a = document.createElement('a');

    a.href = `/crd-admin/companies/${c.id}/assume/confirm`;

    a.className = 'block rounded px-2 py-2 hover:bg-gray-100 dark:hover:bg-gray-700';
    a.textContent = c.name;
    container.appendChild(a);
  });
}


  async function loadCompanies(q = '') {
    if (companiesListDesktop) companiesListDesktop.innerHTML = '<div class="text-xs text-gray-500 dark:text-gray-400 px-2 py-2">Loading...</div>';
    if (companiesListMobile) companiesListMobile.innerHTML = '<div class="text-xs text-gray-500 dark:text-gray-400 px-2 py-2">Loading...</div>';
    try {
      const url = new URL(companiesUrl, window.location.origin);
      if (q) url.searchParams.set('q', q);
      const res = await fetch(url.toString(), { credentials: 'same-origin', headers: { 'Accept': 'application/json' }});
      if (!res.ok) throw new Error('Fetch failed: ' + res.status);
      const data = await res.json();
      companiesData = data;
      if (companiesListDesktop) renderCompaniesTo(companiesListDesktop, data);
      if (companiesListMobile) renderCompaniesTo(companiesListMobile, data);
      companiesLoaded = true;
    } catch (err) {
      if (companiesListDesktop) companiesListDesktop.innerHTML = '<div class="text-xs text-red-500 px-2 py-2">Failed to load companies</div>';
      if (companiesListMobile) companiesListMobile.innerHTML = '<div class="text-xs text-red-500 px-2 py-2">Failed to load companies</div>';
      console.error('CRD companies load error', err);
    }
  }

  if (toggleDesktop) {
    toggleDesktop.addEventListener('click', function () {
      const opened = !companiesContainerDesktop.classList.contains('hidden');
      if (opened) {
        companiesContainerDesktop.classList.add('hidden');
        chevronDesktop.style.transform = '';
      } else {
        companiesContainerDesktop.classList.remove('hidden');
        chevronDesktop.style.transform = 'rotate(180deg)';
        if (!companiesLoaded) loadCompanies();
      }
    });

    if (companySearchDesktop) {
      let timer = null;
      companySearchDesktop.addEventListener('input', function () {
        clearTimeout(timer);
        const q = this.value.trim();
        timer = setTimeout(() => {
          if (companiesLoaded) {
            const filtered = companiesData.filter(c => c.name.toLowerCase().includes(q.toLowerCase()));
            renderCompaniesTo(companiesListDesktop, filtered);
          } else {
            loadCompanies(q);
          }
        }, 220);
      });
    }
  }

  // Mobile toggle
  if (toggleMobile) {
    toggleMobile.addEventListener('click', function () {
      const opened = !companiesContainerMobile.classList.contains('hidden');
      if (opened) {
        companiesContainerMobile.classList.add('hidden');
        chevronMobile.style.transform = '';
      } else {
        companiesContainerMobile.classList.remove('hidden');
        chevronMobile.style.transform = 'rotate(180deg)';
        if (!companiesLoaded) loadCompanies();
      }
    });

    if (companySearchMobile) {
      let timer2 = null;
      companySearchMobile.addEventListener('input', function () {
        clearTimeout(timer2);
        const q = this.value.trim();
        timer2 = setTimeout(() => {
          if (companiesLoaded) {
            const filtered = companiesData.filter(c => c.name.toLowerCase().includes(q.toLowerCase()));
            renderCompaniesTo(companiesListMobile, filtered);
          } else {
            loadCompanies(q);
          }
        }, 220);
      });
    }
  }

  window.__crd_load_companies = loadCompanies;
});

(function () {
  function _findSidebarNodes() {
    const sidebar = document.getElementById('crdMobileSidebar') || document.getElementById('mobileSidebar');
    const backdrop = document.getElementById('crdSidebarBackdrop') || document.getElementById('sidebarBackdrop');
    return { sidebar, backdrop };
  }

  window.openSidebarMobile = function () {
    const { sidebar, backdrop } = _findSidebarNodes();
    if (!sidebar || !backdrop) return;
    sidebar.classList.remove('-translate-x-full');
    sidebar.classList.add('translate-x-0');
    backdrop.classList.remove('hidden');
    document.documentElement.classList.add('overflow-hidden');
    sidebar.setAttribute('aria-hidden', 'false');
    const focusable = sidebar.querySelector('a, button, input, [tabindex]:not([tabindex="-1"])');
    if (focusable) focusable.focus();
  };

  window.closeSidebarMobile = function () {
    const { sidebar, backdrop } = _findSidebarNodes();
    if (!sidebar || !backdrop) return;
    sidebar.classList.add('-translate-x-full');
    sidebar.classList.remove('translate-x-0');
    backdrop.classList.add('hidden');
    document.documentElement.classList.remove('overflow-hidden');
    sidebar.setAttribute('aria-hidden', 'true');
  };

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      if (typeof window.closeSidebarMobile === 'function') window.closeSidebarMobile();
    }
  });

})();
</script>
