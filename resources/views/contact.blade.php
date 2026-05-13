@php $homeUrl = url('/'); @endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Contact Us – SkelApp</title>
<link rel="icon" href="{{ asset('assets/skel.svg') }}" type="image/x-icon" />
<link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body>

{{-- ── Nav ── --}}
@include('partials.site-nav')

{{-- ── Page ── --}}
<main class="contact-page">

  <h1 class="contact-hero-heading">Ready to get started? We're just a message away!</h1>

  {{-- Flash messages --}}
  @if (session('success'))
    <div class="flash-success" role="alert" style="max-width:1100px;margin:0 auto 24px;padding-left:24px;padding-right:24px;">
      {{ session('success') }}
    </div>
  @endif

  @if ($errors->any())
    <div class="flash-error" role="alert" style="max-width:1100px;margin:0 auto 24px;padding-left:24px;padding-right:24px;">
      Please fix the errors below and try again.
    </div>
  @endif

  <div class="contact-layout">

    {{-- ── Left: contact info ── --}}
    <aside class="contact-info-panel">
      <h2>Contact Us:</h2>
      <p>To get more information about us, you can reach us using the following:</p>

      <div class="contact-cards">

        {{-- Email --}}
        <a href="mailto:pos@skelapp.tz" class="contact-card">
          <div class="contact-card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect width="20" height="16" x="2" y="4" rx="2"/>
              <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
            </svg>
          </div>
          <div class="contact-card-body">
            <span class="contact-card-label">Email Us</span>
            <span class="contact-card-value">pos@skelapp.tz</span>
          </div>
        </a>

        {{-- Bebe --}}
        <a href="tel:+255658962000" class="contact-card">
          <div class="contact-card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
              <circle cx="12" cy="8" r="4"/>
              <path d="M4 20c0-4 3.582-7 8-7s8 3 8 7"/>
            </svg>
          </div>
          <div class="contact-card-body">
            <span class="contact-card-label">Talk or chat with PK</span>
            <span class="contact-card-value">+255 658 962 000</span>
          </div>
        </a>

        {{-- Fafa --}}
        <a href="tel:+255659310909" class="contact-card">
          <div class="contact-card-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
              <circle cx="12" cy="8" r="4"/>
              <path d="M4 20c0-4 3.582-7 8-7s8 3 8 7"/>
            </svg>
          </div>
          <div class="contact-card-body">
            <span class="contact-card-label">Talk or chat with JP</span>
            <span class="contact-card-value">+255 659 310 909</span>
          </div>
        </a>

      </div>

      <p class="contact-social-heading">Learn More about us through our social media channels:</p>

      <div class="contact-social-icons">
        {{-- TikTok --}}
        <a href="#" class="contact-social-link" aria-label="TikTok">
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.79 1.52V6.75a4.85 4.85 0 0 1-1.02-.06z"/>
          </svg>
        </a>
        {{-- Facebook --}}
        <a href="#" class="contact-social-link" aria-label="Facebook">
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
          </svg>
        </a>
        {{-- Instagram --}}
        <a href="#" class="contact-social-link" aria-label="Instagram">
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
          </svg>
        </a>
        {{-- LinkedIn --}}
        <a href="#" class="contact-social-link" aria-label="LinkedIn">
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
          </svg>
        </a>
      </div>
    </aside>

    {{-- ── Right: form ── --}}
    <div class="contact-form-card">
      <h2>Book a Demo</h2>

      <form method="POST" action="{{ route('contact.send') }}" novalidate>
        @csrf

        <div class="form-grid-2">
          <div class="form-field">
            <label for="first_name">First Name<span class="req">*</span></label>
            <input
              type="text"
              id="first_name"
              name="first_name"
              placeholder="Enter your first name"
              value="{{ old('first_name') }}"
              autocomplete="given-name"
              required
            >
            @error('first_name')<span class="field-error">{{ $message }}</span>@enderror
          </div>

          <div class="form-field">
            <label for="last_name">Last Name<span class="req">*</span></label>
            <input
              type="text"
              id="last_name"
              name="last_name"
              placeholder="Enter your last name"
              value="{{ old('last_name') }}"
              autocomplete="family-name"
              required
            >
            @error('last_name')<span class="field-error">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="form-grid-2">
          <div class="form-field">
            <label for="email">Email<span class="req">*</span></label>
            <input
              type="email"
              id="email"
              name="email"
              placeholder="Enter your email address"
              value="{{ old('email') }}"
              autocomplete="email"
              required
            >
            @error('email')<span class="field-error">{{ $message }}</span>@enderror
          </div>

          <div class="form-field">
            <label for="phone">Phone Number<span class="req">*</span></label>
            <input
              type="tel"
              id="phone"
              name="phone"
              placeholder="Enter your phone number"
              value="{{ old('phone') }}"
              autocomplete="tel"
              required
            >
            @error('phone')<span class="field-error">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="form-field">
          <label for="company">Company Name<span class="req">*</span></label>
          <input
            type="text"
            id="company"
            name="company"
            placeholder="Enter your company name"
            value="{{ old('company') }}"
            autocomplete="organization"
            required
          >
          @error('company')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-actions">
          <a href="{{ url('/') }}" class="btn-cancel">Cancel</a>
          <button type="submit" class="btn-submit">Submit</button>
        </div>

      </form>
    </div>

  </div>
