<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $title ?? 'SkelApp News' }}</title>
  <meta name="description" content="{{ $metaDescription ?? 'SkelApp news, retail insights, and product updates.' }}" />
  @include('partials.seo', [
    'seoTitle' => $title ?? 'SkelApp News',
    'seoDescription' => $metaDescription ?? 'SkelApp news, retail insights, and product updates.',
    'seoCanonical' => $canonicalUrl ?? url()->current(),
    'seoType' => $seoType ?? 'website',
    'seoImage' => $seoImage ?? content_image('home.hero.background_image_desktop', asset('assets/HeroImage.webp')),
    'seoPageType' => $seoPageType ?? 'CollectionPage',
    'seoBreadcrumbs' => $seoBreadcrumbs ?? [
      ['name' => 'Home', 'url' => url('/')],
      ['name' => 'News', 'url' => route('news.index')],
    ],
    'seoArticle' => $seoArticle ?? null,
  ])
  <link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="news-page {{ $bodyClass ?? '' }}">
  @php
    $homeUrl = url('/');
  @endphp
  @include('partials.site-nav')

  @yield('content')

  @include('partials.site-footer')

  <script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
  @yield('scripts')
</body>
</html>
