@php($title = 'SkelApp CMS Login')

@extends('admin.layout')

@section('content')
  <main class="admin-auth-shell">
    <section class="admin-auth-card">
      <div class="admin-auth-copy">
        <span class="admin-kicker">Private access</span>
        <h1>Sign in to publish news</h1>
        <p>This admin is private and intended only for SkelApp editorial access.</p>
      </div>

      <form method="POST" action="{{ route('admin.login.store') }}" class="admin-form">
        @csrf
        <label>
          <span>Email</span>
          <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
          @error('email')<small>{{ $message }}</small>@enderror
        </label>

        <label>
          <span>Password</span>
          <input type="password" name="password" autocomplete="current-password" required>
          @error('password')<small>{{ $message }}</small>@enderror
        </label>

        <label class="admin-checkbox">
          <input type="checkbox" name="remember" value="1">
          <span>Keep me signed in on this device</span>
        </label>

        <button type="submit" class="admin-primary-button">Sign in</button>
      </form>
    </section>
  </main>
@endsection
