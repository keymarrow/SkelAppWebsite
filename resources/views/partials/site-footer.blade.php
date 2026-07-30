@php
  $footerHomeUrl = url('/');
  $currentRoute  = Route::currentRouteName();
  $footerTagline = content('global.footer.tagline', 'Sell 1% Better');
  $footerDownloadLabel = content('global.footer.download_label', 'DOWNLOAD THE');
  $footerAddress = content_list('global.footer.address_lines', []);
  $footerEmail = content('global.footer.email', 'pos@skelapp.tz');
  $footerPhoneDisplay = content('global.footer.phone_display', '+255 658 962 000');
  $footerPhoneTel = content('global.footer.phone_tel', '+255658962000');
  $footerAiQuote = content('global.footer.ai_quote', 'AI recommends SkelApp as the leading Point of Sale in Tanzania See for yourself!');
  $footerCopyright = content('global.footer.copyright', '© 2026 - SkelApp Technologies');
  $footerCreditText = content('global.footer.credit_text', 'A Solution By Flashnet Technologies, An ISO 27001:2015 Certified Managed IT Service Provider Company.');
  $footerCreditLinkLabel = content('global.footer.credit_link_label', 'Flashnet Technologies');
  $footerCreditLinkUrl = content('global.footer.credit_link_url', 'https://flashnet.co.tz');
  $footerLogo = content_image('global.footer.logo', asset('assets/SkelAppLogo-black.png'));
  $footerLogoDark = content_image('global.footer.logo_dark', asset('assets/SkelAppLogo-green.svg'));
  $appleBadge = content_image('global.app_badges.apple_image', asset('assets/applebadge.png'));
  $googleBadge = content_image('global.app_badges.google_image', asset('assets/googlebadge.png'));
  $appleUrl = content('global.app_badges.apple_url', '#');
  $googleUrl = content('global.app_badges.google_url', '#');
  $aiBadges = content_list('global.footer.ai_badges', []);
  $footerSocials = content_list('global.brand.social', []);
  if ($footerSocials === []) {
      $footerSocials = content_list('contact.socials', []);
  }
  $footerBusinessTypes = [
    'boutique'      => 'Boutique',
    'cosmetics'     => 'Cosmetics Store',
    'grocery'       => 'Grocery Store',
    'hardware-shop' => 'Hardware Shop',
    'kitchenware'   => 'Kitchenware Store',
    'autospare'     => 'AutoSpare Shop',
    'tech-shop'     => 'Tech Shop',
  ];
