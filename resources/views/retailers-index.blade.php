@php
  // ── Advanced pinned showcase steps ─────────────────────────────────
  $rxHeadline = content_text('retailers.showcase.headline', 'Everything your business needs, all in one place');
  $rxSteps = content_list('retailers.showcase.steps', [
    ['title' => 'Yes, it’s a full POS system', 'body' => 'Not just a card machine. SkelApp brings your products, stock, team and payments together in one place — so you stop paying for tools that don’t talk to each other.', 'image' => 'assets/techshop.webp'],
    ['title' => 'No hidden costs',              'body' => 'Free POS software and no monthly licence fees — affordable payments, and you only pay when you get paid.', 'image' => 'assets/PosSystem.webp'],
    ['title' => 'We’ll help you set up',        'body' => 'Getting started is simple, and our team is here to help you load your products and prices from day one.', 'image' => 'assets/poswithtab.webp'],
    ['title' => 'One account for everything',   'body' => 'Payments, POS, receipts and reporting all in one place. No juggling between systems that don’t fit together.', 'image' => 'assets/Moc-lap-phone-02.webp'],
    ['title' => 'Get paid fast, every day',     'body' => 'Sales settle quickly, straight to your account — so your cash keeps moving and your shelves stay full.', 'image' => 'assets/Mobilehomeview.png'],
  ]);
  $rxSteps = array_values($rxSteps);

  // ── Business-type carousel (links to each retailer page) ────────────
  $bizCards = [
    ['title' => 'Boutique',          'category' => 'Fashion',     'slug' => 'boutique',      'image' => 'assets/boutique.webp'],
    ['title' => 'Cosmetics Store',   'category' => 'Beauty',      'slug' => 'cosmetics',     'image' => 'assets/cosmetics.webp'],
    ['title' => 'Grocery Store',     'category' => 'Everyday',    'slug' => 'grocery',       'image' => 'assets/grocery.webp'],
    ['title' => 'Hardware Shop',     'category' => 'Trade',       'slug' => 'hardware-shop', 'image' => 'assets/hardware.webp'],
    ['title' => 'Kitchenware Store', 'category' => 'Homeware',    'slug' => 'kitchenware',   'image' => 'assets/kitchenware.webp'],
    ['title' => 'AutoSpare Shop',    'category' => 'Parts',       'slug' => 'autospare',     'image' => 'assets/autospare.webp'],
    ['title' => 'Tech Shop',         'category' => 'Electronics', 'slug' => 'tech-shop',     'image' => 'assets/techshop.webp'],
  ];
  $bizCards = content_list('retailers.types.cards', $bizCards);

  // ── FAQ ──────────────────────────────────────────────────────────────
  $rxFaq = [
    ['q' => 'Will SkelApp work for my type of shop?',  'a' => 'Yes — from boutiques to grocery, hardware to tech, SkelApp adapts to how you sell. Pick your business type to see how.'],
    ['q' => 'Do I need special hardware to start?',     'a' => 'No. Start on the phone or tablet you already have, and add a register, terminal or printer whenever you’re ready.'],
    ['q' => 'Does it work without internet?',           'a' => 'Absolutely. Keep selling during power cuts or weak network; everything syncs the moment you reconnect.'],
    ['q' => 'What payments can I take?',                'a' => 'Cash, M-Pesa, Airtel Money, Tigo Pesa and cards — all recorded automatically and printed on one receipt.'],
  ];
  $rxFaq = content_list('retailers.faq.items', $rxFaq);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ content('retailers.meta.title', 'Retailers – SkelApp') }}</title>
  <meta name="description" content="{{ content('retailers.meta.description', 'One point of sale for every kind of shop — boutiques, grocery, hardware, cosmetics, kitchenware, auto spares and tech.') }}">
  @include('partials.seo', [
    'seoTitle' => content('retailers.meta.title', 'Retailers – SkelApp'),
    'seoDescription' => content('retailers.meta.description', 'One point of sale for every kind of shop — boutiques, grocery, hardware, cosmetics, kitchenware, auto spares and tech.'),
    'seoImage' => content_image('retailers.hero.image', asset('assets/HeroImage.webp')),
    'seoPageType' => 'CollectionPage',
    'seoFaqs' => $rxFaq,
    'seoBreadcrumbs' => [
      ['name' => 'Home', 'url' => url('/')],
      ['name' => 'Retailers', 'url' => route('retailers.index')],
    ],
  ])
  <link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="hardware-page-body">
  @include('partials.site-nav')

  <main class="hwp hardware-page">

    {{-- ══════════════ HERO (pricing-hero banner) ══════════════ --}}
    <section class="retailer-hero" aria-label="Retailers">
      <div class="pricing-hero-banner">
        <img src="{{ content_image('retailers.hero.image', asset('assets/HeroImage.webp')) }}" alt="" class="pricing-hero-bg" loading="eager" decoding="async">
        <div class="pricing-hero-content">
          <p class="pricing-hero-eyebrow">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.9 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14l-5-4.87 7.1-1.01z"/></svg>
            {{ content('retailers.hero.eyebrow', 'For every type of shop') }}
          </p>
          <h1 {!! content_typography_html('retailers.hero.title', 'pricing-hero-title') !!}>{!! nl2br(e(content_text('retailers.hero.title', "One point of sale\nfor every retailer"))) !!}</h1>
          <p {!! content_typography_html('retailers.hero.subtitle', 'pricing-hero-sub') !!}>{{ content_text('retailers.hero.subtitle', 'Whatever you sell, SkelApp fits the way you trade — fast checkout, every payment, and stock you can trust.') }}</p>
          <div class="pricing-hero-actions">
            <a href="{{ route('contact.show') }}" class="pricing-hero-btn pricing-hero-btn--solid">{{ content('retailers.hero.primary_label', 'Get a demo') }}</a>
            <a href="{{ route('pricing.show') }}" class="pricing-hero-btn pricing-hero-btn--text">{{ content('retailers.hero.secondary_label', 'See pricing') }}</a>
          </div>
        </div>
      </div>
    </section>

    {{-- ══════════════ ADVANCED PINNED SHOWCASE ══════════════ --}}
    <section class="rx-showcase" data-rx>
      <div class="rx-stage">
        <div class="rx-inner">
          <div class="rx-shell">
            <div class="rx-media">
              @foreach ($rxSteps as $i => $s)
                <img class="rx-media-img {{ $i === 0 ? 'is-active' : '' }}" data-rx-media="{{ $i }}" src="{{ cms_image($s['image'] ?? null, asset('assets/PosSystem.webp')) }}" alt="" loading="lazy" decoding="async">
              @endforeach
            </div>
            <div class="rx-copy">
              <div class="rx-list-vp">
                <div class="rx-list" data-rx-list>
                  <h2 class="rx-headline {{ content_typography_class('retailers.showcase.headline') }}" style="{{ content_typography_vars('retailers.showcase.headline') }}">{{ $rxHeadline }}</h2>
                  @foreach ($rxSteps as $i => $s)
                    <div class="rx-item {{ $i === 0 ? 'is-active' : '' }}" data-rx-item="{{ $i }}">
                      <h3 class="rx-item-title">{{ $s['title'] ?? '' }}</h3>
                      <p class="rx-item-body">{{ $s['body'] ?? '' }}</p>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- ══════════════ RETAILERS (carousel, from home) ══════════════ --}}
    <section class="retailers-section" id="retailers">
      <div class="container">
        <div class="retailer-head">
          <div class="section-header retailer-head-copy">
            <h2 class="{{ content_typography_class('retailers.types.title') }}" style="{{ content_typography_vars('retailers.types.title') }}">{{ content_text('retailers.types.title', 'Powering retailers of every type') }}</h2>
            <p class="{{ content_typography_class('retailers.types.subtitle') }}" style="{{ content_typography_vars('retailers.types.subtitle') }}">{{ content_text('retailers.types.subtitle', 'From small shops to large chains — SkelApp scales with your business.') }}</p>
          </div>
          <div class="carousel-slider" aria-label="Retailer carousel controls">
            <button class="carousel-slider-button" type="button" data-carousel-prev aria-label="Previous retailer">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14.5 5.5 8 12l6.5 6.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </button>
            <button class="carousel-slider-button" type="button" data-carousel-next aria-label="Next retailer">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9.5 5.5 16 12l-6.5 6.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </button>
            <div class="carousel-slider-dots" role="tablist" aria-label="Retailer slides" aria-hidden="true">
              @foreach ($bizCards as $i => $card)
                <button class="carousel-slider-dot{{ $i === 1 ? ' is-active' : '' }}" type="button" role="tab" data-carousel-dot="{{ $i }}" aria-label="Go to {{ $card['title'] }}" tabindex="-1"></button>
              @endforeach
            </div>
          </div>
        </div>

        <div class="carousel-container" data-drag-scroll data-carousel-default-index="0">
          <div class="carousel-track">
            @foreach ($bizCards as $card)
              <div class="retailer-card">
                <div class="card-image">
                  <img src="{{ cms_image($card['image'] ?? null, asset('assets/boutique.webp')) }}" alt="{{ $card['title'] }}" draggable="false" loading="lazy" decoding="async">
                  <span class="retailer-card-pill">{{ $card['category'] }}</span>
                  <div class="card-overlay">
                    <h3>{{ $card['title'] }}</h3>
                  </div>
                  <a href="{{ route('retailers.show', $card['slug']) }}" class="retailer-card-link" aria-label="{{ $card['title'] }}"></a>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </section>

    {{-- ══════════════ FAQ ══════════════ --}}
    <section class="faq-section">
      <div class="faq-container">
        <div class="faq-header">
          <h2 class="{{ content_typography_class('retailers.faq.title') }}" style="{{ content_typography_vars('retailers.faq.title') }}">{{ content_text('retailers.faq.title', 'Questions, answered') }}</h2>
          <a href="{{ route('faq.show') }}" class="faq-read-more">{{ content('retailers.faq.read_more_label', 'Read more') }}</a>
        </div>
        <div class="faq-layout">
          <p class="faq-subtitle">{{ content('retailers.faq.subtitle', 'Retailers FAQ') }}</p>
          <div class="faq-list">
            @foreach ($rxFaq as $i => $item)
              <div class="faq-item @if($i === 0) active @endif">
                <button class="faq-question" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                  <span>{{ $item['q'] }}</span>
                  <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                  </svg>
                </button>
                <div class="faq-answer">
                  <p>{{ $item['a'] }}</p>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </section>

    {{-- ══════════════ CLOSING CTA (hwp-pricing-shell) ══════════════ --}}
    <section class="hwp-cta">
      <div class="hwp-pricing-shell">
        <div class="hwp-pricing-head">
          <h2 class="hwp-pricing-title {{ content_typography_class('retailers.cta.title') }}" style="{{ content_typography_vars('retailers.cta.title') }}">{{ content_text('retailers.cta.title', 'Find the SkelApp built for') }} {{ content('retailers.cta.title_accent', 'your shop') }}</h2>
          <a href="{{ route('pricing.show') }}" class="hwp-pricing-link">
            {{ content('retailers.cta.link_label', 'See pricing') }}
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
        <p class="hwp-pricing-note {{ content_typography_class('retailers.cta.note') }}" style="{{ content_typography_vars('retailers.cta.note') }}">{{ content_text('retailers.cta.note', 'Whatever you sell, there’s a SkelApp setup that fits. Start free today — add hardware whenever you’re ready.') }}</p>
        <div class="hwp-hero-actions">
          <a href="{{ route('contact.show') }}" class="hwp-hero-btn hwp-hero-btn--solid" data-download-modal>{{ content('retailers.cta.primary_label', 'Start for free') }}</a>
        </div>
      </div>
    </section>

  </main>

  @include('partials.site-footer')

  <script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
  <script>
    (function () {
      var canPin = window.matchMedia('(min-width: 1025px)');
      var reduce = window.matchMedia('(prefers-reduced-motion: reduce)');
      document.querySelectorAll('[data-rx]').forEach(function (section) {
        var stage  = section.querySelector('.rx-stage');
        var items  = Array.prototype.slice.call(section.querySelectorAll('[data-rx-item]'));
        var medias = section.querySelectorAll('[data-rx-media]');
        var list   = section.querySelector('[data-rx-list]');
        var vp     = section.querySelector('.rx-list-vp');
        if (!stage || !items.length) return;
        var n = items.length, enabled = false, ticking = false, current = -1;
        var STEP = 0.55; // viewport fraction per step

        function stageHeight() { return Math.round(window.innerHeight * (1 + n * STEP)) + 'px'; }
        function position(idx) {
          if (!vp || !list) return;
          // active item sits at the vertical middle of the media; the headline
          // and past items scroll up and out above it.
          var item = items[idx];
          var t = (vp.clientHeight / 2) - (item.offsetTop + item.offsetHeight / 2);
          list.style.transform = 'translateY(' + t + 'px)';
        }
        function reveal(idx) {
          if (idx === current) return;
          current = idx;
          items.forEach(function (it, i) { it.classList.toggle('is-active', i === idx); });
          medias.forEach(function (m) { m.classList.toggle('is-active', parseInt(m.getAttribute('data-rx-media'), 10) === idx); });
          position(idx);
        }
        function onScroll() {
          if (ticking) return;
          ticking = true;
          requestAnimationFrame(function () {
            ticking = false;
            var rect = stage.getBoundingClientRect();
            var total = stage.offsetHeight - window.innerHeight;
            var scrolled = Math.min(Math.max(-rect.top, 0), total);
            var p = total > 0 ? scrolled / total : 0;
            reveal(Math.max(0, Math.min(n - 1, Math.floor(p * n + 0.0001))));
          });
        }
        function enable() {
          if (enabled) return;
          enabled = true;
          section.classList.add('is-pinned');
          stage.style.height = stageHeight();
          window.addEventListener('scroll', onScroll, { passive: true });
          current = -1; reveal(0); onScroll();
        }
        function disable() {
          if (!enabled) return;
          enabled = false;
          section.classList.remove('is-pinned');
          stage.style.height = '';
          window.removeEventListener('scroll', onScroll);
          if (list) list.style.transform = '';
          items.forEach(function (it) { it.classList.add('is-active'); });
        }
        function apply() {
          if (canPin.matches && !reduce.matches && window.innerHeight >= 600) enable();
          else disable();
        }
        apply();
        window.addEventListener('resize', function () {
          if (enabled) { stage.style.height = stageHeight(); if (current >= 0) position(current); }
          apply();
        });
      });
    })();
  </script>
</body>
</html>
