@php
  use Illuminate\Support\Str;

  $homeShowcasePoints = content_list('home.showcase.points', [
    ['icon' => 'speed.svg', 'title' => 'Ready in Minutes', 'body' => 'Set up and selling — no training day.'],
    ['icon' => 'retail.svg', 'title' => 'Made for Tanzanian Retail', 'body' => 'Sales, stock, expenses — in shillings.'],
    ['icon' => 'scale.svg', 'title' => 'Grows With You', 'body' => 'One duka today, five branches tomorrow.'],
  ]);
  $arrowSvg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>';
  $affordCards = content_list('home.affordable.cards', [
    [
      'variant' => 'light',
      'title' => 'Know your profit, not just your sales.',
      'copy' => 'SkelApp does the profit math live — so busy days become paid days.',
      'link_label' => 'See pricing',
      'link_url' => route('pricing.show'),
      'image' => 'poswithtab.webp',
      'overlay_big' => '',
      'overlay_small' => '',
    ],
    [
      'variant' => 'photo',
      'title' => 'Get your debts out of the notebook.',
      'copy' => 'Who owes you, how much, since when — tracked, not scribbled and forgotten.',
      'link_label' => 'Learn more',
      'link_url' => route('features.show'),
      'image' => 'attendants.webp',
      'price_label' => '',
      'price' => '',
      'badge' => '',
    ],
    [
      'variant' => 'tint',
      'title' => 'However they pay, it counts.',
      'copy' => 'Cash, mobile money, or bank transfer — every payment lands in one record.',
      'link_label' => 'Learn more',
      'link_url' => route('features.show'),
      'image' => 'Mobilehomeview.png',
    ],
  ]);
  $homeProductCards = content_list('home.products.cards', [
    [
      'eyebrow' => 'SkelApp Counter',
      'title' => "Your shop's command center.",
      'body' => 'Sales, purchases, stock, and debts — the full picture from one screen at the counter.',
      'link_label' => 'See how it works',
      'link_url' => '#',
      'image' => 'Moc-lap-phone-02.webp',
    ],
    [
      'eyebrow' => 'SkelApp Terminal',
      'title' => 'The whole counter, in your pocket.',
      'body' => 'Record sales, check stock, and chase debts from your phone — at the market, at the supplier, anywhere.',
      'link_label' => 'See how it works',
      'link_url' => '#',
      'image' => 'Mobilehomeview.png',
    ],
  ]);
  $retailerCards = content_list('home.retailers.cards', [
    ['image' => 'boutique.webp', 'category' => 'Fashion', 'title' => 'Boutique', 'copy' => 'Simple inventory management', 'link_url' => '#'],
    ['image' => 'cosmetics.webp', 'category' => 'Beauty', 'title' => 'Cosmetics', 'copy' => 'Track stock with ease', 'link_url' => '#'],
    ['image' => 'grocery.webp', 'category' => 'Food', 'title' => 'Grocery', 'copy' => 'Fresh inventory management', 'link_url' => '#'],
    ['image' => 'hardware.webp', 'category' => 'Hardware', 'title' => 'Hardware', 'copy' => 'Track stock, suppliers, and bulk sales', 'link_url' => '#'],
    ['image' => 'kitchenware.webp', 'category' => 'Homeware', 'title' => 'Kitchenware', 'copy' => 'Organise products neatly', 'link_url' => '#'],
    ['image' => 'autospare.webp', 'category' => 'Automotive', 'title' => 'Auto Spare Shop', 'copy' => 'Manage fast-moving parts', 'link_url' => '#'],
    ['image' => 'techshop.webp', 'category' => 'Electronics', 'title' => 'Tech Shop', 'copy' => 'Built for modern retail', 'link_url' => '#'],
  ]);
  $allFeatureCards = content_list('home.allfeatures.cards', [
    ['image' => 'crm.webp', 'label' => 'Customers', 'title' => "See who buys most, who's gone quiet, and who's worth a call.", 'copy' => "See who buys most, who's gone quiet, and who's worth a call.", 'link_url' => '/features#customers-and-loyalty'],
    ['image' => 'fastbill.webp', 'label' => 'Fast checkout', 'title' => 'Record a sale and print a receipt in under 30 seconds.', 'copy' => 'Record a sale and print a receipt in under 30 seconds.', 'link_url' => '/features#point-of-sale'],
    ['image' => 'catalog.webp', 'label' => 'Catalog', 'title' => 'Organise products, pricing and categories for faster checkout.', 'copy' => 'Organise products, pricing and categories for faster checkout.', 'link_url' => '/features#catalog-and-products'],
    ['image' => 'inventorytrack.webp', 'label' => 'Inventory', 'title' => 'Low-stock alerts before you run out, not after the customer walks out.', 'copy' => 'Low-stock alerts before you run out, not after the customer walks out.', 'link_url' => '/features#inventory-and-stock'],
    ['image' => 'report.webp', 'label' => 'Profits', 'title' => 'What you made, what you spent, which products carry the shop.', 'copy' => 'What you made, what you spent, which products carry the shop.', 'link_url' => '/features#reports-and-profits'],
    ['image' => 'attendants.webp', 'label' => 'Team control', 'title' => 'Give each staff member the right access and track who sold what.', 'copy' => 'Give each staff member the right access and track who sold what.', 'link_url' => '/features#staff-and-branches'],
  ]);
  $howSteps = content_list('home.howitworks.steps', [
    ['image' => 'rw.jpeg', 'title' => 'Add your products', 'copy' => "Enter your items, set your prices. A typical shop's catalogue is done before the kettle boils."],
    ['image' => 'pix.jpeg', 'title' => 'Start selling', 'copy' => 'Tap the product, take the payment, receipt done. Every sale lands in your records automatically.'],
    ['image' => 'sto.jpeg', 'title' => 'Watch the numbers', 'copy' => "Sales, stock, and profit update themselves. Open the app tomorrow and you'll know exactly where you stand."],
  ]);
  $pricingSummaryFeatures = content_list('home.pricing_summary.features', [
    'Products, pricing, purchases, and restocking — one app',
    'Every branch and every staff account included',
    'Daily reports: sales, expenses, profit',
    'Customer and supplier debts, tracked automatically',
  ]);
  $pricingSummaryBenefits = content_list('home.pricing_summary.benefits', [
    'Cancel anytime',
    'Works on mobile & tablet',
    'Setup in minutes',
  ]);
  $homePricingBenefit2Mobile = content('home.pricing_summary.benefit_mobile_2', 'Works on mobile & tablet');
  $featureCatalogSections = config('feature_catalog.sections', []);
  $mixedContentText = static function ($value, string $fallback = ''): string {
      if (is_string($value) || is_numeric($value)) {
          return trim((string) $value);
      }

      if (is_array($value)) {
          $text = $value['text'] ?? null;
          if (is_string($text) || is_numeric($text)) {
              return trim((string) $text);
          }
      }

      return $fallback;
  };
  $allFeatureCardAnchorDefaults = [
      0 => 'customers-and-loyalty',
      1 => 'point-of-sale',
      2 => 'catalog-and-products',
      3 => 'inventory-and-stock',
      4 => 'reports-and-profits',
      5 => 'staff-and-branches',
  ];
  $resolveFeatureDetailUrl = function (array $card, int $index = 0, ?string $preferredSlug = null) use ($featureCatalogSections, $mixedContentText): string {
      $link = $mixedContentText($card['link_url'] ?? '');
      if ($link !== '' && $link !== '#') {
          return $link;
      }

      $slug = $mixedContentText($card['detail_slug'] ?? $preferredSlug ?? '');
      $label = $mixedContentText($card['label'] ?? '');
      $title = $mixedContentText($card['title'] ?? '');
      $search = Str::lower(trim($label.' '.$title));

      if ($slug === '' && $search !== '') {
          foreach ($featureCatalogSections as $section) {
              foreach (($section['match_terms'] ?? []) as $term) {
                  if ($term !== '' && str_contains($search, Str::lower((string) $term))) {
                      $slug = (string) ($section['slug'] ?? '');
                      break 2;
                  }
              }
          }
      }

      if ($slug === '' && isset($featureCatalogSections[$index]['slug'])) {
          $slug = (string) $featureCatalogSections[$index]['slug'];
      }

      if ($slug === '') {
          $slug = 'features-detail';
      }

      return route('features.show').'#'.$slug;
  };
  $allFeaturesCtaUrl = trim((string) content('home.allfeatures.cta_url', ''));
  if ($allFeaturesCtaUrl === '' || $allFeaturesCtaUrl === '#') {
      $allFeaturesCtaUrl = route('features.show').'#features-detail';
  }
  $centerFeatureLink = $resolveFeatureDetailUrl([
      'label' => content_text('home.allfeatures.feature_label', 'Point of sale'),
      'title' => content_text('home.allfeatures.feature_label', 'Point of sale'),
      'link_url' => content('home.allfeatures.feature_link_url', '#'),
      'detail_slug' => 'point-of-sale',
  ]);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>{{ content('home.meta.title', 'SkelApp — POS System & Inventory Management for Small Businesses in Tanzania') }}</title>
