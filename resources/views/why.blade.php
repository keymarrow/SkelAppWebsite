@php
  // ── "Why we build SkelApp" — numbered reason cards ──────────────────
  $buildReasons = content_list('why.build.reasons', [
    ['title' => 'Small shops deserve better', 'body' => 'The businesses that hold up their communities were stuck with paper, guesswork and tools built for someone else. We wanted to change that.'],
    ['title' => 'It has to work offline',       'body' => 'Power and network drop — the counter cannot. SkelApp keeps selling through outages and syncs the moment it is back.'],
    ['title' => 'Price should never lock you out', 'body' => 'One fair price, every feature included. Whether you run one till or ten, the whole app is yours from day one.'],
    ['title' => 'Support that shows up',         'body' => 'A local team in Tanzania for setup, training and repairs — real people who know retail, not a call centre far away.'],
  ]);

  // ── Story cards (the problem / idea / outcome) ──────────────────────
  $storyCards = content_list('why.story.cards', [
    ['num' => '01', 'title' => 'The problem', 'body' => "It's too hard for small shops to track sales, stock and cash — and grow without the numbers they need.", 'image' => 'assets/techshop.webp'],
    ['num' => '02', 'title' => 'The idea',    'body' => 'Put a simple, reliable point of sale in every shop — one that works offline and fits how Tanzania sells.', 'image' => 'assets/client.webp'],
    ['num' => '03', 'title' => 'The outcome', 'body' => 'Thousands of businesses now run on SkelApp — selling faster, wasting less and growing with confidence.', 'image' => 'assets/HeroImage.webp'],
  ]);

  // ── Photo grid (one large image over two) ───────────────────────────
  $gridMain = content_image('why.gallery.image_main', asset('assets/client.webp'));
  $gridA    = content_image('why.gallery.image_a', asset('assets/techshop.webp'));
  $gridB    = content_image('why.gallery.image_b', asset('assets/grocery.webp'));

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
  @include('partials.seo', [
    'seoTitle' => content('why.meta.title', 'Why SkelApp – About Us'),
    'seoDescription' => content('why.meta.description', 'SkelApp is on a mission to transform Tanzanian small businesses — built by people who show up, with products and support that just work.'),
    'seoImage' => content_image('home.hero.background_image_desktop', asset('assets/HeroImage.webp')),
    'seoPageType' => 'AboutPage',
    'seoBreadcrumbs' => [
      ['name' => 'Home', 'url' => url('/')],
      ['name' => 'Why SkelApp', 'url' => route('why.show')],
    ],
  ])
  <link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="why-page-body">
  @include('partials.site-nav')

  <main class="hwp why-page">

    {{-- ══════════════ HERO (identical structure to the home hero) ══════════════ --}}
    <section class="hero" id="about" aria-label="About SkelApp">
      <div
        id="hero-bg"
        class="hero-bg"
        data-bg-desktop="{{ content_image('home.hero.background_image_desktop', asset('assets/HeroImage.webp')) }}"
        data-bg-mobile="{{ content_image('home.hero.background_image_mobile', asset('assets/HeroImage.jpg')) }}"
      ></div>
      <div class="hero-overlay"></div>
      <script>
        (function () {
          var el = document.getElementById('hero-bg');
          if (!el) return;
          var desktop = el.dataset.bgDesktop || '';
          var mobile = el.dataset.bgMobile || '';
          var apply = function () {
            var url = window.matchMedia('(max-width: 900px)').matches ? (mobile || desktop) : desktop;
            if (url) el.style.setProperty('background-image', "url('" + url.replace(/'/g, "\\'") + "')", 'important');
          };
          apply();
          window.addEventListener('resize', apply);

          // Scroll-driven zoom: the whole hero section scales as it scrolls away
          // and scales back in toward the top. Matches the home hero exactly.
          var hero = el.closest('.hero') || el.parentElement;
          var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
          if (!reduce && hero) {
            var ticking = false;
            var update = function () {
              var h = hero.offsetHeight || 1;
              var progress = Math.min(Math.max(window.scrollY / h, 0), 1);
              hero.style.transform = 'scale(' + (1 - progress * 0.18).toFixed(4) + ')';
              ticking = false;
            };
            var onScroll = function () {
              if (!ticking) { window.requestAnimationFrame(update); ticking = true; }
            };
            update();
            window.addEventListener('scroll', onScroll, { passive: true });
          }
        })();
      </script>
      <div class="hero-content">
        <div class="hero-left">
          <h1 class="{{ content_typography_class('why.hero.title') }}" style="{{ content_typography_vars('why.hero.title') }}">{!! content_text_html('why.hero.title', 'About SkelApp <br>and why we built') !!}</h1>
          <p class="{{ content_typography_class('why.hero.subtitle') }}" style="{{ content_typography_vars('why.hero.subtitle') }}">{!! content_text_html('why.hero.subtitle', "We put powerful, affordable tools in the hands of every shop — so businesses grow stronger and the people around them thrive.") !!}</p>
          <div class="hero-cta">
            <a href="{{ content('why.hero.primary_url', route('contact.show')) }}" class="btn-download">{{ content('why.hero.primary_label', 'Talk to us') }}</a>
            <a href="{{ content('why.hero.secondary_url', route('features.show')) }}" class="btn-demo">{{ content('why.hero.secondary_label', 'Explore SkelApp') }}</a>
          </div>
        </div>
      </div>
    </section>

    {{-- ══════════════ WHY WE BUILD SKELAPP (numbered reasons) ══════════════ --}}
    <section class="hwp-showcase why-build">
      <div class="hwp-shell">
        <div class="hwp-section-header">
          <h2 class="hwp-section-title {{ content_typography_class('why.build.title') }}" style="{{ content_typography_vars('why.build.title') }}">{{ content_text('why.build.title', 'Why we build SkelApp') }}</h2>
          <p class="hwp-section-subtitle {{ content_typography_class('why.build.subtitle') }}" style="{{ content_typography_vars('why.build.subtitle') }}">{{ content_text('why.build.subtitle', 'A few beliefs shaped everything — from the first line of code to the team that shows up at your shop.') }}</p>
        </div>
        <div class="hw-why-grid">
          @foreach ($buildReasons as $idx => $reason)
            <div class="hw-why-card">
              <span class="hw-why-num">0{{ $idx + 1 }}</span>
              <h3 class="hw-why-title">{{ $reason['title'] ?? '' }}</h3>
              <p class="hw-why-copy">{{ $reason['body'] ?? '' }}</p>
            </div>
          @endforeach
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

    {{-- ══════════════ PHOTO GRID (one large over two) ══════════════ --}}
    <section class="allfeatures allfeatures--plain">
      <div class="allfeatures-container">
        <div class="hwp-section-header">
          <h2 class="hwp-section-title {{ content_typography_class('why.gallery.title') }}" style="{{ content_typography_vars('why.gallery.title') }}">{!! content('why.gallery.title', 'We genuinely<br><span class="af-accent">care</span>') !!}</h2>
          <p class="hwp-section-subtitle {{ content_typography_class('why.gallery.subtitle') }}" style="{{ content_typography_vars('why.gallery.subtitle') }}">{{ content_text('why.gallery.subtitle', 'A look inside the shops we serve — and the people who make every day worth showing up for.') }}</p>
        </div>

        <div class="why-photo-grid">
          <figure class="why-photo why-photo--main">
            <img src="{{ $gridMain }}" alt="" loading="lazy" decoding="async">
          </figure>
          <figure class="why-photo">
            <img src="{{ $gridA }}" alt="" loading="lazy" decoding="async">
          </figure>
          <figure class="why-photo">
            <img src="{{ $gridB }}" alt="" loading="lazy" decoding="async">
          </figure>
        </div>
      </div>
    </section>

    {{-- ══════════════ SUPPORT SHOWCASE (scroll-pinned, flat) ══════════════ --}}
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
