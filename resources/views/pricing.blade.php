@php
  $titleLines = content_list('pricing.header.title_lines', []);
  $features = content_list('pricing.features', []);
  $plans = content_list('pricing.plans', []);
  $defaultPlan = collect($plans)->firstWhere('is_default', true) ?? ($plans[0] ?? null);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ content('pricing.meta.title', 'Pricing – SkelApp') }}</title>
  <meta name="description" content="{{ content('pricing.meta.description', "SkelApp pricing — clear, affordable, no hidden costs.") }}">
  <link rel="icon" href="{{ content_image('global.brand.favicon', asset('assets/skel.svg')) }}" sizes="any" />
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
          <p class="pricing-hero-eyebrow">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.9 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14l-5-4.87 7.1-1.01z"/></svg>
            {{ content('pricing.hero.trust_label', 'Trusted by') }} <strong>{{ content('pricing.hero.trust_count', '10,000+') }}</strong> {{ content('pricing.hero.trust_suffix', 'small businesses') }}
          </p>
          <h1 class="pricing-hero-title">{!! nl2br(e(content_text('pricing.hero.title', "Get more value\nfrom every payment"))) !!}</h1>
          <p class="pricing-hero-sub">{{ content_text('pricing.hero.subtitle', "We’ve got flexible plans to suit any growing business.") }}</p>
          <div class="pricing-hero-actions">
            <a href="{{ content('pricing.hero.primary_url', '#pricing-plans') }}" class="pricing-hero-btn pricing-hero-btn--solid">{{ content('pricing.hero.primary_label', 'See plans') }}</a>
            <a href="{{ content('pricing.hero.secondary_url', route('contact.show')) }}" class="pricing-hero-btn pricing-hero-btn--text">{{ content('pricing.hero.secondary_label', 'Contact sales') }}</a>
          </div>
        </div>
      </div>
    </section>

    <section class="pricing-plan-section" id="pricing-plans" aria-label="Pricing plans">
      <div class="hwp-section-header">
        <h2 class="hwp-section-title">
          @forelse ($titleLines as $line)
            @if (!empty($line['accent']))<strong>{{ $line['text'] ?? '' }}</strong>@else{{ $line['text'] ?? '' }}@endif{{ !$loop->last ? ' ' : '' }}
          @empty
            Simple, <strong>transparent pricing</strong>
          @endforelse
        </h2>
        <p class="hwp-section-subtitle">{{ content_text('pricing.header.subtitle', 'Clear, affordable plans with no hidden costs — pick what fits your shop today and scale as you grow.') }}</p>
      </div>

      <form class="pricing-plan-grid" data-pricing-form>
        <section class="pricing-box pricing-box--features" aria-labelledby="features-title">
          <header class="pricing-box-header">
            <h2 id="features-title" class="pricing-box-title {{ content_typography_class('pricing.features_box.title') }}" style="{{ content_typography_vars('pricing.features_box.title') }}">{{ content_text('pricing.features_box.title', 'Features') }}</h2>
            <p class="pricing-box-subtitle {{ content_typography_class('pricing.features_box.subtitle') }}" style="{{ content_typography_vars('pricing.features_box.subtitle') }}">{{ content_text('pricing.features_box.subtitle') }}</p>
          </header>

          <ul class="pricing-feature-list">
            @foreach ($features as $feature)
              @php $featureText = is_array($feature) ? ($feature['value'] ?? '') : $feature; @endphp
              <li class="pricing-feature-item">
                <span class="pricing-feature-label">{{ $featureText }}</span>
                <span class="pricing-feature-check" aria-hidden="true">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6L9 17l-5-5"/>
                  </svg>
                </span>
              </li>
            @endforeach
          </ul>
        </section>

        <section class="pricing-box pricing-box--plans" aria-labelledby="plans-title">
          <header class="pricing-box-header">
            <h2 id="plans-title" class="pricing-box-title {{ content_typography_class('pricing.plans_box.title') }}" style="{{ content_typography_vars('pricing.plans_box.title') }}">{{ content_text('pricing.plans_box.title', 'Pricing Plan') }}</h2>
            <p class="pricing-box-subtitle {{ content_typography_class('pricing.plans_box.subtitle') }}" style="{{ content_typography_vars('pricing.plans_box.subtitle') }}">{{ content_text('pricing.plans_box.subtitle') }}</p>
          </header>

          <div class="plan-option-list" role="radiogroup" aria-label="Billing period">
            @foreach ($plans as $plan)
              @php $isDefault = !empty($plan['is_default']); @endphp
              <label class="plan-option {{ $isDefault ? 'is-active' : '' }}" for="plan-{{ $plan['id'] ?? $loop->index }}">
                <input
                  type="radio"
                  name="plan"
                  id="plan-{{ $plan['id'] ?? $loop->index }}"
                  value="{{ $plan['id'] ?? $loop->index }}"
                  data-plan-label="{{ $plan['label'] ?? '' }}"
                  @checked($isDefault)
                />
                <span class="plan-option-radio" aria-hidden="true">
                  <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6L9 17l-5-5"/>
                  </svg>
                </span>

                <span class="plan-option-body">
                  <span class="plan-option-head">
                    <span class="plan-option-label">{{ $plan['label'] ?? '' }}</span>
                    @if (!empty($plan['note']))
                      <span class="plan-option-note">{{ $plan['note'] }}</span>
                    @endif
                  </span>
                  <span class="plan-option-price">
                    {{ $plan['price'] ?? '' }}
                    @if (!empty($plan['sub']))<small>· {{ $plan['sub'] }}</small>@endif
                  </span>
                </span>
              </label>
            @endforeach
          </div>

          @php $ctaUrl = content('pricing.cta.url') ?: route('contact.show'); @endphp
          <a href="{{ $ctaUrl }}" class="plan-cta" data-plan-cta>
            {{ content('pricing.cta.prefix', 'Get started with') }}
            <span data-plan-cta-label>{{ $defaultPlan['label'] ?? '' }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
          </a>
        </section>
      </form>
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

  <script>
    (function () {
      const form = document.querySelector('[data-pricing-form]');
      if (!form) return;

      const radios = form.querySelectorAll('input[name="plan"]');
      const ctaLabel = form.querySelector('[data-plan-cta-label]');

      const sync = () => {
        radios.forEach((radio) => {
          const option = radio.closest('.plan-option');
          if (!option) return;
          option.classList.toggle('is-active', radio.checked);
          if (radio.checked && ctaLabel) {
            ctaLabel.textContent = radio.dataset.planLabel || '';
          }
        });
      };

      radios.forEach((radio) => radio.addEventListener('change', sync));
      sync();
    })();
  </script>

  <script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
</body>
</html>
