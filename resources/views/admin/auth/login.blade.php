@php($title = 'SkelApp CMS Login')

@extends('admin.layout')

@section('content')
  <main class="admin-auth-shell">
    <section class="admin-auth-card admin-auth-card--login">
      <div class="admin-auth-copy">
        <span class="admin-kicker">Private access</span>
        <h1>Sign in to publish news</h1>
        <p>Use your admin account to publish, update, and manage news posts for the public SkelApp website.</p>
      </div>

      <form method="POST" action="{{ route('admin.login.store') }}" class="admin-form admin-auth-form">
        @csrf
        <label class="admin-field">
          <span>Email address</span>
          <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
          @error('email')<small class="admin-field-error">{{ $message }}</small>@enderror
        </label>

        <label class="admin-field">
          <span>Password</span>
          <span class="admin-password-field">
            <input
              id="admin-password"
              type="password"
              name="password"
              autocomplete="current-password"
              data-admin-password-input
              required
            >
            <button
              type="button"
              class="admin-password-toggle"
              data-admin-password-toggle
              aria-controls="admin-password"
              aria-pressed="false"
            >
              Show
            </button>
          </span>
          @error('password')<small class="admin-field-error">{{ $message }}</small>@enderror
        </label>

        <label class="admin-checkbox">
          <input type="checkbox" name="remember" value="1">
          <span>Keep me signed in on this device</span>
        </label>

        <button type="submit" class="admin-primary-button admin-auth-submit">Sign in</button>
      </form>
    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const passwordInput = document.querySelector('[data-admin-password-input]');
      const passwordToggle = document.querySelector('[data-admin-password-toggle]');

      if (!passwordInput || !passwordToggle) {
        return;
      }

      passwordToggle.addEventListener('click', function () {
        const shouldShowPassword = passwordInput.type === 'password';

        passwordInput.type = shouldShowPassword ? 'text' : 'password';
        passwordToggle.textContent = shouldShowPassword ? 'Hide' : 'Show';
        passwordToggle.setAttribute('aria-pressed', shouldShowPassword ? 'true' : 'false');
      });
    });
  </script>
@endsection
