@php
  // ── Hardware lineup (primary devices) ───────────────────────────────
  $lineup = content_list('hardware.lineup.items', [
    ['name' => 'Skel Terminal', 'tag' => 'Handheld POS',       'copy' => 'Take orders and payments anywhere in your shop — pocket-sized, all-day battery.', 'image' => 'assets/Pos System 04.png',      'url' => route('hardware.product', 'skel-terminal')],
    ['name' => 'Skel Register', 'tag' => 'Countertop POS',     'copy' => 'A full checkout station for busy counters — big screen, fast and reliable.',     'image' => 'assets/PosSystemRegister.webp', 'url' => route('hardware.product', 'skel-register')],
    ['name' => 'Skel Tab',      'tag' => 'Tablet POS',         'copy' => 'Turn a tablet into a complete point of sale with the SkelApp stand.',            'image' => 'assets/Moc-tab.webp',           'url' => route('hardware.product', 'skel-tab')],
    ['name' => 'Skel Phone',    'tag' => 'POS on your phone',  'copy' => 'Run your whole shop from the phone in your pocket — scan, sell and print.',      'image' => 'assets/Mobilehomeview.png',     'url' => route('hardware.product', 'skel-phone')],
  ]);

  // ── Spotlight rows (flagship devices, alternating) ──────────────────
  $spotlights = content_list('hardware.spotlights.items', [
    [
      'eyebrow' => 'Flagship countertop',
      'name'    => 'SkelApp Register',
      'copy'    => 'The complete checkout for your counter. A bright, tilting touchscreen, built-in receipt printing and room for a drawer and scanner — all running SkelApp out of the box.',
      'image'   => 'assets/PosSystemRegister.webp',
      'points'  => ['Bright tilting touchscreen', 'Built-in thermal printer', 'Plug-and-play with SkelApp', 'Works offline, syncs later'],
      'url'     => '#',
    ],
    [
      'eyebrow' => 'Sell anywhere',
      'name'    => 'SkelApp Terminal',
      'copy'    => 'Your whole shop in one hand. Scan, sell, take mobile money and print a receipt from the aisle, the table or the doorway — all-day battery included.',
      'image'   => 'assets/Mobilehomeview.png',
      'points'  => ['All-day battery life', 'Built for one-hand use', 'M-Pesa, Airtel & Tigo ready', 'Rugged, dust-resistant body'],
      'url'     => '#',
    ],
  ]);

  // ── Accessories (compact grid) ──────────────────────────────────────
  $accessories = content_list('hardware.accessories.items', [
    ['name' => 'Tablet stand',    'copy' => 'Sturdy, swivelling stand for counter checkout.', 'image' => 'assets/Moc-tab.webp'],
    ['name' => 'Charging dock',   'copy' => 'Keep every Terminal topped up and ready.',       'image' => 'assets/hardware.webp'],
    ['name' => 'Receipt paper',   'copy' => '80mm thermal rolls, sold by the box.',           'image' => 'assets/Pos System 04.png'],
    ['name' => 'Barcode Scanner', 'copy' => 'Ring up items in a tap — wired or wireless.',     'image' => 'assets/inventorytrack.webp'],
    ['name' => 'Cash Drawer',     'copy' => 'Secure cash, opens automatically on each sale.',  'image' => 'assets/PosSystem.webp'],
  ]);

  // ── Why SkelApp hardware (value props) ──────────────────────────────
  $whyItems = content_list('hardware.why.items', [
    ['title' => 'Plug-and-play', 'copy' => 'Open the box, sign in to SkelApp, and start selling in minutes — no IT person required.'],
    ['title' => 'Offline-ready',  'copy' => 'Power cut or weak network? Keep ringing up sales. Everything syncs the moment you reconnect.'],
    ['title' => 'Built for here', 'copy' => 'Chosen and tested for Tanzanian retail — dust, heat, voltage swings and long days.'],
    ['title' => 'Local support',  'copy' => 'On-the-ground help in Dar es Salaam, WhatsApp support and a warranty on every device.'],
  ]);

