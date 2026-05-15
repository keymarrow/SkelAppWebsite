<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? 'SkelApp CMS' }}</title>
  <link rel="icon" href="{{ asset('assets/skel.png') }}" type="image/x-icon" />
  <link href="{{ asset('css/admin-news.css') }}?v={{ @filemtime(public_path('css/admin-news.css')) }}" rel="stylesheet" />
</head>
<body class="admin-body">
  @auth('admin')
    <aside class="admin-sidebar">
      <a href="{{ route('admin.dashboard') }}" class="admin-brand">
        <span class="admin-brand-mark">S</span>
        <span>
          <strong>SkelApp CMS</strong>
          <small>News publishing</small>
        </span>
      </a>

      <nav class="admin-nav">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">Dashboard</a>
        <a href="{{ route('admin.posts.index') }}" class="{{ request()->routeIs('admin.posts.*') ? 'is-active' : '' }}">Posts</a>
        <a href="{{ route('news.index') }}" target="_blank" rel="noreferrer">View public news</a>
      </nav>

      <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="admin-logout">Log out</button>
      </form>
    </aside>
  @endauth

  <div class="admin-main-shell">
    @if (session('status'))
      <div class="admin-flash">{{ session('status') }}</div>
    @endif

    @yield('content')
  </div>
</body>
</html>
