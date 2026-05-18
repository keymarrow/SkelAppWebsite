@php
  use Illuminate\Support\Arr;
  $current = $current ?? Arr::get($content ?? [], $name, '');
  $inputId = $id ?? 'field-'.str_replace(['.', '[', ']'], '-', $name);

  // Resolve the stored value to a usable thumbnail URL (handles full URLs,
  // /storage/ paths, "assets/x.png", AND legacy bare filenames like "speed.svg").
  $previewSrc = $current ? cms_image($current) : '';
@endphp
<div
  class="cms-field cms-image-field"
  data-cms-image-field
  data-field-name="{{ $name }}"
>
  <label class="cms-field-label" for="{{ $inputId }}">{{ $label }}</label>
  @isset($hint)<p class="cms-field-hint">{{ $hint }}</p>@endisset

  <div class="cms-image-row">
    <div
      class="cms-image-thumb {{ $current ? '' : 'cms-image-thumb--empty' }}"
      data-cms-image-thumb
    >
      @if ($current)
        <img src="{{ $previewSrc }}" alt="" data-cms-image-preview>
      @else
        <span data-cms-image-preview-empty>No image</span>
      @endif
    </div>

    <div class="cms-image-controls">
      <input
        type="hidden"
        id="{{ $inputId }}"
        name="content[{{ $name }}]"
        value="{{ $current }}"
        data-cms-image-url
      />

      <input
        type="file"
        accept="image/png,image/jpeg,image/webp,image/gif,image/svg+xml"
        hidden
        data-cms-image-file-input
      />

      <p class="cms-image-feedback" data-cms-image-feedback hidden></p>

      <div class="cms-image-actions">
        <button type="button" class="cms-btn cms-btn-ghost" data-cms-image-upload>
          Upload new
        </button>
        <button type="button" class="cms-btn cms-btn-ghost" data-cms-image-browse>
          Choose existing
        </button>
        <button
          type="button"
          class="cms-btn cms-btn-ghost cms-image-remove-btn"
          data-cms-image-remove
          @unless ($current) hidden @endunless
        >
          Remove
        </button>
      </div>
    </div>
  </div>
</div>
