@php
  $featureGroups = $featureGroups ?? [];
@endphp

<div class="pos-feature-explorer" data-pos-feature>
  <div class="pos-feature-surface">
    <div class="pos-feature-tablist" role="tablist" aria-label="What SkelApp can do">
      @foreach ($featureGroups as $i => $group)
        <button
          type="button"
          class="pos-feature-tab {{ $i === 0 ? 'is-active' : '' }}"
          data-pos-feature-tab="{{ $i }}"
          role="tab"
          aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
        >
          {{ $group['tab'] ?? 'Feature' }}
        </button>
      @endforeach
    </div>

    @foreach ($featureGroups as $i => $group)
      <div
        class="pos-feature-panel {{ $i === 0 ? 'is-active' : '' }}"
        data-pos-feature-panel="{{ $i }}"
        role="tabpanel"
        @if ($i !== 0) hidden @endif
      >
        <div class="pos-feature-accordion">
          @foreach (($group['items'] ?? []) as $itemIndex => $item)
            <details class="pos-feature-item {{ $itemIndex === 0 ? 'is-open' : '' }}" data-pos-feature-item="{{ $itemIndex }}" {{ $itemIndex === 0 ? 'open' : '' }}>
              <summary class="pos-feature-item-head">
                <span class="pos-feature-item-title">{!! cms_text_html($item['title'] ?? '') !!}</span>
                <span class="pos-feature-item-icon" aria-hidden="true"></span>
              </summary>
              @if (! empty($item['body']))
                <div class="pos-feature-item-copy">
                  <p>{!! cms_text_html($item['body']) !!}</p>
                </div>
              @endif
            </details>
          @endforeach
        </div>
      </div>
    @endforeach
  </div>

  <div class="pos-feature-media-surface">
    <div class="pos-feature-media-stage">
      @foreach ($featureGroups as $i => $group)
        @php
          $mediaItems = $group['items'] ?? [];
        @endphp
        @if ($mediaItems === [])
          @php
            $mediaItems = [[
              'title' => $group['tab'] ?? 'SkelApp feature',
              'image' => $group['image'] ?? null,
            ]];
          @endphp
        @endif
        @foreach ($mediaItems as $itemIndex => $item)
          {{-- Every item's image is stacked here and swapped by toggling .is-active,
               so it has to be ready to paint the instant the class flips: async
               decoding hands back a frame with nothing in it and fills it in
               later, which reads as a fade-in that nobody asked for. --}}
          <img
            class="{{ $i === 0 && $itemIndex === 0 ? 'is-active' : '' }}"
            data-pos-feature-media
            data-pos-feature-media-group="{{ $i }}"
            data-pos-feature-media-item="{{ $itemIndex }}"
            src="{{ cms_image($item['image'] ?? ($group['image'] ?? null), asset('assets/CASHFLOW.png')) }}"
            alt="{{ trim(strip_tags((string) ($item['title'] ?? ($group['tab'] ?? 'SkelApp feature')))) }}"
            loading="{{ $i === 0 && $itemIndex === 0 ? 'eager' : 'lazy' }}"
            decoding="sync"
          >
        @endforeach
      @endforeach
    </div>
  </div>
</div>
