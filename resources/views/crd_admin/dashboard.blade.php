@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('content')

<div class="space-y-6">

    {{-- ✅ Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- Total Companies --}}
        <div class="bg-white dark:bg-gray-800 dark:border dark:border-gray-700 shadow p-6 rounded-xl transition">
            <p class="text-sm text-gray-500 dark:text-gray-300">Total Companies</p>
            <p class="text-4xl font-bold mt-2 countUp" data-value="{{ $companies->count() }}">0</p>
        </div>

        {{-- Total Users --}}
        <div class="bg-white dark:bg-gray-800 dark:border dark:border-gray-700 shadow p-6 rounded-xl transition">
            <p class="text-sm text-gray-500 dark:text-gray-300">Total Users</p>
            <p class="text-4xl font-bold mt-2 countUp" data-value="{{ $users->count() }}">0</p>
        </div>

        {{-- Create Company --}}
        <a href="{{ route('crd-admin.companies.create') }}"
           class="bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 
                  text-white shadow p-6 rounded-xl transition flex flex-col justify-center">
            <p class="text-sm opacity-80">Create Company</p>
            <p class="text-xl font-semibold mt-1">+ New Company</p>
        </a>
    </div>


    {{-- ✅ Quick Actions --}}
    <div class="bg-white dark:bg-gray-800 dark:border dark:border-gray-700 p-6 rounded-xl shadow transition">
        <h2 class="text-lg font-semibold mb-4">Quick Actions</h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <a href="{{ route('crd-admin.companies.index') }}"
               class="flex items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 
               dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg transition">
                Manage Companies
            </a>

            <a href="{{ route('crd-admin.companies.create') }}"
               class="flex items-center justify-center p-4 bg-green-50 hover:bg-green-100 
               dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg transition">
                Create Company
            </a>

            <div class="flex items-center justify-center p-4 bg-yellow-50 
                dark:bg-gray-700 rounded-lg opacity-60 cursor-not-allowed">
                Tickets (Coming Soon)
            </div>

            <div class="flex items-center justify-center p-4 bg-purple-50
                dark:bg-gray-700 rounded-lg opacity-60 cursor-not-allowed">
                Contacts (Coming Soon)
            </div>
        </div>
    </div>


    {{-- ✅ Chart Section --}}
    <div class="bg-white dark:bg-gray-800 dark:border dark:border-gray-700 p-6 rounded-xl shadow transition">
        <h2 class="text-lg font-semibold mb-4">Company Growth (Last 6 Months)</h2>

        <canvas id="growthChart" height="120"></canvas>
    </div>

</div>


{{-- ✅ Chart.js Script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('growthChart').getContext('2d');

    const isDark = document.documentElement.classList.contains('dark');

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [{
                label: "Companies Created",
                data: {!! json_encode($companyCounts) !!},
                borderColor: isDark ? '#60A5FA' : '#2563EB',
                backgroundColor: isDark ? 'rgba(96,165,250,0.15)' : 'rgba(37,99,235,0.15)',
                borderWidth: 3,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: isDark ? '#93C5FD' : '#3B82F6'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    ticks: { color: isDark ? '#E5E7EB' : '#4B5563' },
                    grid: { color: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)' }
                },
                x: {
                    ticks: { color: isDark ? '#E5E7EB' : '#4B5563' },
                    grid: { color: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)' }
                }
            },
            plugins: {
                legend: {
                    labels: { color: isDark ? '#E5E7EB' : '#374151' }
                }
            }
        }
    });

    // ✅ Auto-update chart when toggling dark mode
    document.addEventListener("DOMContentLoaded", () => {
        const toggle = document.getElementById("themeIcon");
        if (!toggle) return;

        toggle.addEventListener("click", () => {
            location.reload(); // Quick refresh updates chart theme
        });
    });
</script>

<script>
    // ✅ Smooth animated counter
    function animateCounter(el, duration = 1000) {
        const target = parseInt(el.getAttribute("data-value"));
        let start = 0;
        let startTime = null;

        function update(timestamp) {
            if (!startTime) startTime = timestamp;

            const progress = Math.min((timestamp - startTime) / duration, 1);
            const current = Math.floor(progress * target);

            el.textContent = current;

            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                el.textContent = target;
            }
        }

        requestAnimationFrame(update);
    }

    // ✅ Run animation for all counters on page load
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll(".countUp").forEach(el => {
            animateCounter(el, 1200); // 1.2s animation
        });
    });
</script>


@endsection
