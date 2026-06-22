@php
  $dlTitle    = content('global.download.title', 'Get the SkelApp');
  $dlSubtitle = content('global.download.subtitle', 'Scan the QR code to download the app');
  $dlSmsLabel = content('global.download.sms_label', 'or get a download link via SMS');
  $dlCc       = content('global.download.country_code', '+255');
  $dlPhonePlaceholder = content('global.download.phone_placeholder', 'Mobile number');
  $dlQrAlt    = content('global.download.qr_alt', 'Scan to download SkelApp');
  // Default QR is generated live (points to the site); upload a static one via the CMS to override.
  $dlQr       = content_image('global.download.qr_image', 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&margin=0&data=' . urlencode(url('/')));
  $dlApple    = content_image('global.app_badges.apple_image', asset('assets/applebadge.png'));
  $dlGoogle   = content_image('global.app_badges.google_image', asset('assets/googlebadge.png'));
  $dlAppleUrl = content('global.app_badges.apple_url', config('services.beem.apple_url', '#'));
  $dlGoogleUrl= content('global.app_badges.google_url', config('services.beem.google_url', '#'));
@endphp
<div class="dl-modal" data-download-overlay aria-hidden="true">
  <div class="dl-modal-card" role="dialog" aria-modal="true" aria-labelledby="dl-modal-title">
    <button type="button" class="dl-modal-close" data-download-close aria-label="Close">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
    </button>

    <h2 id="dl-modal-title" class="dl-modal-title">{{ $dlTitle }}</h2>
    <p class="dl-modal-sub">{{ $dlSubtitle }}</p>

    <div class="dl-modal-qr">
      <img src="{{ $dlQr }}" alt="{{ $dlQrAlt }}" width="160" height="160" loading="lazy" decoding="async">
    </div>

    <p class="dl-modal-or">{{ $dlSmsLabel }}</p>

    <form class="dl-modal-form" data-download-form action="{{ route('download.sms') }}" method="post" novalidate>
      @csrf
      <input type="hidden" name="country_code" value="{{ $dlCc }}">
      <span class="dl-modal-cc">🇹🇿 {{ $dlCc }}</span>
      <input class="dl-modal-input" type="tel" name="phone" inputmode="numeric" autocomplete="tel-national" placeholder="{{ $dlPhonePlaceholder }}" aria-label="{{ $dlPhonePlaceholder }}">
      <button type="submit" class="dl-modal-submit" aria-label="Send download link">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
      </button>
    </form>
    <p class="dl-modal-msg" data-download-msg hidden></p>

    <div class="dl-modal-badges">
      <a class="dl-modal-badge" href="{{ $dlAppleUrl }}" target="_blank" rel="noopener">
        <img src="{{ $dlApple }}" alt="Download on the App Store" loading="lazy" decoding="async">
      </a>
      <a class="dl-modal-badge" href="{{ $dlGoogleUrl }}" target="_blank" rel="noopener">
        <img src="{{ $dlGoogle }}" alt="Get it on Google Play" loading="lazy" decoding="async">
      </a>
    </div>
  </div>
</div>

<script>
  (function () {
    var overlay = document.querySelector('[data-download-overlay]');
    if (!overlay) return;
    var lastFocus = null;

    function openModal(e) {
      if (e) e.preventDefault();
      lastFocus = document.activeElement;
      overlay.classList.add('is-open');
      overlay.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
      var input = overlay.querySelector('.dl-modal-input');
      if (input) setTimeout(function () { input.focus(); }, 60);
    }
    function closeModal() {
      overlay.classList.remove('is-open');
      overlay.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
      if (lastFocus && lastFocus.focus) lastFocus.focus();
    }

    // Triggers: every download button across the site + any opt-in element.
    document.querySelectorAll('.app-badge, .store-badge, .btn-download, .btn-cta, [data-download-modal]').forEach(function (el) {
      el.addEventListener('click', openModal);
    });

    overlay.addEventListener('click', function (e) { if (e.target === overlay) closeModal(); });
    overlay.querySelector('[data-download-close]').addEventListener('click', closeModal);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && overlay.classList.contains('is-open')) closeModal(); });

    var form = overlay.querySelector('[data-download-form]');
    var msg = overlay.querySelector('[data-download-msg]');
    var submit = form ? form.querySelector('.dl-modal-submit') : null;

    function setMessage(text, kind) {
      msg.textContent = text;
      msg.className = 'dl-modal-msg ' + (kind === 'ok' ? 'is-ok' : 'is-error');
      msg.hidden = false;
    }

    function firstError(payload) {
      if (payload && payload.errors) {
        var keys = Object.keys(payload.errors);
        if (keys.length && payload.errors[keys[0]] && payload.errors[keys[0]].length) {
          return payload.errors[keys[0]][0];
        }
      }

      return payload && payload.message ? payload.message : 'Something went wrong. Please try again.';
    }

    if (form) {
      form.addEventListener('submit', async function (e) {
        e.preventDefault();
        var val = (form.phone.value || '').replace(/\D/g, '');
        if (val.length < 7) {
          setMessage('Please enter a valid mobile number.', 'error');
          return;
        }

        if (submit) {
          submit.disabled = true;
        }

        msg.hidden = true;

        try {
          var response = await fetch(form.action, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
            },
            body: new FormData(form)
          });

          var payload = await response.json().catch(function () { return {}; });

          if (!response.ok) {
            setMessage(firstError(payload), 'error');
            return;
          }

          setMessage(payload.message || "We've sent the download links to your phone.", 'ok');
          form.reset();
          form.querySelector('input[name="country_code"]').value = @json($dlCc);
        } catch (error) {
          setMessage('We could not send the download links right now. Please try again shortly.', 'error');
        } finally {
          if (submit) {
            submit.disabled = false;
          }
        }
      });
    }
  })();
</script>
