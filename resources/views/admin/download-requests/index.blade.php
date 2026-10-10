@extends('admin.layout', ['title' => 'Download link requests – SkelApp CMS'])

@section('content')
  <main class="admin-page cms-page--list">
    <header class="admin-page-header">
      <div>
        <span class="admin-kicker">Download modal</span>
        <h1>Download link requests</h1>
        <p class="admin-page-subtitle">
          {{ $totalCount }} total · phone numbers people gave the download modal to receive the App Store / Google Play links by SMS
        </p>
      </div>
    </header>

    <section class="admin-panel cms-list-panel">
      <form method="GET" action="{{ route('admin.download-requests.index') }}" class="cms-list-toolbar">
        <input
          type="search"
          name="q"
          value="{{ $search }}"
          placeholder="Search by phone number…"
          class="cms-input"
        >
        <button type="submit" class="cms-btn cms-btn-primary">Search</button>
      </form>

      <div class="admin-table-wrap">
        <table class="admin-table cms-list-table">
          <thead>
            <tr>
              <th>Phone</th>
              <th>Country code</th>
              <th>Sent to</th>
              <th>Requested</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @forelse ($requests as $downloadRequest)
              <tr>
                <td><a href="tel:+{{ $downloadRequest->destination }}">{{ $downloadRequest->phone }}</a></td>
                <td>{{ $downloadRequest->country_code }}</td>
                <td>+{{ $downloadRequest->destination }}</td>
                <td title="{{ $downloadRequest->created_at?->toDayDateTimeString() }}">
                  {{ $downloadRequest->created_at?->diffForHumans() }}
                </td>
                <td class="cms-list-actions">
                  <form method="POST" action="{{ route('admin.download-requests.destroy', $downloadRequest) }}" onsubmit="return confirm('Delete this record permanently?')" class="cms-inline-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="cms-btn cms-btn-ghost cms-btn-danger">Delete</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="admin-table-empty">
                  @if ($search)
                    No requests match your search.
                  @else
                    No download link requests yet — they'll appear here when someone asks the download modal to text them the links.
                  @endif
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if ($requests->hasPages())
        <div class="cms-pagination">
          {{ $requests->onEachSide(1)->links() }}
        </div>
      @endif
    </section>
  </main>
@endsection
