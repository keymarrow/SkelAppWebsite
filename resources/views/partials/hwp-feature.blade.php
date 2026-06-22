@php($reverse = $reverse ?? false)
<div class="hwp-feature {{ $reverse ? 'hwp-feature--reverse' : '' }}" data-feature>
  <div class="hwp-feature-tabs">
    @foreach ($features as $i => $f)
      <button type="button" class="hwp-feature-tab {{ $i === 0 ? 'is-active' : '' }}" data-feature-tab="{{ $i }}">
        <span class="hwp-feature-tab-name">{{ $f['name'] }}</span>
        <span class="hwp-feature-tab-body">
          {{ $f['body'] }}
          @if (! empty($f['link_label']))
            <a href="{{ $f['link_url'] ?? '#' }}" class="hwp-feature-tab-link">
              {{ $f['link_label'] }}
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
          @endif
        </span>
      </button>
    @endforeach
  </div>
  <div class="hwp-feature-media">
    @foreach ($features as $i => $f)
      <img class="{{ $i === 0 ? 'is-active' : '' }}" data-feature-media="{{ $i }}" src="{{ cms_image($f['image'] ?? null, asset('assets/PosSystem.webp')) }}" alt="{{ $f['name'] }}" loading="lazy" decoding="async">
    @endforeach
  </div>
</div>
