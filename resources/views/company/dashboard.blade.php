@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Str;
    // Safety: ensure recent collections are small to avoid huge render times if controller misbehaves
    $recentTickets = collect($recentTickets ?? [])->take(20);
    $recentUsers   = collect($recentUsers ?? [])->take(8);
    $priorityCounts = $priorityCounts ?? ['high'=>0,'normal'=>0,'low'=>0];
@endphp

<style>
:root{
  --bg:#f6f7f9; --card:#fff; --muted:#6b7280; --accent:#0ea5a4; --soft:rgba(16,24,40,0.04);
  --radius:12px; --shadow:0 6px 20px rgba(15,23,42,0.06); --text:#0f172a; --subtext:#4b5563;
  --card-border:rgba(15,23,42,0.03); --input-bg:#fff; --input-border:rgba(15,23,42,0.06);
}
.dark{ --bg:#0b1220; --card:#0f1724; --muted:#9aa4b2; --accent:#06b6d4; --soft:rgba(255,255,255,0.03);
  --shadow:0 8px 28px rgba(2,6,23,0.6); --text:#e6eef6; --subtext:#9aa4b2; --card-border:rgba(255,255,255,0.03);
  --input-bg:rgba(255,255,255,0.02); --input-border:rgba(255,255,255,0.06);
}
/* --- Dashboard layout fixes (paste in your dashboard view CSS) --- */

/* Make the dashboard container use available space and ignore strange margins */
/* FULLSCREEN FIX — keep original design */
.container-min{
  width:100%;
  max-width:none;     /* remove 1100px cap */
  margin:20px 0;      /* vertical spacing only */
  padding:0 24px;     /* same visual padding */
  box-sizing:border-box;
}


/* If some parent adds an offset (like a collapsed sidebar), ensure the dashboard sits normally */
.main-content, .content-wrapper, .app-content {
  margin-left: 0 !important;
  padding-left: 0 !important;
}

/* Make grid reliably use 12 equal columns (prevents columns collapsing into a single narrow column) */
.grid {
  grid-template-columns: repeat(12, minmax(0, 1fr)) !important;
  gap: 16px !important;
  width: 100%;
  box-sizing: border-box;
}

/* On desktop keep the card widths predictable */
@media (min-width: 993px) {
  .card { grid-column: span 4 !important; }
  .card-lg { grid-column: span 8 !important; }
  .card-sm { grid-column: span 4 !important; }
}

/* On mobile make everything full width (keeps things stacked) */
@media (max-width: 992px) {
  .card, .card-lg, .card-sm { grid-column: span 12 !important; width:100% !important; }
  .header-row { padding-right: 0 !important; }
}

/* Defensive: ensure body/content not pushed right by unexpected transforms */
body, html, #app, .wrapper {
  transform: none !important;
  direction: ltr !important;
}

/* If you still see a narrow column on the right, add a temporary outline to debug */
.container-min { outline: 1px dashed rgba(59,130,246,0.15); }

/* Base */
html,body{height:100%} body{margin:0;font-family:Inter,system-ui,-apple-system,"Segoe UI",Roboto,Arial;background:var(--bg);color:var(--text);-webkit-font-smoothing:antialiased;box-sizing:border-box}

