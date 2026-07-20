@php
  /**
   * Reusable "numbered steps + media band" section.
   * Used twice on the POS page: offline mode and online ordering.
   *
   * Inputs:
   *   $prefix  – content key prefix, e.g. 'pos.offline' or 'pos.online'
   *   $data    – defaults array (title, subtitle, media_image, media_caption)
   *   $steps   – content_list() of steps, each ['body' => ...]
   *   $icons   – array of up to 3 raw inline-SVG strings (one per step)
   *   $variant – extra modifier class for the <section> (optional)
   */
  $variant  = $variant ?? '';
  $icons    = $icons ?? [];
  $caption  = content_text("{$prefix}.media_caption", $data['media_caption'] ?? '');
  $mediaImg = content_image("{$prefix}.media_image", cms_image($data['media_image'] ?? null, asset('assets/CASHFLOW.png')));
@endphp
<section class="pos-steps {{ $variant }}">
  <div class="container">
    <div class="pos-steps-head">
      <h2 class="pos-steps-title {{ content_typography_class("{$prefix}.title") }}" style="{{ content_typography_vars("{$prefix}.title") }}">{!! content_text_html("{$prefix}.title", $data['title'] ?? '') !!}</h2>
      <p class="pos-steps-sub {{ content_typography_class("{$prefix}.subtitle") }}" style="{{ content_typography_vars("{$prefix}.subtitle") }}">{!! content_text_html("{$prefix}.subtitle", $data['subtitle'] ?? '') !!}</p>
    </div>

    <div class="pos-steps-grid">
      @foreach ($steps as $i => $step)
        <div class="pos-step">
          @if (! empty($icons[$i]))
            <span class="pos-step-icon" aria-hidden="true">{!! $icons[$i] !!}</span>
          @endif
          <p class="pos-step-text"><span class="pos-step-num">{{ $i + 1 }}.</span> {!! content_text_html("{$prefix}.steps.{$i}.body", $step['body'] ?? '') !!}</p>
        </div>
      @endforeach
    </div>

    <figure class="pos-steps-media">
      <img src="{{ $mediaImg }}" alt="{{ $caption !== '' ? $caption : 'SkelApp' }}" loading="lazy" decoding="async">
      @if ($caption !== '')
        <figcaption class="pos-steps-caption">{!! content_text_html("{$prefix}.media_caption", $data['media_caption'] ?? '') !!}</figcaption>
      @endif
    </figure>
  </div>
</section>
