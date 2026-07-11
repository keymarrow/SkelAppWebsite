@php
  $shared = $shared ?? config('hardware_products.shared', []);
  $tm  = $shared['testimonial'] ?? [];
  $eco = $shared['ecosystem'] ?? [];
  $faq = $shared['faq'] ?? [];

  // ── Core POS features (scroll-pinned showcase) ──────────────────────
  $posFeatures = content_list('pos.features', [
    ['name' => 'Lightning-fast checkout', 'body' => 'Ring up any sale in seconds on a screen your team learns in minutes. Search, scan or tap a favourite, take payment and move on — so queues keep moving even at your busiest.', 'image' => 'assets/CHECKOUTPOS.png'],
    ['name' => 'Takes every payment',     'body' => 'Cash, M-Pesa, Airtel Money, Tigo Pesa and cards — all from one screen and reconciled automatically, so the day balances at closing without anyone keying figures by hand.', 'image' => 'assets/card.webp'],
    ['name' => 'Keeps selling offline',   'body' => 'Power cut or weak network? SkelApp keeps ringing up sales on the device and syncs every order, payment and stock change the moment you reconnect — you never lose a sale.', 'image' => 'assets/CASHFLOW.png'],
    ['name' => 'Receipts your way',        'body' => 'Print a crisp thermal receipt or send it by SMS or email in a tap — every customer leaves with a clear record, however they prefer to receive it.', 'image' => 'assets/Pos System 04.png'],
  ]);
  $tm = content('pos.testimonial', $tm);
  $eco = content('pos.ecosystem', $eco);
  $faq = content('pos.faq', $faq);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ content('pos.meta.title', 'Point of Sale – SkelApp') }}</title>
  <meta name="description" content="{{ content('pos.meta.description', 'SkelApp Point of Sale — fast checkout, every payment, works offline. The POS built for how Tanzania sells.') }}">
  @include('partials.seo', [
    'seoTitle'       => content('pos.meta.title', 'SkelApp - Point of Sale in Tanzania'),
    'seoDescription' => content('pos.meta.description', 'SkelApp Point of Sale and Inventory Management, Track every sale, every expense, and every purchase. The POS built for how Tanzania sells.'),
    'seoType'        => 'website',
    'seoImage'       => content_image('pos.hero.image', content_image('home.hero.background_image_desktop', asset('assets/HeroImage.webp'))),
    'seoSoftware'    => true,
    'seoFaqs'        => $faq['items'] ?? [],
    'seoBreadcrumbs' => [
      ['name' => 'Home', 'url' => url('/')],
      ['name' => 'Point of Sale', 'url' => route('pos.show')],
    ],
  ])
  <link rel="icon" href="{{ content_image('global.brand.favicon', asset('assets/skel.svg')) }}" sizes="any" />
  <link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="hardware-product-body why-page-body">
  @include('partials.site-nav')

  <main class="hwp why-page">

    {{-- ══════════════ HERO (centered + image banner) ══════════════ --}}
    <section class="pos-hero">
      <div class="pos-hero-copy">
        <span class="pos-hero-eyebrow">{{ content('pos.hero.eyebrow', 'Point of sale') }}</span>
        <h1 class="pos-hero-title {{ content_typography_class('pos.hero.title') }}" style="{{ content_typography_vars('pos.hero.title') }}"><strong>{{ content_text('pos.hero.title', 'A point of sale made for') }} {{ content('pos.hero.title_accent', 'how Tanzania sells') }}</strong></h1>
        <p class="pos-hero-sub {{ content_typography_class('pos.hero.subtitle') }}" style="{{ content_typography_vars('pos.hero.subtitle') }}">{{ content_text('pos.hero.subtitle', 'Ring up any sale in seconds, take any payment, and keep selling even when the power or network drops — all from one simple app your team learns in minutes.') }}</p>
        <div class="pos-hero-actions">
          <a href="{{ content('pos.hero.primary_url', route('contact.show')) }}" class="hwp-hero-btn hwp-hero-btn--solid">{{ content('pos.hero.primary_label', 'Get a demo') }}</a>
          <a href="{{ content('pos.hero.secondary_url', route('pricing.show')) }}" class="hwp-hero-btn hwp-hero-btn--ghost">{{ content('pos.hero.secondary_label', 'See pricing') }}</a>
        </div>
      </div>
      <div class="pos-hero-media">
        <img src="{{ content_image('pos.hero.image', content_image('home.hero.background_image_desktop', asset('assets/HeroImage.webp'))) }}" alt="SkelApp Point of Sale" class="pos-hero-image" loading="eager" decoding="async">
      </div>
    </section>

    {{-- ══════════════ CORE POS SHOWCASE (scroll-pinned) ══════════════ --}}
    <section class="hwp-showcase hwp-showcase--pinned" id="pos-detail" data-feature-scroll>
      <div class="hwp-pin-stage">
        <div class="hwp-pin-inner">
          <div class="hwp-shell">
            <div class="hwp-section-header">
              <h2 class="hwp-section-title {{ content_typography_class('pos.detail.title') }}" style="{{ content_typography_vars('pos.detail.title') }}">{{ content_text('pos.detail.title', 'Everything a busy counter needs') }}</h2>
              <p class="hwp-section-subtitle {{ content_typography_class('pos.detail.subtitle') }}" style="{{ content_typography_vars('pos.detail.subtitle') }}">{{ content_text('pos.detail.subtitle', 'One app for checkout, payments and receipts — fast, reliable and built for real trading days.') }}</p>
            </div>
            @include('partials.hwp-feature', ['features' => $posFeatures])
          </div>
        </div>
      </div>
    </section>

    {{-- ══════════════ CONNECTED TOOLS (ecosystem) ══════════════ --}}
    @if (!empty($eco))
    <section class="hwp-showcase hwp-showcase--pinned" data-feature-scroll>
      <div class="hwp-pin-stage">
        <div class="hwp-pin-inner">
          <div class="hwp-shell">
            <div class="hwp-section-header">
              <h2 class="hwp-section-title">{{ $eco['title'] ?? 'Connected tools.' }} {{ $eco['title_accent'] ?? 'Better profit.' }}</h2>
              <p class="hwp-section-subtitle">{{ $eco['subtitle'] ?? '' }}</p>
            </div>
            @include('partials.hwp-feature', ['features' => $eco['features'] ?? []])
          </div>
        </div>
      </div>
    </section>
    @endif

    {{-- ══════════════ TESTIMONIAL ══════════════ --}}
    @if (!empty($tm))
    <section class="hwp-testimonial">
      <div class="hwp-shell">
        <div class="hwp-tm-card">
          <figure class="hwp-tm-media">
            <img src="{{ cms_image($tm['image'] ?? null, asset('assets/client.webp')) }}" alt="{{ $tm['author'] }}" loading="lazy" decoding="async">
            <figcaption class="hwp-tm-person">
              <span class="hwp-tm-name">{{ $tm['author'] }}</span>
              <span class="hwp-tm-role">{{ $tm['role'] }}</span>
            </figcaption>
          </figure>
          <div class="hwp-tm-body">
            <svg class="hwp-tm-quotemark" width="46" height="38" viewBox="0 0 46 38" fill="currentColor" aria-hidden="true"><path d="M0 38V22.8C0 9.6 7.2 1.2 20.4 0l1.8 6.6C15 8.4 11.4 12 11.1 18h7.5V38H0zm25.2 0V22.8C25.2 9.6 32.4 1.2 45.6 0l1.8 6.6C40.2 8.4 36.6 12 36.3 18h7.5V38H25.2z"/></svg>
            <blockquote class="hwp-tm-quote">{{ $tm['quote_lead'] }} <span class="hwp-tm-brand">{{ $tm['quote_brand'] }}</span></blockquote>
            <p class="hwp-tm-support">{{ $tm['support'] }}</p>
            <div class="hwp-tm-actions">
              <a href="{{ route('contact.show') }}" class="hwp-tm-btn hwp-tm-btn--solid">{{ $tm['primary_label'] }}</a>
              <a href="{{ route('contact.show') }}" class="hwp-tm-btn hwp-tm-btn--ghost">{{ $tm['secondary_label'] }}</a>
            </div>
            <ul class="hwp-tm-stats">
              @foreach (($tm['stats'] ?? []) as $s)
                <li><strong>{{ $s['value'] }}</strong><span>{{ $s['label'] }}</span></li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </section>
    @endif

    {{-- ══════════════ PRICING AWARENESS CTA ══════════════ --}}
    <section class="hwp-cta">
      <div class="hwp-pricing-shell">
        <div class="hwp-pricing-head">
          <h2 class="hwp-pricing-title {{ content_typography_class('pos.pricing.title') }}" style="{{ content_typography_vars('pos.pricing.title') }}">{{ content_text('pos.pricing.title', 'Run your whole shop for') }} {{ content('pos.pricing.title_accent', 'nearly nothing a day') }}</h2>
          <a href="{{ route('pricing.show') }}" class="hwp-pricing-link">
            {{ content('pos.pricing.link_label', 'Explore all pricing plans') }}
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
        <a href="{{ route('pricing.show') }}" class="hwp-pricing-card">
          <div class="hwp-pricing-product">
            <span class="hwp-pricing-icon" aria-hidden="true">
              <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <rect x="6" y="2.5" width="12" height="19" rx="2.5"/><line x1="10" y1="6" x2="14" y2="6"/><circle cx="12" cy="15" r="2.5"/>
              </svg>
            </span>
            <span class="hwp-pricing-name">{{ content_text('pos.pricing.card_name', 'SkelApp') }} <strong>{{ content('pos.pricing.card_name_accent', 'POS') }}</strong></span>
          </div>
          <div class="hwp-pricing-figures">
            <span class="hwp-pricing-was">{{ content_text('pos.pricing.price_was', '1,000 TZS') }}<small>{{ content('pos.pricing.price_was_suffix', '/day') }}</small></span>
            <span class="hwp-pricing-now">{{ content_text('pos.pricing.price_now', '500 TZS') }}<small>{{ content('pos.pricing.price_now_suffix', '/day') }}</small></span>
          </div>
        </a>
        <p class="hwp-pricing-note">{{ content_text('pos.pricing.note', 'Karibu sifuri kwa siku — about 500 TZS a day runs your entire shop, less than a cup of chai.') }}</p>
      </div>
    </section>

    {{-- ══════════════ FAQ ══════════════ --}}
    @if (!empty($faq['items']))
    <section class="faq-section">
        <div class="faq-container">
          <div class="faq-header">
            <h2>{{ $faq['title'] ?? 'Questions,' }} {{ $faq['title_accent'] ?? 'answered.' }}</h2>
            <a href="{{ route('faq.show') }}" class="faq-read-more">{{ content('pos.faq.read_more_label', 'Read more') }}</a>
          </div>
          <div class="faq-layout">
            <p class="faq-subtitle">{{ content('pos.faq.subtitle', 'POS FAQ') }}</p>
          <div class="faq-list">
            @foreach ($faq['items'] as $i => $item)
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

    {{-- ══════════════ CLOSING CTA ══════════════ --}}
    <section class="hwp-cta">
      <div class="hwp-pricing-shell">
        <div class="hwp-pricing-head">
          <h2 class="hwp-pricing-title {{ content_typography_class('pos.cta.title') }}" style="{{ content_typography_vars('pos.cta.title') }}">{{ content_text('pos.cta.title', 'Ready to ring up your') }} {{ content('pos.cta.title_accent', 'first sale?') }}</h2>
          <a href="{{ route('hardware.show') }}" class="hwp-pricing-link">
            {{ content('pos.cta.link_label', 'See the hardware') }}
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
        <p class="hwp-pricing-note">{{ content_text('pos.cta.note', 'Download SkelApp and start selling today — add hardware whenever you’re ready.') }}</p>
        <div class="hwp-hero-actions">
          <a href="{{ content('pos.cta.primary_url', route('contact.show')) }}" class="hwp-hero-btn hwp-hero-btn--solid" data-download-modal>{{ content('pos.cta.primary_label', 'Start for free') }}</a>
        </div>
      </div>
    </section>

  </main>

  @include('partials.site-footer')

  <script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
  <script>
    (function () {
      // Hero media zoom: the more you scroll, the more the whole banner zooms in.
      (function () {
        var hero = document.querySelector('.pos-hero');
        var media = document.querySelector('.pos-hero-media');
        if (!hero || !media) return;
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        media.style.transformOrigin = 'center center';
        media.style.willChange = 'transform';
        var ticking = false;
        var update = function () {
          var h = hero.offsetHeight || 1;
          var progress = Math.min(Math.max(window.scrollY / h, 0), 1);
          media.style.transform = 'scale(' + (1 - progress * 0.18).toFixed(4) + ')';
          ticking = false;
        };
        window.addEventListener('scroll', function () {
          if (!ticking) { window.requestAnimationFrame(update); ticking = true; }
        }, { passive: true });
        update();
      })();

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

      // Scroll-pinned showcases: scrolling steps through the tabs, opening each in turn.
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
