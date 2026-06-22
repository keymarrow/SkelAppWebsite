@php
  use App\Support\CmsCatalogs;

  $retailerPages = collect(content_list('retailers.pages', CmsCatalogs::retailerPages()))
    ->filter(fn ($item) => is_array($item) && filled($item['slug'] ?? null))
    ->mapWithKeys(fn ($item) => [(string) $item['slug'] => $item])
    ->all();
  $slug = $slug ?? array_key_first($retailerPages);
  $r = $r ?? ($retailerPages[$slug] ?? []);

  $eco = content('pos.ecosystem', config('hardware_products.shared.ecosystem', []));
  $detailFeatures = !empty($r['detail_features']) && is_array($r['detail_features'])
    ? $r['detail_features']
    : collect(range(1, 4))->map(function (int $index) use ($r) {
        $title = trim((string) ($r["feature_{$index}_title"] ?? ''));
        $body = trim((string) ($r["feature_{$index}_body"] ?? ''));
        $image = $r["feature_{$index}_image"] ?? '';

        if ($title === '' && $body === '' && $image === '') {
            return null;
        }

        return [
            'name' => $title,
            'body' => $body,
            'image' => $image,
        ];
    })->filter()->values()->all();
  $whyItems = !empty($r['why']['items']) && is_array($r['why']['items'])
    ? $r['why']['items']
    : CmsCatalogs::parseTitleBodyLines($r['why_items_text'] ?? null);
  if (empty($whyItems)) {
      $whyItems = CmsCatalogs::retailerWhyDefaults();
  }
  $spotlightPoints = !empty($r['spotlight']['points']) && is_array($r['spotlight']['points'])
    ? $r['spotlight']['points']
    : collect(preg_split('/\r?\n/', (string) ($r['spotlight_points_text'] ?? '')))
        ->map(fn ($line) => trim((string) $line))
        ->filter()
        ->values()
        ->all();
  $faqItems = !empty($r['faq']['items']) && is_array($r['faq']['items'])
    ? $r['faq']['items']
    : CmsCatalogs::parseQuestionAnswerLines($r['faq_items_text'] ?? null);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $r['meta_title'] ?? (($r['name'] ?? 'Retailer').' POS – SkelApp') }}</title>
  <meta name="description" content="{{ $r['meta_description'] ?? '' }}">
  <link rel="icon" href="{{ content_image('global.brand.favicon', asset('assets/skel.svg')) }}" sizes="any" />
  <link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
  <style>
    /* Scoped overrides so retailer feature cards match homepage sizing */
    #features.features-section { padding: 40px 104px; }
    #features .features-row { display: grid; grid-template-columns: 1fr 1.4fr; gap: 32px; margin-bottom: 24px; }
    #features .feature-square { width: min(410px, 100%); height: 389px; min-height: 389px; }
    #features .feature-rectangle-pos,
    #features .feature-rectangle-handheld { width: 810.73px; height: 389px; min-height: 389px; }
    /* Fix reporting mockup placement (bottom-right card) */
    #features .feature-square-reporting { position: relative; }
    #features .feature-square-reporting .feature-content-bottom { position: absolute; inset: 0; padding: 0; overflow: hidden; pointer-events: none; display: flex; align-items: flex-start; justify-content: center; }
    #features .feature-square-reporting .feature-reporting-mockup { position: absolute; top: 185.54px; left: 50%; width: 317px; height: 175.14px; transform: translateX(-50%); max-width: none; max-height: none; border-radius: 0; box-shadow: none; object-fit: contain; object-position: center top; }
  </style>
