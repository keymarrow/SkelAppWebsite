@php
  $homeUrl = url('/');
  $termsIntros = content_list('terms.intro_paragraphs', []);
  $termsSections = content_list('terms.sections', []);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>{{ content('terms.meta.title', 'Terms of Service | SkelApp') }}</title>
<meta name="description" content="{{ content('terms.meta.description') }}">
<link rel="icon" href="{{ content_image('global.brand.favicon', asset('assets/skel.svg')) }}" type="image/x-icon" />
<link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="terms-page">
@include('partials.site-nav')

<main class="terms-main">
  <section class="terms-hero">
    <h1>{{ content('terms.hero.heading', 'Terms of Service') }}</h1>
    <p class="terms-updated">{{ content('terms.hero.last_updated') }}</p>
  </section>

  <section class="terms-card">
    <div class="terms-intro">
      @foreach ($termsIntros as $para)
        @php $text = is_array($para) ? ($para['value'] ?? '') : $para; @endphp
        <p>{{ $text }}</p>
      @endforeach
    </div>

    @foreach ($termsSections as $section)
      <section class="terms-section">
        <h2>{{ $section['title'] ?? '' }}</h2>

        @if (!empty($section['intro']))
          <p>{{ $section['intro'] }}</p>
        @endif

        @if (!empty($section['bullets']))
          <ul class="terms-list">
            @foreach ($section['bullets'] as $bullet)
              <li>
                @if (!empty($bullet['label']))
                  <strong>{{ $bullet['label'] }}</strong> — {{ $bullet['text'] ?? '' }}
                @else
                  {{ $bullet['text'] ?? '' }}
                @endif
              </li>
            @endforeach
          </ul>
        @endif
      </section>
    @endforeach
  </section>
</main>

@include('partials.site-footer')

  <script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
</body>
</html>
