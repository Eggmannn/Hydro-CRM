@extends('layouts.app')

@section('header')
  Admin Dashboard
@endsection

@section('content')
@php
  use Illuminate\Support\Str;

  // Expect these variables from controller:
  // $stats = ['open'=>0,'pending'=>0,'closed'=>0,'unassigned'=>0,'total'=>0];
  // $recentTickets (collection)
  // $unassignedTickets (collection)
  // $agentWorkload (collection of ['name'=>..., 'count'=>...])

  $stats = $stats ?? ['open'=>0,'pending'=>0,'closed'=>0,'unassigned'=>0,'total'=>0];
  $recentTickets = $recentTickets ?? collect();
  $unassignedTickets = $unassignedTickets ?? collect();
  $agentWorkload = $agentWorkload ?? collect();
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

  {{-- Welcome --}}
  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div class="min-w-0">
      <h1 class="text-2xl font-semibold truncate">
        Welcome back, {{ auth()->user()->name ?? 'Admin' }}
      </h1>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
        Manage tickets, agents, and clients for
        <span class="font-semibold text-gray-700 dark:text-gray-200">
          {{ auth()->user()->company->name ?? 'your company' }}
        </span>.
      </p>
    </div>

    {{-- Quick actions --}}
    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
      <a href="{{ route('admin.tickets.create') }}"
         class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition shadow-sm">
        + New Ticket
      </a>

      <a href="{{ route('admin.users.index', ['type' => 'agent']) }}"
         class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:shadow-md transition">
        Manage Agents
      </a>

      <a href="{{ route('admin.users.index', ['type' => 'client']) }}"
         class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:shadow-md transition">
        Manage Clients
      </a>
    </div>
  </div>

  {{-- Stats --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

    {{-- Total --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 border border-gray-100 dark:border-gray-700">
      <div class="text-sm text-gray-500 dark:text-gray-400">Total Tickets</div>
      <div class="mt-2 text-3xl font-semibold">{{ $stats['total'] }}</div>
      <div class="mt-2 text-xs text-gray-400">All tickets in your company</div>
    </div>

    {{-- Open --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 border border-gray-100 dark:border-gray-700">
      <div class="text-sm text-gray-500 dark:text-gray-400">Open</div>
      <div class="mt-2 text-3xl font-semibold">{{ $stats['open'] }}</div>
      <div class="mt-2 text-xs text-gray-400">Needs attention</div>
    </div>

    {{-- Pending --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 border border-gray-100 dark:border-gray-700">
      <div class="text-sm text-gray-500 dark:text-gray-400">Pending</div>
      <div class="mt-2 text-3xl font-semibold">{{ $stats['pending'] }}</div>
      <div class="mt-2 text-xs text-gray-400">Waiting response / progress</div>
    </div>

    {{-- Closed --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 border border-gray-100 dark:border-gray-700">
      <div class="text-sm text-gray-500 dark:text-gray-400">Closed</div>
      <div class="mt-2 text-3xl font-semibold">{{ $stats['closed'] }}</div>
      <div class="mt-2 text-xs text-gray-400">Resolved tickets</div>
    </div>

    {{-- Unassigned --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 border border-gray-100 dark:border-gray-700">
      <div class="text-sm text-gray-500 dark:text-gray-400">Unassigned</div>
      <div class="mt-2 text-3xl font-semibold">{{ $stats['unassigned'] }}</div>
      <div class="mt-2 text-xs text-gray-400">Tickets without agent</div>
    </div>

  </div>

  {{-- Main grid --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left side: Unassigned tickets --}}
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
      <div class="p-5 flex items-center justify-between">
        <div>
          <h2 class="text-lg font-semibold">Unassigned Tickets</h2>
          <p class="text-sm text-gray-500 dark:text-gray-400">
            Assign these tickets to agents to speed up triage.
          </p>
        </div>

        <a href="{{ route('admin.tickets.index', ['status' => 'open']) }}"
           class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
          View all →
        </a>
      </div>

      <div class="divide-y dark:divide-gray-700">
        @forelse($unassignedTickets as $t)
          <a href="{{ route('admin.tickets.show', $t) }}"
             class="block p-5 hover:bg-gray-50 dark:hover:bg-gray-900 transition">

            <div class="flex items-start justify-between gap-4">
              <div class="min-w-0">
                <div class="font-semibold text-gray-900 dark:text-gray-100 truncate">
                  {{ Str::limit($t->subject, 80) }}
                </div>

                <div class="mt-1 text-sm text-gray-500 dark:text-gray-400 truncate">
                  {{ optional($t->contact)->name ?? 'No contact' }}
                  @if(optional($t->contact)->email)
                    • {{ optional($t->contact)->email }}
                  @endif
                </div>

                <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                  {{-- Priority badge --}}
                  @if($t->priority === 'high')
                    <span class="px-2 py-1 rounded-lg font-semibold bg-red-50 text-red-700">HIGH</span>
                  @elseif($t->priority === 'normal')
                    <span class="px-2 py-1 rounded-lg font-semibold bg-yellow-50 text-yellow-800">NORMAL</span>
                  @else
                    <span class="px-2 py-1 rounded-lg font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">LOW</span>
                  @endif

                  {{-- Status badge --}}
                  @if($t->status === 'open')
                    <span class="px-2 py-1 rounded-lg font-semibold bg-green-50 text-green-800">OPEN</span>
                  @elseif($t->status === 'pending')
                    <span class="px-2 py-1 rounded-lg font-semibold bg-yellow-50 text-yellow-800">PENDING</span>
                  @else
                    <span class="px-2 py-1 rounded-lg font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">CLOSED</span>
                  @endif

                  <span class="text-gray-400">
                    Created {{ optional($t->created_at)->diffForHumans() }}
                  </span>
                </div>
              </div>

              <div class="flex-shrink-0">
                <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-semibold bg-blue-50 text-blue-700">
                  Needs assignment
                </span>
              </div>
            </div>
          </a>
        @empty
          <div class="p-6 text-center text-gray-500 dark:text-gray-400">
            🎉 No unassigned tickets right now.
          </div>
        @endforelse
      </div>
    </div>

    {{-- Right side: Agent workload --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
      <div class="p-5">
        <h2 class="text-lg font-semibold">Agent Workload</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">
          How many active tickets each agent has.
        </p>
      </div>

      <div class="px-5 pb-5 space-y-3">
        @forelse($agentWorkload as $a)
          <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
              <div class="font-medium truncate">{{ $a['name'] }}</div>
              <div class="text-xs text-gray-400">Active tickets</div>
            </div>

            <div class="flex items-center gap-3">
              <div class="w-32 bg-gray-100 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                @php
                  $max = max(1, $agentWorkload->max('count') ?? 1);
                  $pct = intval(($a['count'] / $max) * 100);
                @endphp
                <div class="h-2 bg-blue-600" style="width: {{ $pct }}%"></div>
              </div>

              <div class="text-sm font-semibold w-8 text-right">
                {{ $a['count'] }}
              </div>
            </div>
          </div>
        @empty
          <div class="text-sm text-gray-500 dark:text-gray-400">
            No agents found.
          </div>
        @endforelse
      </div>

      <div class="px-5 pb-5">
        <a href="{{ route('admin.users.index', ['type' => 'agent']) }}"
           class="w-full inline-flex items-center justify-center px-4 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:shadow-md transition text-sm font-medium">
          View Agents
        </a>
      </div>
    </div>

  </div>

  {{-- Recent tickets --}}
  <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="p-5 flex items-center justify-between">
      <div>
        <h2 class="text-lg font-semibold">Recent Tickets</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">
          Latest created tickets in your company.
        </p>
      </div>

      <a href="{{ route('admin.tickets.index') }}"
         class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
        View all →
      </a>
    </div>

    <div class="divide-y dark:divide-gray-700">
      @forelse($recentTickets as $t)
        <a href="{{ route('admin.tickets.show', $t) }}"
           class="block px-5 py-4 hover:bg-gray-50 dark:hover:bg-gray-900 transition">
          <div class="flex items-center justify-between gap-4">
            <div class="min-w-0">
              <div class="font-medium truncate">
                #{{ $t->id }} — {{ Str::limit($t->subject, 80) }}
              </div>
              <div class="text-sm text-gray-500 dark:text-gray-400 truncate mt-1">
                {{ optional($t->contact)->name ?? 'No contact' }}
                • Assigned: {{ optional($t->assignee)->name ?? 'Unassigned' }}
              </div>
            </div>

            <div class="text-xs text-gray-400 whitespace-nowrap">
              {{ optional($t->created_at)->diffForHumans() }}
            </div>
          </div>
        </a>
      @empty
        <div class="p-6 text-center text-gray-500 dark:text-gray-400">
          No tickets yet.
        </div>
      @endforelse
    </div>
  </div>

</div>
@endsection
