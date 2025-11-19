@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 py-12">
    <div class="bg-white shadow-lg rounded-2xl p-10 w-full max-w-3xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">🏢 Company Dashboard</h1>

        <p class="text-gray-600 mb-8">
            Welcome, <strong>{{ Auth::user()->name }}</strong>!<br>
            You are logged in as part of <strong>{{ Auth::user()->company->name ?? 'your company' }}</strong>.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg p-4 text-center font-semibold">
                Manage Tickets
            </a>
            <a href="#" class="bg-green-500 hover:bg-green-600 text-white rounded-lg p-4 text-center font-semibold">
                View Contacts
            </a>
            <a href="#" class="bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg p-4 text-center font-semibold">
                Company Users
            </a>
            <a href="{{ route('logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="bg-red-500 hover:bg-red-600 text-white rounded-lg p-4 text-center font-semibold">
               Logout
            </a>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</div>
@endsection
