@extends('admin.layout', ['title' => 'Affiliate applications – SkelApp CMS'])

@section('content')
  <main class="admin-page cms-page--list">
    <header class="admin-page-header">
      <div>
        <span class="admin-kicker">Affiliate program</span>
        <h1>Affiliate applications</h1>
        <p class="admin-page-subtitle">
          {{ $totalCount }} total · <strong>{{ $pendingCount }}</strong> pending review
        </p>
      </div>
    </header>

    <section class="admin-panel cms-list-panel">
      <form method="GET" action="{{ route('admin.affiliate-applications.index') }}" class="cms-list-toolbar">
        <input
          type="search"
          name="q"
          value="{{ $search }}"
          placeholder="Search by name, email, country, phone, method…"
          class="cms-input"
        >
        <div class="cms-filter-pills" role="group" aria-label="Filter">
          <a href="{{ route('admin.affiliate-applications.index') }}" class="{{ $filter === 'all' ? 'is-active' : '' }}">All</a>
          <a href="{{ route('admin.affiliate-applications.index', ['filter' => 'pending']) }}" class="{{ $filter === 'pending' ? 'is-active' : '' }}">Pending</a>
          <a href="{{ route('admin.affiliate-applications.index', ['filter' => 'reviewed']) }}" class="{{ $filter === 'reviewed' ? 'is-active' : '' }}">Reviewed</a>
        </div>
        <button type="submit" class="cms-btn cms-btn-primary">Search</button>
      </form>

      <div class="admin-table-wrap">
        <table class="admin-table cms-list-table">
          <thead>
            <tr>
              <th></th>
              <th>Name</th>
              <th>Email</th>
              <th>Country</th>
              <th>Promotional method</th>
              <th>Received</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @forelse ($applications as $application)
              <tr class="{{ $application->reviewed_at ? '' : 'is-unread' }}">
                <td><span class="cms-unread-dot" aria-label="{{ $application->reviewed_at ? 'Reviewed' : 'Pending' }}"></span></td>
                <td>
                  <a href="{{ route('admin.affiliate-applications.show', $application) }}">{{ $application->fullName() }}</a>
                </td>
                <td><a href="mailto:{{ $application->email }}">{{ $application->email }}</a></td>
                <td>{{ $application->country }}</td>
                <td>{{ $application->primary_promotional_method }}</td>
                <td title="{{ $application->created_at?->toDayDateTimeString() }}">
                  {{ $application->created_at?->diffForHumans() }}
                </td>
                <td class="cms-list-actions">
                  <a href="{{ route('admin.affiliate-applications.show', $application) }}" class="cms-btn cms-btn-ghost">View</a>
                  <form method="POST" action="{{ route('admin.affiliate-applications.destroy', $application) }}" onsubmit="return confirm('Delete this application permanently?')" class="cms-inline-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="cms-btn cms-btn-ghost cms-btn-danger">Delete</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="admin-table-empty">
                  @if ($search || $filter !== 'all')
                    No applications match your filters.
                  @else
                    No affiliate applications yet — they'll appear here when someone applies to the program.
                  @endif
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if ($applications->hasPages())
        <div class="cms-pagination">
          {{ $applications->onEachSide(1)->links() }}
        </div>
      @endif
    </section>
  </main>
@endsection
