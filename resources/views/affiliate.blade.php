@php
  $affiliatePage = config('affiliate_program', []);
  $meta = content('affiliate.meta', $affiliatePage['meta'] ?? []);
  $hero = content('affiliate.hero', $affiliatePage['hero'] ?? []);
  $steps = content_list('affiliate.steps', $affiliatePage['steps'] ?? []);
  $referrals = content('affiliate.referrals', $affiliatePage['referrals'] ?? []);
  $partnerSection = content('affiliate.partners', $affiliatePage['partners'] ?? []);
  $cta = content('affiliate.cta', $affiliatePage['cta'] ?? []);

  $applyUrl = route('affiliate.apply.show');
  $retailerCards = $referrals['cards'] ?? [];
  $partnerCards = $partnerSection['cards'] ?? [];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>{{ $meta['title'] ?? 'Affiliate Program | SkelApp' }}</title>
<meta name="description" content="{{ $meta['description'] ?? 'Join the SkelApp affiliate program and earn bonuses for referring businesses.' }}">
@include('partials.seo', [
  'seoTitle' => $meta['title'] ?? 'Affiliate Program | SkelApp',
  'seoDescription' => $meta['description'] ?? 'Join the SkelApp affiliate program and earn bonuses for referring businesses.',
  'seoImage' => cms_image($hero['image'] ?? null, asset('assets/attendants.webp')),
  'seoPageType' => 'WebPage',
  'seoBreadcrumbs' => [
    ['name' => 'Home', 'url' => url('/')],
    ['name' => 'Affiliate Program', 'url' => route('affiliate.show')],
  ],
])
<link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="affiliate-page-body">
@include('partials.site-nav')

