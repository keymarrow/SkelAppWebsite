@extends('admin.layout', ['title' => 'Contact submissions – SkelApp CMS'])

@section('content')
  <main class="admin-page cms-page--list">
    <header class="admin-page-header">
      <div>
        <span class="admin-kicker">Inbox</span>
        <h1>Contact submissions</h1>
        <p class="admin-page-subtitle">
          {{ $totalCount }} total · <strong>{{ $unreadCount }}</strong> unread
        </p>
      </div>
    </header>

    <section class="admin-panel cms-list-panel">
      <form method="GET" action="{{ route('admin.submissions.index') }}" class="cms-list-toolbar">
        <input
          type="search"
          name="q"
          value="{{ $search }}"
          placeholder="Search by name, email, company, phone…"
          class="cms-input"
        >
        <div class="cms-filter-pills" role="group" aria-label="Filter">
          <a href="{{ route('admin.submissions.index') }}" class="{{ $filter === 'all' ? 'is-active' : '' }}">All</a>
          <a href="{{ route('admin.submissions.index', ['filter' => 'unread']) }}" class="{{ $filter === 'unread' ? 'is-active' : '' }}">Unread</a>
          <a href="{{ route('admin.submissions.index', ['filter' => 'read']) }}" class="{{ $filter === 'read' ? 'is-active' : '' }}">Read</a>
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
              <th>Company</th>
              <th>Phone</th>
              <th>Received</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @forelse ($submissions as $submission)
              <tr class="{{ $submission->read_at ? '' : 'is-unread' }}">
                <td><span class="cms-unread-dot" aria-label="{{ $submission->read_at ? 'Read' : 'Unread' }}"></span></td>
                <td>
                  <a href="{{ route('admin.submissions.show', $submission) }}">{{ $submission->fullName() }}</a>
                </td>
                <td><a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a></td>
                <td>{{ $submission->company }}</td>
                <td><a href="tel:{{ $submission->phone }}">{{ $submission->phone }}</a></td>
                <td title="{{ $submission->created_at?->toDayDateTimeString() }}">
                  {{ $submission->created_at?->diffForHumans() }}
                </td>
                <td class="cms-list-actions">
                  <a href="{{ route('admin.submissions.show', $submission) }}" class="cms-btn cms-btn-ghost">View</a>
                  <form method="POST" action="{{ route('admin.submissions.destroy', $submission) }}" onsubmit="return confirm('Delete this submission permanently?')" class="cms-inline-form">
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
                    No submissions match your filters.
                  @else
                    No contact submissions yet — they'll appear here when someone fills the form.
                  @endif
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if ($submissions->hasPages())
        <div class="cms-pagination">
          {{ $submissions->onEachSide(1)->links() }}
        </div>
      @endif
    </section>
  </main>
@endsection
