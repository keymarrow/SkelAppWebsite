<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $title ?? 'SkelApp News' }}</title>
  <meta name="description" content="{{ $metaDescription ?? 'SkelApp news, retail insights, and product updates.' }}" />
  @if (!empty($canonicalUrl))
    <link rel="canonical" href="{{ $canonicalUrl }}" />
    <meta property="og:url" content="{{ $canonicalUrl }}" />
  @endif
  <meta property="og:title" content="{{ $title ?? 'SkelApp News' }}" />
  <meta property="og:description" content="{{ $metaDescription ?? 'SkelApp news, retail insights, and product updates.' }}" />
  <meta property="og:type" content="website" />
  <link rel="icon" href="{{ asset('assets/skel.png') }}" type="image/x-icon" />
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
