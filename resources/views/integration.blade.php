@php
  use App\Support\CmsCatalogs;

  $items = collect(content_list('integrations.items', CmsCatalogs::integrationItems()))
    ->filter(fn ($item) => is_array($item) && filled($item['slug'] ?? null))
    ->mapWithKeys(fn ($item) => [(string) $item['slug'] => $item])
    ->all();
  $slug = $slug ?? array_key_first($items);
  $integration = $integration ?? ($items[$slug] ?? []);
  $features = !empty($integration['features']) && is_array($integration['features'])
    ? $integration['features']
    : CmsCatalogs::parseTitleBodyLines($integration['features_text'] ?? null);
  $faq = !empty($integration['faq']) && is_array($integration['faq'])
    ? $integration['faq']
    : CmsCatalogs::parseQuestionAnswerLines($integration['faq_text'] ?? null);
  $interestErrors = $errors->getBag('integrationInterest');
  $featureIcons = [
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h8"/><path d="M4 17h16"/><path d="M14 7h6"/><circle cx="12" cy="7" r="2.5"/><circle cx="8" cy="17" r="2.5"/></svg>',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 11 18-8-8 18-2-8-8-2Z"/><path d="m11 13 4-4"/></svg>',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13 2 4 14h7l-1 8 9-12h-7l1-8Z"/></svg>',
    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="3"/><path d="M7 12h10"/><path d="M7 9h4"/><path d="M7 15h6"/></svg>',
  ];
  $interestProfiles = content_list('integration.interest.avatars', [
    ['src' => 'assets/client.png', 'alt' => 'SkelApp merchant profile 1', 'position' => '50% 26%'],
    ['src' => 'assets/pix.jpeg', 'alt' => 'SkelApp merchant profile 2', 'position' => '48% 24%'],
    ['src' => 'assets/attendants.png', 'alt' => 'SkelApp merchant profile 3', 'position' => '50% 22%'],
    ['src' => 'assets/sto.jpeg', 'alt' => 'SkelApp merchant profile 4', 'position' => '58% 18%'],
    ['src' => 'assets/local.jpeg', 'alt' => 'SkelApp merchant profile 5', 'position' => '72% 20%'],
  ]);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ ($integration['name'] ?? 'Integration').' '.content('integration.meta.title_suffix', 'integration - SkelApp') }}</title>
  <meta name="description" content="{{ $integration['summary'] ?? content('integration.meta.description_fallback', 'SkelApp integration details.') }}">
  @include('partials.seo', [
    'seoTitle' => ($integration['name'] ?? 'Integration').' '.content('integration.meta.title_suffix', 'integration - SkelApp'),
    'seoDescription' => $integration['summary'] ?? content('integration.meta.description_fallback', 'SkelApp integration details.'),
    'seoImage' => cms_image($integration['hero_image'] ?? null, asset('assets/devicemockup.webp')),
    'seoPageType' => 'WebPage',
    'seoAbout' => array_values(array_filter([
      $integration['name'] ?? null,
      $integration['category'] ?? 'Integration',
    ])),
    'seoFaqs' => $faq,
    'seoBreadcrumbs' => [
      ['name' => 'Home', 'url' => url('/')],
      ['name' => 'Integrations', 'url' => route('integrations.index')],
      ['name' => $integration['name'] ?? 'Integration', 'url' => route('integrations.show', $slug)],
    ],
  ])
  <link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="integration-detail-body">
  @include('partials.site-nav')

  <main class="integration-detail-page">
    <section class="integration-detail-hero">
      <div class="integration-detail-copy">
        <span class="integration-logo-shell integration-detail-logo" style="--integration-color: {{ $integration['color'] ?? '#4AAD77' }}">
          <img src="{{ cms_image($integration['logo'] ?? null, asset('assets/skel.svg')) }}" alt="{{ $integration['name'] ?? '' }} logo" loading="eager" decoding="async">
        </span>
        <p class="integration-detail-kicker">{{ $integration['name'] ?? '' }} {{ content('integration.hero.kicker_suffix', 'integration') }}</p>
        <h1>{{ $integration['detail_title'] ?? ($integration['name'] ?? 'Integration') }}</h1>
        <p>{{ $integration['detail_copy'] ?? ($integration['summary'] ?? '') }}</p>
        <div class="integration-detail-actions">
          <a href="{{ route('contact.show') }}" class="integration-detail-btn integration-detail-btn--solid">{{ content('integration.hero.primary_label', 'Talk to an expert') }}</a>
          @if (! empty($integration['website_url']))
            <a href="{{ $integration['website_url'] }}" class="integration-detail-btn integration-detail-btn--text" target="_blank" rel="noopener">
              {{ content('integration.hero.secondary_label', 'Visit website') }}
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M8 7h9v9"/></svg>
            </a>
          @endif
        </div>
      </div>

      <div class="integration-detail-art">
        <div class="integration-detail-device">
          <img src="{{ cms_image($integration['hero_image'] ?? null, asset('assets/devicemockup.webp')) }}" alt="{{ $integration['name'] ?? '' }} integration preview" loading="eager" decoding="async">
        </div>
      </div>
    </section>

    <section class="integration-features" id="integration-interest">
      <div class="integration-feature-grid">
        <div class="integration-feature-content">
          <div class="integration-feature-intro">
            <p class="integration-feature-kicker">{{ content('integration.features.kicker', 'Key features') }}</p>
            <h2>{{ content('integration.features.title_prefix', 'What you can expect from') }} {{ $integration['name'] ?? 'this integration' }}</h2>
          </div>

          <div class="integration-feature-list">
            @foreach ($features as $feature)
              <article class="integration-feature-card">
                <span class="integration-feature-icon">{!! $featureIcons[$loop->index % count($featureIcons)] !!}</span>
                <div class="integration-feature-card-copy">
                  <h3>{{ $feature['title'] ?? '' }}</h3>
                  <p>{{ $feature['body'] ?? '' }}</p>
                </div>
              </article>
            @endforeach
          </div>
        </div>

        <aside class="integration-interest-panel">
          <p class="integration-interest-kicker">{{ content('integration.interest.kicker', 'Coming soon') }}</p>
          <h3>{{ content('integration.interest.title_prefix', 'Join the waitlist for') }} {{ $integration['name'] ?? 'this integration' }}</h3>
          <p class="integration-interest-copy">{{ content('integration.interest.copy', 'Share your details and we’ll let you know as soon as this integration becomes available for your team.') }}</p>
          <div class="integration-interest-avatars" aria-label="SkelApp users waiting for this integration">
            @foreach ($interestProfiles as $profile)
              <span class="integration-interest-avatar" style="--avatar-position: {{ $profile['position'] }}">
                <img src="{{ cms_image($profile['src'] ?? null, asset('assets/client.png')) }}" alt="{{ $profile['alt'] ?? '' }}" loading="lazy" decoding="async">
              </span>
            @endforeach
          </div>

          @if (session('integration_interest_success'))
            <div class="integration-interest-flash integration-interest-flash--success" role="status">
              {{ session('integration_interest_success') }}
            </div>
          @endif

          @if ($interestErrors->any())
            <div class="integration-interest-flash integration-interest-flash--error" role="alert">
              Please correct the highlighted fields and try again.
            </div>
          @endif

          <form method="POST" action="{{ route('integrations.interest', $slug) }}" class="integration-interest-form" novalidate>
            @csrf

            <div class="integration-interest-field">
              <label for="integration_full_name">{{ content('integration.form.full_name_label', 'Full name') }}<span class="req">*</span></label>
              <input
                type="text"
                id="integration_full_name"
                name="full_name"
                placeholder="{{ content('integration.form.full_name_placeholder', 'Provide your full name') }}"
                value="{{ old('full_name') }}"
                autocomplete="name"
                required
              >
              @error('full_name', 'integrationInterest')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="integration-interest-field">
              <label for="integration_email">{{ content('integration.form.email_label', 'Email') }}<span class="req">*</span></label>
              <input
                type="email"
                id="integration_email"
                name="email"
                placeholder="{{ content('integration.form.email_placeholder', 'Provide your email address') }}"
                value="{{ old('email') }}"
                autocomplete="email"
                required
              >
              @error('email', 'integrationInterest')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="integration-interest-submit">{{ content('integration.form.submit_label', 'Join waitlist') }}</button>
            <p class="integration-interest-note">{{ content('integration.form.note_prefix', 'We’ll only use these details to contact you about') }} {{ $integration['name'] ?? 'this integration' }}.</p>
          </form>
        </aside>
      </div>
    </section>

    @if (! empty($faq))
      <section class="faq-section integration-detail-faq">
        <div class="faq-container">
          <div class="faq-header">
            <h2>{{ $integration['name'] ?? 'Integration' }} {{ content('integration.faq.title_suffix', 'questions') }}</h2>
            <a href="{{ route('faq.show') }}" class="faq-read-more">{{ content('integration.faq.read_more_label', 'Read more') }}</a>
          </div>
          <div class="faq-layout">
            <p class="faq-subtitle">{{ $integration['category'] ?? 'Integration' }} {{ content('integration.faq.subtitle_suffix', 'FAQ') }}</p>
            <div class="faq-list">
              @foreach ($faq as $i => $item)
                <div class="faq-item @if($i === 0) active @endif">
                  <button class="faq-question" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                    <span>{{ $item['q'] }}</span>
                    <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="12" y1="5" x2="12" y2="19"></line>
                      <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                  </button>
                  <div class="faq-answer">
                    <p>{{ $item['a'] }}</p>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </section>
    @endif

    <div class="integration-back">
      <a href="{{ route('integrations.index') }}">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        {{ content('integration.back_label', 'Back to integration page') }}
      </a>
    </div>
  </main>

  @include('partials.site-footer')
  <script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
</body>
</html>
