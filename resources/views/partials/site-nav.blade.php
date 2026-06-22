@php
  $isHome = $isHome ?? false;
  $sectionPrefix = $isHome ? '' : url('/');
  $primaryNavLinks = [
    ['label' => 'Point of Sale', 'href' => route('pos.show'), 'route' => 'pos.show'],
    ['label' => 'Features', 'href' => route('features.show'), 'route' => 'features.show'],
    ['label' => 'Integrations', 'href' => route('integrations.index'), 'route' => 'integrations.*'],
    ['label' => 'Pricing', 'href' => route('pricing.show'), 'route' => 'pricing.show'],
  ];
  $secondaryNavLinks = [
    ['label' => 'Why SkelApp', 'href' => route('why.show'), 'route' => 'why.show'],
    ['label' => 'News', 'href' => route('news.index'), 'route' => 'news.*'],
  ];
  $mobileNavLinks = array_merge($primaryNavLinks, $secondaryNavLinks);
  $megaBusiness = [
    'boutique'      => 'Boutique',
    'cosmetics'     => 'Cosmetics Store',
    'grocery'       => 'Grocery Store',
    'hardware-shop' => 'Hardware Shop',
    'kitchenware'   => 'Kitchenware Store',
    'autospare'     => 'AutoSpare Shop',
    'tech-shop'     => 'Tech Shop',
  ];
@endphp
{{-- Theme: default to the visitor's device setting (dark unless they prefer light),
     with an explicit saved choice always winning. Applied early to avoid a flash. --}}
<script>
  (function () {
    try {
      var saved = localStorage.getItem('skel-theme');
      var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
      if (saved === 'dark' || (saved !== 'light' && prefersDark)) {
        document.documentElement.setAttribute('data-theme', 'dark');
      }
    } catch (e) {}
  })();
</script>
@php
  $themeToggleSvg = '<svg class="icon-moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>'
    . '<svg class="icon-sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>';
@endphp
<nav>
  <a href="{{ url('/') }}" class="nav-logo" aria-label="{{ config('app.name', 'SkelApp') }} - Home">
    <img
      src="{{ content_image('global.nav.logo', asset('assets/SkelAppLogo-green.svg')) }}"
      alt="{{ config('app.name', 'SkelApp') }} logo"
      width="240"
      height="74"
      loading="eager"
      decoding="async"
    />
  </a>

  <ul class="nav-links" data-nav-links>
    @foreach ($primaryNavLinks as $link)
      <li>
        <a
          href="{{ $link['href'] }}"
          @if (isset($link['route']) && request()->routeIs($link['route'])) aria-current="page" @endif
        >
          {{ $link['label'] }}
        </a>
      </li>
    @endforeach

    <div class="nav-divider"></div>

    @foreach ($secondaryNavLinks as $link)
      <li>
        <a
          href="{{ $link['href'] }}"
          @if (isset($link['route']) && request()->routeIs($link['route'])) aria-current="page" @endif
        >
          {{ $link['label'] }}
        </a>
      </li>
    @endforeach
  </ul>

  @include('partials.nav-mega', ['megaBusiness' => $megaBusiness])

  <div class="nav-actions">
    <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle dark mode" title="Toggle dark mode">{!! $themeToggleSvg !!}</button>
    <a href="{{ route('contact.show') }}" class="btn-login" @if (request()->routeIs('contact.show')) aria-current="page" @endif>
      {{ content('global.nav.contact_label', 'Contact Us') }}
    </a>
    <a href="{{ content('global.nav.login_url', 'https://web.skelapp.tz') }}" class="btn-try">
      {{ content('global.nav.login_label', 'Login') }}
    </a>
  </div>

  <button class="mobile-menu-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false">
    <img
      src="{{ content_image('global.nav.mobile_menu_icon', asset('assets/Vector.svg')) }}"
      alt=""
      aria-hidden="true"
      class="mobile-menu-icon"
      width="17"
      height="17"
    />
  </button>

  <div class="mobile-menu-container" aria-label="Mobile navigation">
    <ul class="mobile-only">
      @foreach ($mobileNavLinks as $link)
        <li>
          <a
            href="{{ $link['href'] }}"
            @if (isset($link['route']) && request()->routeIs($link['route'])) aria-current="page" @endif
          >
            {{ $link['label'] }}
          </a>
        </li>
      @endforeach
    </ul>
    <div class="nav-actions mobile-only">
      <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle dark mode">{!! $themeToggleSvg !!}<span class="theme-toggle-label">Dark mode</span></button>
      <a href="{{ route('contact.show') }}" class="btn-login" @if (request()->routeIs('contact.show')) aria-current="page" @endif>
        {{ content('global.nav.contact_label', 'Contact Us') }}
      </a>
      <a href="{{ content('global.nav.login_url', 'https://web.skelapp.tz') }}" class="btn-try">
        {{ content('global.nav.login_label', 'Login') }}
      </a>
    </div>
  </div>

  <script>
    (function () {
      function setTheme(theme) {
        if (theme === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
        else document.documentElement.removeAttribute('data-theme');
        try { localStorage.setItem('skel-theme', theme); } catch (e) {}
      }
      document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
        btn.addEventListener('click', function () {
          var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
          setTheme(isDark ? 'light' : 'dark');
        });
      });

      // Mega menu: hovering the nav links opens it; it stays open while the
      // cursor is anywhere on the nav or the mega (the mega is a nav descendant),
      // and closes only when the cursor leaves that whole region.
      var nav   = document.currentScript ? document.currentScript.closest('nav') : document.querySelector('nav');
      var links = nav && nav.querySelector('[data-nav-links]');
      var mega  = nav && nav.querySelector('.nav-mega');
      if (nav && links && mega) {
        // Dynamic column: Products hover swaps the middle column; it stays on
        // the chosen category while you move into its list, and only changes
        // when you hover another Products item or the "More" column.
        var panels   = mega.querySelectorAll('[data-mega-panel]');
        var triggers = mega.querySelectorAll('[data-mega-trigger]');
        var rest     = mega.querySelector('[data-mega-rest]');
        function activate(name) {
          panels.forEach(function (p) { p.classList.toggle('is-active', p.getAttribute('data-mega-panel') === name); });
          triggers.forEach(function (t) { t.classList.toggle('is-current', t.getAttribute('data-mega-trigger') === name); });
        }
        triggers.forEach(function (t) {
          t.addEventListener('mouseenter', function () { activate(t.getAttribute('data-mega-trigger')); });
        });
        if (rest) rest.addEventListener('mouseenter', function () { activate('learn'); });

        links.addEventListener('mouseenter', function () { nav.classList.add('mega-open'); });
        nav.addEventListener('mouseleave', function () { nav.classList.remove('mega-open'); activate('learn'); });
        // close after navigating
        mega.querySelectorAll('a').forEach(function (a) {
          a.addEventListener('click', function () { nav.classList.remove('mega-open'); });
        });
      }
    })();
  </script>
</nav>
