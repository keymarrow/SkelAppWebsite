@php
  use Illuminate\Support\Arr;
  // Caller can pass `value` explicitly (used by repeater rows). Otherwise we
  // read from $content via the dot-path `name`.
  $raw = isset($value) ? $value : Arr::get($content ?? [], $name, '');
  if (is_array($raw)) {
    $textValue = (string) ($raw['text'] ?? '');
    $typography = is_array($raw['typography'] ?? null) ? $raw['typography'] : [];
  } else {
    $textValue = is_string($raw) ? $raw : '';
    $typography = [];
  }
  $inputId = 'field-'.str_replace(['.', '[', ']'], '-', $name);

  // Resolve typography defaults: caller passes a preset key (e.g. 'h1-hero')
  // or a raw associative array. Used to pre-fill EMPTY values per viewport
  // so the admin sees the current site values right away.
  $resolvedDefaults = [];
  $presetSource = $preset ?? ($typography_defaults ?? null);
  if (is_string($presetSource)) {
    $resolvedDefaults = config('cms_typography.'.$presetSource, []);
  } elseif (is_array($presetSource)) {
    $resolvedDefaults = $presetSource;
  }

  // Stored value for a viewport+field (empty if user hasn't customised yet).
  // We deliberately do NOT pre-fill defaults into the input value — preset
  // defaults appear as PLACEHOLDERS instead, so saving an untouched form does
  // not override the element's existing CSS.
  $storedValue = function (string $viewport, string $key) use ($typography) {
    $v = $typography[$viewport][$key] ?? null;
    return $v === null ? '' : (string) $v;
  };

  // The current preset value (used as placeholder text so admins know what
  // the page currently looks like at that viewport).
  $presetValue = function (string $viewport, string $key) use ($resolvedDefaults) {
    return $resolvedDefaults[$viewport][$key] ?? '';
  };

  $hasTypography = false;
  foreach (['desktop', 'tablet', 'mobile'] as $vp) {
    if (! empty($typography[$vp]) && is_array($typography[$vp])) {
      foreach ($typography[$vp] as $v) {
        if ($v !== null && $v !== '') { $hasTypography = true; break 2; }
      }
    }
  }
  $weightOptions = ['', '100','200','300','400','500','600','700','800','900'];
  $styleOptions = ['', 'normal', 'italic'];
  $alignOptions = ['', 'left', 'center', 'right', 'justify'];

  // Multi-line text mode: caller passes `multiline => true` (or `rows => N`)
  // to render a textarea instead of a single-line input.
  $isMultiline = ($multiline ?? false) || isset($rows);
  $textareaRows = $rows ?? 4;
@endphp

