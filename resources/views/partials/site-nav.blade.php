@php
  $isHome = $isHome ?? false;
  $sectionPrefix = $isHome ? '' : url('/');
  $primaryNavLinks = [
    ['label' => 'Features', 'href' => route('features.show'), 'route' => 'features.show'],
    ['label' => 'How it Works', 'href' => $sectionPrefix . '#howitworks'],
  ];
  $secondaryNavLinks = [
    ['label' => 'Pricing', 'href' => route('pricing.show'), 'route' => 'pricing.show'],
    ['label' => 'FAQ', 'href' => route('faq.show'), 'route' => 'faq.show'],
    ['label' => 'News', 'href' => route('news.index'), 'route' => 'news.*'],
  ];
  $mobileNavLinks = array_merge($primaryNavLinks, $secondaryNavLinks);
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
    <a href="tel:{{ content('global.nav.phone_tel', '+255658962000') }}" class="btn-login">
      <img src="{{ content_image('global.nav.call_icon', asset('assets/call.svg')) }}" alt="" aria-hidden="true">
      {{ content('global.nav.phone_display', '+255 658 962 000') }}
    </a>
    <a href="{{ route('contact.show') }}" class="btn-try" @if (request()->routeIs('contact.show')) aria-current="page" @endif>
      {{ content('global.nav.contact_label', 'Contact Us') }}
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
      <a href="tel:{{ content('global.nav.phone_tel', '+255658962000') }}" class="btn-login">
        <img src="{{ asset('assets/call.svg') }}" alt="" aria-hidden="true">
        {{ content('global.nav.phone_display', '+255 658 962 000') }}
      </a>
      <a href="{{ route('contact.show') }}" class="btn-try" @if (request()->routeIs('contact.show')) aria-current="page" @endif>
        {{ content('global.nav.contact_label', 'Contact Us') }}
      </a>
    </div>
  </div>
</nav>
