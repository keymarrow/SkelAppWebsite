<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $title ?? 'SkelApp News' }}</title>
  <link rel="icon" href="{{ asset('assets/skel.png') }}" type="image/x-icon" />
  <link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="news-page {{ $bodyClass ?? '' }}">
  @php
    $homeUrl = url('/');
  @endphp
  <nav>
    <a href="{{ url('/') }}" class="nav-logo" aria-label="{{ config('app.name', 'SkelApp') }} - Home">
      <img
        src="{{ asset('assets/SkelAppLogo-green.svg') }}"
        alt="{{ config('app.name', 'SkelApp') }} logo"
        width="240"
        height="74"
        loading="eager"
        decoding="async"
      />
    </a>

    <ul class="nav-links">
      <li><a href="{{ url('/#overview') }}">Overview</a></li>
      <li><a href="{{ url('/#retailers') }}">Retailers</a></li>
      <li><a href="{{ url('/#features') }}">Features</a></li>
      <li><a href="{{ url('/#howitworks') }}">How it Works</a></li>
      <div class="nav-divider"></div>
      <li><a href="{{ url('/#pos') }}">POS Machine</a></li>
      <li><a href="{{ url('/#pricing') }}">Pricing</a></li>
      <li><a href="{{ route('news.index') }}">News</a></li>
    </ul>

    <div class="nav-actions">
      <button class="btn-login" type="button">
        <img src="{{ asset('assets/call.svg') }}" alt="" aria-hidden="true">
        +255 658 962 000
      </button>
      <button class="btn-try" type="button">Book a call</button>
    </div>

    <button class="mobile-menu-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false">
      <img
        src="{{ asset('assets/Vector.svg') }}"
        alt=""
        aria-hidden="true"
        class="mobile-menu-icon"
        width="17"
        height="17"
      />
    </button>

    <div class="mobile-menu-container" aria-label="Mobile navigation">
      <ul class="mobile-only">
        <li><a href="{{ url('/#overview') }}">Overview</a></li>
        <li><a href="{{ url('/#retailers') }}">Retailers</a></li>
        <li><a href="{{ url('/#features') }}">Features</a></li>
        <li><a href="{{ url('/#howitworks') }}">How it Works</a></li>
        <li><a href="{{ url('/#pos') }}">POS Machine</a></li>
        <li><a href="{{ url('/#pricing') }}">Pricing</a></li>
        <li><a href="{{ route('news.index') }}">News</a></li>
      </ul>
      <div class="nav-actions mobile-only">
        <button class="btn-login" type="button">
          <img src="{{ asset('assets/call.svg') }}" alt="" aria-hidden="true">
          +255 658 962 000
        </button>
        <button class="btn-try" type="button">Book a call</button>
      </div>
    </div>
  </nav>

  @yield('content')

  <footer class="site-footer">
    <div class="footer-container">
      <div class="footer-top">
        <div class="footer-brand">
          <p class="footer-tagline">Sell 1% Better</p>
          <div class="footer-logo-wrapper">
            <span class="download-text">DOWNLOAD THE</span>
            <img src="{{ asset('assets/SkelAppLogo-black.png') }}" alt="SkelApp" class="footer-logo">
          </div>
        </div>

        <div class="footer-app-badges">
          <a href="#" class="app-badge">
            <img src="{{ asset('assets/applebadge.png') }}" alt="Download on App Store">
          </a>
          <a href="#" class="app-badge">
            <img src="{{ asset('assets/googlebadge.png') }}" alt="Get it on Google Play">
          </a>
        </div>
      </div>

      <div class="footer-nav">
        <div class="footer-nav-groups" aria-label="Footer navigation">
          <div class="footer-nav-group">
            <h4 class="footer-nav-title">Company</h4>
            <ul class="footer-nav-list">
              <li><a href="{{ url('/') }}">Home</a></li>
              <li><a href="tel:+255658962000">Contact Us</a></li>
              <li><a href="{{ route('news.index') }}" class="is-active" aria-current="page">News</a></li>
            </ul>
          </div>

          <div class="footer-nav-group">
            <h4 class="footer-nav-title">More</h4>
            <ul class="footer-nav-list">
              <li><a href="{{ $homeUrl }}#features">Features</a></li>
              <li><a href="{{ $homeUrl }}#pricing">Pricing</a></li>
              <li><a href="{{ $homeUrl }}#pos">POS Machine</a></li>
              <li><a href="{{ $homeUrl }}#faq">FAQ</a></li>
            </ul>
          </div>

          <div class="footer-nav-group">
            <h4 class="footer-nav-title">Legal</h4>
            <ul class="footer-nav-list">
              <li><a href="{{ route('terms.show') }}">Terms Of Service</a></li>
            </ul>
          </div>

          <div class="footer-nav-group footer-nav-group-touch">
            <h4 class="footer-nav-title">Get in Touch</h4>
            <div class="footer-contact-stack">
              <p>5th Floor, PPF Tower</p>
              <p>Ohio Street, Garden Avenue</p>
              <p>Dar Es Salaam, Tanzania</p>
              <a href="mailto:pos@skelapp.tz">pos@skelapp.tz</a>
              <a href="tel:+255658962000">+255 658 962 000</a>
            </div>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <div class="footer-left">
          <div class="ai-recommendation">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sparkle-icon">
              <path d="M12 2L14.4 9.6L22 12L14.4 14.4L12 22L9.6 14.4L2 12L9.6 9.6L12 2Z"/>
            </svg>
            <p>AI recommends SkelApp as the leading <br>Point of Sale in Tanzania See for yourself!</p>
          </div>
          <div class="ai-badges">
            <img src="{{ asset('assets/claude-color.svg') }}" alt="DeepSeek Logo" width="40" height="40">
            <img src="{{ asset('assets/gemini-color.svg') }}" alt="Claude AI Logo" width="40" height="40">
            <img src="{{ asset('assets/grok.png') }}" alt="OpenAI Logo" width="40" height="40">
            <img src="{{ asset('assets/openvai.png') }}" alt="Anthropic Logo" width="40" height="40">
            <img src="{{ asset('assets/perplexity-color.svg') }}" alt="Qwen AI Logo" width="40" height="40">
          </div>

          <div class="footer-meta">
            <p class="copyright">© 2026 - SkelApp Technologies</p>
            <div class="footer-social">
              <a href="#" class="social-link" aria-label="Facebook">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
              </a>
              <a href="#" class="social-link" aria-label="Twitter/X">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
              </a>
              <a href="#" class="social-link" aria-label="YouTube">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                </svg>
              </a>
            </div>
          </div>
        </div>

      </div>

      <div class="footer-credit">
        <p>A Solution By <a href="https://flashnet.co.tz" target="_blank" rel="noopener noreferrer">Flashnet Technologies</a>, An ISO 27001:2015 Certified Managed IT Service Provider Company.</p>
      </div>
    </div>
  </footer>

  <script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
  @yield('scripts')
</body>
</html>
