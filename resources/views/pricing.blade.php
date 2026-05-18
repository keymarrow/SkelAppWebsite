@php
  $features = [
    'Sales recording & receipts',
    'Purchase & expense tracking',
    'Inventory & stock alerts',
    'Profit & loss reports',
    'Split billing',
    'Staff & cashier management',
    'Customer relationship tools',
    'Mobile & tablet access',
  ];

  $plans = [
    [
      'id' => 'monthly',
      'label' => 'Monthly',
      'note' => null,
      'price' => 'TZS 15,000',
      'sub' => 'billed monthly',
    ],
    [
      'id' => 'sixmonth',
      'label' => '6 months',
      'note' => 'Save TZS 15,000',
      'price' => 'TZS 75,000',
      'sub' => 'TZS 12,500 / month',
    ],
    [
      'id' => 'yearly',
      'label' => '12 months',
      'note' => 'Best value · Save TZS 30,000',
      'price' => 'TZS 150,000',
      'sub' => 'TZS 12,500 / month',
      'is_default' => true,
    ],
  ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pricing – SkelApp</title>
  <meta name="description" content="SkelApp pricing — clear, affordable, no hidden costs. Choose Monthly, 6-month, or 12-month plans and save more the longer you commit.">
  <link rel="icon" href="{{ asset('assets/skel.svg') }}" type="image/x-icon" />
  <link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="pricing-page-body">
  @include('partials.site-nav')

  <main class="pricing-page">
    <section class="pricing-plan-section" aria-labelledby="pricing-plan-title">
      <header class="pricing-plan-header">
        <h1 id="pricing-plan-title" class="pricing-plan-title">
          <span class="pricing-plan-title-line"><span class="pricing-plan-title-mark">One price.</span></span>
          <span class="pricing-plan-title-line"><span class="pricing-plan-title-mark">Every feature.</span></span>
          <span class="pricing-plan-title-line"><span class="pricing-plan-title-mark is-accent">No surprises.</span></span>
        </h1>
        <p class="pricing-plan-subtitle">
          <span class="pricing-plan-subtitle-mark">SkelApp's pricing is as simple as your business should be — one flat rate, everything included, cancel anytime. No tiers. No hidden fees. No confusion.</span>
        </p>
      </header>

      <form class="pricing-plan-grid" data-pricing-form>
        <section class="pricing-box pricing-box--features" aria-labelledby="features-title">
          <header class="pricing-box-header">
            <h2 id="features-title" class="pricing-box-title">Features</h2>
            <p class="pricing-box-subtitle">This subscription includes the following features.</p>
          </header>

          <ul class="pricing-feature-list">
            @foreach ($features as $feature)
              <li class="pricing-feature-item">
                <span class="pricing-feature-label">{{ $feature }}</span>
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
            <h2 id="plans-title" class="pricing-box-title">Pricing Plan</h2>
            <p class="pricing-box-subtitle">Choose the plan that best suits your needs.</p>
          </header>

          <div class="plan-option-list" role="radiogroup" aria-label="Billing period">
            @foreach ($plans as $plan)
              <label class="plan-option @if(!empty($plan['is_default'])) is-active @endif" for="plan-{{ $plan['id'] }}">
                <input
                  type="radio"
                  name="plan"
                  id="plan-{{ $plan['id'] }}"
                  value="{{ $plan['id'] }}"
                  data-plan-label="{{ $plan['label'] }}"
                  @if (!empty($plan['is_default'])) checked @endif
                />
                <span class="plan-option-radio" aria-hidden="true">
                  <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6L9 17l-5-5"/>
                  </svg>
                </span>

                <span class="plan-option-body">
                  <span class="plan-option-head">
                    <span class="plan-option-label">{{ $plan['label'] }}</span>
                    @if (!empty($plan['note']))
                      <span class="plan-option-note">{{ $plan['note'] }}</span>
                    @endif
                  </span>
                  <span class="plan-option-price">
                    {{ $plan['price'] }}
                    <small>· {{ $plan['sub'] }}</small>
                  </span>
                </span>
              </label>
            @endforeach
          </div>

          <a href="{{ route('contact.show') }}" class="plan-cta" data-plan-cta>
            Get started with <span data-plan-cta-label>12 months</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
          </a>
        </section>
      </form>
    </section>

    @include('partials.home-faq')

    <section class="pricing-getstarted" aria-labelledby="getstarted-heading">
      <div class="getstarted-shell">
        <div class="getstarted-phone">
          <img
            src="{{ asset('assets/Mobilehomeview.png') }}"
            alt="SkelApp running on mobile"
            class="getstarted-phone-image"
            loading="lazy"
            decoding="async"
          />
        </div>

        <div class="getstarted-content">
          <h2 id="getstarted-heading" class="getstarted-title">
            Get Started <br/>with the SkelApp
          </h2>

          <a href="#" class="btn-download">Download Now</a>

          <p class="getstarted-copy">
            Using SkelApp is simple and intuitive. Open it, follow the guided steps to set up
            your shop, and explore every feature. Got questions? Our team has you covered.
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
