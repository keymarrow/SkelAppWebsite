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
  <link rel="icon" href="{{ asset('assets/skel.svg') }}" type="image/x-icon" />
  <link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="pricing-page-body">
  @include('partials.site-nav')

  <main class="pricing-page">
    <section class="pricing-plan-section" aria-labelledby="pricing-plan-title">
      <header class="pricing-plan-header">
        <h1 id="pricing-plan-title" class="pricing-plan-title">
          @foreach ($titleLines as $line)
            <span class="pricing-plan-title-line">
              <span class="pricing-plan-title-mark {{ !empty($line['accent']) ? 'is-accent' : '' }}">{{ $line['text'] ?? '' }}</span>
            </span>
          @endforeach
        </h1>
        <p class="pricing-plan-subtitle">
          <span class="pricing-plan-subtitle-mark">{{ content('pricing.header.subtitle') }}</span>
        </p>
      </header>

      <form class="pricing-plan-grid" data-pricing-form>
        <section class="pricing-box pricing-box--features" aria-labelledby="features-title">
          <header class="pricing-box-header">
            <h2 id="features-title" class="pricing-box-title">{{ content('pricing.features_box.title', 'Features') }}</h2>
            <p class="pricing-box-subtitle">{{ content('pricing.features_box.subtitle') }}</p>
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
            <h2 id="plans-title" class="pricing-box-title">{{ content('pricing.plans_box.title', 'Pricing Plan') }}</h2>
            <p class="pricing-box-subtitle">{{ content('pricing.plans_box.subtitle') }}</p>
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

    @include('partials.home-faq')

    @php
      $gsPhone = content('pricing.getstarted.phone_image', asset('assets/Mobilehomeview.png'));
      // If the stored value is a relative asset path (legacy), resolve via asset(); if it already includes a scheme, use as-is.
      if (!str_starts_with($gsPhone, 'http') && !str_starts_with($gsPhone, '/storage/')) {
        $gsPhone = asset($gsPhone);
      }
    @endphp
    <section class="pricing-getstarted" aria-labelledby="getstarted-heading">
      <div class="getstarted-shell">
        <div class="getstarted-phone">
          <img
            src="{{ $gsPhone }}"
            alt="SkelApp running on mobile"
            class="getstarted-phone-image"
            loading="lazy"
            decoding="async"
          />
        </div>

        <div class="getstarted-content">
          <h2 id="getstarted-heading" class="getstarted-title">
            {{ content('pricing.getstarted.title_top', 'Get Started') }} <br/>{{ content('pricing.getstarted.title_bottom', 'with the App') }}
          </h2>

          <a href="{{ content('pricing.getstarted.cta_url', '#') }}" class="btn-download">{{ content('pricing.getstarted.cta_label', 'Download Now') }}</a>

          <p class="getstarted-copy">
            {{ content('pricing.getstarted.copy') }}
          </p>
        </div>
      </div>
    </section>
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