</main>

<footer class="site-footer">
  <div class="footer-container">
    <div class="footer-top">
      <div class="footer-brand">
        <p class="footer-tagline">Sell 1% Better</p>
        <div class="footer-logo-wrapper">
          <span class="download-text">DOWNLOAD THE</span>
          <img src="{{ asset('assets/SkelAppLogo-black.png') }}" alt="SkelApp" class="footer-logo" loading="lazy" decoding="async">
        </div>
      </div>

      <div class="footer-app-badges">
        <a href="#" class="app-badge" aria-label="Download on App Store">
          <img src="{{ asset('assets/applebadge.png') }}" alt="Download on App Store" loading="lazy" decoding="async">
        </a>
        <a href="#" class="app-badge" aria-label="Get it on Google Play">
          <img src="{{ asset('assets/googlebadge.png') }}" alt="Get it on Google Play" loading="lazy" decoding="async">
        </a>
      </div>
    </div>

    <div class="footer-nav">
      <div class="footer-nav-groups" aria-label="Footer navigation">
        <div class="footer-nav-group">
          <h4 class="footer-nav-title">Company</h4>
          <ul class="footer-nav-list">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><a href="{{ route('contact.show') }}" aria-current="page">Contact Us</a></li>
            <li><a href="{{ route('news.index') }}">News</a></li>
          </ul>
        </div>

        <div class="footer-nav-group">
          <h4 class="footer-nav-title">More</h4>
          <ul class="footer-nav-list">
            <li><a href="{{ $homeUrl }}#features">Features</a></li>
            <li><a href="{{ $homeUrl }}#pricing">Pricing</a></li>
            <li><a href="{{ $homeUrl }}#pos">POS Machine</a></li>
            <li><a href="{{ route('faq.show') }}">FAQ</a></li>
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
          <img src="{{ asset('assets/claude-color.svg') }}" alt="Claude" width="40" height="40" loading="lazy" decoding="async">
          <img src="{{ asset('assets/gemini-color.svg') }}" alt="Gemini" width="40" height="40" loading="lazy" decoding="async">
          <img src="{{ asset('assets/grok.png') }}" alt="Grok" width="40" height="40" loading="lazy" decoding="async">
          <img src="{{ asset('assets/openvai.png') }}" alt="OpenAI" width="40" height="40" loading="lazy" decoding="async">
          <img src="{{ asset('assets/perplexity-color.svg') }}" alt="Perplexity" width="40" height="40" loading="lazy" decoding="async">
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
</body>
</html>
