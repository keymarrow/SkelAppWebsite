@php
  $homeShowcasePoints = content_list('home.showcase.points', []);
  $retailerCards = content_list('home.retailers.cards', []);
  $allFeatureCards = content_list('home.allfeatures.cards', []);
  $howSteps = content_list('home.howitworks.steps', []);
  $pricingSummaryFeatures = content_list('home.pricing_summary.features', []);
  $pricingSummaryBenefits = content_list('home.pricing_summary.benefits', []);
  $homePricingBenefit2Mobile = content('home.pricing_summary.benefit_mobile_2', '');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>{{ content('home.meta.title', 'SkelApp – The Best POS in Tanzania.') }}</title>
<meta name="description" content="{{ content('home.meta.description') }}">
<link rel="icon" href="{{ asset('assets/skel.svg') }}" type="image/x-icon" />
<link rel="preload" as="image" href="{{ asset('assets/HeroImage.webp') }}" media="(min-width: 901px)" fetchpriority="high" />
<link rel="preload" as="image" href="{{ asset('assets/HeroImage.jpg') }}" media="(max-width: 900px)" fetchpriority="high" />
<link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body>
@include('partials.site-nav', ['isHome' => true])
<section class="hero" id="overview">
  <div class="hero-bg"></div>
  <div class="hero-overlay"></div>
<div class="hero-content">
  <div class="hero-left">
    <h1>{{ content('home.hero.title', 'Run your Business like a pro.') }}</h1>
    <p>{!! nl2br(e(content('home.hero.subtitle', "SkelApp is Tanzania's #1 Point Of Sale. Track every sale, purchase, expense and stock level — from your phone."))) !!}</p>
    <a href="{{ content('home.hero.cta_url', '#') }}" class="btn-download">{{ content('home.hero.cta_label', 'Start free — Download SkelApp') }}</a>
  </div>

  <div class="hero-right">
    <div class="testimonial-card">
      <div class="stars">
        <img src="{{ asset('assets/Stars.svg') }}" alt="5 star rating">
      </div>
      <blockquote>
        {{ content('home.hero.testimonial_quote') }}
      </blockquote>
      <cite>{{ content('home.hero.testimonial_attribution') }}</cite>
    </div>
  </div>
</div>
</section>

<section class="app-showcase" id="showcase">
  <div class="showcase-container">
    <div class="showcase-card">
      <div class="showcase-header">
        <h2 class="showcase-title">{{ content('home.showcase.title', 'POS, But 1% Better') }}</h2>
        <p class="showcase-subtitle showcase-subtitle-primary">
          {{ content('home.showcase.subtitle_primary') }}
        </p>
        <p class="showcase-subtitle showcase-subtitle-secondary">
          {{ content('home.showcase.subtitle_secondary') }}
        </p>
        <p class="showcase-subtitle showcase-subtitle-mobile">
          {{ content('home.showcase.subtitle_mobile') }}
        </p>

        <div class="app-buttons">
          <a href="#" class="store-badge store-badge-apple" aria-label="Download on the App Store">
            <img src="{{ asset('assets/applebadge.png') }}" alt="Download on the App Store">
          </a>

          <a href="#" class="store-badge store-badge-google" aria-label="Get it on Google Play">
            <img src="{{ asset('assets/googlebadge.png') }}" alt="Get it on Google Play">
          </a>
        </div>
      </div>

      <div class="device-mockup">
        <img src="{{ asset('assets/devicemockup.webp') }}" alt="SkelApp on desktop" class="device-mockup-image desktop-only-img" loading="lazy" decoding="async">
        <img src="{{ asset('assets/Mobilehomeview.png') }}" alt="SkelApp on mobile" class="device-mockup-image mobile-only-img" loading="lazy" decoding="async">
      </div>
    </div>

    <div class="showcase-points desktop-only" aria-label="SkelApp highlights">
      @foreach ($homeShowcasePoints as $point)
        <article class="showcase-point">
          <div class="showcase-point-heading">
            <img src="{{ asset('assets/'.($point['icon'] ?? 'speed.svg')) }}" alt="" class="showcase-point-icon" aria-hidden="true">
            <h3>{{ $point['title'] ?? '' }}</h3>
          </div>
          <p>{{ $point['body'] ?? '' }}</p>
        </article>
      @endforeach
    </div>
  </div>
</section>

