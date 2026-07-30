@php
  $heroBenefits = content_list('pricing.hero.benefits', []);
  $tiers = content_list('pricing.tiers', []);
  $featuresHeading = content_text('pricing.tiers_features_heading', 'What you get');
  $pricingFaqs = content_list('faq.home_preview.items', []);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ content('pricing.meta.title', 'Pricing') }}</title>
  <meta name="description" content="{{ content('pricing.meta.description', "SkelApp pricing — clear, affordable, no hidden costs.") }}">
  @include('partials.seo', [
    'seoTitle' => content('pricing.meta.title', 'Pricing'),
    'seoDescription' => content('pricing.meta.description', 'SkelApp pricing — clear, affordable, no hidden costs.'),
    'seoImage' => content_image('pricing.hero.image', asset('assets/HeroImage.webp')),
    'seoPageType' => 'WebPage',
    'seoFaqs' => $pricingFaqs,
    'seoBreadcrumbs' => [
      ['name' => 'Home', 'url' => url('/')],
      ['name' => 'Pricing', 'url' => route('pricing.show')],
    ],
  ])
  <link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="pricing-page-body">
  @include('partials.site-nav')

  <main class="pricing-page">
    {{-- ══════════════ HERO BANNER ══════════════ --}}
    <section class="pricing-hero" aria-label="Pricing overview">
      <div class="pricing-hero-banner">
        <img src="{{ content_image('pricing.hero.image', asset('assets/HeroImage.webp')) }}" alt="" class="pricing-hero-bg" loading="eager" decoding="async">
        <div class="pricing-hero-content">
          <p class="pricing-hero-eyebrow">{{ content_text('pricing.hero.eyebrow', 'SkelApp pricing') }}</p>
          <h1 class="pricing-hero-title">{!! nl2br(e(content_text('pricing.hero.title', "The point of sale built for Tanzania.\nPriced so any shop can start."))) !!}</h1>

          @if (!empty($heroBenefits))
            <ul class="pricing-hero-benefits">
              @foreach ($heroBenefits as $benefit)
                <li class="pricing-hero-benefit">
                  @include('partials.pricing-icon', ['name' => $benefit['icon'] ?? 'check', 'size' => 17])
                  <span>{{ $benefit['text'] ?? '' }}</span>
                </li>
              @endforeach
            </ul>
          @endif

          <p class="pricing-hero-altlink">
            {{ content_text('pricing.hero.alt_prefix', 'Need the hardware too?') }}
            <a href="{{ content('pricing.hero.alt_url', '/hardware') }}">{{ content_text('pricing.hero.alt_label', 'Explore SkelApp devices') }}</a>
          </p>
        </div>
      </div>
    </section>

    <section class="pricing-plan-section" id="pricing-plans" aria-label="Pricing plans">
      <div class="pricing-tier-grid">
        @foreach ($tiers as $tier)
          @php
            // Features are stored one-per-line in a textarea (the CMS repeater
            // has no nested-repeater support).
            $tierFeatures = array_values(array_filter(
              array_map('trim', preg_split('/\r\n|\r|\n/', (string) ($tier['features'] ?? ''))),
              fn ($line) => $line !== ''
            ));
            $isFeatured = !empty($tier['is_featured']);
            $price = $tier['price'] ?? '';
            $priceNote = $tier['price_note'] ?? '';
          @endphp
          <article class="pricing-tier {{ $isFeatured ? 'pricing-tier--featured' : '' }}">
            @if (!empty($tier['badge']))
              <span class="pricing-tier-badge">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.9 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14l-5-4.87 7.1-1.01z"/></svg>
                {{ $tier['badge'] }}
              </span>
            @endif

            <h3 class="pricing-tier-name">{{ $tier['name'] ?? '' }}</h3>
            <p class="pricing-tier-desc">{{ $tier['description'] ?? '' }}</p>

            <hr class="pricing-tier-rule">

            <p class="pricing-tier-price">
              <span class="pricing-tier-amount">{{ $price }}</span>
              @if (!empty($tier['price_suffix']))
                <span class="pricing-tier-suffix">{{ $tier['price_suffix'] }}</span>
              @endif
            </p>
            {{-- Always rendered, even when blank: it reserves its line so the CTAs
                 stay on one baseline across all three cards. --}}
            <p class="pricing-tier-note">{{ $priceNote }}</p>

            <a href="{{ ($tier['cta_url'] ?? '') ?: route('contact.show') }}" class="pricing-tier-cta">{{ $tier['cta_label'] ?? 'Get started' }}</a>

            @if (!empty($tierFeatures))
              <hr class="pricing-tier-rule">
              <p class="pricing-tier-features-heading">{{ $featuresHeading }}</p>
              <ul class="pricing-tier-features">
                @foreach ($tierFeatures as $tierFeature)
                  <li class="pricing-tier-feature">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
                    <span>{{ $tierFeature }}</span>
                  </li>
                @endforeach
              </ul>
            @endif
          </article>
        @endforeach
      </div>
    </section>

    @php
      $gsPhone = content('pricing.getstarted.phone_image', asset('assets/Mobilehomeview.png'));
      // If the stored value is a relative asset path (legacy), resolve via asset(); if it already includes a scheme, use as-is.
      if (!str_starts_with($gsPhone, 'http') && !str_starts_with($gsPhone, '/storage/')) {
        $gsPhone = asset($gsPhone);
      }
    @endphp
    <section class="pricing-getstarted" aria-labelledby="getstarted-heading">
      <div class="hwp-hero-shell">
        <div class="hwp-hero-panel">
          <div class="hwp-hero-copy">
            <div class="hwp-hero-tagrow">
              <span class="hwp-hero-badge">SkelApp</span>
              <span class="hwp-hero-label">Get started</span>
            </div>
            <h2 id="getstarted-heading" class="hwp-hero-title">{{ content_text('pricing.getstarted.title_top', 'Get Started') }} {{ content('pricing.getstarted.title_bottom', 'with the App') }}</h2>
            <p class="hwp-hero-subtitle">{{ content_text('pricing.getstarted.copy') }}</p>
            <div class="hwp-hero-actions">
              <a href="{{ content('pricing.getstarted.cta_url', '#') }}" class="hwp-hero-btn hwp-hero-btn--solid" data-download-modal>{{ content('pricing.getstarted.cta_label', 'Download Now') }}</a>
            </div>
          </div>
          <div class="hwp-hero-art">
            <img
              src="{{ $gsPhone }}"
              alt="SkelApp running on mobile"
              class="hwp-hero-image"
              loading="lazy"
              decoding="async"
            />
          </div>
        </div>
      </div>
    </section>

    @include('partials.home-faq')
  </main>

  @include('partials.site-footer')

  <script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
</body>
</html>