/* Container & header */
.container-min{max-width:1100px;margin:20px auto;padding:0 14px;box-sizing:border-box}
.header-row{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:16px;flex-wrap:wrap}
.header-left{min-width:0}
.page-title{font-weight:600;font-size:18px;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.page-sub{color:var(--subtext);font-size:13px;margin-top:4px}

/* Actions */
.header-actions{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
.action{background:transparent;border:1px solid var(--card-border);padding:9px 12px;border-radius:10px;font-weight:600;color:var(--text);text-decoration:none;display:inline-flex;align-items:center;justify-content:center;min-height:40px;gap:8px}
.action.primary{background:var(--accent);color:#fff;border-color:transparent}

/* Grid & cards */
.grid{display:grid;gap:12px;grid-template-columns:repeat(12,1fr);align-items:start}
.card{grid-column:span 4;background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow);padding:14px;border:1px solid var(--card-border);box-sizing:border-box}
.card-lg{grid-column:span 8;padding:16px}
.card-sm{grid-column:span 4;padding:14px}
.stat-title{font-size:13px;color:var(--muted);margin-bottom:6px}
.stat-value{font-size:26px;font-weight:700;color:var(--text)}

/* Tickets */
.card-header{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:12px;flex-wrap:wrap}
.controls{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
.control-input{background:var(--input-bg);border:1px solid var(--input-border);padding:8px 10px;border-radius:8px;color:var(--text);font-size:13px;min-width:0;box-sizing:border-box}

/* ticket item */
.tickets{display:flex;flex-direction:column;gap:10px;margin-top:8px}
.ticket{display:flex;justify-content:space-between;align-items:center;padding:12px;border-radius:10px;background:linear-gradient(180deg,#fafafa,#fff);border:1px solid var(--card-border);gap:10px;text-decoration:none;color:inherit}
.dark .ticket{background:linear-gradient(180deg,rgba(13,18,25,0.7),rgba(15,23,34,0.7))}
.ticket-left{display:flex;gap:12px;align-items:center;min-width:0;flex:1 1 auto;overflow:hidden}
.avatar{width:40px;height:40px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#eef2ff,#f3f9ff);color:#0f172a;font-weight:700;font-size:14px;flex:0 0 40px}
.ticket-meta{min-width:0;overflow:hidden}
.ticket-sub{font-weight:600;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:420px;font-size:14px}
.ticket-sub .muted{color:var(--muted);font-size:12px;font-weight:500;display:block;margin-top:4px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis}
.ticket-right{display:flex;gap:8px;align-items:center;flex-shrink:0;justify-content:flex-end}

/* badge/priority */
.badge{padding:6px 10px;border-radius:999px;font-size:12px;font-weight:600;display:inline-block;white-space:nowrap}
.badge-open{background:#e6fffa;color:#03554e;border:1px solid rgba(6,182,212,0.08)}
.badge-pending{background:#fff7ed;color:#7a4100;border:1px solid rgba(249,115,22,0.06)}
.badge-closed{background:#f3f4f6;color:#374151;border:1px solid rgba(15,23,42,0.04)}
.priority{padding:5px 8px;border-radius:8px;font-size:12px;font-weight:700;white-space:nowrap}
.pr-high{background:#fff0f0;color:#b91c1c;border:1px solid rgba(185,28,28,0.06)}
.pr-normal{background:#fffbeb;color:#92400e;border:1px solid rgba(252,211,77,0.06)}
.pr-low{background:#f3f4f6;color:#374151;border:1px solid rgba(15,23,42,0.04)}

/* users */
.users{display:flex;flex-direction:column;gap:10px}
.user-row{display:flex;justify-content:space-between;align-items:center;padding:10px;border-radius:10px;background:var(--card);border:1px solid var(--card-border);gap:12px}
.user-left{display:flex;gap:12px;align-items:center;min-width:0}

/* helpers & focus */
.small-muted{color:var(--muted);font-size:12px}
.ticket:focus,.action:focus,.control-input:focus{outline:3px solid rgba(59,130,246,0.12);outline-offset:2px;border-radius:8px}

/* responsive */
@media(max-width:992px){
  .card,.card-lg,.card-sm{grid-column:span 12}
  .ticket-sub{max-width:260px;font-size:13px}
  .controls{width:100%}
  .control-input{flex:1 1 auto}
}
@media(max-width:640px){
  .header-row{flex-direction:column;align-items:flex-start;gap:8px}
  .header-actions{width:100%;display:flex;gap:8px;flex-wrap:wrap}
  .action{flex:1 1 auto;text-align:center;padding:10px 8px;font-size:14px}
  .ticket{flex-direction:column;align-items:stretch;padding:12px}
  .ticket-left{width:100%;display:flex;gap:10px;align-items:center}
  .ticket-sub{white-space:normal;max-width:100%}
  .ticket-right{width:100%;display:flex;justify-content:space-between;margin-top:10px;gap:8px}
  .controls{flex-direction:column}
  .control-input{width:100%}
  .user-row{flex-direction:column;align-items:flex-start;gap:8px}
}
</style>

<div class="container-min">

  <div class="header-row">
    <div class="header-left">
      <h1 class="page-title">Hello, {{ auth()->user()->name }}</h1>
      <div class="page-sub">Minimal overview — quick actions and recent activity for your company</div>
    </div>

    <div class="header-actions" aria-hidden="false">
      <a href="{{ route('customer-admin.users.create') }}" class="action primary" aria-label="Create user">+ User</a>
      <a href="{{ route('customer-admin.contacts.create') }}" class="action primary" aria-label="Create contact">+ Contact</a>
      <a href="{{ route('customer-admin.tickets.create') }}" class="action primary" aria-label="Create ticket">+ Ticket</a>
      <!-- theme toggle lives elsewhere -->
    </div>
  </div>

  <div class="grid" role="main">
    <div class="card" role="region" aria-label="Users">
      <div class="stat-title">Users</div>
      <div class="stat-value">{{ $usersCount ?? 0 }}</div>
      <div class="page-sub" style="margin-top:8px;">Active users in your company</div>
    </div>

    <div class="card" role="region" aria-label="Contacts">
      <div class="stat-title">Contacts</div>
      <div class="stat-value">{{ $contactsCount ?? 0 }}</div>
      <div class="page-sub" style="margin-top:8px;">People & organizations you manage</div>
    </div>

    <div class="card" role="region" aria-label="Open tickets">
      <div class="stat-title">Open Tickets</div>
      <div class="stat-value">{{ $openCount ?? 0 }}</div>
      <div class="page-sub" style="margin-top:8px;">Require attention</div>
    </div>

    <div class="card card-lg" role="region" aria-label="Recent tickets">
      <div class="card-header">
        <div>
          <div style="font-weight:700;font-size:15px">Recent Tickets</div>
          <div class="page-sub" style="margin-top:4px">Latest activity — tap to open ticket</div>
        </div>

        <div class="controls" role="search" aria-label="Search and filter tickets">
          <input id="searchTickets" class="control-input" placeholder="Search tickets or contact…" aria-label="Search tickets">
          <select id="statusFilter" class="control-input" aria-label="Filter by status">
            <option value="">All</option>
            <option value="open">Open</option>
            <option value="pending">Pending</option>
            <option value="closed">Closed</option>
          </select>
        </div>
      </div>

      <div class="tickets" id="ticketsContainer" aria-live="polite">
        @forelse($recentTickets as $t)
          @php
            $subject = Str::limit($t->subject ?? '', 80);
            $contactName = optional($t->contact)->name ?? '';
            $dataContact = $contactName ? 'data-contact="'.e(strtolower($contactName)).'"' : '';
          @endphp

          <a href="{{ route('customer-admin.tickets.edit', $t) }}"
             class="ticket"
             data-status="{{ $t->status ?? '' }}"
             data-subject="{{ strtolower($t->subject ?? '') }}"
             {!! $dataContact !!}>
            <div class="ticket-left">
              <div class="avatar" aria-hidden="true">{{ strtoupper(substr($t->subject ?? '-',0,1)) }}</div>
              <div class="ticket-meta">
                <div class="ticket-sub" title="{{ $t->subject ?? '' }}">{{ $subject }}</div>
                <span class="muted small-muted">{{ $contactName ? $contactName . ' • ' : '' }}{{ optional($t->created_at)->diffForHumans() }}</span>
              </div>
            </div>

            <div class="ticket-right" role="group" aria-label="Status and priority">
              @if(($t->status ?? '') === 'open')
                <div class="badge badge-open">Open</div>
              @elseif(($t->status ?? '') === 'pending')
                <div class="badge badge-pending">Pending</div>
              @else
                <div class="badge badge-closed">Closed</div>
              @endif

              @if(($t->priority ?? '') === 'high')
                <div class="priority pr-high">HIGH</div>
              @elseif(($t->priority ?? '') === 'normal')
                <div class="priority pr-normal">NORMAL</div>
              @else
                <div class="priority pr-low">LOW</div>
              @endif
            </div>
          </a>
        @empty
          <div class="page-sub">No tickets yet.</div>
        @endforelse
      </div>

      <div style="margin-top:14px;display:flex;justify-content:flex-end">
        <a class="page-sub" href="{{ route('customer-admin.tickets.index') }}">View all tickets →</a>
      </div>
    </div>

    <div class="card card-sm" role="region" aria-label="Recent users">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
        <div style="font-weight:700">Recent Users</div>
        <div class="page-sub">{{ $recentUsers->count() }} shown</div>
      </div>

      <div class="users" id="recentUsersList">
        @forelse($recentUsers as $u)
          <div class="user-row">
            <div class="user-left">
              <div class="avatar" aria-hidden="true">{{ strtoupper(substr($u->name ?? '-',0,1)) }}</div>
              <div style="min-width:0">
                <div style="font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:180px;">{{ $u->name }}</div>
                <div class="page-sub">{{ $u->email }}</div>
              </div>
            </div>
            <div style="text-align:right">
              <div class="small-muted" style="font-size:12px">{{ optional($u->created_at)->diffForHumans() }}</div>
              <div style="margin-top:6px">
                <span class="badge badge-closed" style="background:transparent;border:1px solid var(--card-border);color:var(--subtext)">{{ optional($u->primaryRole())->role_type ?? '—' }}</span>
              </div>
            </div>
          </div>
        @empty
          <div class="page-sub">No users yet.</div>
        @endforelse
      </div>

      <div style="margin-top:14px;border-top:1px dashed var(--card-border);padding-top:12px">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <div class="page-sub">Priority breakdown</div>
          <div style="font-weight:700">{{ ($priorityCounts['high'] ?? 0) + ($priorityCounts['normal'] ?? 0) + ($priorityCounts['low'] ?? 0) }}</div>
        </div>

        @php $total = max(1, ($priorityCounts['high'] ?? 0) + ($priorityCounts['normal'] ?? 0) + ($priorityCounts['low'] ?? 0)); @endphp
        <div style="height:8px;border-radius:6px;overflow:hidden;display:flex;gap:2px;background:var(--soft);margin-top:10px;margin-bottom:8px">
          <div style="background:#ff6b6b;width:{{ round(($priorityCounts['high'] ?? 0)/$total*100) }}%"></div>
          <div style="background:#ffbf69;width:{{ round(($priorityCounts['normal'] ?? 0)/$total*100) }}%"></div>
          <div style="background:#d1d5db;width:{{ round(($priorityCounts['low'] ?? 0)/$total*100) }}%"></div>
        </div>

        <div style="display:flex;justify-content:space-between;color:var(--muted);font-size:13px">
          <div>High: {{ $priorityCounts['high'] ?? 0 }}</div>
          <div>Normal: {{ $priorityCounts['normal'] ?? 0 }}</div>
          <div>Low: {{ $priorityCounts['low'] ?? 0 }}</div>
        </div>
      </div>
    </div>
  </div> {{-- grid --}}
</div> {{-- container --}}

<script>
(function () {

  // Debounce utility (FIXED)
  function debounce(fn, wait) {
    let timeout;
    return function (...args) {
      clearTimeout(timeout);
      timeout = setTimeout(() => fn.apply(this, args), wait);
    };
  }

  // Ticket filtering
  const ticketsContainer = document.getElementById('ticketsContainer');
  const searchInput = document.getElementById('searchTickets');
  const statusFilter = document.getElementById('statusFilter');

  function filterTickets() {
    if (!ticketsContainer) return;

    const q = (searchInput?.value || '').toLowerCase().trim();
    const status = (statusFilter?.value || '').toLowerCase();
    const items = ticketsContainer.querySelectorAll('.ticket');

    window.requestAnimationFrame(() => {
      items.forEach(item => {
        const subject = (item.dataset.subject || '').toLowerCase();
        const contact = (item.dataset.contact || '').toLowerCase();
        const itemStatus = (item.dataset.status || '').toLowerCase();

        const matchesQ = !q || subject.includes(q) || contact.includes(q);
        const matchesStatus = !status || itemStatus === status;

        item.style.display = (matchesQ && matchesStatus) ? '' : 'none';
      });
    });
  }

  const debouncedFilter = debounce(filterTickets, 300);

  if (searchInput) {
    searchInput.addEventListener('input', debouncedFilter, { passive: true });
  }

  if (statusFilter) {
    statusFilter.addEventListener('change', filterTickets, { passive: true });
  }

})();
</script>
@endsection
