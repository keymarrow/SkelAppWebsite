<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? 'SkelApp CMS' }}</title>
  <link rel="icon" href="{{ asset('assets/skel.png') }}" type="image/x-icon" />
  <link href="{{ asset('css/admin-news.css') }}?v={{ @filemtime(public_path('css/admin-news.css')) }}" rel="stylesheet" />
</head>
@php
  $isAdminAuthenticated = auth('admin')->check();
  $adminIconPaths = [
    'dashboard' => '<rect x="3" y="3" width="7" height="7" rx="1.5"></rect><rect x="14" y="3" width="7" height="7" rx="1.5"></rect><rect x="3" y="14" width="7" height="7" rx="1.5"></rect><rect x="14" y="14" width="7" height="7" rx="1.5"></rect>',
    'news' => '<path d="M5 5.5h14v13H5z"></path><path d="M8 8.5h8"></path><path d="M8 12h8"></path><path d="M8 15.5h5"></path>',
    'home' => '<path d="M3 10.5 12 3l9 7.5"></path><path d="M5.5 9.5V20h13V9.5"></path>',
    'pricing' => '<path d="M6 4h12v16l-3-2-3 2-3-2-3 2z"></path><path d="M9 9h6"></path><path d="M9 13h6"></path>',
    'faq' => '<circle cx="12" cy="12" r="9"></circle><path d="M9.75 9.25a2.75 2.75 0 1 1 4.1 2.4c-.92.53-1.35 1.02-1.35 2.1"></path><path d="M12 17h.01"></path>',
    'contact' => '<rect x="3" y="6" width="18" height="12" rx="2"></rect><path d="m4 8 8 6 8-6"></path>',
    'terms' => '<path d="M8 3.5h6l4 4V20H8z"></path><path d="M14 3.5V8h4"></path><path d="M10 12h6"></path><path d="M10 15.5h6"></path>',
    'global' => '<rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="M9 5v14"></path><path d="M13 9h4"></path><path d="M13 13h4"></path>',
    'inbox' => '<path d="M22 12h-6l-2 3h-4l-2-3H2"></path><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>',
    'logout' => '<path d="M15 17l5-5-5-5"></path><path d="M20 12H9"></path><path d="M11 4H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h5"></path>',
    'collapse' => '<path d="m15 18-6-6 6-6"></path>',
  ];
  $pageIconMap = [
    'home' => 'home',
    'pricing' => 'pricing',
    'faq' => 'faq',
    'contact' => 'contact',
    'terms' => 'terms',
    'global' => 'global',
  ];
  $renderAdminIcon = function (string $icon) use ($adminIconPaths) {
      $paths = $adminIconPaths[$icon] ?? $adminIconPaths['dashboard'];

      return new \Illuminate\Support\HtmlString(
          '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.$paths.'</svg>'
      );
  };
@endphp
<body class="admin-body{{ $isAdminAuthenticated ? '' : ' admin-body--auth' }}">
  @if ($isAdminAuthenticated)
    <aside class="admin-sidebar">
      <div class="admin-sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="admin-brand">
          <span class="admin-brand-mark">S</span>
          <span class="admin-brand-copy">
            <strong>SkelApp CMS</strong>
            <small>News publishing</small>
          </span>
        </a>
      </div>

      <nav class="admin-nav">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" title="Dashboard">
          <span class="admin-nav-icon">{!! $renderAdminIcon('dashboard') !!}</span>
          <span class="admin-nav-label">Dashboard</span>
        </a>
        <a href="{{ route('admin.posts.index') }}" class="{{ request()->routeIs('admin.posts.*') ? 'is-active' : '' }}" title="News posts">
          <span class="admin-nav-icon">{!! $renderAdminIcon('news') !!}</span>
          <span class="admin-nav-label">News posts</span>
        </a>

        @php($submissionsUnread = \App\Models\ContactSubmission::unreadCount())
        <a href="{{ route('admin.submissions.index') }}" class="{{ request()->routeIs('admin.submissions.*') ? 'is-active' : '' }}" title="Contact submissions">
          <span class="admin-nav-icon">{!! $renderAdminIcon('inbox') !!}</span>
          <span class="admin-nav-label">Contact submissions</span>
          @if ($submissionsUnread > 0)
            <span class="admin-nav-badge" aria-label="{{ $submissionsUnread }} unread">{{ $submissionsUnread }}</span>
          @endif
        </a>

        <div class="admin-nav-section">Site pages</div>
        @foreach (\App\Http\Controllers\Admin\PageController::PAGES as $pageSlug => $pageConfig)
          <a
            href="{{ route('admin.pages.edit', $pageSlug) }}"
            class="{{ (request()->routeIs('admin.pages.*') && request()->route('slug') === $pageSlug) ? 'is-active' : '' }}"
            title="{{ $pageConfig['label'] }}"
          >
            <span class="admin-nav-icon">{!! $renderAdminIcon($pageIconMap[$pageSlug] ?? 'dashboard') !!}</span>
            <span class="admin-nav-label">{{ $pageConfig['label'] }}</span>
          </a>
        @endforeach

        <div class="admin-nav-section">Public site</div>
        <a href="{{ url('/') }}" target="_blank" rel="noreferrer" title="View home">
          <span class="admin-nav-icon">{!! $renderAdminIcon('home') !!}</span>
          <span class="admin-nav-label">View home</span>
        </a>
        <a href="{{ url('/pricing') }}" target="_blank" rel="noreferrer" title="View pricing">
          <span class="admin-nav-icon">{!! $renderAdminIcon('pricing') !!}</span>
          <span class="admin-nav-label">View pricing</span>
        </a>
        <a href="{{ route('news.index') }}" target="_blank" rel="noreferrer" title="View news">
          <span class="admin-nav-icon">{!! $renderAdminIcon('news') !!}</span>
          <span class="admin-nav-label">View news</span>
        </a>
      </nav>

      <div class="admin-sidebar-footer">
        <form method="POST" action="{{ route('admin.logout') }}">
          @csrf
          <button type="submit" class="admin-logout" title="Log out">
            <span class="admin-nav-icon">{!! $renderAdminIcon('logout') !!}</span>
            <span class="admin-nav-label">Log out</span>
          </button>
        </form>

        <button
          type="button"
          class="admin-sidebar-toggle"
          data-admin-sidebar-toggle
          aria-expanded="true"
          aria-label="Collapse sidebar"
          title="Collapse sidebar"
        >
          <span class="admin-sidebar-toggle-icon">{!! $renderAdminIcon('collapse') !!}</span>
        </button>
      </div>
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
