@extends('admin.layout', ['title' => $application->fullName().' – Affiliate application'])

@section('content')
  <main class="admin-page cms-page--detail">
    <header class="admin-page-header">
      <div>
        <a href="{{ route('admin.affiliate-applications.index') }}" class="cms-back-link">← All applications</a>
        <h1>{{ $application->fullName() }}</h1>
        <p class="admin-page-subtitle">
          Applied {{ $application->created_at?->toDayDateTimeString() }}
          @if ($application->reviewed_at)
            · <span class="cms-status-pill cms-status-pill--live">Reviewed</span>
          @else
            · <span class="cms-status-pill">Pending</span>
          @endif
        </p>
      </div>

      <div class="cms-page-actions-header">
        <a href="mailto:{{ $application->email }}?subject=Re: Your SkelApp affiliate application" class="cms-btn cms-btn-primary">Reply by email</a>
        <form method="POST" action="{{ route('admin.affiliate-applications.destroy', $application) }}" onsubmit="return confirm('Delete this application permanently?')" class="cms-inline-form">
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
          <dd>{{ $application->first_name }}</dd>
        </div>
        <div>
          <dt>Last name</dt>
          <dd>{{ $application->last_name }}</dd>
        </div>
        <div>
          <dt>Email</dt>
          <dd><a href="mailto:{{ $application->email }}">{{ $application->email }}</a></dd>
        </div>
        <div>
          <dt>Phone</dt>
          <dd><a href="tel:{{ $application->phoneDisplay() }}">{{ $application->phoneDisplay() }}</a></dd>
        </div>
        <div>
          <dt>Country</dt>
          <dd>{{ $application->country }}</dd>
        </div>
        <div>
          <dt>Primary promotional method</dt>
          <dd>{{ $application->primary_promotional_method }}</dd>
        </div>
        <div>
          <dt>How they heard about us</dt>
          <dd>{{ $application->hear_about_program }}</dd>
        </div>
        <div>
          <dt>Accepts marketing</dt>
          <dd>{{ $application->accepts_marketing ? 'Yes' : 'No' }}</dd>
        </div>
        <div>
          <dt>Agreement accepted</dt>
          <dd>{{ $application->accepts_agreement ? 'Yes' : 'No' }}</dd>
        </div>
        <div>
          <dt>Eligibility confirmed</dt>
          <dd>{{ $application->eligibility_confirmed ? 'Yes' : 'No' }}</dd>
        </div>
        <div>
          <dt>IP address</dt>
          <dd>{{ $application->ip_address ?? '—' }}</dd>
        </div>
        @if ($application->marketing_details)
          <div class="cms-detail-grid-full">
            <dt>Marketing details</dt>
            <dd>{{ $application->marketing_details }}</dd>
          </div>
        @endif
        <div class="cms-detail-grid-full">
          <dt>Device / browser</dt>
          <dd>{{ $application->user_agent ?? '—' }}</dd>
        </div>
      </dl>
    </section>
  </main>
@endsection
