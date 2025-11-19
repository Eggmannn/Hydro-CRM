@extends('layouts.app')

@section('title', 'Companies')

@section('header', 'Manage Companies')

@section('content')
<div class="space-y-6 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold">Company List</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">All companies in the system.</p>
        </div>

        <div class="w-full sm:w-auto">
            <a href="{{ route('crd-admin.companies.create') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow transition text-sm">
                + New Company
            </a>
        </div>
    </div>

    {{-- Search + Filter Bar --}}
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col md:flex-row md:items-center md:justify-between gap-3 transition">
        <div class="flex-1">
            <input type="text"
                id="searchInput"
                placeholder="Search companies…"
                class="w-full md:w-80 px-4 py-2 rounded border dark:border-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring focus:ring-blue-300 transition"
                aria-label="Search companies by name or domain">
        </div>

        <div class="flex w-full md:w-auto items-center gap-3 flex-col sm:flex-row">
            <select id="statusFilter" class="w-full sm:w-auto px-3 py-2 rounded border dark:border-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-200 transition text-sm">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>

            <button id="applyFilterBtn" class="w-full sm:w-auto px-4 py-2 bg-gray-200 dark:bg-gray-700 dark:text-gray-300 rounded transition text-sm">
                Filter
            </button>
        </div>
    </div>

    {{-- Companies Table (desktop) + Mobile Cards --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden transition">

        {{-- Desktop table: scrollable on small widths --}}
        <div class="hidden md:block w-full overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-sm font-semibold">Company Name</th>
                        <th class="px-6 py-3 text-sm font-semibold">Domain</th>
                        <th class="px-6 py-3 text-sm font-semibold">Created At</th>
                        <th class="px-6 py-3 text-sm font-semibold text-right">Actions</th>
                    </tr>
                </thead>

                <tbody id="companyTable" class="divide-y">
                    @foreach($companies as $company)
                    <tr class="company-row border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition" data-name="{{ strtolower($company->name) }}" data-domain="{{ strtolower($company->domain) }}" data-status="{{ $company->active ? 'active' : 'inactive' }}">
                        <td class="px-6 py-4 font-medium">
                            {{ $company->name }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="text-blue-600 dark:text-blue-400">{{ $company->domain }}</span>
                        </td>

                        <td class="px-6 py-4">
                            {{ $company->created_at->format('d M Y') }}
                        </td>

                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('crd-admin.company-users.index', $company->id) }}" class="inline-block px-3 py-1 bg-indigo-500 hover:bg-indigo-600 text-white rounded text-sm transition">Users</a>

                            <a href="{{ route('crd-admin.company-users.create', $company->id) }}" class="inline-block px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded text-sm transition">+ Add User</a>

                            <form action="{{ route('crd-admin.companies.destroy', $company->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this company?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-sm transition">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach

                    @if($companies->count() == 0)
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-500 dark:text-gray-400">
                            No companies found.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Mobile list --}}
        <div class="md:hidden divide-y">
            @foreach($companies as $company)
            <div class="company-card px-4 py-4" data-name="{{ strtolower($company->name) }}" data-domain="{{ strtolower($company->domain) }}" data-status="{{ $company->active ? 'active' : 'inactive' }}">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $company->name }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $company->domain }}</div>
                        <div class="text-xs text-gray-400 dark:text-gray-500 mt-2">Created {{ $company->created_at->format('d M Y') }}</div>
                    </div>

                    <div class="flex-shrink-0 flex flex-col items-end gap-2">
                        <a href="{{ route('crd-admin.company-users.index', $company->id) }}" class="w-full sm:w-auto inline-block px-3 py-2 rounded bg-indigo-500 hover:bg-indigo-600 text-white text-sm text-center">Users</a>

                        <a href="{{ route('crd-admin.company-users.create', $company->id) }}" class="w-full sm:w-auto inline-block px-3 py-2 rounded bg-yellow-500 hover:bg-yellow-600 text-white text-sm text-center">+ Add User</a>

                        <form action="{{ route('crd-admin.companies.destroy', $company->id) }}" method="POST" onsubmit="return confirm('Delete this company?')" class="w-full sm:w-auto">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-3 py-2 rounded bg-red-600 hover:bg-red-700 text-white text-sm">Delete</button>
                        </form>

                        {{-- Mobile Assume / Release --}}
                        @if(session('assumed_company_id') == $company->id)
                            @if(\Illuminate\Support\Facades\Route::has('crd.access.release'))
                                <form method="POST" action="{{ route('crd.access.release') }}" class="w-full">
                            @elseif(\Illuminate\Support\Facades\Route::has('crd-admin.access.release'))
                                <form method="POST" action="{{ route('crd-admin.access.release') }}" class="w-full">
                            @else
                                <form method="POST" action="{{ url('/crd/access/release') }}" class="w-full">
                            @endif
                                @csrf
                                <button class="w-full px-3 py-2 rounded bg-yellow-500 hover:bg-yellow-600 text-white text-sm">Release</button>
                            </form>
                        @else
                            @if(\Illuminate\Support\Facades\Route::has('crd.access.assume'))
                                <form method="POST" action="{{ route('crd.access.assume', $company->id) }}" class="w-full">
                            @elseif(\Illuminate\Support\Facades\Route::has('crd-admin.companies.assume'))
                                <form method="POST" action="{{ route('crd-admin.companies.assume', $company->id) }}" class="w-full">
                            @else
                                <form method="POST" action="{{ url('/crd/companies/'.$company->id.'/assume') }}" class="w-full">
                            @endif
                                @csrf
                                <input type="hidden" name="reason" value="Operational review">
                                <button type="submit" class="w-full px-3 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white text-sm" onclick="return confirm('Assume authorization for {{ $company->name }}?')">Assume Authorization</button>
                            </form>
                        @endif

                    </div>
                </div>
            </div>
            @endforeach

            @if($companies->count() == 0)
            <div class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">No companies found.</div>
            @endif
        </div>

    </div>
</div>

{{-- Search + Filter Script (defensive) --}}
<script>
(function () {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const applyFilterBtn = document.getElementById('applyFilterBtn');

    function normalize(s) { return (s || '').toString().trim().toLowerCase(); }

    function applyFilters() {
        const q = normalize(searchInput?.value);
        const status = normalize(statusFilter?.value);

        // desktop rows
        document.querySelectorAll('.company-row').forEach(row => {
            const name = normalize(row.dataset.name);
            const domain = normalize(row.dataset.domain);
            const rowStatus = normalize(row.dataset.status);

            const matchesQ = !q || name.includes(q) || domain.includes(q);
            const matchesStatus = !status || rowStatus === status;

            row.style.display = (matchesQ && matchesStatus) ? '' : 'none';
        });

        // mobile cards
        document.querySelectorAll('.company-card').forEach(card => {
            const name = normalize(card.dataset.name);
            const domain = normalize(card.dataset.domain);
            const cardStatus = normalize(card.dataset.status);

            const matchesQ = !q || name.includes(q) || domain.includes(q);
            const matchesStatus = !status || cardStatus === status;

            card.style.display = (matchesQ && matchesStatus) ? '' : 'none';
        });
    }

    // live search
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            applyFilters();
        });
    }

    // filter button for accessibility / explicit filtering
    if (applyFilterBtn) {
        applyFilterBtn.addEventListener('click', function (e) {
            e.preventDefault();
            applyFilters();
        });
    }

    // also apply filters initially (in case of query params)
    document.addEventListener('DOMContentLoaded', applyFilters);
})();
</script>

@endsection
