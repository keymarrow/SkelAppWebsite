@php
  $brandName = config('app.name');
  if (blank($brandName) || $brandName === 'Laravel') {
      $brandName = 'SkelApp';
  }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>404 | {{ $brandName }}</title>
  <link rel="icon" href="{{ asset('assets/skel.png') }}" type="image/x-icon" />
  <link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="error-404-page">
  <main class="error-404-shell">
    <a href="{{ url('/') }}" class="error-404-brand" aria-label="{{ $brandName }} home">
      <img
        src="{{ asset('assets/SkelAppLogo-green.svg') }}"
        alt="{{ $brandName }} logo"
        width="240"
        height="74"
        loading="eager"
        decoding="async"
      />
    </a>

    <section class="error-404-card" aria-labelledby="error-404-title">
      <p class="error-404-code">404</p>
      <h1 id="error-404-title">A wrong turn can still lead to a better restart.</h1>
      <p class="error-404-copy">
        As a business owner, you sometimes miss your way. That is okay.
        Getting back home and starting again is often where better systems begin.
      </p>

      <a href="{{ url('/') }}" class="error-404-button">
        Go Back Home
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
      </a>
    </section>
  </main>
</body>
</html>
