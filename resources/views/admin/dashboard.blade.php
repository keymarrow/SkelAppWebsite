@extends('admin.layout', ['title' => 'Dashboard – SkelApp CMS'])

@section('content')
  <main class="admin-page dashboard-page">
    <header class="dashboard-header">
      <div>
        <h1 class="dashboard-title">Dashboard</h1>
        <p class="dashboard-subtitle">Real-time visitor activity and submissions across your site.</p>
      </div>

      <div class="dashboard-header-actions">
        <button type="button" class="dashboard-icon-btn" aria-label="Search" title="Search">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><path d="m21 21-4.3-4.3"></path></svg>
        </button>
        <button type="button" class="dashboard-icon-btn" aria-label="Theme" title="Theme">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2"></path><path d="M12 20v2"></path><path d="m4.93 4.93 1.41 1.41"></path><path d="m17.66 17.66 1.41 1.41"></path><path d="M2 12h2"></path><path d="M20 12h2"></path><path d="m4.93 19.07 1.41-1.41"></path><path d="m17.66 6.34 1.41-1.41"></path></svg>
        </button>
        <a href="{{ route('admin.submissions.index') }}" class="dashboard-icon-btn" aria-label="Notifications" title="Notifications" data-header-notif>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path></svg>
          <span class="dashboard-icon-badge" data-header-unread @if ($unreadSubmissions <= 0) hidden @endif>{{ $unreadSubmissions > 99 ? '99+' : $unreadSubmissions }}</span>
        </a>
        <a href="{{ route('admin.posts.create') }}" class="cms-btn cms-btn-primary">Create post</a>
      </div>
    </header>

    {{-- ── KPI cards ── --}}
    <section class="dashboard-kpis-section" aria-labelledby="dashboard-kpis-title">
      <header class="dashboard-section-head">
        <div>
          <h2 id="dashboard-kpis-title">Key metrics</h2>
          <p>Showing {{ strtolower($rangeLabel) }} compared with the previous period.</p>
        </div>

        <nav class="dashboard-range-pills" aria-label="Dashboard time range">
          @foreach ($ranges as $key => $label)
            <a
              href="{{ route('admin.dashboard', array_merge(request()->except(['range', 'start', 'end']), ['range' => $key])) }}"
              class="{{ $rangeKey === $key ? 'is-active' : '' }}"
              aria-current="{{ $rangeKey === $key ? 'page' : 'false' }}"
            >
              {{ $label }}
            </a>
          @endforeach
        </nav>
      </header>

      @if ($rangeKey === 'custom')
        <form method="get" action="{{ route('admin.dashboard') }}" class="dashboard-custom-range" aria-label="Custom date range">
          <input type="hidden" name="range" value="custom">
          <label>
            <span>From</span>
            <input type="date" name="start" value="{{ $customStart }}" max="{{ now()->toDateString() }}" required>
          </label>
          <label>
            <span>To</span>
            <input type="date" name="end" value="{{ $customEnd }}" max="{{ now()->toDateString() }}" required>
          </label>
          <button type="submit" class="cms-btn cms-btn-primary">Apply</button>
        </form>
      @endif

      <div class="dashboard-kpis" aria-label="Key metrics">
        @foreach ($kpis as $key => $card)
          @php $spark = $sparklines[$key] ?? []; @endphp
          <article class="kpi-card" data-kpi-card="{{ $key }}">
            <div class="kpi-card-body">
              <p class="kpi-card-label">{{ $card['label'] }}</p>
              <p class="kpi-card-value" data-kpi-value>{{ $card['value_formatted'] }}</p>
              <span class="kpi-card-delta {{ $card['delta_positive'] ? 'is-up' : 'is-down' }}" data-kpi-delta>
                {{ $card['delta_formatted'] }}
              </span>
            </div>
            <div class="kpi-card-spark">
              <canvas
                data-kpi-spark="{{ $key }}"
                data-spark-values="{{ json_encode(array_column($spark, 'value')) }}"
                data-spark-trend="{{ $card['delta_positive'] ? 'up' : 'down' }}"
              ></canvas>
            </div>
          </article>
        @endforeach
      </div>
    </section>

    {{-- ── Charts row ── --}}
    <section class="dashboard-grid">
      <article class="dashboard-card dashboard-card--main">
        <header class="dashboard-card-header">
          <div>
            <h2>Website visitors</h2>
            <p class="dashboard-card-subtitle">Your site's traffic over time</p>
          </div>
          <span class="dashboard-card-range">{{ $rangeLabel }}</span>
        </header>

        <div class="dashboard-chart-wrap">
          <canvas
            id="visitorsChart"
            data-visitors-chart
            data-labels="{{ json_encode($visitorsChart['labels']) }}"
            data-this-period="{{ json_encode($visitorsChart['this_period']) }}"
            data-prior-period="{{ json_encode($visitorsChart['prior_period']) }}"
          ></canvas>
        </div>

        <footer class="dashboard-card-footer">
          <span class="dashboard-legend"><span class="dashboard-legend-dot is-solid"></span> This period</span>
          <span class="dashboard-legend"><span class="dashboard-legend-dot is-dashed"></span> Previous period</span>
        </footer>
      </article>

      <article class="dashboard-card dashboard-card--side">
        <header class="dashboard-card-header dashboard-card-header--simple">
          <div>
            <h2>Users in last 30 minutes</h2>
            <p class="dashboard-realtime-count" data-realtime-count>{{ number_format($usersInLast30['total']) }}</p>
          </div>
        </header>

        <div class="dashboard-bars" data-realtime-bars>
          @foreach ($usersInLast30['series'] as $bucket)
            @php
              $values = array_column($usersInLast30['series'], 'value');
              $peak = max([1, ...$values]);
              $height = max(2, round(($bucket['value'] / $peak) * 100));
            @endphp
            <span
              class="dashboard-bar"
              style="height: {{ $height }}%"
              title="{{ $bucket['label'] }} · {{ $bucket['value'] }} user{{ $bucket['value'] === 1 ? '' : 's' }}"
            ></span>
          @endforeach
        </div>

        <section class="dashboard-active-pages">
          <header>
            <h3>Most active pages</h3>
            <a href="{{ route('admin.dashboard', ['range' => $rangeKey]) }}#all-pages">View all</a>
          </header>
          <ul data-active-pages>
            @forelse ($activePages as $page)
              <li>
                <span class="dashboard-active-pages-path">{{ $page['path'] }}</span>
                <span class="dashboard-active-pages-hits">{{ number_format($page['hits']) }}</span>
              </li>
            @empty
              <li class="dashboard-active-pages-empty">
                No traffic recorded yet. Visit the public site to start collecting analytics.
              </li>
            @endforelse
          </ul>
        </section>
      </article>
    </section>

    {{-- ── Recent activity row ── --}}
    <section class="dashboard-grid dashboard-grid--secondary">
      <article class="dashboard-card">
        <header class="dashboard-card-header">
          <div>
            <h2>Recent contact submissions</h2>
            <p class="dashboard-card-subtitle">
              <span data-submissions-total>{{ number_format($totalSubmissions) }}</span> total · <strong data-submissions-unread>{{ $unreadSubmissions }}</strong> unread
            </p>
          </div>
          <a href="{{ route('admin.submissions.index') }}" class="cms-btn cms-btn-ghost">View all</a>
        </header>

        <div class="admin-table-wrap">
          <table class="admin-table cms-list-table">
            <thead>
              <tr>
                <th></th>
                <th>Name</th>
                <th>Company</th>
                <th>Received</th>
              </tr>
            </thead>
            <tbody data-recent-submissions>
              @forelse ($recentSubmissions as $submission)
                <tr class="{{ $submission->read_at ? '' : 'is-unread' }}">
                  <td><span class="cms-unread-dot"></span></td>
                  <td><a href="{{ route('admin.submissions.show', $submission) }}">{{ $submission->fullName() }}</a></td>
                  <td>{{ $submission->company }}</td>
                  <td title="{{ $submission->created_at?->toDayDateTimeString() }}">
                    {{ $submission->created_at?->diffForHumans() }}
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="admin-table-empty">No submissions yet.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </article>

      <article class="dashboard-card">
        <header class="dashboard-card-header dashboard-card-header--simple">
          <div>
            <h2>Content snapshot</h2>
            <p class="dashboard-card-subtitle">Site at a glance</p>
          </div>
        </header>

        <ul class="dashboard-mini-stats">
          <li>
            <span class="dashboard-mini-label">Published posts</span>
            <strong data-mini-stat="publishedPosts">{{ number_format($publishedPosts) }}</strong>
          </li>
          <li>
            <span class="dashboard-mini-label">Total posts</span>
            <strong data-mini-stat="totalPosts">{{ number_format($totalPosts) }}</strong>
          </li>
          <li>
            <span class="dashboard-mini-label">Unread submissions</span>
            <strong data-mini-stat="unreadSubmissions">{{ number_format($unreadSubmissions) }}</strong>
          </li>
          <li>
            <span class="dashboard-mini-label">Live now</span>
            <strong data-mini-stat="liveNow">{{ number_format($usersInLast30['total']) }}</strong>
          </li>
        </ul>
      </article>
    </section>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const POLL_MS = 15000;
      const DATA_URL = @json(route('admin.dashboard.data'));

      const sparkCharts = {};
      let visitorsChart = null;
      let pollTimer = null;

      function setText(sel, value) {
        const el = document.querySelector(sel);
        if (el && el.textContent !== String(value)) el.textContent = value;
      }

      function buildSpark(canvas, values, trend) {
        const safeValues = values && values.length ? values : [0, 0];
        const color = trend === 'down' ? '#dc2626' : '#16a34a';
        return new Chart(canvas, {
          type: 'line',
          data: {
            labels: safeValues.map((_, i) => i),
            datasets: [{
              data: safeValues,
              borderColor: color,
              backgroundColor: color + '22',
              borderWidth: 2,
              fill: true,
              tension: 0.4,
              pointRadius: 0,
              pointHoverRadius: 3,
            }],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            plugins: { legend: { display: false }, tooltip: { enabled: false } },
            scales: { x: { display: false }, y: { display: false, beginAtZero: true } },
            elements: { line: { borderJoinStyle: 'round' } },
          },
        });
      }

      function buildVisitorsChart(canvas, labels, current, prior) {
        return new Chart(canvas, {
          type: 'line',
          data: {
            labels,
            datasets: [
              {
                label: 'This period', data: current,
                borderColor: '#16a34a', backgroundColor: 'rgba(22, 163, 74, 0.10)',
                borderWidth: 2.5, fill: true, tension: 0.35, pointRadius: 0, pointHoverRadius: 5,
              },
              {
                label: 'Previous period', data: prior,
                borderColor: '#94a3b8', borderDash: [6, 5],
                borderWidth: 2, fill: false, tension: 0.35, pointRadius: 0, pointHoverRadius: 5,
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 400 },
            interaction: { mode: 'index', intersect: false },
            plugins: {
              legend: { display: false },
              tooltip: {
                backgroundColor: '#0f1116', padding: 10,
                titleColor: '#ffffff', bodyColor: '#cbd5e1',
                borderColor: 'rgba(255,255,255,0.05)', borderWidth: 1, cornerRadius: 8,
              },
            },
            scales: {
              x: { grid: { display: false }, ticks: { color: '#94a3b8', maxRotation: 0, autoSkipPadding: 16 } },
              y: { beginAtZero: true, grid: { color: 'rgba(15, 17, 22, 0.06)', drawTicks: false }, ticks: { color: '#94a3b8', padding: 8 } },
            },
          },
        });
      }

      // ── Initial build from server-rendered data ──
      if (typeof Chart !== 'undefined') {
        Chart.defaults.font.family = "'Satoshi', system-ui, -apple-system, sans-serif";
        Chart.defaults.color = '#6b7380';

        document.querySelectorAll('[data-kpi-spark]').forEach((canvas) => {
          const key = canvas.dataset.kpiSpark;
          let values = [];
          try { values = JSON.parse(canvas.dataset.sparkValues || '[]'); } catch (e) {}
          sparkCharts[key] = buildSpark(canvas, values, canvas.dataset.sparkTrend);
        });

        const visitorsCanvas = document.querySelector('[data-visitors-chart]');
        if (visitorsCanvas) {
          const labels = JSON.parse(visitorsCanvas.dataset.labels || '[]');
          const current = JSON.parse(visitorsCanvas.dataset.thisPeriod || '[]');
          const prior = JSON.parse(visitorsCanvas.dataset.priorPeriod || '[]');
          visitorsChart = buildVisitorsChart(visitorsCanvas, labels, current, prior);
        }
      }

      // ── DOM patching ──
      function patchKpis(kpis, sparklines) {
        Object.entries(kpis || {}).forEach(([key, card]) => {
          const root = document.querySelector(`[data-kpi-card="${key}"]`);
          if (!root) return;
          const valEl = root.querySelector('[data-kpi-value]');
          const delEl = root.querySelector('[data-kpi-delta]');
          if (valEl) valEl.textContent = card.value_formatted;
          if (delEl) {
            delEl.textContent = card.delta_formatted;
            delEl.classList.toggle('is-up', !!card.delta_positive);
            delEl.classList.toggle('is-down', !card.delta_positive);
          }

          const chart = sparkCharts[key];
          const series = (sparklines && sparklines[key]) || [];
          const values = series.map((b) => b.value);
          if (chart && values.length) {
            const color = card.delta_positive ? '#16a34a' : '#dc2626';
            chart.data.labels = values.map((_, i) => i);
            chart.data.datasets[0].data = values;
            chart.data.datasets[0].borderColor = color;
            chart.data.datasets[0].backgroundColor = color + '22';
            chart.update('none');
          }
        });
      }

      function patchVisitorsChart(payload) {
        if (!visitorsChart || !payload) return;
        visitorsChart.data.labels = payload.labels || [];
        visitorsChart.data.datasets[0].data = payload.this_period || [];
        visitorsChart.data.datasets[1].data = payload.prior_period || [];
        visitorsChart.update();
      }

      function patchRealtime(usersInLast30) {
        if (!usersInLast30) return;
        const countEl = document.querySelector('[data-realtime-count]');
        if (countEl) countEl.textContent = new Intl.NumberFormat().format(usersInLast30.total || 0);

        const bars = document.querySelector('[data-realtime-bars]');
        if (bars) {
          const series = usersInLast30.series || [];
          const peak = Math.max(1, ...series.map((b) => b.value || 0));
          bars.innerHTML = series.map((b) => {
            const h = Math.max(2, Math.round(((b.value || 0) / peak) * 100));
            const noun = (b.value === 1) ? 'user' : 'users';
            const safeLabel = String(b.label).replace(/"/g, '&quot;');
            return `<span class="dashboard-bar" style="height: ${h}%" title="${safeLabel} · ${b.value} ${noun}"></span>`;
          }).join('');
        }
      }

      function patchActivePages(activePages) {
        const ul = document.querySelector('[data-active-pages]');
        if (!ul) return;
        if (!activePages || !activePages.length) {
          ul.innerHTML = '<li class="dashboard-active-pages-empty">No traffic recorded yet. Visit the public site to start collecting analytics.</li>';
          return;
        }
        const fmt = new Intl.NumberFormat();
        ul.innerHTML = activePages.map((p) => `
          <li>
            <span class="dashboard-active-pages-path">${escapeHtml(p.path)}</span>
            <span class="dashboard-active-pages-hits">${fmt.format(p.hits)}</span>
          </li>
        `).join('');
      }

      function patchRecentSubmissions(items) {
        const tbody = document.querySelector('[data-recent-submissions]');
        if (!tbody) return;
        if (!items || !items.length) {
          tbody.innerHTML = '<tr><td colspan="4" class="admin-table-empty">No submissions yet.</td></tr>';
          return;
        }
        tbody.innerHTML = items.map((s) => `
          <tr class="${s.is_unread ? 'is-unread' : ''}">
            <td><span class="cms-unread-dot"></span></td>
            <td><a href="${escapeAttr(s.url)}">${escapeHtml(s.full_name || '')}</a></td>
            <td>${escapeHtml(s.company || '')}</td>
            <td title="${escapeAttr(s.created_at_full || '')}">${escapeHtml(s.created_at_human || '')}</td>
          </tr>
        `).join('');
      }

      function patchHeaderUnread(count) {
        const badge = document.querySelector('[data-header-unread]');
        if (!badge) return;
        if (count > 0) {
          badge.textContent = count > 99 ? '99+' : String(count);
          badge.removeAttribute('hidden');
        } else {
          badge.setAttribute('hidden', '');
        }
      }

      function escapeHtml(s) {
        return String(s).replace(/[&<>"']/g, (c) => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
      }
      function escapeAttr(s) { return escapeHtml(s); }

      function applyPayload(data) {
        if (!data) return;
        patchKpis(data.kpis, data.sparklines);
        patchVisitorsChart(data.visitorsChart);
        patchRealtime(data.usersInLast30);
        patchActivePages(data.activePages);
        patchRecentSubmissions(data.recentSubmissions);

        const fmt = new Intl.NumberFormat();
        setText('[data-submissions-total]', fmt.format(data.totalSubmissions || 0));
        setText('[data-submissions-unread]', String(data.unreadSubmissions || 0));
        setText('[data-mini-stat="publishedPosts"]', fmt.format(data.publishedPosts || 0));
        setText('[data-mini-stat="totalPosts"]', fmt.format(data.totalPosts || 0));
        setText('[data-mini-stat="unreadSubmissions"]', fmt.format(data.unreadSubmissions || 0));
        setText('[data-mini-stat="liveNow"]', fmt.format((data.usersInLast30 && data.usersInLast30.total) || 0));

        patchHeaderUnread(Number(data.unreadSubmissions || 0));
      }

      async function fetchAndApply() {
        try {
          const qs = window.location.search; // preserves range / custom dates
          const url = DATA_URL + qs;
          const res = await fetch(url, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
          });
          if (!res.ok) return;
          applyPayload(await res.json());
        } catch (e) { /* swallow — next tick will retry */ }
      }

      function startPolling() {
        if (pollTimer) return;
        pollTimer = setInterval(fetchAndApply, POLL_MS);
      }
      function stopPolling() {
        if (!pollTimer) return;
        clearInterval(pollTimer);
        pollTimer = null;
      }

      document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
          stopPolling();
        } else {
          fetchAndApply();
          startPolling();
        }
      });

      startPolling();
    });
  </script>
@endsection