<meta name="description" content="{{ content('home.meta.description', 'SkelApp is the point of sale and inventory management app for small businesses in Tanzania — every sale, expense, and stock item tracked in real time.') }}">
@include('partials.seo', [
  'seoTitle'       => content('home.meta.title', 'SkelApp — POS System & Inventory Management for Small Businesses in Tanzania'),
  'seoDescription' => content('home.meta.description', 'SkelApp is the point of sale and inventory management app for small businesses in Tanzania — every sale, expense, and stock item tracked in real time.'),
  'seoType'        => 'website',
  'seoImage'       => content_image('home.hero.background_image_desktop', asset('assets/HeroImage.webp')),
  'seoPageType'    => 'WebPage',
  'seoSoftware'    => true,
  'seoFaqs'        => content_list('faq.home_preview.items', []),
])
<link rel="preload" as="image" href="{{ content_image('home.hero.background_image_desktop', asset('assets/HeroImage.webp')) }}" media="(min-width: 901px)" fetchpriority="high" />
<link rel="preload" as="image" href="{{ content_image('home.hero.background_image_mobile', asset('assets/HeroImage.jpg')) }}" media="(max-width: 900px)" fetchpriority="high" />
<link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="welcome-page-body">
@include('partials.site-nav', ['isHome' => true])
<section class="hero" id="overview">
  <div
    id="hero-bg"
    class="hero-bg" 
    data-bg-desktop="{{ content_image('home.hero.background_image_desktop', asset('assets/HeroImage.webp')) }}"
    data-bg-mobile="{{ content_image('home.hero.background_image_mobile', asset('assets/HeroImage.jpg')) }}"
  ></div>
  <div class="hero-overlay"></div>
  <script>
    (function () {
      var el = document.getElementById('hero-bg');
      if (!el) return;
      var desktop = el.dataset.bgDesktop || '';
      var mobile = el.dataset.bgMobile || '';
      var apply = function () {
        var url = window.matchMedia('(max-width: 900px)').matches ? (mobile || desktop) : desktop;
        if (url) el.style.setProperty('background-image', "url('" + url.replace(/'/g, "\\'") + "')", 'important');
      };
      apply();
      window.addEventListener('resize', apply);

      // Scroll-driven zoom: the whole overview section scales up as it
      // scrolls away, and scales back in as you scroll up toward the top.
      var hero = el.closest('.hero') || el.parentElement;
      var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      if (!reduce && hero) {
        var ticking = false;
        var update = function () {
          var h = hero.offsetHeight || 1;
          var progress = Math.min(Math.max(window.scrollY / h, 0), 1);
          hero.style.transform = 'scale(' + (1 - progress * 0.18).toFixed(4) + ')';
          ticking = false;
        };
        var onScroll = function () {
          if (!ticking) { window.requestAnimationFrame(update); ticking = true; }
        };
        update();
        window.addEventListener('scroll', onScroll, { passive: true });
      }
    })();
  </script>
<div class="hero-content">
  <div class="hero-left">
    <h1 class="{{ content_typography_class('home.hero.title') }}" style="{{ content_typography_vars('home.hero.title') }}">{!! content_text_html('home.hero.title', 'Run your business <br>like you mean it.') !!}</h1>
    <p class="{{ content_typography_class('home.hero.subtitle') }}" style="{{ content_typography_vars('home.hero.subtitle') }}">{!! content_text_html('home.hero.subtitle', "SkelApp is Tanzania's simplest way to run a shop — every sale, every expense, every debt tracked from your phone. Nothing missing, nothing guessed.") !!}</p>
    <div class="hero-cta">
      <a href="{{ content('home.hero.cta_url', '#') }}" class="btn-download">{{ content('home.hero.cta_label', 'Start for free') }}</a>
      <a href="{{ content('home.hero.cta2_url', '#') }}" class="btn-demo">{{ content('home.hero.cta2_label', 'See it work') }}</a>
    </div>
  </div>

  <div class="hero-right">
    <div class="testimonial-card">
      <div class="stars">
        <img src="{{ content_image('home.hero.testimonial_stars_image', asset('assets/Stars.svg')) }}" alt="5 star rating">
      </div>
      <blockquote class="{{ content_typography_class('home.hero.testimonial_quote') }}" style="{{ content_typography_vars('home.hero.testimonial_quote') }}">
        {!! content_text_html('home.hero.testimonial_quote', 'Anyone can record a sale. SkelApp shows your real profit.') !!}
      </blockquote>
      <cite class="{{ content_typography_class('home.hero.testimonial_attribution') }}" style="{{ content_typography_vars('home.hero.testimonial_attribution') }}">{!! content_text_html('home.hero.testimonial_attribution', 'Profit, debts, and every payment — one screen, no guesswork.') !!}</cite>
    </div>
  </div>
</div>
</section>

<section class="whyus-section afford-section" id="whyus">
  <div class="container">
    <div class="afford-head products-header">
      <h2 class="afford-title products-title {{ content_typography_class('home.affordable.title') }}" style="{{ content_typography_vars('home.affordable.title') }}">{!! content_text_html('home.affordable.title', "You handle today's queue. SkelApp handles the next decade.") !!}</h2>
      <p class="afford-subtitle products-subtitle {{ content_typography_class('home.affordable.subtitle') }}" style="{{ content_typography_vars('home.affordable.subtitle') }}">{!! content_text_html('home.affordable.subtitle', content_text('home.affordable.eyebrow', "Today's records build a strong business — for the next ten years.")) !!}</p>
    </div>

    <div class="afford-grid" data-afford-reveal>
      @foreach ($affordCards as $idx => $card)
        @php
          $variant  = $card['variant'] ?? 'light';
          $titleKey = "home.affordable.cards.{$idx}.title";
          $img      = cms_image($card['image'] ?? null, asset('assets/PosSystemRegister.webp'));
        @endphp

        @if ($variant === 'photo')
          <article class="afford-card afford-card--photo" style="background-image: linear-gradient(180deg, rgba(8,14,11,0.12) 0%, rgba(8,14,11,0.80) 100%), url('{{ $img }}');">
            <div class="afford-card-top">
              <h3 class="afford-card-title">{!! content_text_html($titleKey, $card['title'] ?? '') !!}</h3>
              @if (! empty($card['copy']))<p class="afford-card-copy">{{ $card['copy'] }}</p>@endif
              <a href="{{ $card['link_url'] ?? '#' }}" class="afford-card-link">{{ $card['link_label'] ?? 'Learn more' }}{!! $arrowSvg !!}</a>
            </div>
            @if (! empty($card['price']))
              <div class="afford-price">
                @if (! empty($card['price_label']))<span class="afford-price-label">{{ $card['price_label'] }}</span>@endif
                <span class="afford-price-amount">{{ $card['price'] }}</span>
              </div>
            @endif
            @if (! empty($card['badge']))<span class="afford-badge">{{ $card['badge'] }}</span>@endif
          </article>
        @else
          <article class="afford-card afford-card--{{ $variant }}">
            <div class="afford-card-top">
              <h3 class="afford-card-title">{!! content_text_html($titleKey, $card['title'] ?? '') !!}</h3>
              @if (! empty($card['copy']))<p class="afford-card-copy">{{ $card['copy'] }}</p>@endif
              <a href="{{ $card['link_url'] ?? '#' }}" class="afford-card-link">{{ $card['link_label'] ?? 'Learn more' }}{!! $arrowSvg !!}</a>
            </div>
            <div class="afford-card-media">
              <img src="{{ $img }}" alt="{{ content_text($titleKey, $card['title'] ?? '') }}" loading="lazy" decoding="async">
              @if (! empty($card['overlay_big']))
              @endif
            </div>
          </article>
        @endif
      @endforeach
    </div>
  </div>
</section>

<section class="app-showcase" id="showcase">
  <div class="showcase-container">
    <div class="showcase-card">
      <div class="showcase-header">
        <h2 class="showcase-title {{ content_typography_class('home.showcase.title') }}" style="{{ content_typography_vars('home.showcase.title') }}">{!! content_text_html('home.showcase.title', 'POS System, But Actually Simple') !!}</h2>
        <p class="showcase-subtitle showcase-subtitle-primary {{ content_typography_class('home.showcase.subtitle_primary') }}" style="{{ content_typography_vars('home.showcase.subtitle_primary') }}">
          {!! content_text_html('home.showcase.subtitle_primary', 'SkelApp is the point of sale and inventory management app for small businesses in Tanzania — every sale, expense, and stock item in real time.') !!}
        </p>
        <p class="showcase-subtitle showcase-subtitle-secondary {{ content_typography_class('home.showcase.subtitle_secondary') }}" style="{{ content_typography_vars('home.showcase.subtitle_secondary') }}">
          {!! content_text_html('home.showcase.subtitle_secondary', 'No accountant needed. No complexity. Honest tools for real shops.') !!}
        </p>
        <p class="showcase-subtitle showcase-subtitle-mobile {{ content_typography_class('home.showcase.subtitle_mobile') }}" style="{{ content_typography_vars('home.showcase.subtitle_mobile') }}">
          {!! content_text_html('home.showcase.subtitle_mobile', 'SkelApp is the point of sale and inventory management app for small businesses in Tanzania — every sale, expense, and stock item in real time. No accountant needed. No complexity. Honest tools for real shops.') !!}
        </p>

        <div class="app-buttons">
          <a href="{{ content('global.app_badges.apple_url', '#') }}" class="store-badge store-badge-apple" aria-label="Download on the App Store">
            <img src="{{ content_image('global.app_badges.apple_image', asset('assets/applebadge.png')) }}" alt="Download on the App Store">
          </a>

          <a href="{{ content('global.app_badges.google_url', '#') }}" class="store-badge store-badge-google" aria-label="Get it on Google Play">
            <img src="{{ content_image('global.app_badges.google_image', asset('assets/googlebadge.png')) }}" alt="Get it on Google Play">
          </a>
        </div>
      </div>

      <div class="device-mockup">
        <img src="{{ content_image('home.showcase.device_image_desktop', asset('assets/devicemockup.webp')) }}" alt="SkelApp on desktop" class="device-mockup-image desktop-only-img" loading="lazy" decoding="async">
        <img src="{{ content_image('home.showcase.device_image_mobile', asset('assets/Mobilehomeview.png')) }}" alt="SkelApp on mobile" class="device-mockup-image mobile-only-img" loading="lazy" decoding="async">
      </div>
    </div>

    <div class="showcase-points desktop-only" aria-label="SkelApp highlights">
      @foreach ($homeShowcasePoints as $idx => $point)
        @php
          $titleKey = "home.showcase.points.{$idx}.title";
          $bodyKey = "home.showcase.points.{$idx}.body";
        @endphp
        <article class="showcase-point">
          <div class="showcase-point-heading">
            <img src="{{ cms_image($point['icon'] ?? null, asset('assets/speed.svg')) }}" alt="" class="showcase-point-icon" aria-hidden="true">
            <h3 class="{{ content_typography_class($titleKey) }}" style="{{ content_typography_vars($titleKey) }}">{!! content_text_html($titleKey, $point['title'] ?? '') !!}</h3>
          </div>
          <p class="{{ content_typography_class($bodyKey) }}" style="{{ content_typography_vars($bodyKey) }}">{!! content_text_html($bodyKey, $point['body'] ?? '') !!}</p>
        </article>
      @endforeach
    </div>
  </div>
</section>

<section class="products-section" id="products">
  <div class="container">
    <div class="products-header">
      <h2 class="products-title {{ content_typography_class('home.products.title') }}" style="{{ content_typography_vars('home.products.title') }}">{!! content_text_html('home.products.title', 'One system. Two ways to run it.') !!}</h2>
      <p class="products-subtitle {{ content_typography_class('home.products.subtitle') }}" style="{{ content_typography_vars('home.products.subtitle') }}">{!! content_text_html('home.products.subtitle', "At the counter or on the move — your shop's numbers stay with you.") !!}</p>
    </div>

    <div class="products-grid">
      @foreach ($homeProductCards as $idx => $card)
        @php
          $eyebrowKey = "home.products.cards.{$idx}.eyebrow";
          $titleKey = "home.products.cards.{$idx}.title";
          $bodyKey = "home.products.cards.{$idx}.body";
          $linkLabelKey = "home.products.cards.{$idx}.link_label";
        @endphp
        <article class="product-card">
          <div class="product-card-body">
            <span class="product-card-eyebrow {{ content_typography_class($eyebrowKey) }}" style="{{ content_typography_vars($eyebrowKey) }}">{!! content_text_html($eyebrowKey, $card['eyebrow'] ?? '') !!}</span>
            <h3 class="product-card-title {{ content_typography_class($titleKey) }}" style="{{ content_typography_vars($titleKey) }}">{!! content_text_html($titleKey, $card['title'] ?? '') !!}</h3>
            <p class="product-card-copy {{ content_typography_class($bodyKey) }}" style="{{ content_typography_vars($bodyKey) }}">{!! content_text_html($bodyKey, $card['body'] ?? '') !!}</p>
            <a href="{{ content("home.products.cards.{$idx}.link_url", $card['link_url'] ?? '#') }}" class="product-card-link">
              {!! content_text_html($linkLabelKey, $card['link_label'] ?? 'See how it works') !!}
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M5 12h14M12 5l7 7-7 7"/>
              </svg>
            </a>
          </div>
          <div class="product-card-media">
            <img src="{{ cms_image($card['image'] ?? null, asset('assets/Moc-lap-phone-02.webp')) }}" alt="{{ content_text($titleKey, $card['title'] ?? '') }}" loading="lazy" decoding="async">
          </div>
        </article>
      @endforeach
    </div>
  </div>
</section>

<section class="allfeatures" id="allfeatures">
  @php
    $afLeft = array_slice($allFeatureCards, 0, 3, true);
    $afRight = array_slice($allFeatureCards, 3, 3, true);
  @endphp
  <div class="allfeatures-container">
    <div class="allfeatures-head">
      <h2 class="allfeatures-head-title {{ content_typography_class('home.allfeatures.title_line_1') }}" style="{{ content_typography_vars('home.allfeatures.title_line_1') }}">{!! content_text_html('home.allfeatures.title_line_1', 'Run Your Shop') !!} {!! content_text_html('home.allfeatures.title_line_2', 'the Way It Actually Works') !!}</h2>
      <p class="allfeatures-head-copy {{ content_typography_class('home.allfeatures.copy') }}" style="{{ content_typography_vars('home.allfeatures.copy') }}">{!! content_text_html('home.allfeatures.copy', 'Five jobs every shop owner does daily. One app that does them all in shillings, not spreadsheets.') !!}</p>
      <a href="{{ $allFeaturesCtaUrl }}" class="btn-allfeatures-cta">{{ content('home.allfeatures.cta_label', 'Download Now') }}</a>
    </div>

    <div class="allfeatures-gallery" data-af-gallery>
      <div class="af-col af-col--left">
        @foreach ($afLeft as $idx => $featureCard)
          @php
            $titleKey = "home.allfeatures.cards.{$idx}.title";
            $labelKey = "home.allfeatures.cards.{$idx}.label";
            $copyKey = "home.allfeatures.cards.{$idx}.copy";
            $afLabel = content_text($labelKey, $featureCard['label'] ?? content_text($titleKey, $featureCard['title'] ?? ''));
            $afDesc = content_text($copyKey, $featureCard['copy'] ?? '');
            $featureCard['link_url'] = content("home.allfeatures.cards.{$idx}.link_url", $featureCard['link_url'] ?? '#');
            $afLink = $resolveFeatureDetailUrl($featureCard, $idx, $allFeatureCardAnchorDefaults[$idx] ?? null);
          @endphp
          <div class="af-parallax" data-af-parallax>
            <a href="{{ $afLink }}" class="af-card af-card-link af-anim" data-af-anim style="--af-delay: {{ $idx * 150 }}ms">
              <div class="af-media">
                <img src="{{ cms_image($featureCard['image'] ?? null, asset('assets/crm.webp')) }}" alt="{{ $afLabel }}" loading="lazy" decoding="async">
              </div>
              <div class="af-content">
                <h3 class="af-label {{ content_typography_class($labelKey) }}" style="{{ content_typography_vars($labelKey) }}">{!! cms_text_html($afLabel) !!}</h3>
                @if ($afDesc !== '')
                  <span class="af-desc">{!! cms_text_html($afDesc) !!}</span>
                @endif
                <span class="af-learn">
                  <span>Learn more</span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </span>
              </div>
            </a>
          </div>
        @endforeach
      </div>

      <div class="af-col af-col--center">
        <div class="af-center-parallax" data-af-center>
          <a href="{{ $centerFeatureLink }}" class="af-card af-card-link af-feature" data-af-feature>
            <div class="af-media">
              <img src="{{ content_image('home.allfeatures.feature_image', asset('assets/techshop.webp')) }}" alt="{{ content_text('home.allfeatures.feature_label', 'Point of sale') }}" loading="lazy" decoding="async">
            </div>
            @php $featureDesc = content_text('home.allfeatures.feature_desc'); @endphp
            <div class="af-content">
              <h3 class="af-label {{ content_typography_class('home.allfeatures.feature_label') }}" style="{{ content_typography_vars('home.allfeatures.feature_label') }}">{!! content_text_html('home.allfeatures.feature_label', 'Point of sale') !!}</h3>
              @if ($featureDesc !== '')
                <span class="af-desc">{!! cms_text_html($featureDesc) !!}</span>
              @endif
              <span class="af-learn">
                <span>Learn more</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
              </span>
            </div>
          </a>
          <p class="af-tagline {{ content_typography_class('home.allfeatures.tagline') }}" style="{{ content_typography_vars('home.allfeatures.tagline') }}">{!! content_text_html('home.allfeatures.tagline', 'Everything above, in one app. Nothing above, in a notebook.') !!}</p>
        </div>
      </div>

      <div class="af-col af-col--right">
        @foreach ($afRight as $idx => $featureCard)
          @php
            $titleKey = "home.allfeatures.cards.{$idx}.title";
            $labelKey = "home.allfeatures.cards.{$idx}.label";
            $copyKey = "home.allfeatures.cards.{$idx}.copy";
            $afLabel = content_text($labelKey, $featureCard['label'] ?? content_text($titleKey, $featureCard['title'] ?? ''));
            $afDesc = content_text($copyKey, $featureCard['copy'] ?? '');
            $featureCard['link_url'] = content("home.allfeatures.cards.{$idx}.link_url", $featureCard['link_url'] ?? '#');
            $afLink = $resolveFeatureDetailUrl($featureCard, $idx, $allFeatureCardAnchorDefaults[$idx] ?? null);
          @endphp
          <div class="af-parallax" data-af-parallax>
            <a href="{{ $afLink }}" class="af-card af-card-link af-anim" data-af-anim style="--af-delay: {{ $idx * 150 }}ms">
              <div class="af-media">
                <img src="{{ cms_image($featureCard['image'] ?? null, asset('assets/crm.webp')) }}" alt="{{ $afLabel }}" loading="lazy" decoding="async">
              </div>
              <div class="af-content">
                <h3 class="af-label {{ content_typography_class($labelKey) }}" style="{{ content_typography_vars($labelKey) }}">{!! cms_text_html($afLabel) !!}</h3>
                @if ($afDesc !== '')
                  <span class="af-desc">{!! cms_text_html($afDesc) !!}</span>
                @endif
                <span class="af-learn">
                  <span>Learn more</span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </span>
              </div>
            </a>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

<section class="retailers-section" id="retailers">
  <div class="container">
    <div class="retailer-head">
      <div class="section-header retailer-head-copy">
        <h2 class="{{ content_typography_class('home.retailers.title') }}" style="{{ content_typography_vars('home.retailers.title') }}">{!! content_text_html('home.retailers.title', 'Built for Every Kind of Shop') !!}</h2>
        <p class="{{ content_typography_class('home.retailers.subtitle') }}" style="{{ content_typography_vars('home.retailers.subtitle') }}">{!! content_text_html('home.retailers.subtitle', 'From boutiques in Dar es Salaam to hardware shops in Arusha — SkelApp is built for how Tanzanian retailers actually work.') !!}</p>
      </div>

      <div class="carousel-slider" aria-label="Retailer carousel controls">
        <button class="carousel-slider-button" type="button" data-carousel-prev aria-label="Previous retailer">
          <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path d="M14.5 5.5 8 12l6.5 6.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>

        <button class="carousel-slider-button" type="button" data-carousel-next aria-label="Next retailer">
          <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path d="M9.5 5.5 16 12l-6.5 6.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>

        <div class="carousel-slider-dots" role="tablist" aria-label="Retailer slides" aria-hidden="true">
          @foreach ($retailerCards as $card)
            @php $dotLabel = content_text("home.retailers.cards.{$loop->index}.title", $card['title'] ?? ''); @endphp
            <button
              class="carousel-slider-dot{{ $loop->index === 1 ? ' is-active' : '' }}"
              type="button"
              role="tab"
              data-carousel-dot="{{ $loop->index }}"
              aria-label="Go to {{ $dotLabel }}"
              aria-current="{{ $loop->index === 1 ? 'true' : 'false' }}"
              tabindex="-1"
            ></button>
          @endforeach
        </div>
      </div>
    </div>

    <div class="carousel-container" data-drag-scroll data-carousel-default-index="0">
      <div class="carousel-track">
        @foreach ($retailerCards as $idx => $card)
          @php
            $titleKey = "home.retailers.cards.{$idx}.title";
            $catKey = "home.retailers.cards.{$idx}.category";
            $cat = content_text($catKey, $card['category'] ?? '');
          @endphp
          <div class="retailer-card">
            <div class="card-image">
              <img src="{{ cms_image($card['image'] ?? null, asset('assets/boutique.webp')) }}" alt="{{ content_text($titleKey, $card['title'] ?? '') }}" draggable="false" loading="lazy" decoding="async">
              @if ($cat !== '')
                <span class="retailer-card-pill {{ content_typography_class($catKey) }}" style="{{ content_typography_vars($catKey) }}">{!! cms_text_html($cat) !!}</span>
              @endif
              <div class="card-overlay">
                <h3 class="{{ content_typography_class($titleKey) }}" style="{{ content_typography_vars($titleKey) }}">{!! content_text_html($titleKey, $card['title'] ?? '') !!}</h3>
              </div>
              <a href="{{ content("home.retailers.cards.{$idx}.link_url", $card['link_url'] ?? '#') }}" class="retailer-card-link" aria-label="{{ content_text($titleKey, $card['title'] ?? '') }}"></a>
            </div>
          </div>
        @endforeach
      </div>
    </div>

  </div>

</section>

<!-- Retailers bottom (kept separate to preserve layout) -->
<div class="section-bottom retailers-section-bottom">
  <div class="container">
    <h2 class="{{ content_typography_class('home.retailers.bottom_title') }}" style="{{ content_typography_vars('home.retailers.bottom_title') }}">{!! content_text_html('home.retailers.bottom_title', 'One app. Every number in your business — tracked.') !!}</h2>
    <p class="{{ content_typography_class('home.retailers.bottom_copy') }}" style="{{ content_typography_vars('home.retailers.bottom_copy') }}">{!! content_text_html('home.retailers.bottom_copy') !!}</p>
  </div>
</div>

<section class="features-section" id="features">
  <div class="container">
    <!-- Top Row -->
    <div class="features-row">
      <div class="feature-card feature-square feature-light feature-square-catalogue">
        <div class="feature-content-top">
          <h3 class="feature-card-heading {{ content_typography_class('home.features.top_left.title') }}" style="{{ content_typography_vars('home.features.top_left.title') }}">{!! content_text_html('home.features.top_left.title', 'Record every sale in seconds') !!}</h3>
          <p class="{{ content_typography_class('home.features.top_left.body') }}" style="{{ content_typography_vars('home.features.top_left.body') }}">{!! content_text_html('home.features.top_left.body', 'Your full catalogue — searchable, priced, always current. Tap the product, take the payment, done.') !!}</p>
        </div>
        <div class="feature-content-bottom">
          <img src="{{ content_image('home.features.top_left.image', asset('assets/Moc-tab.webp')) }}" alt="SkelApp tablet catalogue" class="feature-tab-mockup" loading="lazy" decoding="async">
        </div>
      </div>

      <div class="feature-card feature-rectangle feature-green feature-rectangle-pos">
        <div class="feature-content-left">
          <h3 class="feature-card-heading {{ content_typography_class('home.features.top_right.title') }}" style="{{ content_typography_vars('home.features.top_right.title') }}">{!! content_text_html('home.features.top_right.title', 'Any phone. Any tablet.') !!}<br>{!! cms_text_html(content('home.features.top_right.title_line_2', 'No hardware bill.')) !!}</h3>
          <p class="{{ content_typography_class('home.features.top_right.body') }}" style="{{ content_typography_vars('home.features.top_right.body') }}">{!! content_text_html('home.features.top_right.body', 'iPhone or Android — SkelApp runs on the device already in your pocket. Add a printer or terminal only when you want one.') !!}</p>
        </div>
        <div class="feature-content-right">
          <img src="{{ content_image('home.features.top_right.image', asset('assets/PosSystem.webp')) }}" alt="SkelApp POS system" loading="lazy" decoding="async">
        </div>
      </div>
    </div>

    <!-- Bottom Row -->
    <div class="features-row">
      <div class="feature-card feature-rectangle feature-dark feature-rectangle-handheld">
        <div class="feature-content-left">
          <h3 class="feature-card-heading {{ content_typography_class('home.features.bottom_left.title') }}" style="{{ content_typography_vars('home.features.bottom_left.title') }}">{!! content_text_html('home.features.bottom_left.title', 'Your whole team, always in sync.') !!}</h3>
          <p class="{{ content_typography_class('home.features.bottom_left.body') }}" style="{{ content_typography_vars('home.features.bottom_left.body') }}">{!! content_text_html('home.features.bottom_left.body', "Every attendant's sales land in the same record, live. You see everything, from anywhere.") !!}</p>
        </div>
        <div class="feature-content-right">
          <img src="{{ content_image('home.features.bottom_left.image', asset('assets/poswithtab.webp')) }}" alt="SkelApp handheld POS with tablet" class="feature-handheld-pos" loading="lazy" decoding="async">
        </div>
      </div>

      <div class="feature-card feature-square feature-light feature-square-reporting">
        <div class="feature-content-top">
          <h3 class="feature-card-heading {{ content_typography_class('home.features.bottom_right.title') }}" style="{{ content_typography_vars('home.features.bottom_right.title') }}">{!! content_text_html('home.features.bottom_right.title', 'Know where you stand, every morning.') !!}</h3>
          <p class="{{ content_typography_class('home.features.bottom_right.body') }}" style="{{ content_typography_vars('home.features.bottom_right.body') }}">{!! content_text_html('home.features.bottom_right.body', "Yesterday's sales, today's stock, this month's profit — before you've had your chai.") !!}</p>
        </div>
        <div class="feature-content-bottom">
          <img src="{{ content_image('home.features.bottom_right.image', asset('assets/Moc-lap-phone-02.webp')) }}" alt="SkelApp reporting dashboard on laptop and phone" class="feature-reporting-mockup" loading="lazy" decoding="async">
        </div>
      </div>
    </div>
  </div>
</section>

<section class="how-it-works-section" id="howitworks">
  <div class="container how-it-works-scroll">
    <div class="how-it-works-stage">
      <div class="section-headerr">
        <div class="section-headerr-copy">
          <h2 class="{{ content_typography_class('home.howitworks.title') }}" style="{{ content_typography_vars('home.howitworks.title') }}">{!! content_text_html('home.howitworks.title', 'If they can WhatsApp, they can SkelApp.') !!}</h2>
          <p class="{{ content_typography_class('home.howitworks.copy') }}" style="{{ content_typography_vars('home.howitworks.copy') }}">{!! content_text_html('home.howitworks.copy', "Download, add your products, start selling — you're running today, not next week.") !!}</p>
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
          @foreach ($howSteps as $idx => $step)
              @php
              $titleKey = "home.howitworks.steps.{$idx}.title";
              $copyKey = "home.howitworks.steps.{$idx}.copy";
            @endphp
            <article class="step-item">
              <div class="step-image">
                <img src="{{ cms_image($step['image'] ?? null, asset('assets/rw.jpeg')) }}" alt="{{ content_text($titleKey, $step['title'] ?? '') }}" loading="lazy" decoding="async">
              </div>
              <div class="step-marker">
                <div class="step-number-box">{{ $loop->iteration }}</div>
              </div>
              <div class="step-content">
                <h3 class="{{ content_typography_class($titleKey) }}" style="{{ content_typography_vars($titleKey) }}">{!! content_text_html($titleKey, $step['title'] ?? '') !!}</h3>
                <p class="{{ content_typography_class($copyKey) }}" style="{{ content_typography_vars($copyKey) }}">{!! content_text_html($copyKey, $step['copy'] ?? '') !!}</p>
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
        <h2 class="{{ content_typography_class('home.hardware.title') }}" style="{{ content_typography_vars('home.hardware.title') }}">{!! content_text_html('home.hardware.title', 'Ready for the counter? Meet the Skel Register.') !!}</h2>
        <p class="{{ content_typography_class('home.hardware.copy') }}" style="{{ content_typography_vars('home.hardware.copy') }}">{!! content_text_html('home.hardware.copy', 'A full point-of-sale station — screen, printer, cash drawer — running the same SkelApp your phone does. Upgrade when the queue gets longer.') !!}</p>

        <a href="{{ content('home.hardware.cta_url', route('hardware.product', 'skel-register')) }}" class="btn-hardware">
          {{ content('home.hardware.cta_label', 'See the Skel Register') }}
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </a>
      </div>

      <div class="hardware-image">
        <img src="{{ content_image('home.hardware.image', asset('assets/PosSystemRegister.webp')) }}" alt="POS Hardware Terminal" loading="lazy" decoding="async">
      </div>
    </div>
  </div>

</section>

<section class="pricing-section" id="pricing">
  <div class="container">
    <div class="pricing-layout">
      @php
        $pricingThumbnails = content_list('home.pricing_summary.thumbnails', []);
        $pricingCardImage = content_image('home.pricing_summary.card_image', asset('assets/card.webp'));
      @endphp
      <!-- Left Side - Card Preview -->
      <div class="pricing-preview is-subscription-preview">
        <div class="preview-card is-subscription-preview">
          <img src="{{ $pricingCardImage }}" alt="SkelApp Subscription Card" class="card-preview-image" loading="lazy" decoding="async">
        </div>

        <!-- Thumbnail Previews -->
        <div class="thumbnail-grid">
          @foreach ($pricingThumbnails as $thumbIdx => $thumb)
            <div class="thumbnail{{ $thumbIdx === 0 ? ' active' : '' }}">
              <img src="{{ cms_image($thumb['image'] ?? null, asset('assets/card.webp')) }}" alt="{{ $thumb['alt'] ?? '' }}" loading="lazy" decoding="async">
            </div>
          @endforeach
        </div>
      </div>

      <!-- Right Side - Pricing Details -->
      <div class="pricing-details">
        <p class="pricing-intro {{ content_typography_class('home.pricing_summary.intro') }}" style="{{ content_typography_vars('home.pricing_summary.intro') }}">{!! content_text_html('home.pricing_summary.intro', 'Untracked stock costs millions a year. SkelApp? Jero kwa siku.') !!}</p>

        <h2 class="{{ content_typography_class('home.pricing_summary.title_line_1') }}" style="{{ content_typography_vars('home.pricing_summary.title_line_1') }}">{!! content_text_html('home.pricing_summary.title_line_1', 'One price.') !!} <br>{!! cms_text_html(content('home.pricing_summary.title_line_2', 'Everything included.')) !!}</h2>

        <p class="pricing-description {{ content_typography_class('home.pricing_summary.description') }}" style="{{ content_typography_vars('home.pricing_summary.description') }}">
          {!! content_text_html('home.pricing_summary.description', 'Point of sale, inventory, debt tracking, reports — the whole app, every feature, one subscription. No tiers, no locked features, no surprises.') !!}
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
            <span class="price-main">{{ content('home.pricing_summary.price_main', '150,000/year') }}</span>
          </div>
          <span class="price-period">{{ content('home.pricing_summary.price_period', '— two months free · or TZS 15,000 month-to-month. Cancel anytime.') }}</span>
        </div>

        <div class="payment-row">
          <span class="payment-label">{{ content('home.pricing_summary.payment_label', 'Flexible payment options') }}</span>
          <div class="payment-methods-art">
            <img src="{{ content_image('home.pricing_summary.payment_methods_image', asset('assets/paymentmethod.png')) }}" alt="Supported payment methods" loading="lazy" decoding="async">
          </div>
        </div>

        <a href="{{ content('home.pricing_summary.cta_url', route('contact.show')) }}" class="btn-pricing">
          {{ content('home.pricing_summary.cta_label', 'Start for free') }}
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
    <img src="{{ content_image('home.image_cta.background_image', asset('assets/client.webp')) }}" alt="Building momentum" class="cta-img" loading="lazy" decoding="async">
    <div class="cta-overlay"></div>
  </div>
  <div class="cta-content-wrapper">
    <div class="cta-content">
      <h2 class="{{ content_typography_class('home.image_cta.heading') }}" style="{{ content_typography_vars('home.image_cta.heading') }}">{!! content_text_html('home.image_cta.heading', 'Simple tools. Real people. Businesses that grow.') !!}</h2>
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
<script>window.$zoho=window.$zoho || {};$zoho.salesiq=$zoho.salesiq||{ready:function(){}}</script><script id="zsiqscript" src="https://salesiq.zohopublic.com/widget?wc=siqa0f9828d26a6345a15cb1a7907634290f7f5bfa5509a978c7226ae97a79099da" defer></script>
</body>
</html>
