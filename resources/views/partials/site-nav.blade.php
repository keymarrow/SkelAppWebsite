@php
  $isHome = $isHome ?? false;
  $sectionPrefix = $isHome ? '' : url('/');
  $primaryNavLinks = [
    ['label' => 'Features', 'href' => $sectionPrefix . '#features'],
    ['label' => 'How it Works', 'href' => $sectionPrefix . '#howitworks'],
  ];
  $secondaryNavLinks = [
    ['label' => 'Pricing', 'href' => $sectionPrefix . '#pricing'],
    ['label' => 'FAQ', 'href' => route('faq.show'), 'route' => 'faq.show'],
    ['label' => 'News', 'href' => route('news.index'), 'route' => 'news.*'],
  ];
  $mobileNavLinks = array_merge($primaryNavLinks, $secondaryNavLinks);
@endphp
<nav>
  <a href="{{ url('/') }}" class="nav-logo" aria-label="{{ config('app.name', 'SkelApp') }} - Home">
    <img
      src="{{ asset('assets/SkelAppLogo-green.svg') }}"
      alt="{{ config('app.name', 'SkelApp') }} logo"
      width="240"
      height="74"
      loading="eager"
      decoding="async"
    />
  </a>

  <ul class="nav-links">
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

  <div class="nav-actions">
    <a href="tel:+255658962000" class="btn-login">
      <img src="{{ asset('assets/call.svg') }}" alt="" aria-hidden="true">
      +255 658 962 000
    </a>
    <a href="{{ route('contact.show') }}" class="btn-try" @if (request()->routeIs('contact.show')) aria-current="page" @endif>
      Contact Us
    </a>
  </div>

  <button class="mobile-menu-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false">
    <img
      src="{{ asset('assets/Vector.svg') }}"
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
      <a href="tel:+255658962000" class="btn-login">
        <img src="{{ asset('assets/call.svg') }}" alt="" aria-hidden="true">
        +255 658 962 000
      </a>
      <a href="{{ route('contact.show') }}" class="btn-try" @if (request()->routeIs('contact.show')) aria-current="page" @endif>
        Contact Us
      </a>
    </div>
  </div>
</nav>