<main class="affiliate-page">
  <section class="affiliate-hero">
    <div class="affiliate-shell">
      <div class="affiliate-hero-grid">
        <div class="affiliate-hero-copy">
          <p class="affiliate-hero-eyebrow">{{ $hero['eyebrow'] ?? 'SkelApp affiliate program' }}</p>
          <h1>
            <span>{{ $hero['title_lead'] ?? 'SkelApp' }}</span>
            <span class="affiliate-hero-accent">{{ $hero['title_accent'] ?? 'Affiliate' }}</span><br>
            {{ $hero['title_trail'] ?? 'Partner Program' }}
          </h1>
          <p class="affiliate-hero-text">{{ $hero['copy'] ?? '' }}</p>

          <div class="affiliate-hero-actions">
            <a href="{{ $applyUrl }}" class="affiliate-hero-btn affiliate-hero-btn--solid">
              {{ $hero['primary_label'] ?? 'Get started' }}
            </a>
          </div>

          @if (!empty($hero['note']))
            <p class="affiliate-hero-note">{{ $hero['note'] }}</p>
          @endif
        </div>

        <figure class="affiliate-hero-media">
          <img
            src="{{ cms_image($hero['image'] ?? null, asset('assets/attendants.webp')) }}"
            alt="{{ $hero['image_alt'] ?? 'SkelApp affiliate program' }}"
            loading="eager"
            decoding="async"
          >
        </figure>
      </div>
    </div>
  </section>

  <section class="affiliate-steps" id="affiliate-steps" aria-label="How the affiliate program works">
    <div class="affiliate-shell">
      <div class="affiliate-steps-grid">
        @foreach ($steps as $step)
          <article class="affiliate-step-card">
            <span class="affiliate-step-pill">{{ $step['label'] ?? '' }}</span>
            <h2>{{ $step['title'] ?? '' }}</h2>
            <p>{{ $step['copy'] ?? '' }}</p>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <section class="retailers-section affiliate-carousel-section affiliate-referrals-section" id="affiliate-referrals">
    <div class="container">
      <div class="retailer-head">
        <div class="section-header retailer-head-copy">
          <h2>
            <span class="affiliate-section-accent">{{ $referrals['eyebrow'] ?? 'Earn bonuses for referring' }}</span>
            {{ $referrals['title_rest'] ?? 'every type of retailer' }}
          </h2>
          <p>{{ $referrals['copy'] ?? '' }}</p>
        </div>

        <div class="carousel-slider" aria-label="Affiliate referral industries carousel controls">
          <button class="carousel-slider-button" type="button" data-carousel-prev aria-label="Previous retailer type">
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
              <path d="M14.5 5.5 8 12l6.5 6.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </button>

          <button class="carousel-slider-button" type="button" data-carousel-next aria-label="Next retailer type">
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
              <path d="M9.5 5.5 16 12l-6.5 6.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </button>

          <div class="carousel-slider-dots" role="tablist" aria-label="Affiliate referral industries" aria-hidden="true">
            @foreach ($retailerCards as $card)
              <button
                class="carousel-slider-dot{{ $loop->first ? ' is-active' : '' }}"
                type="button"
                role="tab"
                data-carousel-dot="{{ $loop->index }}"
                aria-label="Go to {{ $card['title'] ?? '' }}"
                aria-current="{{ $loop->first ? 'true' : 'false' }}"
                tabindex="-1"
              ></button>
            @endforeach
          </div>
        </div>
      </div>

      <div class="carousel-container" data-drag-scroll data-carousel-default-index="0">
        <div class="carousel-track">
          @foreach ($retailerCards as $card)
            <div class="retailer-card">
              <div class="card-image">
                <img
                  src="{{ cms_image($card['image'] ?? null, asset('assets/boutique.webp')) }}"
                  alt="{{ $card['title'] ?? '' }}"
                  draggable="false"
                  loading="lazy"
                  decoding="async"
                >
                @if (!empty($card['category']))
                  <span class="retailer-card-pill">{{ $card['category'] }}</span>
                @endif
                <div class="card-overlay">
                  <h3>{{ $card['title'] ?? '' }}</h3>
                </div>
                <a
                  href="{{ !empty($card['slug']) ? route('retailers.show', $card['slug']) : $applyUrl }}"
                  class="retailer-card-link"
                  aria-label="{{ $card['title'] ?? '' }}"
                ></a>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <section class="retailers-section affiliate-carousel-section affiliate-partner-section" id="affiliate-partners">
    <div class="container">
      <div class="retailer-head">
        <div class="section-header retailer-head-copy">
          <p class="affiliate-section-kicker">{{ $partnerSection['eyebrow'] ?? 'Who can be an affiliate?' }}</p>
          <h2>{{ $partnerSection['title'] ?? 'Built for partners who already influence commerce.' }}</h2>
          <p>{{ $partnerSection['copy'] ?? '' }}</p>
        </div>

        <div class="carousel-slider" aria-label="Affiliate partner profiles carousel controls">
          <button class="carousel-slider-button" type="button" data-carousel-prev aria-label="Previous partner profile">
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
              <path d="M14.5 5.5 8 12l6.5 6.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </button>

          <button class="carousel-slider-button" type="button" data-carousel-next aria-label="Next partner profile">
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
              <path d="M9.5 5.5 16 12l-6.5 6.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </button>

          <div class="carousel-slider-dots" role="tablist" aria-label="Affiliate partner profiles" aria-hidden="true">
            @foreach ($partnerCards as $card)
              <button
                class="carousel-slider-dot{{ $loop->first ? ' is-active' : '' }}"
                type="button"
                role="tab"
                data-carousel-dot="{{ $loop->index }}"
                aria-label="Go to {{ $card['title'] ?? '' }}"
                aria-current="{{ $loop->first ? 'true' : 'false' }}"
                tabindex="-1"
              ></button>
            @endforeach
          </div>
        </div>
      </div>

      <div class="carousel-container" data-drag-scroll data-carousel-default-index="0">
        <div class="carousel-track">
          @foreach ($partnerCards as $card)
            <div class="retailer-card retailer-card--affiliate-role">
              <div class="card-image">
                <img
                  src="{{ cms_image($card['image'] ?? null, asset('assets/client.webp')) }}"
                  alt="{{ $card['title'] ?? '' }}"
                  draggable="false"
                  loading="lazy"
                  decoding="async"
                >
                @if (!empty($card['category']))
                  <span class="retailer-card-pill">{{ $card['category'] }}</span>
                @endif
                <div class="card-overlay">
                  <h3>{{ $card['title'] ?? '' }}</h3>
                  @if (!empty($card['copy']))
                    <p>{{ $card['copy'] }}</p>
                  @endif
                </div>
                <a href="{{ $applyUrl }}" class="retailer-card-link" aria-label="{{ $card['title'] ?? '' }}"></a>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <section class="affiliate-cta" id="affiliate-cta">
    <div class="affiliate-shell">
      <div class="affiliate-cta-card">
        <h2>{{ $cta['title'] ?? 'Become a SkelApp affiliate partner' }}</h2>
        <p>{{ $cta['copy'] ?? '' }}</p>
        <a href="{{ $applyUrl }}" class="affiliate-hero-btn affiliate-hero-btn--solid">
          {{ $cta['button_label'] ?? 'Get started' }}
        </a>
      </div>
    </div>
  </section>
</main>

@include('partials.site-footer')

<script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
</body>
</html>
