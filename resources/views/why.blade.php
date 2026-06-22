@php
  // ── Gallery images — reuse the same images as the home All-features gallery
  $galleryLeft = [
    content_image('home.allfeatures.cards.0.image', asset('assets/techshop.webp')),
    content_image('home.allfeatures.cards.1.image', asset('assets/CHECKOUTPOS.png')),
    content_image('home.allfeatures.cards.2.image', asset('assets/STOCKLIST.png')),
  ];
  $galleryCenter = content_image('home.allfeatures.feature_image', asset('assets/techshop.webp'));
  $galleryRight = [
    content_image('home.allfeatures.cards.3.image', asset('assets/DASHBOARD.png')),
    content_image('home.allfeatures.cards.4.image', asset('assets/CRM-phone-feature.png')),
    content_image('home.allfeatures.cards.5.image', asset('assets/hardware.webp')),
  ];

  // ── Story cards (the problem / idea / outcome) ──────────────────────
  $storyCards = content_list('why.story.cards', [
    ['num' => '01', 'title' => 'The problem', 'body' => "It's too hard for small shops to track sales, stock and cash — and grow without the numbers they need.", 'image' => 'assets/techshop.webp'],
    ['num' => '02', 'title' => 'The idea',    'body' => 'Put a simple, reliable point of sale in every shop — one that works offline and fits how Tanzania sells.', 'image' => 'assets/client.webp'],
    ['num' => '03', 'title' => 'The outcome', 'body' => 'Thousands of businesses now run on SkelApp — selling faster, wasting less and growing with confidence.', 'image' => 'assets/HeroImage.webp'],
  ]);

  // ── Support feature showcase ────────────────────────────────────────
  $supportFeatures = content_list('why.support.features', [
    ['name' => 'Always-on help',   'body' => 'Reach us on WhatsApp, phone or email — real people who know retail, ready the moment you need them.', 'image' => 'assets/CRM-phone-feature.png'],
    ['name' => 'Set up in minutes', 'body' => 'Hands-on onboarding and training so your whole team is selling confidently from the very first day.', 'image' => 'assets/CHECKOUTPOS.png'],
    ['name' => 'On the ground',     'body' => 'A local team in Dar es Salaam for setup, repairs and advice — not a call centre on the other side of the world.', 'image' => 'assets/techshop.webp'],
    ['name' => 'Warranty & care',   'body' => 'Every device is covered, and we keep you running with fast repairs and replacements when you need them.', 'image' => 'assets/hardware.webp'],
  ]);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ content('why.meta.title', 'Why SkelApp – About Us') }}</title>
  <meta name="description" content="{{ content('why.meta.description', 'SkelApp is on a mission to transform Tanzanian small businesses — built by people who show up, with products and support that just work.') }}">
  <link rel="icon" href="{{ content_image('global.brand.favicon', asset('assets/skel.svg')) }}" sizes="any" />
  <link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="why-page-body">
  @include('partials.site-nav')

  <main class="hwp why-page">

    {{-- ══════════════ HERO BANNER ══════════════ --}}
    <section class="why-hero" aria-label="About SkelApp">
      <div class="pricing-hero-banner">
        <img src="{{ content_image('why.hero.image', asset('assets/HeroImage.webp')) }}" alt="" class="pricing-hero-bg" loading="eager" decoding="async">
        <div class="pricing-hero-content">
          <p class="pricing-hero-eyebrow">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.9 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14l-5-4.87 7.1-1.01z"/></svg>
            {{ content('why.hero.eyebrow', 'About SkelApp') }}
          </p>
          <h1 {!! content_typography_html('why.hero.title', 'pricing-hero-title') !!}>{!! nl2br(e(content_text('why.hero.title', "Transforming Tanzania's small businesses to grow — and communities to thrive."))) !!}</h1>
          <p {!! content_typography_html('why.hero.subtitle', 'pricing-hero-sub') !!}>{{ content_text('why.hero.subtitle', "We put powerful, affordable tools in the hands of every shop — so businesses grow stronger and the people around them thrive.") }}</p>
          <div class="pricing-hero-actions">
            <a href="{{ content('why.hero.primary_url', route('contact.show')) }}" class="pricing-hero-btn pricing-hero-btn--solid">{{ content('why.hero.primary_label', 'Talk to us') }}</a>
            <a href="{{ content('why.hero.secondary_url', route('features.show')) }}" class="pricing-hero-btn pricing-hero-btn--text">{{ content('why.hero.secondary_label', 'Explore SkelApp') }}</a>
          </div>
        </div>
      </div>
    </section>

    {{-- ══════════════ GALLERY (visuals only) ══════════════ --}}
    <section class="allfeatures allfeatures--plain">
      <div class="allfeatures-container">
        <div class="allfeatures-head">
          <span class="why-gallery-eyebrow">{{ content('why.gallery.eyebrow', 'Why SkelApp?') }}</span>
          <h2 class="allfeatures-head-title">{!! content('why.gallery.title', 'We genuinely<br><span class="af-accent">care</span>') !!}</h2>
        </div>

        <div class="allfeatures-gallery" data-af-gallery>
          <div class="af-col af-col--left">
            @foreach ($galleryLeft as $i => $img)
              <div class="af-parallax" data-af-parallax>
                <article class="af-card af-anim" data-af-anim style="--af-delay: {{ $i * 150 }}ms">
                  <div class="af-media"><img src="{{ $img }}" alt="" loading="lazy" decoding="async"></div>
                </article>
              </div>
            @endforeach
          </div>

          <div class="af-col af-col--center">
            <div class="af-center-parallax" data-af-center>
              <article class="af-card af-feature" data-af-feature>
                <div class="af-media"><img src="{{ $galleryCenter }}" alt="" loading="lazy" decoding="async"></div>
              </article>
            </div>
          </div>

          <div class="af-col af-col--right">
            @foreach ($galleryRight as $i => $img)
              <div class="af-parallax" data-af-parallax>
                <article class="af-card af-anim" data-af-anim style="--af-delay: {{ $i * 150 }}ms">
                  <div class="af-media"><img src="{{ $img }}" alt="" loading="lazy" decoding="async"></div>
                </article>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </section>

    {{-- ══════════════ PEOPLE WHO SHOW UP ══════════════ --}}
    <section class="why-people">
      <div class="why-shell">
        <div class="why-people-head">
          <h2 {!! content_typography_html('why.people.title', 'why-people-title') !!}>{!! nl2br(e(content_text('why.people.title', "People who\nshow up"))) !!}</h2>
          <p {!! content_typography_html('why.people.copy', 'why-people-copy') !!}>{{ content_text('why.people.copy', "We take the time to understand what really matters to you. From the first discussion, we've got your back — and we see it through until it's right.") }}</p>
        </div>

        <div class="why-story-grid">
          @foreach ($storyCards as $card)
            <article class="why-story-card">
              <img src="{{ cms_image($card['image'] ?? null, asset('assets/techshop.webp')) }}" alt="" loading="lazy" decoding="async">
              <div class="why-story-content">
                <span class="why-story-num">{{ $card['num'] ?? '' }}</span>
                <h3 class="why-story-title">{{ $card['title'] ?? '' }}</h3>
                <p class="why-story-body">{{ $card['body'] ?? '' }}</p>
              </div>
            </article>
          @endforeach
        </div>
      </div>
    </section>

    {{-- ══════════════ PRODUCTS THAT FUEL EFFICIENCY ══════════════ --}}
    <section class="why-product">
      <div class="why-product-grid">
        <div class="why-product-media">
          <img src="{{ content_image('why.product.image', asset('assets/PosSystemRegister.webp')) }}" alt="SkelApp hardware" loading="lazy" decoding="async">
        </div>
        <div class="why-product-copy">
          <h2 {!! content_typography_html('why.product.title', 'why-product-title') !!}>{!! nl2br(e(content_text('why.product.title', "Products that fuel\nefficiency"))) !!}</h2>
          <p {!! content_typography_html('why.product.subtitle', 'why-product-sub') !!}>{{ content_text('why.product.subtitle', 'We build products that make your job easier and more profitable. From taking orders and payments to managing stock and staff — everything works together so you can focus on your customers.') }}</p>
          <a href="{{ content('why.product.cta_url', route('contact.show')) }}" class="why-product-btn">{{ content('why.product.cta_label', 'Get a demo') }}</a>
        </div>
      </div>
    </section>

    {{-- ══════════════ SUPPORT SHOWCASE (scroll-pinned) ══════════════ --}}
    <section class="hwp-showcase hwp-showcase--pinned" data-feature-scroll>
      <div class="hwp-pin-stage">
        <div class="hwp-pin-inner">
          <div class="hwp-shell">
            <div class="hwp-section-header">
              <h2 class="hwp-section-title {{ content_typography_class('why.support.title') }}" style="{{ content_typography_vars('why.support.title') }}">{{ content_text('why.support.title', 'Support') }} {{ content('why.support.title_accent', "that's always there") }}</h2>
              <p class="hwp-section-subtitle {{ content_typography_class('why.support.subtitle') }}" style="{{ content_typography_vars('why.support.subtitle') }}">{{ content_text('why.support.subtitle', "When something matters, you reach a real person who knows your business — fast, local and on your side.") }}</p>
            </div>
            @include('partials.hwp-feature', ['features' => $supportFeatures])
          </div>
        </div>
      </div>
    </section>

    {{-- ══════════════ CULTURE CTA ══════════════ --}}
    <section class="hwp-cta">
      <div class="hwp-pricing-shell">
        <div class="hwp-pricing-head">
          <h2 class="hwp-pricing-title {{ content_typography_class('why.culture.title') }}" style="{{ content_typography_vars('why.culture.title') }}">{{ content_text('why.culture.title', 'A culture that') }} {{ content('why.culture.title_accent', 'shows up') }}</h2>
          <a href="{{ content('why.culture.link_url', route('contact.show')) }}" class="hwp-pricing-link">
            {{ content('why.culture.link_label', 'Work with us') }}
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
        <p class="hwp-pricing-note {{ content_typography_class('why.culture.note') }}" style="{{ content_typography_vars('why.culture.note') }}">{{ content_text('why.culture.note', "We're builders, problem-solvers and people who care. We move fast, stay close to our customers, and take pride in work that lasts — kazi safi, kila siku.") }}</p>
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

      // Scroll-pinned showcase: scrolling steps through the tabs, opening each
      // in turn (same behaviour as the product pages).
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
