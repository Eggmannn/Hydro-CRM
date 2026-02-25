@extends('layouts.app')

@section('header')
Dashboard
@endsection

@section('content')
@php
    use Illuminate\Support\Str;
    $recentTickets = collect($recentTickets ?? [])->take(10);
@endphp

<style>
/* =========================
   THEME VARIABLES
========================= */
:root{
  --bg:#f6f7f9;
  --card:#ffffff;
  --ticket:#fafafa;
  --text:#111827;
  --muted:#6b7280;
  --accent:#2563eb;
  --border:#e5e7eb;

  --radius:12px;
  --shadow:0 6px 20px rgba(15,23,42,0.06);
}

/* =========================
   DARK MODE
========================= */
html.dark{
  --bg:#0f172a;
  --card:#111827;
  --ticket:#020617;
  --text:#e5e7eb;
  --muted:#9ca3af;
  --accent:#3b82f6;
  --border:#1f2937;

  --shadow:0 6px 20px rgba(0,0,0,.45);
}

/* =========================
   GLOBAL
========================= */
html,body{
  background:var(--bg);
  color:var(--text);
}

strong,h1,h2,h3,h4,h5{color:var(--text)}
a{color:inherit;text-decoration:none}

/* =========================
   LAYOUT
========================= */
.container-min{
  width:100%;
  padding:0 24px;
  margin:20px 0;
}

.grid{
  display:grid;
  grid-template-columns:repeat(12,1fr);
  gap:16px;
}

.card{
  grid-column:span 4;
  background:var(--card);
  border-radius:var(--radius);
  box-shadow:var(--shadow);
  padding:14px;
  border:1px solid var(--border);
}

.card-lg{grid-column:span 6}
.card-sm{grid-column:span 6}

/* =========================
   HEADER
========================= */
.header-row{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:16px;
  flex-wrap:wrap;
  gap:12px;
}

.page-title{font-size:18px;font-weight:600}
.page-sub{color:var(--muted);font-size:13px;margin-top:4px}

.header-actions{
  display:flex;
  gap:8px;
  flex-wrap:wrap;
}

.action{
  padding:9px 12px;
  border-radius:10px;
  border:1px solid var(--border);
  font-weight:600;
  background:var(--card);
  color:var(--text);
}

.action.primary{
  background:var(--accent);
  color:#fff;
  border-color:transparent;
}

/* =========================
   STATS
========================= */
.stat-title{font-size:13px;color:var(--muted)}
.stat-value{font-size:26px;font-weight:700}

/* =========================
   TICKETS
========================= */
.tickets{
  display:flex;
  flex-direction:column;
  gap:10px;
}

.ticket{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:12px;
  border-radius:10px;
  background:var(--ticket);
  border:1px solid var(--border);
  color:var(--text);
  gap:12px;
}

.ticket-left{
  display:flex;
  gap:12px;
  align-items:center;
  flex:1;
  min-width:0;
}

.avatar{
  width:36px;
  height:36px;
  border-radius:8px;
  background:var(--accent);
  color:#fff;
  font-weight:700;
  display:flex;
  align-items:center;
  justify-content:center;
  flex-shrink:0;
}

.ticket-sub{
  font-weight:600;
  white-space:nowrap;
  overflow:hidden;
  text-overflow:ellipsis;
  max-width:260px;
}

.small-muted{
  font-size:12px;
  color:var(--muted);
}

/* =========================
   BADGES
========================= */
.badge{
  padding:5px 10px;
  border-radius:999px;
  font-size:12px;
  font-weight:600;
}