</head>
<body class="hardware-page-body">
  @include('partials.site-nav')

  <main class="hwp hardware-page">

    {{-- ══════════════ HERO (pricing-hero banner) ══════════════ --}}
    <section class="retailer-hero" aria-label="{{ $r['name'] ?? 'Retailer' }} POS">
      <div class="pricing-hero-banner">
        <img src="{{ cms_image($r['hero_image'] ?? null, asset('assets/HeroImage.webp')) }}" alt="" class="pricing-hero-bg" loading="eager" decoding="async">
        <div class="pricing-hero-content">
          <p class="pricing-hero-eyebrow">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.9 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14l-5-4.87 7.1-1.01z"/></svg>
            {{ $r['hero_eyebrow'] ?? '' }}
          </p>
          <h1 class="pricing-hero-title">{!! nl2br(e($r['hero_title'] ?? '')) !!}</h1>
          <p class="pricing-hero-sub">{{ $r['hero_subtitle'] ?? '' }}</p>
          <div class="pricing-hero-actions">
            <a href="{{ route('contact.show') }}" class="pricing-hero-btn pricing-hero-btn--solid">{{ $r['hero_primary_label'] ?? 'Get a demo' }}</a>
            <a href="{{ route('pricing.show') }}" class="pricing-hero-btn pricing-hero-btn--text">{{ $r['hero_secondary_label'] ?? 'See pricing' }}</a>
          </div>
        </div>
      </div>
    </section>

    {{-- ══════════════ FEATURES (use homepage `home.features` content on boutique page only) ══════════════ --}}
    <section id="features" class="features-section">
      <div class="container">
        <div class="section-bottom retailers-section-bottom" style="margin-bottom:2.5rem;">
          <h2 class="{{ content_typography_class('home.retailers.bottom_title') }}" style="{{ content_typography_vars('home.retailers.bottom_title') }}">{{ content_text('home.retailers.bottom_title', 'One app. Every number in your business — tracked.') }}</h2>
          <p class="{{ content_typography_class('home.retailers.bottom_copy') }}" style="{{ content_typography_vars('home.retailers.bottom_copy') }}">{{ content_text('home.retailers.bottom_copy') }}</p>
        </div>

        <!-- Top Row -->
        <div class="features-row">
          <div class="feature-card feature-square feature-light feature-square-catalogue">
            <div class="feature-content-top">
              <h2 class="{{ content_typography_class('home.features.top_left.title') }}" style="{{ content_typography_vars('home.features.top_left.title') }}">{{ content_text('home.features.top_left.title') }}</h2>
              <p class="{{ content_typography_class('home.features.top_left.body') }}" style="{{ content_typography_vars('home.features.top_left.body') }}">{{ content_text('home.features.top_left.body') }}</p>
            </div>
            <div class="feature-content-bottom">
              <img src="{{ content_image('home.features.top_left.image', asset('assets/Moc-tab.webp')) }}" alt="{{ content_text('home.features.top_left.title') }}" class="feature-tab-mockup" loading="lazy" decoding="async">
            </div>
          </div>

          <div class="feature-card feature-rectangle feature-green feature-rectangle-pos">
            <div class="feature-content-left">
              <h2 class="{{ content_typography_class('home.features.top_right.title') }}" style="{{ content_typography_vars('home.features.top_right.title') }}">{{ content_text('home.features.top_right.title') }}<br>{{ content('home.features.top_right.title_line_2', 'Ready for Both Apple & Android') }}</h2>
              <p class="{{ content_typography_class('home.features.top_right.body') }}" style="{{ content_typography_vars('home.features.top_right.body') }}">{{ content_text('home.features.top_right.body') }}</p>
            </div>
            <div class="feature-content-right">
              <img src="{{ content_image('home.features.top_right.image', asset('assets/PosSystem.webp')) }}" alt="{{ content_text('home.features.top_right.title') }}" loading="lazy" decoding="async">
            </div>
          </div>
        </div>

        <!-- Bottom Row -->
        <div class="features-row">
          <div class="feature-card feature-rectangle feature-dark feature-rectangle-handheld">
            <div class="feature-content-left">
              <h2 class="{{ content_typography_class('home.features.bottom_left.title') }}" style="{{ content_typography_vars('home.features.bottom_left.title') }}">{{ content_text('home.features.bottom_left.title') }}</h2>
              <p class="{{ content_typography_class('home.features.bottom_left.body') }}" style="{{ content_typography_vars('home.features.bottom_left.body') }}">{{ content_text('home.features.bottom_left.body') }}</p>
            </div>
            <div class="feature-content-right">
              <img src="{{ content_image('home.features.bottom_left.image', asset('assets/poswithtab.webp')) }}" alt="{{ content_text('home.features.bottom_left.title') }}" class="feature-handheld-pos" loading="lazy" decoding="async">
            </div>
          </div>

          <div class="feature-card feature-square feature-light feature-square-reporting">
            <div class="feature-content-top">
              <h2 class="{{ content_typography_class('home.features.bottom_right.title') }}" style="{{ content_typography_vars('home.features.bottom_right.title') }}">{!! nl2br(e(content_text('home.features.bottom_right.title'))) !!}</h2>
              <p class="{{ content_typography_class('home.features.bottom_right.body') }}" style="{{ content_typography_vars('home.features.bottom_right.body') }}">{{ content_text('home.features.bottom_right.body') }}</p>
            </div>
            <div class="feature-content-bottom">
              <img src="{{ content_image('home.features.bottom_right.image', asset('assets/Moc-lap-phone-02.webp')) }}" alt="{{ content_text('home.features.bottom_right.title') }}" class="feature-reporting-mockup" loading="lazy" decoding="async">
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- ══════════════ DETAIL (scroll-pinned, industry features) ══════════════ --}}
    <section class="hwp-showcase hwp-showcase--pinned" id="retail-detail" data-feature-scroll>
      <div class="hwp-pin-stage">
        <div class="hwp-pin-inner">
          <div class="hwp-shell">
            <div class="hwp-section-header">
              <h2 class="hwp-section-title">{{ $r['detail_title'] ?? '' }}</h2>
              <p class="hwp-section-subtitle">{{ $r['detail_subtitle'] ?? '' }}</p>
            </div>
            @include('partials.hwp-feature', ['features' => $detailFeatures])
          </div>
        </div>
      </div>
    </section>

    {{-- ══════════════ WHY (hw-shell value-props, from hardware page) ══════════════ --}}
    <section class="hwp-showcase">
      <div class="hw-shell">
        <div class="hwp-section-header">
          <h2 class="hwp-section-title">{{ $r['why_title'] ?? 'Made to be switched on and forgotten about' }}</h2>
          <p class="hwp-section-subtitle">{{ $r['why_subtitle'] ?? 'Reliable, locally supported hardware that just works — set it up once and get on with selling.' }}</p>
        </div>
        <div class="hw-why-grid">
          @foreach ($whyItems as $idx => $w)
            <div class="hw-why-card">
              <span class="hw-why-num">0{{ $idx + 1 }}</span>
              <h3 class="hw-why-title">{{ $w['title'] ?? '' }}</h3>
              <p class="hw-why-copy">{{ $w['body'] ?? ($w['copy'] ?? '') }}</p>
            </div>
          @endforeach
        </div>
      </div>
    </section>

    {{-- ══════════════ HARDWARE SPOTLIGHT (single row) ══════════════ --}}
    @if (!empty($r['spotlight_headline']) || !empty($r['spotlight_name']))
    <section class="hw-spotlights">
      <div class="hw-shell">
        <article class="hw-spot hw-spot--retail">
          <div class="hw-spot-media">
            <img src="{{ cms_image($r['spotlight_image'] ?? null, asset('assets/Moc-tab.webp')) }}" alt="{{ $r['spotlight_name'] ?? '' }}" loading="lazy" decoding="async">
          </div>
          <div class="hw-spot-copy">
            <h3 class="hw-spot-name">{{ $r['spotlight_headline'] ?? ($r['spotlight_name'] ?? '') }}</h3>
            <span class="hw-spot-label">{{ $r['spotlight_name'] ?? ($r['spotlight_eyebrow'] ?? '') }}</span>
            <p class="hw-spot-text">{{ $r['spotlight_copy'] ?? '' }}</p>
            <ul class="hw-spot-points">
              @foreach ($spotlightPoints as $p)
                <li>
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                  {{ $p }}
                </li>
              @endforeach
            </ul>
            @php $spotUrl = !empty($r['spotlight_product']) ? route('hardware.product', $r['spotlight_product']) : route('hardware.show'); @endphp
            <a href="{{ $spotUrl }}" class="hwp-hero-btn hwp-hero-btn--ghost">{{ $r['spotlight_button_label'] ?? 'Explore' }} {{ $r['spotlight_name'] ?? 'hardware' }}</a>
          </div>
        </article>
      </div>
    </section>
    @endif

    {{-- ══════════════ CONNECTED TOOLS (ecosystem) ══════════════ --}}
    @if (!empty($eco))
    <section class="hwp-showcase hwp-showcase--alt">
      <div class="hwp-shell">
        <div class="hwp-section-header">
          <h2 class="hwp-section-title">{{ $eco['title'] ?? 'Connected tools.' }} {{ $eco['title_accent'] ?? 'Better profit.' }}</h2>
          <p class="hwp-section-subtitle">{{ $eco['subtitle'] ?? '' }}</p>
        </div>
        @include('partials.hwp-feature', ['features' => $eco['features'] ?? []])
      </div>
    </section>
    @endif

    {{-- ══════════════ FAQ ══════════════ --}}
    @if (!empty($faqItems))
    <section class="faq-section">
      <div class="faq-container">
        <div class="faq-header">
          <h2>{{ $r['faq_title'] ?? '' }} {{ $r['faq_title_accent'] ?? '' }}</h2>
          <a href="{{ route('faq.show') }}" class="faq-read-more">{{ content('retailers.faq.read_more_label', 'Read more') }}</a>
        </div>
        <div class="faq-layout">
          <p class="faq-subtitle">{{ $r['name'] ?? 'Retailer' }} FAQ</p>
          <div class="faq-list">
            @foreach ($faqItems as $i => $item)
              <div class="faq-item @if($i === 0) active @endif">
                <button class="faq-question" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                  <span>{{ $item['q'] ?? '' }}</span>
                  <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                  </svg>
                </button>
                <div class="faq-answer">
                  <p>{{ $item['a'] ?? '' }}</p>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </section>
    @endif

    {{-- ══════════════ CLOSING CTA (unified hwp-cta) ══════════════ --}}
    <section class="hwp-cta">
      <div class="hwp-pricing-shell">
        <div class="hwp-pricing-head">
          <h2 class="hwp-pricing-title">{{ $r['cta_title'] ?? 'Ready to get started?' }}</h2>
          <a href="{{ route('pricing.show') }}" class="hwp-pricing-link">
            See pricing
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
        <p class="hwp-pricing-note">{{ $r['cta_note'] ?? '' }}</p>
        <div class="hwp-hero-actions">
          <a href="{{ route('contact.show') }}" class="hwp-hero-btn hwp-hero-btn--solid" data-download-modal>{{ $r['cta_label'] ?? 'Start for free' }}</a>
        </div>
      </div>
    </section>

  </main>

  @include('partials.site-footer')

  <script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
  <script>
    (function () {
      // Feature tabs: click to swap copy + media.
      document.querySelectorAll('[data-feature]').forEach(function (group) {
        var tabs  = group.querySelectorAll('[data-feature-tab]');
        var media = group.querySelectorAll('[data-feature-media]');
        tabs.forEach(function (tab) {
          tab.addEventListener('click', function () {
            var idx = parseInt(tab.getAttribute('data-feature-tab'), 10);
            tabs.forEach(function (t) { t.classList.toggle('is-active', t === tab); });
            media.forEach(function (m) { m.classList.toggle('is-active', parseInt(m.getAttribute('data-feature-media'), 10) === idx); });
          });
        });
      });

      // Scroll-pinned showcase: scrolling steps through the tabs.
      var canPin = window.matchMedia('(min-width: 1025px)');
      var reduce = window.matchMedia('(prefers-reduced-motion: reduce)');
      document.querySelectorAll('[data-feature-scroll]').forEach(function (section) {
        var stage = section.querySelector('.hwp-pin-stage');
        var group = section.querySelector('[data-feature]');
        if (!stage || !group) return;
        var mediaWrap = group.querySelector('.hwp-feature-media');
        var tabs  = group.querySelectorAll('[data-feature-tab]');
        var media = group.querySelectorAll('[data-feature-media]');
        var n = tabs.length;
        var enabled = false, ticking = false, current = 0, leaveTimer;
        var STEP = 0.4;
        function stageHeight() { return Math.round(window.innerHeight * (1 + n * STEP)) + 'px'; }
        function reveal(idx) {
          tabs.forEach(function (t, i) { t.classList.toggle('is-active', i === idx); });
          if (idx === current) {
            media.forEach(function (m) {
              var mi = parseInt(m.getAttribute('data-feature-media'), 10);
              m.classList.toggle('is-active', mi === idx);
              if (mi !== idx) m.classList.remove('is-leaving');
            });
            return;
          }
          var prev = current;
          current = idx;
          if (mediaWrap) mediaWrap.setAttribute('data-dir', idx > prev ? 'down' : 'up');
          clearTimeout(leaveTimer);
          media.forEach(function (m) {
            var mi = parseInt(m.getAttribute('data-feature-media'), 10);
            m.classList.remove('is-active', 'is-leaving');
            if (mi === idx) m.classList.add('is-active');
            else if (mi === prev) m.classList.add('is-leaving');
          });
          leaveTimer = setTimeout(function () {
            media.forEach(function (m) { m.classList.remove('is-leaving'); });
          }, 650);
        }
        function onScroll() {
          if (ticking) return;
          ticking = true;
          requestAnimationFrame(function () {
            ticking = false;
            var rect = stage.getBoundingClientRect();
            var total = stage.offsetHeight - window.innerHeight;
            var scrolled = Math.min(Math.max(-rect.top, 0), total);
            var progress = total > 0 ? scrolled / total : 0;
            reveal(Math.max(0, Math.min(n - 1, Math.floor(progress * n + 0.0001))));
          });
        }
        function enable() {
          if (enabled) return;
          enabled = true;
          section.classList.add('is-pinned');
          stage.style.height = stageHeight();
          window.addEventListener('scroll', onScroll, { passive: true });
          onScroll();
        }
        function disable() {
          if (!enabled) return;
          enabled = false;
          section.classList.remove('is-pinned');
          stage.style.height = '';
          window.removeEventListener('scroll', onScroll);
          reveal(0);
        }
        function apply() {
          if (canPin.matches && !reduce.matches && window.innerHeight >= 600) enable();
          else disable();
        }
        apply();
        window.addEventListener('resize', function () {
          if (enabled) stage.style.height = stageHeight();
          apply();
        });
      });
    })();
  </script>
</body>
</html>
