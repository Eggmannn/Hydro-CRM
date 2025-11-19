@extends('layouts.app')

@section('title', 'Create User for ' . $company->name)

@section('content')
<div class="px-6 py-8 animate-fadeIn">

    {{-- ✅ Page Title --}}
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100">
        Add New User — {{ $company->name }}
    </h1>

    {{-- ✅ Validation Errors --}}
    @if ($errors->any())
        <div class="bg-red-100 dark:bg-red-800 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded mb-6">
            <strong>Whoops!</strong> Please fix the following:
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ✅ Form Card --}}
    <div class="bg-white dark:bg-gray-800 border dark:border-gray-700 shadow-lg rounded-xl p-8">

        <form method="POST" action="{{ route('crd-admin.company-users.store', $company->id) }}" class="space-y-6">
            @csrf

            {{-- ✅ Full Name --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-200">
            </div>

            {{-- ✅ Email --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-200">
            </div>

            {{-- ✅ Password --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold">Password</label>
                <input type="password" name="password" required
                       class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-200">
            </div>

            {{-- ✅ Confirm Password --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold">Confirm Password</label>
                <input type="password" name="password_confirmation" required
                       class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-200">
            </div>

            {{-- ✅ Role Selector --}}
            <div>
                <label class="block text-gray-700 dark:text-gray-300 font-semibold">Role</label>

                <select name="role" required
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-200">

                    <option value="">-- Select Role --</option>

                    {{-- ✅ Only Super Admin can assign Customer Admin --}}
                    @if(auth('crd_admin')->check())
                        <option value="customer_admin">Customer Admin</option>
                    @endif

                    <option value="admin">Company Admin</option>
                    <option value="agent">Agent</option>
                    <option value="viewer">Viewer</option>
                </select>

            </div>

            {{-- ✅ Actions --}}
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('crd-admin.company-users.index', $company->id) }}"
                   class="text-gray-600 dark:text-gray-300 hover:underline">
                    Cancel
                </a>

                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg shadow transition">
                    Create User
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
