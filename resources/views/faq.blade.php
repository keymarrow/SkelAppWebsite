@php
  $homeUrl = url('/');
  $faqGroups = content_list('faq.page.groups', []);
  $seoFaqs = collect($faqGroups)
    ->flatMap(fn ($group) => $group['questions'] ?? [])
    ->map(fn ($item) => [
      'question' => $item['question'] ?? '',
      'answer' => $item['answer'] ?? '',
    ])
    ->filter(fn ($item) => trim((string) $item['question']) !== '' && trim((string) $item['answer']) !== '')
    ->values()
    ->all();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ content('faq.meta.title', 'FAQ – SkelApp') }}</title>
  <meta name="description" content="{{ content('faq.meta.description') }}">
  @include('partials.seo', [
    'seoTitle' => content('faq.meta.title', 'FAQ – SkelApp'),
    'seoDescription' => content('faq.meta.description'),
    'seoPageType' => 'WebPage',
    'seoFaqs' => $seoFaqs,
    'seoBreadcrumbs' => [
      ['name' => 'Home', 'url' => url('/')],
      ['name' => 'FAQ', 'url' => route('faq.show')],
    ],
  ])
  <link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="faq-page-body">
  @include('partials.site-nav')

  <main class="faq-page">
    <div class="faq-page-shell">
      <aside class="faq-page-sidebar">
        <h1 class="faq-page-title {{ content_typography_class('faq.page.title') }}" style="{{ content_typography_vars('faq.page.title') }}">{{ content_text('faq.page.title', 'FAQs') }}</h1>

        <div class="faq-page-categories" role="navigation" aria-label="FAQ categories">
          @foreach ($faqGroups as $group)
            <a
              href="#{{ $group['id'] ?? '' }}"
              class="faq-page-category-link{{ $loop->first ? ' is-active' : '' }}"
              data-faq-nav-link
              aria-current="{{ $loop->first ? 'location' : 'false' }}"
            >
              {{ $group['label'] ?? '' }}
            </a>
          @endforeach
        </div>
      </aside>

      <div class="faq-page-content">
        @foreach ($faqGroups as $group)
          <section
            id="{{ $group['id'] ?? '' }}"
            class="faq-page-section"
            data-faq-page-section
            aria-labelledby="{{ ($group['id'] ?? '') . '-heading' }}"
          >
            <header class="faq-page-section-header">
              <h2 id="{{ ($group['id'] ?? '') . '-heading' }}" class="faq-page-section-heading">{{ $group['label'] ?? '' }}</h2>
            </header>

            <div class="faq-page-accordion" data-faq-accordion-group>
              @foreach ($group['questions'] ?? [] as $item)
                <article class="faq-item">
                  <button class="faq-question" type="button" aria-expanded="false">
                    <span>{{ $item['question'] ?? '' }}</span>
                    <svg class="faq-icon" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                      <line x1="10" y1="4" x2="10" y2="16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                      <line x1="4" y1="10" x2="16" y2="10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                  </button>
                  <div class="faq-answer">
                    <p>{{ $item['answer'] ?? '' }}</p>
                  </div>
                </article>
              @endforeach
            </div>
          </section>
        @endforeach
      </div>
    </div>
  </main>

  @include('partials.site-footer')

  <script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
</body>
</html>