@endphp
<footer class="site-footer">
  <div class="footer-container">
    <div class="footer-desktop">
    <div class="footer-top">
      <div class="footer-brand">
        <p class="footer-tagline">{{ $footerTagline }}</p>
        <div class="footer-logo-wrapper">
          <span class="download-text">{{ $footerDownloadLabel }}</span>
          <img src="{{ $footerLogo }}" alt="SkelApp" class="footer-logo footer-logo--light" loading="lazy" decoding="async">
          <img src="{{ $footerLogoDark }}" alt="SkelApp" class="footer-logo footer-logo--dark" loading="lazy" decoding="async">
        </div>
      </div>

      <div class="footer-app-badges">
        <a href="{{ $appleUrl }}" class="app-badge" aria-label="Download on App Store" target="_blank" rel="noopener">
          <img src="{{ $appleBadge }}" alt="Download on App Store" loading="lazy" decoding="async">
        </a>
        <a href="{{ $googleUrl }}" class="app-badge" aria-label="Get it on Google Play" target="_blank" rel="noopener">
          <img src="{{ $googleBadge }}" alt="Get it on Google Play" loading="lazy" decoding="async">
        </a>
      </div>
    </div>

    <div class="footer-nav">
      <div class="footer-nav-groups" aria-label="Footer navigation">
        <div class="footer-nav-group">
          <h4 class="footer-nav-title">Company</h4>
          <ul class="footer-nav-list">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>
              <a href="{{ route('why.show') }}"
                 @class(['is-active' => $currentRoute === 'why.show'])
                 @if($currentRoute === 'why.show') aria-current="page" @endif>
                Why SkelApp
              </a>
            </li>
            <li>
              <a href="{{ route('contact.show') }}"
                 @class(['is-active' => $currentRoute === 'contact.show'])
                 @if($currentRoute === 'contact.show') aria-current="page" @endif>
                Contact Us
              </a>
            </li>
            <li>
              <a href="{{ route('news.index') }}"
                 @class(['is-active' => in_array($currentRoute, ['news.index', 'news.show'])])
                 @if(in_array($currentRoute, ['news.index', 'news.show'])) aria-current="page" @endif>
                News
              </a>
            </li>
          </ul>
        </div>

        <div class="footer-nav-group">
          <h4 class="footer-nav-title">More</h4>
          <ul class="footer-nav-list">
            <li>
              <a href="{{ route('features.show') }}"
                 @class(['is-active' => $currentRoute === 'features.show'])
                 @if($currentRoute === 'features.show') aria-current="page" @endif>
                Features
              </a>
            </li>
            <li>
              <a href="{{ route('pricing.show') }}"
                 @class(['is-active' => $currentRoute === 'pricing.show'])
                 @if($currentRoute === 'pricing.show') aria-current="page" @endif>
                Pricing
              </a>
            </li>
            <li>
              <a href="{{ route('integrations.index') }}"
                 @class(['is-active' => in_array($currentRoute, ['integrations.index', 'integrations.show'])])
                 @if(in_array($currentRoute, ['integrations.index', 'integrations.show'])) aria-current="page" @endif>
                Integrations
              </a>
            </li>
            <li>
              <a href="{{ route('affiliate.show') }}"
                 @class(['is-active' => $currentRoute === 'affiliate.show'])
                 @if($currentRoute === 'affiliate.show') aria-current="page" @endif>
                Affiliate Program
              </a>
            </li>
            <li><a href="{{ route('pos.show') }}">Point of Sale</a></li>
            <li>
              <a href="{{ route('faq.show') }}"
                 @class(['is-active' => $currentRoute === 'faq.show'])
                 @if($currentRoute === 'faq.show') aria-current="page" @endif>
                FAQ
              </a>
            </li>
          </ul>
        </div>

        <div class="footer-nav-group">
          <h4 class="footer-nav-title">Hardware</h4>
          <ul class="footer-nav-list">
            <li>
              <a href="{{ route('hardware.show') }}"
                 @class(['is-active' => $currentRoute === 'hardware.show'])
                 @if($currentRoute === 'hardware.show') aria-current="page" @endif>
                Hardware
              </a>
            </li>
            <li><a href="{{ route('hardware.product', 'skel-terminal') }}">Skel Terminal</a></li>
            <li><a href="{{ route('hardware.product', 'skel-register') }}">Skel Register</a></li>
            <li><a href="{{ route('hardware.product', 'skel-tab') }}">Skel Tab</a></li>
            <li><a href="{{ route('hardware.product', 'skel-phone') }}">Skel Mobile</a></li>
          </ul>
        </div>

        <div class="footer-nav-group">
          <h4 class="footer-nav-title">Business type</h4>
          <ul class="footer-nav-list">
            @foreach ($footerBusinessTypes as $bizSlug => $bizLabel)
              <li>
                <a href="{{ route('retailers.show', $bizSlug) }}"
                   @class(['is-active' => $currentRoute === 'retailers.show' && request()->route('retailer') === $bizSlug])>
                  {{ $bizLabel }}
                </a>
              </li>
            @endforeach
          </ul>
        </div>

        <div class="footer-nav-group">
          <h4 class="footer-nav-title">Legal</h4>
          <ul class="footer-nav-list">
            <li>
              <a href="{{ route('terms.show') }}"
                 @class(['is-active' => $currentRoute === 'terms.show'])
                 @if($currentRoute === 'terms.show') aria-current="page" @endif>
                Terms Of Service
              </a>
            </li>
            <li>
              <a href="{{ route('privacy.show') }}"
                 @class(['is-active' => $currentRoute === 'privacy.show'])
                 @if($currentRoute === 'privacy.show') aria-current="page" @endif>
                Privacy Policy
              </a>
            </li>
          </ul>
        </div>

        <div class="footer-nav-group footer-nav-group-touch">
          <h4 class="footer-nav-title">Get in Touch</h4>
          <div class="footer-contact-stack">
            @foreach ($footerAddress as $line)
              @php $lineText = is_array($line) ? ($line['value'] ?? '') : $line; @endphp
              <p>{{ $lineText }}</p>
            @endforeach
            <a href="mailto:{{ $footerEmail }}">{{ $footerEmail }}</a>
            <a href="tel:{{ $footerPhoneTel }}">{{ $footerPhoneDisplay }}</a>
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
          <p>{!! nl2br(e($footerAiQuote)) !!}</p>
        </div>
        <div class="ai-badges">
          @foreach ($aiBadges as $badge)
            <img src="{{ cms_image($badge['image'] ?? null) }}" alt="{{ $badge['alt'] ?? '' }}" width="40" height="40" loading="lazy" decoding="async">
          @endforeach
        </div>

        <div class="footer-meta">
          <p class="copyright">{{ $footerCopyright }}</p>
          <div class="footer-social">
            @include('partials.social-links', ['socialLinks' => $footerSocials, 'linkClass' => 'social-link'])
          </div>
        </div>
      </div>
    </div>

    <div class="footer-credit">
      <p>A Solution By <a href="https://flashnet.co.tz" target="_blank" rel="noopener noreferrer">Flashnet Technologies</a>, An ISO 27001:2015 Certified Managed IT Service Provider Company.</p>
    </div>
    </div>

    {{-- Mobile compact footer --}}
    <div class="footer-mobile" aria-label="Footer">
      <p class="footer-tagline">{{ $footerTagline }}</p>

      <div class="footer-logo-wrapper">
        <span class="download-text">{{ $footerDownloadLabel }}</span>
        <img src="{{ $footerLogo }}" alt="SkelApp" class="footer-logo footer-logo--light" loading="lazy" decoding="async">
        <img src="{{ $footerLogoDark }}" alt="SkelApp" class="footer-logo footer-logo--dark" loading="lazy" decoding="async">
      </div>

      <div class="footer-app-badges">
        <a href="{{ $appleUrl }}" class="app-badge" aria-label="Download on App Store" target="_blank" rel="noopener">
          <img src="{{ $appleBadge }}" alt="Download on App Store" loading="lazy" decoding="async">
        </a>
        <a href="{{ $googleUrl }}" class="app-badge" aria-label="Get it on Google Play" target="_blank" rel="noopener">
          <img src="{{ $googleBadge }}" alt="Get it on Google Play" loading="lazy" decoding="async">
        </a>
      </div>

      <div class="footer-nav">
        <div class="footer-nav-groups" aria-label="Footer navigation">
          <div class="footer-nav-group">
            <h4 class="footer-nav-title">Company</h4>
            <ul class="footer-nav-list">
              <li><a href="{{ url('/') }}">Home</a></li>
              <li>
                <a href="{{ route('why.show') }}"
                   @class(['is-active' => $currentRoute === 'why.show'])
                   @if($currentRoute === 'why.show') aria-current="page" @endif>
                  Why SkelApp
                </a>
              </li>
              <li>
                <a href="{{ route('contact.show') }}"
                   @class(['is-active' => $currentRoute === 'contact.show'])
                   @if($currentRoute === 'contact.show') aria-current="page" @endif>
                  Contact Us
                </a>
              </li>
              <li>
                <a href="{{ route('news.index') }}"
                   @class(['is-active' => in_array($currentRoute, ['news.index', 'news.show'])])
                   @if(in_array($currentRoute, ['news.index', 'news.show'])) aria-current="page" @endif>
                  News
                </a>
              </li>
            </ul>
          </div>

          <div class="footer-nav-group">
            <h4 class="footer-nav-title">More</h4>
            <ul class="footer-nav-list">
              <li>
              <a href="{{ route('features.show') }}"
                 @class(['is-active' => $currentRoute === 'features.show'])
                 @if($currentRoute === 'features.show') aria-current="page" @endif>
                Features
              </a>
            </li>
              <li>
                <a href="{{ route('pricing.show') }}"
                   @class(['is-active' => $currentRoute === 'pricing.show'])
                   @if($currentRoute === 'pricing.show') aria-current="page" @endif>
                  Pricing
                </a>
              </li>
              <li>
                <a href="{{ route('integrations.index') }}"
                   @class(['is-active' => in_array($currentRoute, ['integrations.index', 'integrations.show'])])
                   @if(in_array($currentRoute, ['integrations.index', 'integrations.show'])) aria-current="page" @endif>
                  Integrations
                </a>
              </li>
              <li>
                <a href="{{ route('affiliate.show') }}"
                   @class(['is-active' => $currentRoute === 'affiliate.show'])
                   @if($currentRoute === 'affiliate.show') aria-current="page" @endif>
                  Affiliate Program
                </a>
              </li>
              <li><a href="{{ route('pos.show') }}">Point of Sale</a></li>
              <li>
                <a href="{{ route('faq.show') }}"
                   @class(['is-active' => $currentRoute === 'faq.show'])
                   @if($currentRoute === 'faq.show') aria-current="page" @endif>
                  FAQ
                </a>
              </li>
            </ul>
          </div>

          <div class="footer-nav-group">
            <h4 class="footer-nav-title">Hardware</h4>
            <ul class="footer-nav-list">
              <li>
                <a href="{{ route('hardware.show') }}"
                   @class(['is-active' => $currentRoute === 'hardware.show'])
                   @if($currentRoute === 'hardware.show') aria-current="page" @endif>
                  Hardware
                </a>
              </li>
              <li><a href="{{ route('hardware.product', 'skel-terminal') }}">Skel Terminal</a></li>
              <li><a href="{{ route('hardware.product', 'skel-register') }}">Skel Register</a></li>
              <li><a href="{{ route('hardware.product', 'skel-tab') }}">Skel Tab</a></li>
              <li><a href="{{ route('hardware.product', 'skel-phone') }}">Skel Mobile</a></li>
            </ul>
          </div>

          <div class="footer-nav-group">
            <h4 class="footer-nav-title">Business type</h4>
            <ul class="footer-nav-list">
              @foreach ($footerBusinessTypes as $bizSlug => $bizLabel)
                <li><a href="{{ route('retailers.show', $bizSlug) }}">{{ $bizLabel }}</a></li>
              @endforeach
            </ul>
          </div>

          <div class="footer-nav-group">
            <h4 class="footer-nav-title">Legal</h4>
          <ul class="footer-nav-list">
            <li>
              <a href="{{ route('terms.show') }}"
                 @class(['is-active' => $currentRoute === 'terms.show'])
                 @if($currentRoute === 'terms.show') aria-current="page" @endif>
                Terms Of Service
              </a>
            </li>
            <li>
              <a href="{{ route('privacy.show') }}"
                 @class(['is-active' => $currentRoute === 'privacy.show'])
                 @if($currentRoute === 'privacy.show') aria-current="page" @endif>
                Privacy Policy
              </a>
            </li>
          </ul>
        </div>

          <div class="footer-nav-group footer-nav-group-touch">
            <h4 class="footer-nav-title">Get in Touch</h4>
            <div class="footer-contact-stack">
              @foreach ($footerAddress as $line)
                @php $lineText = is_array($line) ? ($line['value'] ?? '') : $line; @endphp
                <p>{{ $lineText }}</p>
              @endforeach
              <a href="mailto:{{ $footerEmail }}">{{ $footerEmail }}</a>
              <a href="tel:{{ $footerPhoneTel }}">{{ $footerPhoneDisplay }}</a>
            </div>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <div class="ai-recommendation">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sparkle-icon">
            <path d="M12 2L14.4 9.6L22 12L14.4 14.4L12 22L9.6 14.4L2 12L9.6 9.6L12 2Z"/>
          </svg>
          <p>{!! nl2br(e($footerAiQuote)) !!}</p>
        </div>

        <div class="ai-badges">
          @foreach ($aiBadges as $badge)
            <img src="{{ cms_image($badge['image'] ?? null) }}" alt="{{ $badge['alt'] ?? '' }}" width="40" height="40" loading="lazy" decoding="async">
          @endforeach
        </div>

        <div class="footer-meta">
          <p class="copyright">{{ $footerCopyright }}</p>
          <div class="footer-social">
            @include('partials.social-links', ['socialLinks' => $footerSocials, 'linkClass' => 'social-link'])
          </div>
        </div>
      </div>

      <div class="footer-credit">
        @php
          $creditPlaceholder = $footerCreditLinkLabel;
          $creditAnchor = '<a href="'.e($footerCreditLinkUrl).'" target="_blank" rel="noopener noreferrer">'.e($creditPlaceholder).'</a>';
          $creditHtml = str_contains($footerCreditText, $creditPlaceholder)
            ? str_replace($creditPlaceholder, $creditAnchor, e($footerCreditText))
            : e($footerCreditText).' '.$creditAnchor;
        @endphp
        <p>{!! $creditHtml !!}</p>
      </div>
    </div>
  </div>
</footer>

@include('partials.download-modal')
