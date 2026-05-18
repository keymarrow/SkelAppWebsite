<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? 'SkelApp CMS' }}</title>
  <link rel="icon" href="{{ asset('assets/skel.png') }}" type="image/x-icon" />
  <link href="{{ asset('css/admin-news.css') }}?v={{ @filemtime(public_path('css/admin-news.css')) }}" rel="stylesheet" />
</head>
@php($isAdminAuthenticated = auth('admin')->check())
<body class="admin-body{{ $isAdminAuthenticated ? '' : ' admin-body--auth' }}">
  @if ($isAdminAuthenticated)
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
        <a href="{{ route('admin.posts.index') }}" class="{{ request()->routeIs('admin.posts.*') ? 'is-active' : '' }}">News posts</a>

        <div class="admin-nav-section">Site pages</div>
        @foreach (\App\Http\Controllers\Admin\PageController::PAGES as $pageSlug => $pageConfig)
          <a
            href="{{ route('admin.pages.edit', $pageSlug) }}"
            class="{{ (request()->routeIs('admin.pages.*') && request()->route('slug') === $pageSlug) ? 'is-active' : '' }}"
          >{{ $pageConfig['label'] }}</a>
        @endforeach

        <div class="admin-nav-section">Public site</div>
        <a href="{{ url('/') }}" target="_blank" rel="noreferrer">View home</a>
        <a href="{{ url('/pricing') }}" target="_blank" rel="noreferrer">View pricing</a>
        <a href="{{ route('news.index') }}" target="_blank" rel="noreferrer">View news</a>
      </nav>

      <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="admin-logout">Log out</button>
      </form>
    </aside>
  @endif

  <div class="admin-main-shell{{ $isAdminAuthenticated ? '' : ' admin-main-shell--auth' }}">
    @if (session('status'))
      <div class="admin-flash">{{ session('status') }}</div>
    @endif

    @yield('content')
  </div>
  <script src="{{ asset('js/admin-cms.js') }}?v={{ @filemtime(public_path('js/admin-cms.js')) }}" defer></script>
</body>
</html>