<section class="retailers-section" id="retailers">
  <div class="container">
    <div class="section-header">
      <h2>{{ content('home.retailers.title', 'Powering Retailers for Every Type') }}</h2>
      <p>{{ content('home.retailers.subtitle') }}</p>
    </div>

    <div class="carousel-container" data-drag-scroll data-carousel-default-index="1">
      <div class="carousel-track">
        @foreach ($retailerCards as $card)
          <div class="retailer-card">
            <div class="card-image">
              <img src="{{ asset('assets/' . pathinfo($card['image'] ?? 'boutique.png', PATHINFO_FILENAME) . '.webp') }}" alt="{{ $card['title'] ?? '' }}" draggable="false" loading="lazy" decoding="async">
              <div class="card-overlay">
                <h3>{{ $card['title'] ?? '' }}</h3>
                <p>{{ $card['copy'] ?? '' }}</p>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <div class="retailer-controls">
      <div class="carousel-slider" aria-label="Retailer carousel controls">
        <button class="carousel-slider-button" type="button" data-carousel-prev aria-label="Previous retailer">
          <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path d="M14.5 5.5 8 12l6.5 6.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>

        <div class="carousel-slider-dots" role="tablist" aria-label="Retailer slides">
          @foreach ($retailerCards as $card)
            <button
              class="carousel-slider-dot{{ $loop->index === 1 ? ' is-active' : '' }}"
              type="button"
              role="tab"
              data-carousel-dot="{{ $loop->index }}"
              aria-label="Go to {{ $card['title'] }}"
              aria-current="{{ $loop->index === 1 ? 'true' : 'false' }}"
            ></button>
          @endforeach
        </div>

        <button class="carousel-slider-button" type="button" data-carousel-next aria-label="Next retailer">
          <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path d="M9.5 5.5 16 12l-6.5 6.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
      </div>

      <div class="cta-container">
        <button class="btn-talk">
          {{ content('home.retailers.cta_label', 'Talk to our Team') }}
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </button>
      </div>
    </div>

    <div class="section-bottom">
      <h2>{{ content('home.retailers.bottom_title', 'One app. Every number in your business — tracked.') }}</h2>
      <p>{{ content('home.retailers.bottom_copy') }}</p>
    </div>
  </div>

</section>
<section class="features-section" id="features">
  <div class="container">
    <!-- Top Row -->
    <div class="features-row">
      <div class="feature-card feature-square feature-light feature-square-catalogue">
        <div class="feature-content-top">
          <h2>{{ content('home.features.top_left.title', 'Record every sale in seconds') }}</h2>
          <p>{{ content('home.features.top_left.body') }}</p>
        </div>
        <div class="feature-content-bottom">
          <img src="{{ asset('assets/Moc-tab.webp') }}" alt="SkelApp tablet catalogue" class="feature-tab-mockup" loading="lazy" decoding="async">
        </div>
      </div>

      <div class="feature-card feature-rectangle feature-green feature-rectangle-pos">
        <div class="feature-content-left">
          <h2>{{ content('home.features.top_right.title', 'Mobile Application') }}<br>{{ content('home.features.top_right.title_line_2', 'Ready for Both Apple & Android') }}</h2>
          <p>{{ content('home.features.top_right.body') }}</p>
        </div>
        <div class="feature-content-right">
          <img src="{{ asset('assets/PosSystem.webp') }}" alt="SkelApp POS system" loading="lazy" decoding="async">
        </div>
      </div>
    </div>

    <!-- Bottom Row -->
    <div class="features-row">
      <div class="feature-card feature-rectangle feature-dark feature-rectangle-handheld">
        <div class="feature-content-left">
          <h2>{{ content('home.features.bottom_left.title', 'Works on mobile, tablet or POS terminal') }}</h2>
          <p>{{ content('home.features.bottom_left.body') }}</p>
        </div>
        <div class="feature-content-right">
          <img src="{{ asset('assets/poswithtab.webp') }}" alt="SkelApp handheld POS with tablet" class="feature-handheld-pos" loading="lazy" decoding="async">
        </div>
      </div>

      <div class="feature-card feature-square feature-light feature-square-reporting">
        <div class="feature-content-top">
          <h2>{!! nl2br(e(content('home.features.bottom_right.title', 'Smarter sales & staff reporting'))) !!}</h2>
          <p>{{ content('home.features.bottom_right.body') }}</p>
        </div>
        <div class="feature-content-bottom">
          <img src="{{ asset('assets/Moc-lap-phone-02.webp') }}" alt="SkelApp reporting dashboard on laptop and phone" class="feature-reporting-mockup" loading="lazy" decoding="async">
        </div>
      </div>
    </div>
  </div>
