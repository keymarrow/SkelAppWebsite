@php
  $detailCards = content_list('features.detail.cards', []);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ content('features.meta.title', 'Features – SkelApp') }}</title>
  <meta name="description" content="{{ content('features.meta.description', 'Every SkelApp feature in one place — sales, inventory, customers, and reports built for Tanzanian retailers.') }}">
  <link rel="icon" href="{{ content_image('global.brand.favicon', asset('assets/skel.svg')) }}" type="image/x-icon" />
  <link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="features-page-body">
  @include('partials.site-nav')

  <main class="features-page">
    <section class="features-hero">
      <div class="features-hero-shell">
        <span class="features-eyebrow">{{ content_text('features.hero.eyebrow', 'All in One Place') }}</span>
        <h1 {!! content_typography_html('features.hero.title', 'features-hero-title') !!}>{!! nl2br(e(content_text('features.hero.title', "Powerful Features,\nSeamlessly Connected"))) !!}</h1>
        <p {!! content_typography_html('features.hero.subtitle', 'features-hero-subtitle') !!}>{{ content_text('features.hero.subtitle', "SkelApp brings sales, inventory, customers, and reports together in one simple platform — so you can focus on growing your business, not juggling tools.") }}</p>

        <div class="features-hero-art">
          <img
            src="{{ content_image('features.hero.image', asset('assets/featureheroimage.webp')) }}"
            alt="SkelApp running across multiple devices"
            class="features-hero-image"
            loading="eager"
            decoding="async"
          />
        </div>
      </div>
    </section>

    <section class="features-detail">
      <div class="features-detail-shell">
        <header class="features-detail-header">
          <span class="features-eyebrow">{{ content_text('features.detail.eyebrow', 'Key Features') }}</span>
          <h2 {!! content_typography_html('features.detail.title', 'features-detail-title') !!}>{{ content_text('features.detail.title', 'Detailed Overview of Our POS Features') }}</h2>
          <p {!! content_typography_html('features.detail.subtitle', 'features-detail-subtitle') !!}>{{ content_text('features.detail.subtitle', 'Our point-of-sale features help you record sales, track inventory, and grow customer loyalty — keeping every shop running smoothly.') }}</p>
        </header>

        <div class="features-detail-grid">
          @foreach ($detailCards as $idx => $card)
            @php
              $titleKey = "features.detail.cards.{$idx}.title";
              $bodyKey = "features.detail.cards.{$idx}.body";
              $imageKey = "features.detail.cards.{$idx}.image";
              $iconKey = "features.detail.cards.{$idx}.icon";
              $variant = $card['variant'] ?? 'light';
              $layout = $card['layout'] ?? 'stack';
            @endphp
            <article class="features-card features-card--{{ $variant }} features-card--{{ $layout }}">
              @if (! empty($card['icon']))
                <div class="features-card-icon" aria-hidden="true">
                  <img src="{{ cms_image($card['icon'] ?? null, asset('assets/skel.svg')) }}" alt="">
                </div>
              @endif

              <div class="features-card-body">
                @if (! empty($card['eyebrow']))
                  <span class="features-card-eyebrow">{{ $card['eyebrow'] }}</span>
                @endif

                <h3 {!! content_typography_html($titleKey, 'features-card-title') !!}>{{ content_text($titleKey, $card['title'] ?? '') }}</h3>

                @if (! empty($card['bullets']) && is_array($card['bullets']))
                  <ul class="features-card-bullets">
                    @foreach ($card['bullets'] as $bIdx => $bullet)
                      <li class="features-card-bullet">
                        <span class="features-card-bullet-check" aria-hidden="true">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 6L9 17l-5-5"/>
                          </svg>
                        </span>
                        <span class="features-card-bullet-text">
                          <strong>{{ $bullet['title'] ?? '' }}</strong>
                          @if (! empty($bullet['body']))
                            <span class="features-card-bullet-body">{{ $bullet['body'] }}</span>
                          @endif
                        </span>
                      </li>
                    @endforeach
                  </ul>
                @endif

                @if (! empty($card['cta_label']))
                  <a href="{{ $card['cta_url'] ?? '#' }}" class="features-card-cta">
                    {{ $card['cta_label'] }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                      <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                  </a>
                @endif
              </div>

              <div class="features-card-mockup">
                <img
                  src="{{ cms_image($card['image'] ?? null, asset('assets/DASHBOARD.png')) }}"
                  alt="{{ content_text($titleKey, $card['title'] ?? '') }}"
                  class="features-card-image"
                  loading="lazy"
                  decoding="async"
                />
              </div>
            </article>
          @endforeach
        </div>
      </div>
    </section>

    @php $featuresFaqItems = content_list('features.faq.items', []); @endphp
    @if (count($featuresFaqItems) > 0)
      <section class="faq-section" id="features-faq">
        <div class="faq-container">
          <div class="faq-header">
            <h2 {!! content_typography_html('features.faq.title') !!}>{{ content_text('features.faq.title', 'POS questions, answered') }}</h2>
            <a href="{{ route('faq.show') }}" class="faq-read-more">Read more</a>
          </div>
          <div class="faq-layout">
            <p class="faq-subtitle">{{ content_text('features.faq.eyebrow', 'POS FAQ') }}</p>

            <div class="faq-list">
              @foreach ($featuresFaqItems as $idx => $item)
                @php
                  $qKey = "features.faq.items.{$idx}.question";
                  $aKey = "features.faq.items.{$idx}.answer";
                  $isOpen = $idx === 0;
                @endphp
                <div class="faq-item @if($isOpen) active @endif">
                  <button class="faq-question" aria-expanded="{{ $isOpen ? 'true' : 'false' }}">
                    <span>{{ content_text($qKey, $item['question'] ?? '') }}</span>
                    <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="12" y1="5" x2="12" y2="19"></line>
                      <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                  </button>
                  <div class="faq-answer">
                    <p>{{ content_text($aKey, $item['answer'] ?? '') }}</p>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </section>
    @endif

    <section class="features-cta">
      <div class="features-cta-shell">
        @php
          $ctaTitle = content_text('features.cta.title', 'Ready to run your shop 1% better');
          $ctaAccent = content('features.cta.title_accent', '1% better');
          $ctaTitleHtml = ($ctaAccent && str_contains($ctaTitle, $ctaAccent))
            ? str_replace($ctaAccent, '<span class="features-cta-accent">'.e($ctaAccent).'</span>', e($ctaTitle))
            : e($ctaTitle);
        @endphp
        <h2 {!! content_typography_html('features.cta.title', 'features-cta-title') !!}>{!! $ctaTitleHtml !!}</h2>
        <p {!! content_typography_html('features.cta.subtitle', 'features-cta-subtitle') !!}>{{ content_text('features.cta.subtitle', 'Download SkelApp and start tracking every sale, every product, every customer — all from your phone.') }}</p>
        <div class="features-cta-actions">
          <a href="{{ content('features.cta.primary_url', '#') }}" class="btn-download">{{ content('features.cta.primary_label', 'Download Now') }}</a>
        </div>
      </div>
    </section>
  </main>

  @include('partials.site-footer')

  <script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
</body>
</html>
