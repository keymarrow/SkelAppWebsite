@php
  $homeUrl = url('/');
  $contactCards = content_list('contact.cards', []);
  $contactSocials = content_list('global.brand.social', []);
  if ($contactSocials === []) {
      $contactSocials = content_list('contact.socials', []);
  }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>{{ content('contact.meta.title', 'Contact Us') }}</title>
<meta name="description" content="{{ content('contact.meta.description') }}">
@include('partials.seo', [
  'seoTitle' => content('contact.meta.title', 'Contact Us'),
  'seoDescription' => content('contact.meta.description'),
  'seoPageType' => 'ContactPage',
  'seoBreadcrumbs' => [
    ['name' => 'Home', 'url' => url('/')],
    ['name' => 'Contact', 'url' => route('contact.show')],
  ],
])
<link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="contact-page">

{{-- ── Nav ── --}}
@include('partials.site-nav')

{{-- ── Page ── --}}
<main class="contact-page">

  <h1 class="contact-hero-heading {{ content_typography_class('contact.hero.heading') }}" style="{{ content_typography_vars('contact.hero.heading') }}">{{ content_text('contact.hero.heading', "Ready to get started? We're just a message away!") }}</h1>

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
      <h2 class="{{ content_typography_class('contact.info.heading') }}" style="{{ content_typography_vars('contact.info.heading') }}">{{ content_text('contact.info.heading', 'Contact Us:') }}</h2>
      <p class="{{ content_typography_class('contact.info.intro') }}" style="{{ content_typography_vars('contact.info.intro') }}">{{ content_text('contact.info.intro') }}</p>

      <div class="contact-cards">
        @foreach ($contactCards as $card)
          @php
            $type = $card['type'] ?? 'email';
            $value = $card['value'] ?? '';
            if ($type === 'email') {
              $href = 'mailto:' . $value;
            } else {
              $href = 'tel:' . preg_replace('/\s+/', '', $value);
            }
          @endphp
          <a href="{{ $href }}" class="contact-card">
            <div class="contact-card-icon">
              @if ($type === 'email')
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <rect width="20" height="16" x="2" y="4" rx="2"/>
                  <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                </svg>
              @else
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                  <circle cx="12" cy="8" r="4"/>
                  <path d="M4 20c0-4 3.582-7 8-7s8 3 8 7"/>
                </svg>
              @endif
            </div>
            <div class="contact-card-body">
              <span class="contact-card-label">{{ $card['label'] ?? '' }}</span>
              <span class="contact-card-value">{{ $value }}</span>
            </div>
          </a>
        @endforeach
      </div>

      <p class="contact-social-heading {{ content_typography_class('contact.info.social_heading') }}" style="{{ content_typography_vars('contact.info.social_heading') }}">{{ content_text('contact.info.social_heading') }}</p>

      <div class="contact-social-icons">
        @include('partials.social-links', ['socialLinks' => $contactSocials, 'linkClass' => 'contact-social-link'])
      </div>
    </aside>

    {{-- ── Right: form ── --}}
    <div class="contact-form-card">
      <h2 class="{{ content_typography_class('contact.form.heading') }}" style="{{ content_typography_vars('contact.form.heading') }}">{{ content_text('contact.form.heading', 'Book a Demo') }}</h2>

      <form method="POST" action="{{ route('contact.send') }}" novalidate>
        @csrf

        <div class="form-grid-2">
          <div class="form-field">
            <label for="first_name">{{ content('contact.form.first_name_label', 'First Name') }}<span class="req">*</span></label>
            <input
              type="text"
              id="first_name"
              name="first_name"
              placeholder="{{ content('contact.form.first_name_placeholder', 'Enter your first name') }}"
              value="{{ old('first_name') }}"
              autocomplete="given-name"
              required
            >
            @error('first_name')<span class="field-error">{{ $message }}</span>@enderror
          </div>

          <div class="form-field">
            <label for="last_name">{{ content('contact.form.last_name_label', 'Last Name') }}<span class="req">*</span></label>
            <input
              type="text"
              id="last_name"
              name="last_name"
              placeholder="{{ content('contact.form.last_name_placeholder', 'Enter your last name') }}"
              value="{{ old('last_name') }}"
              autocomplete="family-name"
              required
            >
            @error('last_name')<span class="field-error">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="form-grid-2">
          <div class="form-field">
            <label for="email">{{ content('contact.form.email_label', 'Email') }}<span class="req">*</span></label>
            <input
              type="email"
              id="email"
              name="email"
              placeholder="{{ content('contact.form.email_placeholder', 'Enter your email address') }}"
              value="{{ old('email') }}"
              autocomplete="email"
              required
            >
            @error('email')<span class="field-error">{{ $message }}</span>@enderror
          </div>

          <div class="form-field">
            <label for="phone">{{ content('contact.form.phone_label', 'Phone Number') }}<span class="req">*</span></label>
            <input
              type="tel"
              id="phone"
              name="phone"
              placeholder="{{ content('contact.form.phone_placeholder', 'Enter your phone number') }}"
              value="{{ old('phone') }}"
              autocomplete="tel"
              required
            >
            @error('phone')<span class="field-error">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="form-field">
          <label for="company">{{ content('contact.form.company_label', 'Business Name') }}<span class="req">*</span></label>
          <input
            type="text"
            id="company"
            name="company"
            placeholder="{{ content('contact.form.company_placeholder', 'Enter your business name') }}"
            value="{{ old('company') }}"
            autocomplete="organization"
            required
          >
          @error('company')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-actions">
          <a href="{{ url('/') }}" class="btn-cancel">{{ content('contact.form.cancel_label', 'Cancel') }}</a>
          <button type="submit" class="btn-submit">{{ content('contact.form.submit_label', 'Submit') }}</button>
        </div>

      </form>
    </div>

  </div>
</main>

@include('partials.site-footer')

<script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
</body>
</html>
