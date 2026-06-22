@extends('admin.layout', ['title' => 'Team & admins – SkelApp CMS'])

@section('content')
  <main class="admin-page">
    <header class="admin-page-header">
      <div>
        <span class="admin-kicker">Access</span>
        <h1>Team &amp; admins</h1>
        <p class="admin-page-subtitle">
          {{ $users->count() }} admin {{ \Illuminate\Support\Str::plural('account', $users->count()) }} with access to the CMS.
        </p>
      </div>
    </header>

    @if (session('error'))
      <div class="admin-flash admin-flash--error">{{ session('error') }}</div>
    @endif

    <div class="cms-users-grid">
      <section class="admin-panel">
        <div class="admin-panel-heading">
          <h2>Add an admin</h2>
        </div>
        <p class="admin-panel-note">New admins can sign in to the CMS immediately with the password you set.</p>

        <form method="POST" action="{{ route('admin.users.store') }}" class="admin-form">
          @csrf
          <label class="admin-field">
            <span>Full name</span>
            <input type="text" name="name" value="{{ old('name') }}" maxlength="120" required>
            @error('name')<small class="admin-field-error">{{ $message }}</small>@enderror
          </label>

          <label class="admin-field">
            <span>Email address</span>
            <input type="email" name="email" value="{{ old('email') }}" autocomplete="off" required>
            @error('email')<small class="admin-field-error">{{ $message }}</small>@enderror
          </label>

          <label class="admin-field">
            <span>Password</span>
            <input type="password" name="password" autocomplete="new-password" required>
            @error('password')<small class="admin-field-error">{{ $message }}</small>@enderror
            <small class="admin-field-hint">At least 8 characters, with letters and numbers.</small>
          </label>

          <label class="admin-field">
            <span>Confirm password</span>
            <input type="password" name="password_confirmation" autocomplete="new-password" required>
          </label>

          <button type="submit" class="admin-primary-button">Add admin user</button>
        </form>
      </section>

      <section class="admin-panel">
        <div class="admin-panel-heading">
          <h2>Existing admins</h2>
        </div>

        <ul class="cms-user-list">
          @foreach ($users as $user)
            <li class="cms-user-row">
              <div class="cms-user-info">
                <span class="cms-user-name">
                  {{ $user->name }}
                  @if ($user->id === auth('admin')->id())<span class="cms-user-you">You</span>@endif
                </span>
                <span class="cms-user-email">{{ $user->email }}</span>
                <span class="cms-user-meta">
                  @if ($user->last_login_at)
                    Last sign-in {{ $user->last_login_at->diffForHumans() }}
                  @else
                    Never signed in
                  @endif
                </span>
              </div>

              @if ($user->id !== auth('admin')->id())
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                      onsubmit="return confirm('Remove {{ $user->name }} from the CMS?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="cms-btn cms-btn-danger">Remove</button>
                </form>
              @endif
            </li>
          @endforeach
        </ul>
      </section>
    </div>
  </main>
@endsection
