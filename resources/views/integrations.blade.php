@php
  use App\Support\CmsCatalogs;

  $config = config('integrations', []);
  $hero = content('integrations.hero', $hero ?? ($config['hero'] ?? []));
  $categories = content_list('integrations.categories', $categories ?? ($config['categories'] ?? []));
  $items = collect(content_list('integrations.items', CmsCatalogs::integrationItems()))
    ->filter(fn ($item) => is_array($item) && filled($item['slug'] ?? null))
    ->mapWithKeys(fn ($item) => [(string) $item['slug'] => $item])
    ->all();
  $faqHeading = content_text('integrations.faq.heading', 'Integration questions, answered');
  $faqSubtitle = content_text('integrations.faq.subtitle', 'Integrations FAQ');
  $faqReadMoreLabel = content('integrations.faq.read_more_label', 'Read more');
  $faqStripLabel = content('integrations.strip.label', 'Also integrates with');
  $faqItems = content_list('integrations.faq.items', $faq ?? ($config['faq'] ?? []));
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ content('integrations.meta.title', 'Integrations - SkelApp') }}</title>
  <meta name="description" content="{{ content('integrations.meta.description', 'Connect SkelApp with Zoho Books, Xero, QuickBooks, Sage, WhatsApp and Notify Africa BulkSMS.') }}">
  <link rel="icon" href="{{ content_image('global.brand.favicon', asset('assets/skel.svg')) }}" sizes="any" />
  <link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="integrations-page-body">
  @include('partials.site-nav')

  <main class="integrations-page">
    <section class="retailer-hero integrations-hero" aria-label="Integrations">
      <div class="pricing-hero-banner">
        <img src="{{ cms_image($hero['image'] ?? null, asset('assets/HeroImage.webp')) }}" alt="" class="pricing-hero-bg" loading="eager" decoding="async">
        <div class="pricing-hero-content">
          <p class="pricing-hero-eyebrow">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.9 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14l-5-4.87 7.1-1.01z"/></svg>
            {{ $hero['eyebrow'] ?? 'SkelApp integrations' }}
          </p>
          <h1 {!! content_typography_html('integrations.hero.title', 'pricing-hero-title') !!}>{!! nl2br(e(content_text('integrations.hero.title', $hero['title'] ?? "Connect SkelApp\nto your business tools"))) !!}</h1>
          <p {!! content_typography_html('integrations.hero.subtitle', 'pricing-hero-sub') !!}>{{ content_text('integrations.hero.subtitle', $hero['subtitle'] ?? '') }}</p>
          <div class="pricing-hero-actions">
            <a href="#integrations-list" class="pricing-hero-btn pricing-hero-btn--solid">{{ $hero['primary_label'] ?? 'Explore integrations' }}</a>
            <a href="{{ route('contact.show') }}" class="pricing-hero-btn pricing-hero-btn--text">{{ $hero['secondary_label'] ?? 'Talk to an expert' }}</a>
          </div>
        </div>
      </div>
    </section>

    <section class="integrations-list" id="integrations-list">
      @foreach ($categories as $category)
        @php
          $categorySlugs = $category['slugs'] ?? preg_split('/\r?\n/', trim((string) ($category['slugs_text'] ?? '')));
          $categoryItems = collect($categorySlugs)
            ->filter(fn ($slug) => is_string($slug) && trim($slug) !== '')
            ->map(fn ($slug) => trim((string) $slug))
            ->map(fn ($slug) => ['slug' => $slug, 'item' => $items[$slug] ?? null])
            ->filter(fn ($entry) => ! empty($entry['item']))
            ->values();
        @endphp
        <div class="integrations-category">
          <div class="integrations-category-head">
            <h2>{{ $category['title'] ?? '' }}</h2>
            <p>{{ $category['subtitle'] ?? '' }}</p>
          </div>

          <div class="integrations-card-grid">
            @foreach ($categoryItems as $entry)
              @php
                $integration = $entry['item'];
                $slug = $entry['slug'];
              @endphp
              <a href="{{ route('integrations.show', $slug) }}" class="integration-card">
                <span class="integration-logo-shell" style="--integration-color: {{ $integration['color'] ?? '#4AAD77' }}">
                  <img src="{{ cms_image($integration['logo'] ?? null, asset('assets/skel.svg')) }}" alt="{{ $integration['name'] ?? '' }} logo" loading="lazy" decoding="async">
                </span>
                <span class="integration-card-copy">
                  <span class="integration-card-kicker">{{ $integration['category'] ?? 'Integration' }}</span>
                  <strong>{{ $integration['name'] ?? '' }}</strong>
                  <span>{{ $integration['summary'] ?? '' }}</span>
                </span>
              </a>
            @endforeach
          </div>
        </div>
      @endforeach
    </section>

    <section class="integrations-strip" aria-label="Supported integration logos">
      <span>{{ $faqStripLabel }}</span>
      <div class="integrations-strip-logos">
        @foreach ($items as $integration)
          <span><img src="{{ cms_image($integration['logo'] ?? null, asset('assets/skel.svg')) }}" alt="{{ $integration['name'] ?? '' }}" loading="lazy" decoding="async"></span>
        @endforeach
      </div>
    </section>

    <section class="faq-section">
      <div class="faq-container">
        <div class="faq-header">
          <h2 class="{{ content_typography_class('integrations.faq.heading') }}" style="{{ content_typography_vars('integrations.faq.heading') }}">{{ $faqHeading }}</h2>
          <a href="{{ route('faq.show') }}" class="faq-read-more">{{ $faqReadMoreLabel }}</a>
        </div>
        <div class="faq-layout">
          <p class="faq-subtitle {{ content_typography_class('integrations.faq.subtitle') }}" style="{{ content_typography_vars('integrations.faq.subtitle') }}">{{ $faqSubtitle }}</p>
          <div class="faq-list">
            @foreach ($faqItems as $i => $item)
              <div class="faq-item @if($i === 0) active @endif">
                <button class="faq-question" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                  <span>{{ $item['q'] ?? '' }}</span>
                  <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                  </svg>
                </button>
                <div class="faq-answer">
                  <p>{{ $item['a'] ?? '' }}</p>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </section>
  </main>

  @include('partials.site-footer')
  <script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
</body>
</html>
