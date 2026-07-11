@php
  $megaTags = content_list('global.nav.mega_tags', ['FAST CHECKOUT', 'WORKS OFFLINE']);
@endphp
<div class="nav-mega" role="menu" aria-label="SkelApp menu">
  <div class="nav-mega-inner">

    {{-- Featured card --}}
    <a class="nav-mega-feature" href="{{ route('retailers.index') }}" style="background-image: linear-gradient(180deg, rgba(10,16,20,0.15) 0%, rgba(10,16,20,0.72) 100%), url('{{ content_image('global.nav.mega_image', asset('assets/techshop.webp')) }}');">
      <span class="nav-mega-feature-eyebrow">{{ content('global.nav.mega_eyebrow', 'Built for Tanzania') }}</span>
      <p class="nav-mega-feature-title">{{ content('global.nav.mega_title', 'Run your whole shop, 1% better') }}</p>
      <div class="nav-mega-feature-tags">
        @foreach ($megaTags as $tag)
          <span>{{ is_array($tag) ? ($tag['value'] ?? '') : $tag }}</span>
        @endforeach
      </div>
    </a>

    {{-- Products (hover swaps the dynamic column) --}}
    <div class="nav-mega-col nav-mega-col--products">
      <span class="nav-mega-heading">Products</span>
      <a class="nav-mega-item" data-mega-trigger="business" href="{{ route('retailers.index') }}">
        <span class="nav-mega-icon" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l1.5-5h15L21 9M4 9h16v10a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9zM3 9a3 3 0 0 0 6 0 3 3 0 0 0 6 0 3 3 0 0 0 6 0"/></svg>
        </span>
        <span class="nav-mega-text"><strong>Business Type</strong><span>For your kind of shop</span></span>
      </a>
      <a class="nav-mega-item" data-mega-trigger="hardware" href="{{ route('hardware.show') }}">
        <span class="nav-mega-icon" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="2.5" width="12" height="19" rx="2.5"/><line x1="10" y1="6" x2="14" y2="6"/><circle cx="12" cy="15" r="2.4"/></svg>
        </span>
        <span class="nav-mega-text"><strong>Hardware</strong><span>Terminals, registers &amp; tablets</span></span>
      </a>
      <a class="nav-mega-item" data-mega-trigger="features" href="{{ route('features.show') }}">
        <span class="nav-mega-icon" aria-hidden="true">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
        </span>
        <span class="nav-mega-text"><strong>Features</strong><span>Everything SkelApp can do</span></span>
      </a>
    </div>

    {{-- Dynamic column: defaults to Learn More, swaps per Products hover --}}
    <div class="nav-mega-col nav-mega-dynamic" data-mega-dynamic>
      <div class="nav-mega-panel is-active" data-mega-panel="learn">
        <span class="nav-mega-heading">Learn More</span>
        <a class="nav-mega-link" href="{{ route('faq.show') }}">FAQ</a>
        <a class="nav-mega-link" href="{{ route('integrations.index') }}">Integrations</a>
        <a class="nav-mega-link" href="{{ route('news.index') }}">News</a>
        <a class="nav-mega-link" href="{{ route('why.show') }}">Why SkelApp</a>
      </div>
      <div class="nav-mega-panel" data-mega-panel="business">
        <span class="nav-mega-heading">Business type</span>
        @foreach ($megaBusiness as $slug => $label)
          <a class="nav-mega-link" href="{{ route('retailers.show', $slug) }}">{{ $label }}</a>
        @endforeach
      </div>
      <div class="nav-mega-panel" data-mega-panel="hardware">
        <span class="nav-mega-heading">Hardware</span>
        <a class="nav-mega-link" href="{{ route('hardware.show') }}">All hardware</a>
        <a class="nav-mega-link" href="{{ route('hardware.product', 'skel-terminal') }}">Skel Terminal</a>
        <a class="nav-mega-link" href="{{ route('hardware.product', 'skel-register') }}">Skel Register</a>
        <a class="nav-mega-link" href="{{ route('hardware.product', 'skel-tab') }}">Skel Tab</a>
        <a class="nav-mega-link" href="{{ route('hardware.product', 'skel-phone') }}">Skel Mobile</a>
      </div>
      <div class="nav-mega-panel" data-mega-panel="features">
        <span class="nav-mega-heading">Features</span>
        <a class="nav-mega-link" href="{{ route('features.show') }}">All features</a>
        <a class="nav-mega-link" href="{{ route('pos.show') }}">Point of Sale</a>
        <a class="nav-mega-link" href="{{ route('integrations.index') }}">Integrations</a>
        <a class="nav-mega-link" href="{{ route('pricing.show') }}">Pricing</a>
      </div>
    </div>

    {{-- More --}}
    <div class="nav-mega-col nav-mega-col--rest" data-mega-rest>
      <span class="nav-mega-heading">More</span>
      <a class="nav-mega-link" href="{{ route('contact.show') }}">Contact us</a>
      <a class="nav-mega-link" href="{{ route('terms.show') }}">Terms &amp; Conditions</a>
      <a class="nav-mega-link" href="{{ route('privacy.show') }}">Privacy Policy</a>
    </div>

  </div>
</div>