</section>

<section class="allfeatures" id="allfeatures">
  <div class="allfeatures-container">
    <div class="allfeatures-intro-wrap">
      <div class="allfeatures-intro">
        <h2 class="allfeatures-intro-title">{{ content('home.allfeatures.title_line_1', 'All the features.') }}<br>{{ content('home.allfeatures.title_line_2', 'All in one place.') }}</h2>
        <p class="allfeatures-intro-copy">
          {{ content('home.allfeatures.copy') }}
        </p>
        <a href="{{ content('home.allfeatures.cta_url', '#') }}" class="btn-download">{{ content('home.allfeatures.cta_label', 'Download Now') }}</a>
      </div>
    </div>

    <div class="allfeatures-grid">
      @foreach ($allFeatureCards as $featureCard)
        <article class="allfeatures-card">
          <div class="allfeatures-card-media">
            <img
              src="{{ asset('assets/' . pathinfo($featureCard['image'] ?? 'crm.png', PATHINFO_FILENAME) . '.webp') }}"
              alt="{{ $featureCard['title'] ?? '' }}"
              width="352"
              height="352"
              loading="lazy"
              decoding="async"
            >
          </div>
          <h2>{{ $featureCard['title'] ?? '' }}</h2>
          <p>{{ $featureCard['copy'] ?? '' }}</p>
        </article>
      @endforeach
    </div>
  </div>
</section>

<section class="how-it-works-section" id="howitworks">
  <div class="container how-it-works-scroll">
    <div class="how-it-works-stage">
      <div class="section-headerr">
        <div class="section-headerr-copy">
          <h2>{{ content('home.howitworks.title', 'Up and running in under 5 minutes.') }}</h2>
          <p>{{ content('home.howitworks.copy') }}</p>
        </div>
        <div class="section-headerr-action">
          <a href="{{ content('home.howitworks.cta_url', '#') }}" class="btn-download">{{ content('home.howitworks.cta_label', 'Download Now') }}</a>
        </div>
      </div>

      <div class="steps-wrapper">
        <div class="steps-line-container" aria-hidden="true">
          <div class="steps-line-progress"></div>
        </div>
        <div class="steps-container">
          @foreach ($howSteps as $step)
            <article class="step-item">
              <div class="step-image">
                <img src="{{ asset('assets/' . ($step['image'] ?? 'rw.jpeg')) }}" alt="{{ $step['title'] ?? '' }}" loading="lazy" decoding="async">
              </div>
              <div class="step-marker">
                <div class="step-number-box">{{ $loop->iteration }}</div>
              </div>
              <div class="step-content">
                <h3>{{ $step['title'] ?? '' }}</h3>
                <p>{{ $step['copy'] ?? '' }}</p>
              </div>
            </article>
          @endforeach
        </div>
      </div>
    </div>
  </div>

</section>

<section class="hardware-section" id="pos">
  <div class="container">
    <div class="hardware-card">
      <div class="hardware-content">
        <span class="hardware-label">{{ content('home.hardware.label', 'Optional hardware — if you want the full setup') }}</span>
        <h2>{{ content('home.hardware.title', 'Complete your setup with Skel hardware.') }}</h2>
        <p>{{ content('home.hardware.copy') }}</p>

        <a href="{{ content('home.hardware.cta_url', '#') }}" class="btn-hardware">
          {{ content('home.hardware.cta_label', 'Request Hardware Pricing') }}
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </a>
      </div>

      <div class="hardware-image">
        <img src="{{ asset('assets/PosSystemRegister.webp') }}" alt="POS Hardware Terminal" loading="lazy" decoding="async">
      </div>
    </div>
  </div>

</section>

