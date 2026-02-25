<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">

  <div class="overflow-x-auto">
    <table class="min-w-full divide-y">
      <thead class="bg-gray-50 dark:bg-gray-700">
        <tr>
          <th class="px-4 py-3 text-left text-sm font-medium">Subject</th>
          <th class="px-4 py-3 text-left text-sm font-medium">Contact</th>
          <th class="px-4 py-3 text-left text-sm font-medium">Assignee</th>
          <th class="px-4 py-3 text-left text-sm font-medium">Status</th>
          <th class="px-4 py-3 text-right text-sm font-medium">Action</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        @forelse($tickets as $t)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
          <td class="px-4 py-4">
            <a href="{{ route('agent.tickets.show', $t->id) }}"
               class="font-semibold text-blue-600 hover:underline">
              {{ \Illuminate\Support\Str::limit($t->subject, 60) }}
            </a>
          </td>

          <td class="px-4 py-4 text-sm">
            {{ optional($t->contact)->name ?? '—' }}
          </td>

          <td class="px-4 py-4 text-sm">
            {{ optional($t->assignee)->name ?? 'Unassigned' }}
          </td>

          <td class="px-4 py-4 text-sm">
            {{ strtoupper($t->status) }}
          </td>

          <td class="px-4 py-4 text-right">
            <a href="{{ route('agent.tickets.show', $t->id) }}"
               class="px-3 py-1 rounded bg-blue-600 text-white text-sm">
              View
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="px-4 py-8 text-center text-gray-500">
            {{ $empty }}
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="p-4 border-t">
    {{ $tickets->links() }}
  </div>
</div>