<div class="cms-field cms-field--typography" data-cms-typography-field>
  <label for="{{ $inputId }}-text" class="cms-field-label">{{ $label }}</label>
  @isset($hint)<p class="cms-field-hint">{{ $hint }}</p>@endisset

  @if ($isMultiline)
    <textarea
      id="{{ $inputId }}-text"
      name="content[{{ $name }}.text]"
      class="cms-input cms-textarea"
      rows="{{ $textareaRows }}"
      @isset($placeholder) placeholder="{{ $placeholder }}" @endisset
    >{{ $textValue }}</textarea>
  @else
    <input
      type="text"
      id="{{ $inputId }}-text"
      name="content[{{ $name }}.text]"
      value="{{ $textValue }}"
      class="cms-input"
      @isset($placeholder) placeholder="{{ $placeholder }}" @endisset
    />
  @endif

  <details class="cms-typography-panel" {{ $hasTypography ? 'open' : '' }}>
    <summary>
      <span>Typography</span>
      <small>Override font size, weight, style per viewport</small>
    </summary>

    <div class="cms-typography-tabs" role="tablist" aria-label="Viewport">
      @foreach (['desktop', 'tablet', 'mobile'] as $vp)
        <button type="button" class="cms-typography-tab {{ $loop->first ? 'is-active' : '' }}" data-typography-tab="{{ $vp }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
          {{ ucfirst($vp) }}
        </button>
      @endforeach
    </div>

    @foreach (['desktop', 'tablet', 'mobile'] as $vp)
      @php
        $fsPh = $presetValue($vp, 'font_size'); $fsPh = $fsPh ? 'current: '.$fsPh.'px' : 'inherit';
        $lhPh = $presetValue($vp, 'line_height') ?: 'inherit';
        $lsPh = $presetValue($vp, 'letter_spacing') ?: 'inherit';
        $colorPh = $presetValue($vp, 'color') ?: 'inherit';
        $weightCurrent = $presetValue($vp, 'font_weight');
        $styleCurrent = $presetValue($vp, 'font_style');
        $alignCurrent = $presetValue($vp, 'text_align');
        $weightInheritLabel = $weightCurrent ? '— inherit ('.$weightCurrent.') —' : '— inherit —';
        $styleInheritLabel = $styleCurrent ? '— inherit ('.$styleCurrent.') —' : '— inherit —';
        $alignInheritLabel = $alignCurrent ? '— inherit ('.$alignCurrent.') —' : '— inherit —';
      @endphp
      <div class="cms-typography-panel-body {{ $loop->first ? 'is-active' : '' }}" data-typography-body="{{ $vp }}" {{ $loop->first ? '' : 'hidden' }}>
        <div class="cms-typography-grid">
          <label class="cms-typography-input">
            <span>Font size</span>
            <input
              type="text"
              name="content[{{ $name }}.typography.{{ $vp }}.font_size]"
              value="{{ $storedValue($vp, 'font_size') }}"
              placeholder="{{ $fsPh }}"
              class="cms-input"
            />
          </label>

          <label class="cms-typography-input">
            <span>Font weight</span>
            <select name="content[{{ $name }}.typography.{{ $vp }}.font_weight]" class="cms-input">
              @foreach ($weightOptions as $opt)
                <option value="{{ $opt }}" @selected($storedValue($vp, 'font_weight') === $opt)>{{ $opt === '' ? $weightInheritLabel : $opt }}</option>
              @endforeach
            </select>
          </label>

          <label class="cms-typography-input">
            <span>Font style</span>
            <select name="content[{{ $name }}.typography.{{ $vp }}.font_style]" class="cms-input">
              @foreach ($styleOptions as $opt)
                <option value="{{ $opt }}" @selected($storedValue($vp, 'font_style') === $opt)>{{ $opt === '' ? $styleInheritLabel : ucfirst($opt) }}</option>
              @endforeach
            </select>
          </label>

          <label class="cms-typography-input">
            <span>Line height</span>
            <input
              type="text"
              name="content[{{ $name }}.typography.{{ $vp }}.line_height]"
              value="{{ $storedValue($vp, 'line_height') }}"
              placeholder="{{ $lhPh }}"
              class="cms-input"
            />
          </label>

          <label class="cms-typography-input">
            <span>Letter spacing</span>
            <input
              type="text"
              name="content[{{ $name }}.typography.{{ $vp }}.letter_spacing]"
              value="{{ $storedValue($vp, 'letter_spacing') }}"
              placeholder="{{ $lsPh }}"
              class="cms-input"
            />
          </label>

          <label class="cms-typography-input">
            <span>Text align</span>
            <select name="content[{{ $name }}.typography.{{ $vp }}.text_align]" class="cms-input">
              @foreach ($alignOptions as $opt)
                <option value="{{ $opt }}" @selected($storedValue($vp, 'text_align') === $opt)>{{ $opt === '' ? $alignInheritLabel : ucfirst($opt) }}</option>
              @endforeach
            </select>
          </label>

          <label class="cms-typography-input cms-typography-input--full">
            <span>Color</span>
            <input
              type="text"
              name="content[{{ $name }}.typography.{{ $vp }}.color]"
              value="{{ $storedValue($vp, 'color') }}"
              placeholder="{{ $colorPh }}"
              class="cms-input"
            />
          </label>
        </div>
      </div>
    @endforeach
  </details>
</div>