<section class="pricing-section" id="pricing">
  <div class="container">
    <div class="pricing-layout">
      <!-- Left Side - Card Preview -->
      <div class="pricing-preview is-subscription-preview">
        <div class="preview-card is-subscription-preview">
          <img src="{{ asset('assets/card.webp') }}" alt="SkelApp Subscription Card" class="card-preview-image" loading="lazy" decoding="async">
        </div>

        <!-- Thumbnail Previews -->
        <div class="thumbnail-grid">
          <div class="thumbnail active">
            <img src="{{ asset('assets/card.webp') }}" alt="SkelApp Subscription Card" loading="lazy" decoding="async">
          </div>
          <div class="thumbnail">
            <img src="{{ asset('assets/Moc-tab.webp') }}" alt="Products" loading="lazy" decoding="async">
          </div>
          <div class="thumbnail">
            <img src="{{ asset('assets/Pos System 04.png') }}" alt="Mobile" loading="lazy" decoding="async">
          </div>
          <div class="thumbnail">
            <img src="{{ asset('assets/Moc-tab-02.webp') }}" alt="Reports" loading="lazy" decoding="async">
          </div>
        </div>
      </div>

      <!-- Right Side - Pricing Details -->
      <div class="pricing-details">
        <p class="pricing-intro">{{ content('home.pricing_summary.intro') }}</p>

        <h2>{{ content('home.pricing_summary.title_line_1', 'SkelApp') }} <br>{{ content('home.pricing_summary.title_line_2', 'Subscription') }}</h2>

        <p class="pricing-description">
          {{ content('home.pricing_summary.description') }}
        </p>

        <ul class="pricing-features">
          @foreach ($pricingSummaryFeatures as $feat)
            @php $featText = is_array($feat) ? ($feat['value'] ?? '') : $feat; @endphp
            <li>
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 6L9 17l-5-5"/>
              </svg>
              {{ $featText }}
            </li>
          @endforeach
        </ul>

        <div class="pricing-price">
          <div class="price-figure">
            <span class="currency">{{ content('home.pricing_summary.currency', 'TZS') }}</span>
            <span class="price-main">{{ content('home.pricing_summary.price_main', '15,000') }}</span>
          </div>
          <span class="price-period">{{ content('home.pricing_summary.price_period', '/month · billed annually') }}</span>
        </div>

        <div class="payment-row">
          <span class="payment-label">{{ content('home.pricing_summary.payment_label', 'Flexible payment options') }}</span>
          <div class="payment-methods-art">
            <img src="{{ asset('assets/paymentmethod.png') }}" alt="Supported payment methods" loading="lazy" decoding="async">
          </div>
        </div>

        <a href="{{ content('home.pricing_summary.cta_url', '#') }}" class="btn-pricing">
          {{ content('home.pricing_summary.cta_label', 'Talk to SkelTeam') }}
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </a>

        <div class="pricing-benefits">
          @foreach ($pricingSummaryBenefits as $index => $benefit)
            @php $bText = is_array($benefit) ? ($benefit['value'] ?? '') : $benefit; @endphp
            <div class="benefit-item">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                @if ($index === 1)
                  <circle cx="12" cy="12" r="10"/>
                  <path d="M12 6v6l4 2"/>
                @elseif ($index === 2)
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                  <line x1="16" y1="2" x2="16" y2="6"/>
                  <line x1="8" y1="2" x2="8" y2="6"/>
                  <line x1="3" y1="10" x2="21" y2="10"/>
                @else
                  <path d="M20 6L9 17l-5-5"/>
                @endif
              </svg>
              @if ($index === 1 && $homePricingBenefit2Mobile)
                <span class="benefit-copy-desktop">{{ $bText }}</span>
                <span class="benefit-copy-mobile">{{ $homePricingBenefit2Mobile }}</span>
              @else
                <span>{{ $bText }}</span>
              @endif
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

</section>
@include('partials.home-faq')

<!-- Image CTA Section - Separate section below FAQ -->
<section class="image-cta-section">
  <div class="cta-background">
    <img src="{{ asset('assets/client.webp') }}" alt="Building momentum" class="cta-img" loading="lazy" decoding="async">
    <div class="cta-overlay"></div>
  </div>
  <div class="cta-content-wrapper">
    <div class="cta-content">
      <h2>{{ content('home.image_cta.heading', 'Building momentum to move your business 1% better every day.') }}</h2>
      <a href="{{ content('home.image_cta.cta_url', '#') }}" class="btn-cta">
        {{ content('home.image_cta.cta_label', 'Download SkelApp Today') }}
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
      </a>
    </div>
  </div>
</section>


@include('partials.site-footer')



<script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
</body>
</html>
