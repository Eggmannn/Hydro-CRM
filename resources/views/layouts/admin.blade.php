<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-900">
    <!-- Navbar -->
    <nav class="bg-white shadow fixed top-0 w-full z-10">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <h1 class="text-xl font-semibold text-blue-600">CRM PoC</h1>

            <!-- Profile Dropdown -->
            <div class="relative">
                <button id="profileButton" class="flex items-center space-x-2">
                    <span class="font-medium">{{ Auth::guard('crd_admin')->user()->name ?? 'Super Admin' }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div id="profileDropdown"
                     class="hidden absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg">
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">Profile Settings</a>
                    <form method="POST" action="{{ route('crd-admin.logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex pt-16">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow h-screen fixed">
            <div class="p-4 border-b font-semibold text-gray-600">Menu</div>
            <ul class="mt-2">
                <li>
                    <a href="{{ route('crd_admin.dashboard') }}"
                       class="block px-4 py-2 hover:bg-blue-50">🏠 Dashboard</a>
                </li>
                <li>
                    <a href="{{ route('crd-admin.companies.index') }}"
                       class="block px-4 py-2 hover:bg-blue-50">🏢 Companies</a>
                </li>
                <li>
                    <a href="#"
                       class="block px-4 py-2 hover:bg-blue-50">👥 Users</a>
                </li>
                <li>
                    <a href="#"
                       class="block px-4 py-2 hover:bg-blue-50">⚙️ Settings</a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="ml-64 flex-1 p-6">
            @yield('content')
        </main>
    </div>

    <script>
        document.getElementById('profileButton').addEventListener('click', function () {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('hidden');
        });
    </script>
</body>
</html>
