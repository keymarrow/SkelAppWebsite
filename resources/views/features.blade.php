@php
  use Illuminate\Support\Str;

  $featureBlueprints = config('feature_catalog.sections', []);
  $featureContent = content_list('features.detail.cards', []);
  $defaultSectionCtaUrl = route('contact.show');

  $parseFeatureCapabilities = function ($value): array {
      if (! is_string($value) || trim($value) === '') {
          return [];
      }

      return collect(preg_split('/\r\n|\r|\n/', $value))
          ->map(static function ($line) {
              $line = trim((string) $line);
              if ($line === '') {
                  return null;
              }

              [$title, $body] = array_pad(array_map('trim', explode('|', $line, 2)), 2, '');

              if ($title === '') {
                  return null;
              }

              return [
                  'title' => $title,
                  'body' => $body,
              ];
          })
          ->filter()
          ->values()
          ->all();
  };

  // Editors can enable typography on any field, which stores the value as
  // ['text' => '...', 'typography' => [...]]. Flatten those back to plain
  // strings (recursively) so the (string) casts below never hit an array.
  $flattenTypography = function ($value) use (&$flattenTypography) {
      if (is_array($value)) {
          if (array_key_exists('text', $value) && ! array_is_list($value)) {
              return is_string($value['text']) ? $value['text'] : '';
          }
          return array_map($flattenTypography, $value);
      }
      return $value;
  };

  $formatFeatureSection = function (array $section, ?array $fallback, int $index) use ($parseFeatureCapabilities, $defaultSectionCtaUrl, $flattenTypography): array {
      $section = $flattenTypography($section);
      $merged = $fallback ? array_replace_recursive($fallback, $section) : $section;

      $slugSource = trim((string) ($merged['slug'] ?? ''));
      if ($slugSource === '') {
          $slugSource = trim((string) ($merged['nav_label'] ?? $merged['eyebrow'] ?? $merged['title'] ?? 'feature-'.($index + 1)));
      }

      $capabilities = [];
      if (! empty($section['capabilities']) && is_array($section['capabilities'])) {
          $capabilities = $section['capabilities'];
      } elseif (! empty($section['capabilities_text'])) {
          $capabilities = $parseFeatureCapabilities($section['capabilities_text']);
      } elseif (! empty($section['bullets']) && is_array($section['bullets'])) {
          $capabilities = array_values(array_filter(array_map(static function ($bullet) {
              if (! is_array($bullet) || empty(trim((string) ($bullet['title'] ?? '')))) {
                  return null;
              }

              return [
                  'title' => trim((string) ($bullet['title'] ?? '')),
                  'body' => trim((string) ($bullet['body'] ?? '')),
              ];
          }, $section['bullets'])));
      } elseif ($fallback && ! empty($fallback['capabilities']) && is_array($fallback['capabilities'])) {
          $capabilities = $fallback['capabilities'];
      }

      $ctaUrl = trim((string) ($merged['cta_url'] ?? ''));
      if ($ctaUrl === '' || $ctaUrl === '#') {
          $ctaUrl = $defaultSectionCtaUrl;
      }

      return [
          'slug' => Str::slug($slugSource),
          'nav_label' => trim((string) ($merged['nav_label'] ?? $merged['eyebrow'] ?? $merged['title'] ?? 'Feature')),
          'eyebrow' => trim((string) ($merged['eyebrow'] ?? '')),
          'title' => trim((string) ($merged['title'] ?? '')),
          'overview' => trim((string) ($merged['overview'] ?? $merged['intro'] ?? $merged['body'] ?? '')),
          'note_label' => trim((string) ($merged['note_label'] ?? '')),
          'note_value' => trim((string) ($merged['note_value'] ?? $merged['note'] ?? '')),
          'image' => $merged['image'] ?? null,
          'image_alt' => trim((string) ($merged['image_alt'] ?? $merged['title'] ?? 'SkelApp feature')),
          'cta_label' => trim((string) ($merged['cta_label'] ?? '')),
          'cta_url' => $ctaUrl,
          'capabilities' => $capabilities,
      ];
  };

  $featureContentByIndex = [];
  $featureContentBySlug = [];
  foreach ($featureContent as $idx => $section) {
      if (! is_array($section)) {
          continue;
      }

      $featureContentByIndex[$idx] = $section;
      $sectionSlug = Str::slug(trim((string) ($section['slug'] ?? '')));
      if ($sectionSlug !== '') {
          $featureContentBySlug[$sectionSlug] = [
              'index' => $idx,
              'data' => $section,
          ];
      }
  }

  $detailSections = [];
  $consumedCmsIndexes = [];
  foreach ($featureBlueprints as $idx => $blueprint) {
      $cmsSection = [];
      $blueprintSlug = Str::slug(trim((string) ($blueprint['slug'] ?? '')));

      if ($blueprintSlug !== '' && isset($featureContentBySlug[$blueprintSlug])) {
          $cmsSection = $featureContentBySlug[$blueprintSlug]['data'];
          $consumedCmsIndexes[$featureContentBySlug[$blueprintSlug]['index']] = true;
      } elseif (isset($featureContentByIndex[$idx])) {
          $cmsSection = $featureContentByIndex[$idx];
          $consumedCmsIndexes[$idx] = true;
      }

      $detailSections[] = $formatFeatureSection(is_array($cmsSection) ? $cmsSection : [], is_array($blueprint) ? $blueprint : null, $idx);
  }

  foreach ($featureContentByIndex as $idx => $cmsSection) {
      if (isset($consumedCmsIndexes[$idx])) {
          continue;
      }

      $detailSections[] = $formatFeatureSection($cmsSection, null, $idx);
  }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ content('features.meta.title', 'Features') }}</title>
  <meta name="description" content="{{ content('features.meta.description', 'Every SkelApp feature in one place — sales, inventory, customers, and reports built for Tanzanian retailers.') }}">
  @include('partials.seo', [
    'seoTitle' => content('features.meta.title', 'Features'),
    'seoDescription' => content('features.meta.description', 'Every SkelApp feature in one place — sales, inventory, customers, and reports built for Tanzanian retailers.'),
    'seoImage' => content_image('features.hero.image', asset('assets/featureheroimage.webp')),
    'seoPageType' => 'CollectionPage',
    'seoBreadcrumbs' => [
      ['name' => 'Home', 'url' => url('/')],
      ['name' => 'Features', 'url' => route('features.show')],
    ],
  ])
  <link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="features-page-body">
  @include('partials.site-nav')

  <main class="hwp features-page">
    {{-- ══════════════ HERO (hardware-style panel) ══════════════ --}}
    <section class="hwp-hero">
      <div class="hwp-hero-shell">
        <div class="hwp-hero-panel">
          <div class="hwp-hero-copy">
            <div class="hwp-hero-tagrow">
              <span class="hwp-hero-badge">Features</span>
              <span class="hwp-hero-label">{{ content_text('features.hero.eyebrow', 'All in One Place') }}</span>
            </div>
            <h1 class="hwp-hero-title">{!! nl2br(e(content_text('features.hero.title', "Powerful Features,\nSeamlessly Connected"))) !!}</h1>
            <p class="hwp-hero-subtitle">{{ content_text('features.hero.subtitle', "SkelApp brings sales, inventory, customers, and reports together in one simple platform — so you can focus on growing your business, not juggling tools.") }}</p>
            <div class="hwp-hero-actions">
              <a href="#features-detail" class="hwp-hero-btn hwp-hero-btn--ghost">Explore features</a>
              <a href="{{ content('features.cta.primary_url', route('contact.show')) }}" class="hwp-hero-btn hwp-hero-btn--solid">{{ content('features.cta.primary_label', 'Get started') }}</a>
            </div>
          </div>
          <div class="hwp-hero-art">
            <img
              src="{{ content_image('features.hero.image', asset('assets/featureheroimage.webp')) }}"
              alt="SkelApp running across multiple devices"
              class="hwp-hero-image"
              loading="eager"
              decoding="async"
            />
          </div>
        </div>
      </div>
    </section>

    {{-- ══════════════ DETAIL ══════════════ --}}
    <section class="features-detail" id="features-detail">
      <div class="features-detail-shell">
        <div class="features-outline-header">
          @if (content_text('features.detail.eyebrow', 'Key Features') !== '')
            <span class="features-outline-kicker">{{ content_text('features.detail.eyebrow', 'Key Features') }}</span>
          @endif
          <h2 class="hwp-section-title">{{ content_text('features.detail.title', 'Detailed Overview of Our POS Features') }}</h2>
          <p class="hwp-section-subtitle">{{ content_text('features.detail.subtitle', 'Our point-of-sale features help you record sales, track inventory, and grow customer loyalty — keeping every shop running smoothly.') }}</p>
        </div>

        <div class="features-outline-layout">
          <aside class="features-outline-nav" aria-label="Feature detail sections">
            <div class="features-outline-nav-sticky">
              @foreach ($detailSections as $idx => $section)
                <a
                  href="#{{ $section['slug'] }}"
                  class="features-outline-nav-link{{ $loop->first ? ' is-active' : '' }}"
                  data-feature-nav-link="{{ $section['slug'] }}"
                  aria-current="{{ $loop->first ? 'true' : 'false' }}"
                >
                  <span class="features-outline-nav-index">{{ str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT) }}</span>
                  <span class="features-outline-nav-text">{{ $section['nav_label'] }}</span>
                </a>
              @endforeach
            </div>
          </aside>

          <div class="features-outline-sections">
            @foreach ($detailSections as $idx => $section)
              @php $titleKey = "features.detail.cards.{$idx}.title"; @endphp
              <article class="feature-outline-section" id="{{ $section['slug'] }}" data-feature-section>
                <div class="feature-outline-section-shell">
                  <figure class="feature-outline-media">
                    <img
                      src="{{ cms_image($section['image'] ?? null, asset($section['image'] ?? 'assets/DASHBOARD.png')) }}"
                      alt="{{ $section['image_alt'] }}"
                      loading="lazy"
                      decoding="async"
                    />
                  </figure>

                  <header class="feature-outline-head">
                    @if ($section['eyebrow'] !== '')
                      <span class="feature-outline-eyebrow">{{ content_text("features.detail.cards.{$idx}.eyebrow", $section['eyebrow']) }}</span>
                    @endif

                    <h3 class="feature-outline-title">{{ content_text($titleKey, $section['title']) }}</h3>

                    @if ($section['overview'] !== '')
                      <p class="feature-outline-copy">{{ content_text("features.detail.cards.{$idx}.overview", $section['overview']) }}</p>
                    @endif
                  </header>

                  @if ($section['note_label'] !== '' || $section['note_value'] !== '' || $section['cta_label'] !== '')
                    <div class="feature-outline-meta">
                      <div class="feature-outline-meta-copy">
                        @if ($section['note_label'] !== '')
                          <span class="feature-outline-note-label">{{ content_text("features.detail.cards.{$idx}.note_label", $section['note_label']) }}</span>
                        @endif

                        @if ($section['note_value'] !== '')
                          <p>{{ content_text("features.detail.cards.{$idx}.note_value", $section['note_value']) }}</p>
                        @endif
                      </div>

                      @if ($section['cta_label'] !== '')
                        <a href="{{ $section['cta_url'] }}" class="feature-outline-cta">
                          {{ $section['cta_label'] }}
                          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                      @endif
                    </div>
                  @endif

                  <div class="feature-outline-items" role="list">
                    @foreach ($section['capabilities'] as $capability)
                      <article class="feature-outline-item" role="listitem">
                        <h4>{{ $capability['title'] ?? '' }}</h4>
                        <p>{{ $capability['body'] ?? '' }}</p>
                      </article>
                    @endforeach
                  </div>
                </div>
              </article>
            @endforeach
          </div>
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

    {{-- ══════════════ CTA (hardware-style banner) ══════════════ --}}
    <section class="hwp-cta">
      <div class="hwp-pricing-shell">
        <div class="hwp-pricing-head">
          <h2 class="hwp-pricing-title">{{ content_text('features.cta.title', 'Ready to run your shop 1% better') }}</h2>
          <a href="{{ route('pricing.show') }}" class="hwp-pricing-link">
            See pricing
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
        <p class="hwp-pricing-note">{{ content_text('features.cta.subtitle', 'Download SkelApp and start tracking every sale, every product, every customer — all from your phone.') }}</p>
        <div class="hwp-hero-actions">
          <a href="{{ content('features.cta.primary_url', '#') }}" class="hwp-hero-btn hwp-hero-btn--solid" data-download-modal>{{ content('features.cta.primary_label', 'Download Now') }}</a>
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

    (function () {
      var links = Array.prototype.slice.call(document.querySelectorAll('[data-feature-nav-link]'));
      var sections = Array.prototype.slice.call(document.querySelectorAll('[data-feature-section]'));
      if (!links.length || !sections.length) return;

      var setActive = function (id) {
        links.forEach(function (link) {
          var isActive = link.getAttribute('data-feature-nav-link') === id;
          link.classList.toggle('is-active', isActive);
          link.setAttribute('aria-current', isActive ? 'true' : 'false');
        });
      };

      var hash = window.location.hash ? window.location.hash.slice(1) : '';
      setActive(hash || sections[0].id);

      if (!('IntersectionObserver' in window)) return;

      var observer = new IntersectionObserver(function (entries) {
        var visible = entries
          .filter(function (entry) { return entry.isIntersecting; })
          .sort(function (a, b) { return b.intersectionRatio - a.intersectionRatio; });

        if (visible[0] && visible[0].target && visible[0].target.id) {
          setActive(visible[0].target.id);
        }
      }, {
        rootMargin: '-18% 0px -58% 0px',
        threshold: [0.16, 0.35, 0.6],
      });

      sections.forEach(function (section) { observer.observe(section); });
    })();
  </script>
</body>
</html>
