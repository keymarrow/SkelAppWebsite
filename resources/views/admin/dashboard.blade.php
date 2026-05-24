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
        <a href="{{ route('admin.submissions.index') }}" class="dashboard-icon-btn" aria-label="Notifications" title="Notifications">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path></svg>
          @if ($unreadSubmissions > 0)
            <span class="dashboard-icon-badge">{{ $unreadSubmissions > 99 ? '99+' : $unreadSubmissions }}</span>
          @endif
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
          <article class="kpi-card">
            <div class="kpi-card-body">
              <p class="kpi-card-label">{{ $card['label'] }}</p>
              <p class="kpi-card-value">{{ number_format($card['value']) }}</p>
              <span class="kpi-card-delta {{ $card['delta_positive'] ? 'is-up' : 'is-down' }}">
                {{ $card['delta_positive'] ? '+' : '' }}{{ rtrim(rtrim(number_format($card['delta_percent'], 2), '0'), '.') }}%
              </span>
            </div>
            <div class="kpi-card-spark">
              <canvas
                data-kpi-spark
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
            <p class="dashboard-realtime-count">{{ number_format($usersInLast30['total']) }}</p>
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
          <ul>
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
              {{ number_format($totalSubmissions) }} total · <strong>{{ $unreadSubmissions }}</strong> unread
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
            <tbody>
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
            <strong>{{ number_format($publishedPosts) }}</strong>
          </li>
          <li>
            <span class="dashboard-mini-label">Total posts</span>
            <strong>{{ number_format($totalPosts) }}</strong>
          </li>
          <li>
            <span class="dashboard-mini-label">Unread submissions</span>
            <strong>{{ number_format($unreadSubmissions) }}</strong>
          </li>
          <li>
            <span class="dashboard-mini-label">Live now</span>
            <strong>{{ number_format($usersInLast30['total']) }}</strong>
          </li>
        </ul>
      </article>
    </section>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (typeof Chart === 'undefined') {
        // Chart.js failed to load — leave canvases blank (no error).
        return;
      }
      Chart.defaults.font.family = "'Satoshi', system-ui, -apple-system, sans-serif";
      Chart.defaults.color = '#6b7380';

      // KPI sparklines
      document.querySelectorAll('[data-kpi-spark]').forEach((canvas) => {
        let values = [];
        try { values = JSON.parse(canvas.dataset.sparkValues || '[]'); } catch (e) { values = []; }
        if (!values.length) values = [0, 0];
        const trend = canvas.dataset.sparkTrend === 'down' ? '#dc2626' : '#16a34a';
        new Chart(canvas, {
          type: 'line',
          data: {
            labels: values.map((_, i) => i),
            datasets: [{
              data: values,
              borderColor: trend,
              backgroundColor: trend + '22',
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
            plugins: { legend: { display: false }, tooltip: { enabled: false } },
            scales: { x: { display: false }, y: { display: false, beginAtZero: true } },
            elements: { line: { borderJoinStyle: 'round' } },
          },
        });
      });

      // Main visitors chart
      const visitorsCanvas = document.querySelector('[data-visitors-chart]');
      if (visitorsCanvas) {
        const labels = JSON.parse(visitorsCanvas.dataset.labels || '[]');
        const current = JSON.parse(visitorsCanvas.dataset.thisPeriod || '[]');
        const prior = JSON.parse(visitorsCanvas.dataset.priorPeriod || '[]');
        new Chart(visitorsCanvas, {
          type: 'line',
          data: {
            labels,
            datasets: [
              {
                label: 'This period',
                data: current,
                borderColor: '#16a34a',
                backgroundColor: 'rgba(22, 163, 74, 0.10)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.35,
                pointRadius: 0,
                pointHoverRadius: 5,
              },
              {
                label: 'Previous period',
                data: prior,
                borderColor: '#94a3b8',
                borderDash: [6, 5],
                borderWidth: 2,
                fill: false,
                tension: 0.35,
                pointRadius: 0,
                pointHoverRadius: 5,
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
              legend: { display: false },
              tooltip: {
                backgroundColor: '#0f1116',
                padding: 10,
                titleColor: '#ffffff',
                bodyColor: '#cbd5e1',
                borderColor: 'rgba(255,255,255,0.05)',
                borderWidth: 1,
                cornerRadius: 8,
              },
            },
            scales: {
              x: {
                grid: { display: false },
                ticks: { color: '#94a3b8', maxRotation: 0, autoSkipPadding: 16 },
              },
              y: {
                beginAtZero: true,
                grid: { color: 'rgba(15, 17, 22, 0.06)', drawTicks: false },
                ticks: { color: '#94a3b8', padding: 8 },
              },
            },
          },
        });
      }
    });
  </script>
@endsection
