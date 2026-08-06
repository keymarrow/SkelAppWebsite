@php
  $affiliatePage = config('affiliate_program', []);
  $application = content('affiliate-apply.application', $affiliatePage['application'] ?? []);
  $countryCodes = content_list('affiliate-apply.application.country_codes', $application['country_codes'] ?? ['+255']);
  $countries = content_list('affiliate-apply.application.countries', $application['countries'] ?? ['Tanzania']);
  $promotionMethods = content_list('affiliate-apply.application.promotion_methods', $application['promotion_methods'] ?? []);
  $discoverySources = content_list('affiliate-apply.application.discovery_sources', $application['discovery_sources'] ?? []);
  $captchaGlyphs = $captchaGlyphs ?? collect(str_split('SKEL24'))->map(
    fn (string $char, int $index) => [
      'char' => $char,
      'rotate' => [-14, -6, 4, -10, 8, 12][$index % 6],
      'shift' => [-2, 2, -1, 3, -2, 1][$index % 6],
    ]
  )->all();

  $firstNameLabel = content('affiliate-apply.form.first_name_label', 'First Name');
  $firstNamePlaceholder = content('affiliate-apply.form.first_name_placeholder', 'Enter your first name');
  $lastNameLabel = content('affiliate-apply.form.last_name_label', 'Last Name');
  $lastNamePlaceholder = content('affiliate-apply.form.last_name_placeholder', 'Enter your last name');
  $emailLabel = content('affiliate-apply.form.email_label', 'Email');
  $emailPlaceholder = content('affiliate-apply.form.email_placeholder', 'Enter your email address');
  $phoneLabel = content('affiliate-apply.form.phone_label', 'Phone Number');
  $phonePlaceholder = content('affiliate-apply.form.phone_placeholder', '712 345 678');
  $countryLabel = content('affiliate-apply.form.country_label', 'Country');
  $countryPlaceholder = content('affiliate-apply.form.country_placeholder', 'Select country');
  $primaryMethodLabel = content('affiliate-apply.form.primary_method_label', 'Primary Promotional Method');
  $primaryMethodPlaceholder = content('affiliate-apply.form.primary_method_placeholder', '-None-');
  $discoveryLabel = content('affiliate-apply.form.discovery_label', 'How did you hear about the program?');
  $discoveryPlaceholder = content('affiliate-apply.form.discovery_placeholder', 'Select one');
  $captchaLabel = content('affiliate-apply.form.captcha_label', 'Enter the captcha');
  $captchaPlaceholder = content('affiliate-apply.form.captcha_placeholder', 'Type the code shown below');
  $submitLabel = content('affiliate-apply.form.submit_label', 'Submit');
  $resetLabel = content('affiliate-apply.form.reset_label', 'Reset');
  $backLabel = content('affiliate-apply.form.back_label', 'Back');

  $termsUrl = route('terms.show');
  $privacyUrl = route('privacy.show');
  $landingUrl = route('affiliate.show');
  $refreshCaptchaUrl = route('affiliate.apply.show', ['refresh' => 1]);

  $agreementLabel = content('affiliate-apply.application.agreement_label', 'I accept the SkelApp affiliate agreement, Terms of Service and Privacy Policy.');
  $agreementLinkLabel = content('affiliate-apply.application.agreement_link_label', 'affiliate agreement');
  $termsLinkLabel = content('affiliate-apply.application.terms_link_label', 'Terms of Service');
  $privacyLinkLabel = content('affiliate-apply.application.privacy_link_label', 'Privacy Policy');
  $agreementLabelHtml = $agreementLabel;
  if (! str_contains($agreementLabelHtml, '<a')) {
    $agreementLabelHtml = str_replace(
      ['affiliate agreement', 'Terms of Service', 'Privacy Policy'],
      [
        '<a href="'.$termsUrl.'">'.$agreementLinkLabel.'</a>',
        '<a href="'.$termsUrl.'">'.$termsLinkLabel.'</a>',
        '<a href="'.$privacyUrl.'">'.$privacyLinkLabel.'</a>',
      ],
      e($agreementLabelHtml)
    );
  }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>{{ content('affiliate-apply.meta.title', content_text('affiliate-apply.application.title', 'Affiliate Application')) }}</title>
