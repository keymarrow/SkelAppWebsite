@extends('admin.layout', ['title' => $submission->fullName().' – Contact submission'])

@section('content')
  <main class="admin-page cms-page--detail">
    <header class="admin-page-header">
      <div>
        <a href="{{ route('admin.submissions.index') }}" class="cms-back-link">← All submissions</a>
        <h1>{{ $submission->fullName() }}</h1>
        <p class="admin-page-subtitle">
          Submitted {{ $submission->created_at?->toDayDateTimeString() }}
          @if ($submission->read_at)
            · <span class="cms-status-pill cms-status-pill--live">Read</span>
          @endif
        </p>
      </div>

      <div class="cms-page-actions-header">
        <a href="mailto:{{ $submission->email }}?subject=Re: Your SkelApp demo request" class="cms-btn cms-btn-primary">Reply by email</a>
        <form method="POST" action="{{ route('admin.submissions.destroy', $submission) }}" onsubmit="return confirm('Delete this submission permanently?')" class="cms-inline-form">
          @csrf
          @method('DELETE')
          <button type="submit" class="cms-btn cms-btn-ghost cms-btn-danger">Delete</button>
        </form>
      </div>
    </header>

    <section class="admin-panel">
      <dl class="cms-detail-grid">
        <div>
          <dt>First name</dt>
          <dd>{{ $submission->first_name }}</dd>
        </div>
        <div>
          <dt>Last name</dt>
          <dd>{{ $submission->last_name }}</dd>
        </div>
        <div>
          <dt>Email</dt>
          <dd><a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a></dd>
        </div>
        <div>
          <dt>Phone</dt>
          <dd><a href="tel:{{ $submission->phone }}">{{ $submission->phone }}</a></dd>
        </div>
        <div>
          <dt>Company</dt>
          <dd>{{ $submission->company }}</dd>
        </div>
        <div>
          <dt>IP address</dt>
          <dd>{{ $submission->ip_address ?? '—' }}</dd>
        </div>
        <div class="cms-detail-grid-full">
          <dt>Device / browser</dt>
          <dd>{{ $submission->user_agent ?? '—' }}</dd>
        </div>
      </dl>
    </section>
  </main>
@endsection