.badge-open{background:#dcfce7;color:#166534}
.badge-pending{background:#fef3c7;color:#92400e}
.badge-closed{background:#e5e7eb;color:#374151}

html.dark .badge-open{background:#052e16;color:#86efac}
html.dark .badge-pending{background:#451a03;color:#fde68a}
html.dark .badge-closed{background:#020617;color:#cbd5f5}

/* =========================
   PRIORITY
========================= */
.priority{
  padding:5px 8px;
  border-radius:8px;
  font-size:12px;
  font-weight:700;
}

.pr-high{background:#fee2e2;color:#991b1b}
.pr-normal{background:#fef3c7;color:#92400e}
.pr-low{background:#e5e7eb;color:#374151}

html.dark .pr-high{background:#450a0a;color:#fecaca}
html.dark .pr-normal{background:#451a03;color:#fde68a}
html.dark .pr-low{background:#020617;color:#cbd5f5}

/* =========================
   BUTTONS
========================= */
.assign-btn{
  background:var(--accent);
  color:#fff;
  border:none;
  padding:8px 12px;
  border-radius:8px;
  font-size:13px;
  font-weight:600;
  cursor:pointer;
}

.assign-btn:hover{opacity:.9}

/* =========================
   MOBILE FIXES
========================= */
@media(max-width:768px){

  .container-min{padding:0 14px}

  .header-actions{
    width:100%;
  }

  .action{
    flex:1;
    text-align:center;
  }

  .ticket{
    flex-direction:column;
    align-items:flex-start;
  }

  .ticket-sub{
    max-width:100%;
    white-space:normal;
  }

  .ticket > div:last-child,
  .ticket form{
    width:100%;
    display:flex;
    justify-content:space-between;
    gap:8px;
    flex-wrap:wrap;
  }

  .assign-btn{
    width:100%;
    text-align:center;
  }
}

/* =========================
   GRID STACKING
========================= */
@media(max-width:992px){
  .card,.card-lg,.card-sm{
    grid-column:span 12;
  }
}
</style>

<div class="container-min">

  <div class="header-row">
    <div>
      <h1 class="page-title">Hello, {{ auth()->user()->name }}</h1>
      <div class="page-sub">Here’s an overview of your work</div>
    </div>

    <div class="header-actions">
      <a href="{{ route('agent.tickets.my') }}" class="action primary">My Tickets</a>
      <a href="{{ route('agent.tickets.index') }}" class="action primary">All Tickets</a>
      <a href="{{ route('agent.contacts.index') }}" class="action primary">Contacts</a>
    </div>
  </div>

  <div class="grid">

    <div class="card">
      <div class="stat-title">Total</div>
      <div class="stat-value">{{ $total ?? 0 }}</div>
      <div class="page-sub">Assigned to you</div>
    </div>

    <div class="card">
      <div class="stat-title">Open</div>
      <div class="stat-value">{{ $open ?? 0 }}</div>
      <div class="page-sub">Need action</div>
    </div>

    <div class="card">
      <div class="stat-title">Pending</div>
      <div class="stat-value">{{ $pending ?? 0 }}</div>
      <div class="page-sub">Waiting</div>
    </div>

    <div class="card-lg">
      <strong>My Recent Tickets</strong>
      <div class="page-sub">Assigned to you</div>

      <div class="tickets" style="margin-top:10px">
        @forelse($recentTickets as $t)
          <a href="{{ route('agent.tickets.show', $t->id) }}" class="ticket">
            <div class="ticket-left">
              <div class="avatar">{{ strtoupper(substr($t->subject ?? '-',0,1)) }}</div>
              <div>
                <div class="ticket-sub">{{ Str::limit($t->subject, 60) }}</div>
                <div class="small-muted">
                  {{ optional($t->contact)->name ?? '—' }} • {{ $t->created_at?->diffForHumans() }}
                </div>
              </div>
            </div>
            <div>
              <span class="badge badge-{{ $t->status }}">{{ ucfirst($t->status) }}</span>
              <span class="priority pr-{{ $t->priority }}">{{ strtoupper($t->priority) }}</span>
            </div>
          </a>
        @empty
          <div class="page-sub">No tickets assigned to you.</div>
        @endforelse
      </div>

      <div style="margin-top:10px;text-align:right">
        <a href="{{ route('agent.tickets.my') }}" class="page-sub">View all →</a>
      </div>
    </div>

    <div class="card-sm">
      <strong>Unassigned Tickets</strong>
      <div class="page-sub">Claim one quickly</div>

      <div class="tickets" id="unassignedContainer" style="margin-top:10px">
        <div class="page-sub">Loading…</div>
      </div>

      <div style="margin-top:10px;text-align:right">
        <a href="{{ route('agent.tickets.index') }}" class="page-sub">View all →</a>
      </div>
    </div>

  </div>
</div>

<script>
(function () {
  const box = document.getElementById('unassignedContainer');
  const csrf = "{{ csrf_token() }}";

  function formatDate(iso) {
    if (!iso) return '—';
    const d = new Date(iso);
    return d.toLocaleString();
  }

  async function loadUnassigned() {
    try {
      const res = await fetch("/agent/dashboard/unassigned", {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        credentials: 'same-origin'
      });

      if (!res.ok || !res.headers.get('content-type')?.includes('application/json')) {
        throw new Error();
      }

      const data = await res.json();

      if (!data.length) {
        box.innerHTML = `<div class="page-sub">No unassigned tickets 🎉</div>`;
        return;
      }

      box.innerHTML = data.map(t => `
        <div class="ticket">
          <div class="ticket-left">
            <div class="avatar">${(t.subject || '-')[0].toUpperCase()}</div>
            <div>
              <div class="ticket-sub">${t.subject ?? '—'}</div>
              <div class="small-muted">
                ${(t.contact?.name ?? '—')} • ${formatDate(t.created_at)}
              </div>
            </div>
          </div>
          <form method="POST" action="/agent/tickets/${t.id}/assign">
            <input type="hidden" name="_token" value="${csrf}">
            <button class="assign-btn">Assign to me</button>
          </form>
        </div>
      `).join('');

    } catch {
      box.innerHTML = `<div class="page-sub text-danger">Failed to load unassigned tickets</div>`;
    }
  }

  loadUnassigned();
  setInterval(loadUnassigned, 15000);
})();
</script>

@endsection