<meta name="description" content="{{ content('affiliate-apply.meta.description', $affiliatePage['meta']['description'] ?? 'Apply to join the SkelApp affiliate program.') }}">
@include('partials.seo', [
  'seoTitle' => content('affiliate-apply.meta.title', content_text('affiliate-apply.application.title', 'Affiliate Application')),
  'seoDescription' => content('affiliate-apply.meta.description', $affiliatePage['meta']['description'] ?? 'Apply to join the SkelApp affiliate program.'),
  'seoPageType' => 'WebPage',
  'seoBreadcrumbs' => [
    ['name' => 'Home', 'url' => url('/')],
    ['name' => 'Affiliate Program', 'url' => route('affiliate.show')],
    ['name' => 'Apply', 'url' => route('affiliate.apply.show')],
  ],
])
<link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="contact-page affiliate-application-page">
@include('partials.site-nav')

<main class="contact-page affiliate-application-main">
  <section class="affiliate-application-shell">
    <div class="affiliate-application-head">
      <h1 class="contact-hero-heading {{ content_typography_class('affiliate-apply.application.title') }}" style="{{ content_typography_vars('affiliate-apply.application.title') }}">{{ content_text('affiliate-apply.application.title', $application['title'] ?? 'Join the SkelApp Affiliate Program') }}</h1>
      <span class="affiliate-application-rule" aria-hidden="true"></span>
      <p class="affiliate-application-intro {{ content_typography_class('affiliate-apply.application.intro') }}" style="{{ content_typography_vars('affiliate-apply.application.intro') }}">{{ content_text('affiliate-apply.application.intro', $application['intro'] ?? '') }}</p>
    </div>

    @if (session('success'))
      <div class="flash-success" role="alert">
        {{ session('success') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="flash-error" role="alert">
        Please fix the errors below and try again.
      </div>
    @endif

    <div class="contact-form-card affiliate-application-card">
      <form method="POST" action="{{ route('affiliate.apply.submit') }}" novalidate>
        @csrf

        <div class="form-grid-2">
          <div class="form-field">
            <label for="first_name">{{ $firstNameLabel }}<span class="req">*</span></label>
            <input
              type="text"
              id="first_name"
              name="first_name"
              value="{{ old('first_name') }}"
              placeholder="{{ $firstNamePlaceholder }}"
              autocomplete="given-name"
              required
            >
            @error('first_name')<span class="field-error">{{ $message }}</span>@enderror
          </div>

          <div class="form-field">
            <label for="last_name">{{ $lastNameLabel }}<span class="req">*</span></label>
            <input
              type="text"
              id="last_name"
              name="last_name"
              value="{{ old('last_name') }}"
              placeholder="{{ $lastNamePlaceholder }}"
              autocomplete="family-name"
              required
            >
            @error('last_name')<span class="field-error">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="form-grid-2">
          <div class="form-field">
            <label for="email">{{ $emailLabel }}<span class="req">*</span></label>
            <input
              type="email"
              id="email"
              name="email"
              value="{{ old('email') }}"
              placeholder="{{ $emailPlaceholder }}"
              autocomplete="email"
              required
            >
            @if (!empty($application['email_helper']))
              <span class="form-helper">{{ $application['email_helper'] }}</span>
            @endif
            @error('email')<span class="field-error">{{ $message }}</span>@enderror
          </div>

          <div class="form-field">
            <label for="phone_number">{{ $phoneLabel }}<span class="req">*</span></label>
            <div class="affiliate-phone-grid">
              <div class="select-wrapper">
                <select id="phone_country_code" name="phone_country_code" required>
                  @foreach ($countryCodes as $countryCode)
                    <option value="{{ $countryCode }}" @selected(old('phone_country_code', '+255') === $countryCode)>{{ $countryCode }}</option>
                  @endforeach
                </select>
              </div>
              <input
                type="tel"
                id="phone_number"
                name="phone_number"
                value="{{ old('phone_number') }}"
                placeholder="{{ $phonePlaceholder }}"
                autocomplete="tel-national"
                inputmode="tel"
                required
              >
            </div>
            @if (!empty($application['phone_helper']))
              <span class="form-helper">{{ $application['phone_helper'] }}</span>
            @endif
            @error('phone_country_code')<span class="field-error">{{ $message }}</span>@enderror
            @error('phone_number')<span class="field-error">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="form-field">
          <label for="country">{{ $countryLabel }}<span class="req">*</span></label>
          <div class="select-wrapper">
            <select id="country" name="country" required>
              <option value="">{{ $countryPlaceholder }}</option>
              @foreach ($countries as $country)
                <option value="{{ $country }}" @selected(old('country') === $country)>{{ $country }}</option>
              @endforeach
            </select>
          </div>
          @error('country')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <section class="affiliate-form-section">
          <h2 class="affiliate-form-section-title">{{ $application['promotion_title'] ?? 'Promotion Method' }}</h2>
          <p class="affiliate-form-section-copy">{{ $application['promotion_intro'] ?? '' }}</p>

          <div class="form-field">
            <label for="primary_promotional_method">{{ $primaryMethodLabel }}<span class="req">*</span></label>
            <div class="select-wrapper">
              <select id="primary_promotional_method" name="primary_promotional_method" required>
                <option value="">{{ $primaryMethodPlaceholder }}</option>
                @foreach ($promotionMethods as $method)
                  <option value="{{ $method }}" @selected(old('primary_promotional_method') === $method)>{{ $method }}</option>
                @endforeach
              </select>
            </div>
            @error('primary_promotional_method')<span class="field-error">{{ $message }}</span>@enderror
          </div>

          <div class="form-field">
            <label for="hear_about_program">{{ $discoveryLabel }}<span class="req">*</span></label>
            <div class="select-wrapper">
              <select id="hear_about_program" name="hear_about_program" required>
                <option value="">{{ $discoveryPlaceholder }}</option>
                @foreach ($discoverySources as $source)
                  <option value="{{ $source }}" @selected(old('hear_about_program', 'Google') === $source)>{{ $source }}</option>
                @endforeach
              </select>
            </div>
            @error('hear_about_program')<span class="field-error">{{ $message }}</span>@enderror
          </div>

          <div class="form-field">
            <label for="marketing_details">{{ $application['marketing_details_label'] ?? 'Tell us about your audience or marketing capabilities' }}</label>
            <textarea
              id="marketing_details"
              name="marketing_details"
              placeholder="{{ $application['marketing_details_placeholder'] ?? 'Share your audience and how you plan to promote SkelApp.' }}"
              rows="6"
            >{{ old('marketing_details') }}</textarea>
            @error('marketing_details')<span class="field-error">{{ $message }}</span>@enderror
          </div>
        </section>

        <section class="affiliate-form-section affiliate-form-section--final">
          <div class="form-field">
            <label for="captcha">{{ $captchaLabel }}<span class="req">*</span></label>
            <input
              type="text"
              id="captcha"
              name="captcha"
              value=""
              placeholder="{{ $captchaPlaceholder }}"
              autocomplete="off"
              autocapitalize="characters"
              spellcheck="false"
              required
            >
            @error('captcha')<span class="field-error">{{ $message }}</span>@enderror
          </div>

          <div class="affiliate-captcha-row">
            <div class="affiliate-captcha-art" aria-label="Captcha code">
              @foreach ($captchaGlyphs as $glyph)
                <span
                  class="affiliate-captcha-glyph"
                  style="--captcha-rotate: {{ $glyph['rotate'] }}deg; --captcha-shift: {{ $glyph['shift'] }}px;"
                >{{ $glyph['char'] }}</span>
              @endforeach
            </div>
            <a href="{{ $refreshCaptchaUrl }}" class="affiliate-captcha-refresh" aria-label="Refresh captcha">
              <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M21 12a9 9 0 0 1-15.36 6.36M3 12A9 9 0 0 1 18.36 5.64" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>
                <path d="M7 5H3v4M21 19h-4v-4" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </a>
          </div>

          <div class="affiliate-checklist">
            <label class="affiliate-check">
              <input type="checkbox" name="accepts_agreement" value="1" @checked(old('accepts_agreement')) required>
              <span>
                {!! $agreementLabelHtml !!}<span class="req">*</span>
              </span>
            </label>
            @error('accepts_agreement')<span class="field-error">{{ $message }}</span>@enderror

            <label class="affiliate-check">
              <input type="checkbox" name="accepts_marketing" value="1" @checked(old('accepts_marketing'))>
              <span>{{ $application['marketing_label'] ?? 'I agree to receive emails and promotional communications.' }}</span>
            </label>

            <label class="affiliate-check">
              <input type="checkbox" name="eligibility_confirmed" value="1" @checked(old('eligibility_confirmed')) required>
              <span>{{ $application['eligibility_label'] ?? 'I confirm that the information provided is accurate and complete.' }}<span class="req">*</span></span>
            </label>
            @error('eligibility_confirmed')<span class="field-error">{{ $message }}</span>@enderror
          </div>
        </section>

        <div class="form-actions affiliate-form-actions">
          <button type="submit" class="btn-submit">{{ $submitLabel }}</button>
          <button type="reset" class="btn-cancel btn-reset">{{ $resetLabel }}</button>
          <a href="{{ $landingUrl }}" class="btn-cancel affiliate-form-back">{{ $backLabel }}</a>
        </div>
      </form>
    </div>
  </section>
</main>

@include('partials.site-footer')

<script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
</body>
</html>