@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ content('hardware.meta.title', 'POS Hardware & Accessories in Tanzania') }}</title>
  <meta name="description" content="{{ content('hardware.meta.description', 'SkelApp hardware and accessories — terminals, registers, printers, scanners and complete kits built for Tanzanian retail.') }}">
  @include('partials.seo', [
    'seoTitle' => content('hardware.meta.title', 'POS Hardware & Accessories in Tanzania'),
    'seoDescription' => content('hardware.meta.description', 'SkelApp hardware and accessories — terminals, registers, printers, scanners and complete kits built for Tanzanian retail.'),
    'seoImage' => content_image('hardware.hero.image', asset('assets/PosSystemRegister.webp')),
    'seoPageType' => 'CollectionPage',
    'seoBreadcrumbs' => [
      ['name' => 'Home', 'url' => url('/')],
      ['name' => 'Hardware', 'url' => route('hardware.show')],
    ],
  ])
  <link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="hardware-page-body">
  @include('partials.site-nav')

  <main class="hwp hardware-page">

    {{-- ══════════════ HERO ══════════════ --}}
    <section class="hwp-hero">
      <div class="hwp-hero-shell">
        <div class="hwp-hero-panel">
          <div class="hwp-hero-copy">
            <div class="hwp-hero-tagrow">
              <span class="hwp-hero-badge">SkelApp</span>
              <span class="hwp-hero-label">{{ content_text('hardware.hero.eyebrow', 'Hardware lineup') }}</span>
            </div>
            <h1 {!! content_typography_html('hardware.hero.title', 'hwp-hero-title') !!}>{!! nl2br(e(content_text('hardware.hero.title', "Hardware built for\nhow Tanzania sells."))) !!}</h1>
            <p {!! content_typography_html('hardware.hero.subtitle', 'hwp-hero-subtitle') !!}>{{ content_text('hardware.hero.subtitle', 'Terminals, registers, printers, scanners and everything in between — chosen, tested and supported to run SkelApp from day one.') }}</p>
            <div class="hwp-hero-actions">
              <a href="#lineup" class="hwp-hero-btn hwp-hero-btn--ghost">{{ content('hardware.hero.secondary_label', 'See the lineup') }}</a>
              <a href="{{ content('hardware.hero.primary_url', route('contact.show')) }}" class="hwp-hero-btn hwp-hero-btn--solid">{{ content('hardware.hero.primary_label', 'Talk to our team') }}</a>
            </div>
            <ul class="hw-hero-stats">
              <li><strong>{{ content('hardware.hero.stat1_value', '5 min') }}</strong><span>{{ content('hardware.hero.stat1_label', 'to set up') }}</span></li>
              <li><strong>{{ content('hardware.hero.stat2_value', '100%') }}</strong><span>{{ content('hardware.hero.stat2_label', 'offline-ready') }}</span></li>
              <li><strong>{{ content('hardware.hero.stat3_value', '1 yr') }}</strong><span>{{ content('hardware.hero.stat3_label', 'warranty') }}</span></li>
            </ul>
          </div>
          <div class="hwp-hero-art">
            <img src="{{ content_image('hardware.hero.image', asset('assets/PosSystemRegister.webp')) }}" alt="SkelApp hardware lineup" class="hwp-hero-image" loading="eager" decoding="async">
          </div>
        </div>
      </div>
    </section>

    {{-- ══════════════ LINEUP ══════════════ --}}
    <section class="hwp-showcase" id="lineup">
      <div class="hw-shell">
        <div class="hwp-section-header">
          <h2 {!! content_typography_html('hardware.lineup.title', 'hwp-section-title') !!}>{{ content_text('hardware.lineup.title', 'Everything your counter needs') }}</h2>
          <p {!! content_typography_html('hardware.lineup.subtitle', 'hwp-section-subtitle') !!}>{{ content_text('hardware.lineup.subtitle', 'Mix and match the devices that fit your shop — every one of them speaks SkelApp natively.') }}</p>
        </div>

        <div class="hw-lineup-grid">
          @foreach ($lineup as $idx => $item)
            <a href="{{ $item['url'] ?? '#' }}" class="hw-product">
              <div class="hw-product-media">
                <img src="{{ cms_image($item['image'] ?? null, asset('assets/PosSystem.webp')) }}" alt="{{ $item['name'] ?? 'SkelApp hardware' }}" loading="lazy" decoding="async">
              </div>
              <div class="hw-product-body">
                <span class="hw-product-tag">{{ $item['tag'] ?? '' }}</span>
                <h3 class="hw-product-name">{{ $item['name'] ?? '' }}</h3>
                <p class="hw-product-copy">{{ $item['copy'] ?? '' }}</p>
                <span class="hw-product-link">Learn more
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </span>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    </section>

    {{-- ══════════════ SPOTLIGHTS ══════════════ --}}
    <section class="hwp-showcase hwp-showcase--alt">
      <div class="hw-shell">
        @foreach ($spotlights as $idx => $s)
          @php
            $spotPoints = $s['points'] ?? [];
            if (empty($spotPoints) && ! empty($s['points_text'])) {
              $spotPoints = collect(preg_split('/\r?\n/', (string) $s['points_text']))
                ->map(fn ($line) => trim((string) $line))
                ->filter()
                ->values()
                ->all();
            }
          @endphp
          <article class="hw-spot {{ $idx % 2 === 1 ? 'hw-spot--reverse' : '' }}">
            <div class="hw-spot-media">
              <img src="{{ cms_image($s['image'] ?? null, asset('assets/PosSystemRegister.webp')) }}" alt="{{ $s['name'] ?? '' }}" loading="lazy" decoding="async">
            </div>
            <div class="hw-spot-copy">
              <span class="hwp-eyebrow">{{ $s['eyebrow'] ?? '' }}</span>
              <h3 class="hw-spot-name">{{ $s['name'] ?? '' }}</h3>
              <p class="hw-spot-text">{{ $s['copy'] ?? '' }}</p>
              <ul class="hw-spot-points">
                @foreach ($spotPoints as $p)
                  <li>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    {{ $p }}
                  </li>
                @endforeach
              </ul>
              <a href="{{ $s['url'] ?? '#' }}" class="hwp-hero-btn hwp-hero-btn--ghost">Explore {{ $s['name'] ?? 'device' }}</a>
            </div>
          </article>
        @endforeach
      </div>
    </section>

    {{-- ══════════════ WHY ══════════════ --}}
    <section class="hwp-showcase">
      <div class="hw-shell">
        <div class="hwp-section-header">
          <h2 {!! content_typography_html('hardware.why.title', 'hwp-section-title') !!}>{{ content_text('hardware.why.title', 'Made to be switched on and forgotten about') }}</h2>
          <p {!! content_typography_html('hardware.why.subtitle', 'hwp-section-subtitle') !!}>{{ content_text('hardware.why.subtitle', 'Reliable, locally supported hardware that just works — set it up once and get on with selling.') }}</p>
        </div>
        <div class="hw-why-grid">
          @foreach ($whyItems as $idx => $w)
            <div class="hw-why-card">
              <span class="hw-why-num">0{{ $idx + 1 }}</span>
              <h3 class="hw-why-title">{{ $w['title'] ?? '' }}</h3>
              <p class="hw-why-copy">{{ $w['copy'] ?? '' }}</p>
            </div>
          @endforeach
        </div>
      </div>
    </section>

    {{-- ══════════════ ACCESSORIES ══════════════ --}}
    <section class="hwp-showcase">
      <div class="hw-shell">
        <div class="hwp-section-header">
          <h2 {!! content_typography_html('hardware.accessories.title', 'hwp-section-title') !!}>{{ content_text('hardware.accessories.title', 'The little things that finish the setup') }}</h2>
          <p {!! content_typography_html('hardware.accessories.subtitle', 'hwp-section-subtitle') !!}>{{ content_text('hardware.accessories.subtitle', 'Stands, printers, scanners and more — everything you need to complete your counter.') }}</p>
        </div>
        <div class="hw-acc-grid">
          @foreach ($accessories as $a)
            <div class="hw-acc-card">
              <div class="hw-acc-media">
                <img src="{{ cms_image($a['image'] ?? null, asset('assets/hardware.webp')) }}" alt="{{ $a['name'] ?? '' }}" loading="lazy" decoding="async">
              </div>
              <h3 class="hw-acc-name">{{ $a['name'] ?? '' }}</h3>
              <p class="hw-acc-copy">{{ $a['copy'] ?? '' }}</p>
            </div>
          @endforeach
        </div>
      </div>
    </section>

    {{-- ══════════════ CTA (pricing awareness, shared with product pages) ══════════════ --}}
    <section class="hwp-cta">
      <div class="hwp-pricing-shell">
        <div class="hwp-pricing-head">
          <h2 {!! content_typography_html('hardware.cta.title', 'hwp-pricing-title') !!}>{!! nl2br(e(content_text('hardware.cta.title', "Let's build your setup."))) !!}</h2>
          <a href="{{ content('hardware.cta.secondary_url', route('pricing.show')) }}" class="hwp-pricing-link">
            {{ content('hardware.cta.secondary_label', 'See pricing') }}
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
        <p class="hwp-pricing-note {{ content_typography_class('hardware.cta.subtitle') }}" style="{{ content_typography_vars('hardware.cta.subtitle') }}">{{ content_text('hardware.cta.subtitle', 'Tell us about your shop and we’ll recommend the right hardware — and have you selling on SkelApp in no time.') }}</p>
        <div class="hwp-hero-actions">
          <a href="{{ content('hardware.cta.primary_url', route('contact.show')) }}" class="hwp-hero-btn hwp-hero-btn--solid">{{ content('hardware.cta.primary_label', 'Talk to our team') }}</a>
        </div>
      </div>
    </section>

  </main>

  @include('partials.site-footer')

  <script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
  <script>
    (function () {
      // Hero zoom: scales up toward the top, eases back as it scrolls away.
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
  </script>
</body>
</html>
