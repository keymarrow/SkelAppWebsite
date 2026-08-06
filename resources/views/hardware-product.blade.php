@php
  use App\Support\CmsCatalogs;

  $products = collect(content_list('hardware.products', CmsCatalogs::hardwareProducts()))
    ->filter(fn ($item) => is_array($item) && filled($item['slug'] ?? null))
    ->mapWithKeys(fn ($item) => [(string) $item['slug'] => $item])
    ->all();
  $slug = $slug ?? array_key_first($products);
  $product = $product ?? ($products[$slug] ?? []);

  $features = !empty($product['features']) && is_array($product['features'])
    ? $product['features']
    : collect(range(1, 4))->map(function (int $index) use ($product) {
        $title = trim((string) ($product["feature_{$index}_title"] ?? ''));
        $body = trim((string) ($product["feature_{$index}_body"] ?? ''));
        $image = $product["feature_{$index}_image"] ?? '';

        if ($title === '' && $body === '' && $image === '') {
            return null;
        }

        return [
            'name' => $title,
            'body' => $body,
            'image' => $image,
        ];
    })->filter()->values()->all();
  $specs = !empty($product['specs']) && is_array($product['specs'])
    ? $product['specs']
    : collect(range(1, 5))->map(function (int $index) use ($product) {
        $label = trim((string) ($product["spec_{$index}_label"] ?? ''));
        $rows = CmsCatalogs::parseKeyValueLines($product["spec_{$index}_rows_text"] ?? null);

        if ($label === '' && empty($rows)) {
            return null;
        }

        return [
            'label' => $label,
            'rows' => $rows,
        ];
    })->filter()->values()->all();
  $tm = content('pos.testimonial', config('hardware_products.shared.testimonial', []));
  $eco = content('pos.ecosystem', config('hardware_products.shared.ecosystem', []));
  $faq = content('hardware.product_faq', array_merge(config('hardware_products.shared.faq', []), [
      'subtitle' => 'Hardware FAQ',
      'read_more_label' => 'Read more',
  ]));
  $seoProductAttributes = collect($specs)
    ->flatMap(fn ($spec) => collect($spec['rows'] ?? [])
      ->map(fn ($row) => ['name' => $row['k'] ?? '', 'value' => $row['v'] ?? '']))
    ->filter(fn ($row) => trim((string) ($row['name'] ?? '')) !== '' && trim((string) ($row['value'] ?? '')) !== '')
    ->values()
    ->all();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $product['meta_title'] ?? (($product['name'] ?? 'Hardware').' POS Hardware') }}</title>
  <meta name="description" content="{{ $product['meta_description'] ?? '' }}">
  @include('partials.seo', [
    'seoTitle' => $product['meta_title'] ?? (($product['name'] ?? 'Hardware').' POS Hardware'),
    'seoDescription' => $product['meta_description'] ?? ($product['hero_subtitle'] ?? 'SkelApp hardware built for Tanzanian retail.'),
    'seoImage' => cms_image($product['hero_image'] ?? null, asset('assets/PosSystemRegister.webp')),
    'seoPageType' => 'WebPage',
    'seoProduct' => [
      'name' => $product['name'] ?? 'SkelApp Hardware',
      'description' => $product['meta_description'] ?? ($product['hero_subtitle'] ?? 'SkelApp hardware built for Tanzanian retail.'),
      'image' => cms_image($product['hero_image'] ?? null, asset('assets/PosSystemRegister.webp')),
      'category' => 'Point of Sale Hardware',
      'attributes' => $seoProductAttributes,
    ],
    'seoFaqs' => $faq['items'] ?? [],
    'seoBreadcrumbs' => [
      ['name' => 'Home', 'url' => url('/')],
      ['name' => 'Hardware', 'url' => route('hardware.show')],
      ['name' => $product['name'] ?? 'Hardware', 'url' => route('hardware.product', $slug)],
    ],
  ])
  <link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="hardware-product-body">
  @include('partials.site-nav')

  <main class="hwp">

    {{-- ══════════════ HERO BANNER ══════════════ --}}
    <section class="hwp-hero">
      <div class="hwp-hero-shell">
        <div class="hwp-hero-panel">
          <div class="hwp-hero-copy">
            <div class="hwp-hero-tagrow">
              <span class="hwp-hero-badge">{{ $product['hero_badge'] ?? 'NEW' }}</span>
              <span class="hwp-hero-label">{{ $product['name'] ?? '' }}</span>
            </div>
            <h1 class="hwp-hero-title">{{ $product['intro_title'] ?? '' }} <strong>{{ $product['intro_title_accent'] ?? '' }}</strong></h1>
            <p class="hwp-hero-subtitle">{{ $product['hero_subtitle'] ?? '' }}</p>
            <div class="hwp-hero-actions">
              <a href="#hwp-detail" class="hwp-hero-btn hwp-hero-btn--ghost">{{ $product['hero_secondary_label'] ?? 'Learn more' }}</a>
              <a href="{{ route('contact.show') }}" class="hwp-hero-btn hwp-hero-btn--solid">{{ $product['hero_primary_label'] ?? 'Get a demo' }}</a>
            </div>
          </div>
          <div class="hwp-hero-art">
            <img src="{{ cms_image($product['hero_image'] ?? null, asset('assets/PosSystemRegister.webp')) }}" alt="{{ $product['name'] ?? '' }}" class="hwp-hero-image" loading="eager" decoding="async">
          </div>
        </div>
      </div>
    </section>

    {{-- ══════════════ DEVICE SHOWCASE (details, scroll-pinned) ══════════════ --}}
    <section class="hwp-showcase hwp-showcase--pinned" id="hwp-detail" data-feature-scroll>
      <div class="hwp-pin-stage">
        <div class="hwp-pin-inner">
          <div class="hwp-shell">
            <div class="hwp-section-header">
              <h2 class="hwp-section-title">{{ $product['detail_title_prefix'] ?? (($product['name'] ?? '').',') }} {{ $product['detail_title_accent'] ?? 'up close' }}</h2>
              <p class="hwp-section-subtitle">{{ $product['detail_subtitle'] ?? '' }}</p>
            </div>
            @include('partials.hwp-feature', ['features' => $features])
          </div>
        </div>
      </div>
    </section>

    {{-- ══════════════ SPECIFICATIONS ══════════════ --}}
    <section class="hwp-specs">
      <div class="hwp-shell">
        <div class="hwp-section-header">
          <h2 class="hwp-section-title">{{ $product['specs_title_prefix'] ?? ($product['name'] ?? '') }} {{ $product['specs_title_accent'] ?? 'specifications' }}</h2>
          <p class="hwp-section-subtitle">{{ $product['specs_subtitle'] ?? 'Every detail that matters — screen, power, payments and connectivity, all in one place.' }}</p>
        </div>
        <div class="hwp-specs-grid">
          <div class="hwp-specs-media">
            <img src="{{ cms_image($product['hero_image'] ?? null, asset('assets/PosSystemRegister.webp')) }}" alt="{{ $product['name'] ?? '' }}" loading="lazy" decoding="async">
          </div>
          <div class="hwp-accordion">
            @foreach ($specs as $i => $spec)
              <div class="hwp-acc-item {{ $i === 0 ? 'is-open' : '' }}">
                <button type="button" class="hwp-acc-head" data-acc>
                  <span>{{ $spec['label'] }}</span>
                  <span class="hwp-acc-icon" aria-hidden="true"></span>
                </button>
                <div class="hwp-acc-panel">
                  <dl class="hwp-spec-list">
                    @foreach ($spec['rows'] as $row)
                      <div><dt>{{ $row['k'] }}</dt><dd>{{ $row['v'] }}</dd></div>
                    @endforeach
                  </dl>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </section>

    {{-- ══════════════ TESTIMONIAL ══════════════ --}}
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
              @foreach ($tm['stats'] as $s)
                <li><strong>{{ $s['value'] }}</strong><span>{{ $s['label'] }}</span></li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </section>

    {{-- ══════════════ ECOSYSTEM SHOWCASE (details, mirrored, scroll-pinned) ══════════════ --}}
    <section class="hwp-showcase hwp-showcase--alt hwp-showcase--pinned" data-feature-scroll>
      <div class="hwp-pin-stage">
        <div class="hwp-pin-inner">
          <div class="hwp-shell">
            <div class="hwp-section-header">
              <h2 class="hwp-section-title">{{ $eco['title'] }} {{ $eco['title_accent'] }}</h2>
              <p class="hwp-section-subtitle">{{ $eco['subtitle'] }}</p>
            </div>
            @include('partials.hwp-feature', ['features' => $eco['features']])
          </div>
        </div>
      </div>
    </section>

    {{-- ══════════════ FAQ (shared arrangement with the features page) ══════════════ --}}
    <section class="faq-section">
      <div class="faq-container">
        <div class="faq-header">
          <h2>{{ $faq['title'] }} {{ $faq['title_accent'] }}</h2>
          <a href="{{ route('faq.show') }}" class="faq-read-more">{{ $faq['read_more_label'] ?? 'Read more' }}</a>
        </div>
        <div class="faq-layout">
          <p class="faq-subtitle">{{ $faq['subtitle'] ?? 'Hardware FAQ' }}</p>

          <div class="faq-list">
            @foreach ($faq['items'] as $i => $item)
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

    {{-- ══════════════ CLOSING CTA (pricing awareness) ══════════════ --}}
    <section class="hwp-cta">
      <div class="hwp-pricing-shell">
        <div class="hwp-pricing-head">
          <h2 class="hwp-pricing-title">{{ content_text('pos.pricing.title', 'Run your whole shop for') }} {{ content('pos.pricing.title_accent', 'nearly nothing a day') }}</h2>
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
            <span class="hwp-pricing-name">{{ $product['name'] ?? 'SkelApp' }} <strong>{{ content('pos.pricing.card_name_accent', '+ SkelApp') }}</strong></span>
          </div>
          <div class="hwp-pricing-figures">
            <span class="hwp-pricing-was">{{ content_text('pos.pricing.price_was', '1,000 TZS') }}<small>{{ content('pos.pricing.price_was_suffix', '/day') }}</small></span>
            <span class="hwp-pricing-now">{{ content_text('pos.pricing.price_now', '500 TZS') }}<small>{{ content('pos.pricing.price_now_suffix', '/day') }}</small></span>
          </div>
        </a>

        <p class="hwp-pricing-note">{{ content_text('pos.pricing.note', 'Karibu sifuri kwa siku — about 500 TZS a day runs your entire shop, less than a cup of chai.') }}</p>
      </div>
    </section>

  </main>

  @include('partials.site-footer')

  <script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
  <script>
    (function () {
      // Hero zoom: the hero scales up as you scroll toward the top (and eases
      // back down as it scrolls away) — same feel as the home hero section.
      (function () {
        var hero = document.querySelector('.hwp-hero');
        var panel = document.querySelector('.hwp-hero-panel');
        if (!hero || !panel) return;
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        panel.style.transformOrigin = 'center top';
        panel.style.willChange = 'transform';
        var ticking = false;
        var update = function () {
          var h = hero.offsetHeight || 1;
          var progress = Math.min(Math.max(window.scrollY / h, 0), 1);
          panel.style.transform = 'scale(' + (1 - progress * 0.12).toFixed(4) + ')';
          ticking = false;
        };
        window.addEventListener('scroll', function () {
          if (!ticking) { window.requestAnimationFrame(update); ticking = true; }
        }, { passive: true });
        update();
      })();

      // Feature switchers: clicking a tab swaps the active copy + media.
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
      // Accordions (specs + FAQ): opening one closes the others in the same group.
      document.querySelectorAll('[data-acc]').forEach(function (head) {
        head.addEventListener('click', function () {
          var item = head.closest('.hwp-acc-item');
          var group = head.closest('.hwp-accordion');
          if (group) {
            group.querySelectorAll('.hwp-acc-item.is-open').forEach(function (other) {
              if (other !== item) {
                other.classList.add('is-closing-instant'); // close with no transition
                other.classList.remove('is-open');
              }
            });
            // restore transitions on the next frame for future opens
            requestAnimationFrame(function () {
              requestAnimationFrame(function () {
                group.querySelectorAll('.is-closing-instant').forEach(function (o) {
                  o.classList.remove('is-closing-instant');
                });
              });
            });
          }
          item.classList.toggle('is-open');
        });
      });

      // Scroll-pinned feature showcase: while the section is pinned, scrolling
      // progressively opens each tab until all are revealed, then releases.
      var canPin = window.matchMedia('(min-width: 1025px)');
      var reduce = window.matchMedia('(prefers-reduced-motion: reduce)');
      document.querySelectorAll('[data-feature-scroll]').forEach(function (section) {
        var stage = section.querySelector('.hwp-pin-stage'); // the tall track
        var group = section.querySelector('[data-feature]');
        if (!stage || !group) return;
        var mediaWrap = group.querySelector('.hwp-feature-media');
        var tabs  = group.querySelectorAll('[data-feature-tab]');
        var media = group.querySelectorAll('[data-feature-media]');
        var n = tabs.length;
        var enabled = false;
        var ticking = false;
        var current = 0;
        var leaveTimer;

        var STEP = 0.4; // viewport fraction of scroll per tab (lower = quicker)
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
