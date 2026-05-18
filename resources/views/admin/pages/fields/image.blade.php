@php
  use Illuminate\Support\Arr;
  $current = $current ?? Arr::get($content ?? [], $name, '');
  $inputId = $id ?? 'field-'.str_replace(['.', '[', ']'], '-', $name);
  // For image fields the form path is `image[dot.path]` (file input) and the
  // stored URL lives at `content[dot.path]`. We render a hidden `content[*]`
  // copy of the current URL so re-saving without picking a new file keeps it.
@endphp
<div class="cms-field">
  <label class="cms-field-label">{{ $label }}</label>
  @isset($hint)<p class="cms-field-hint">{{ $hint }}</p>@endisset

  <div class="cms-image-row">
    @if ($current)
      <a class="cms-image-thumb" href="{{ $current }}" target="_blank" rel="noreferrer">
        <img src="{{ $current }}" alt="">
      </a>
    @else
      <div class="cms-image-thumb cms-image-thumb--empty">No image</div>
    @endif

    <div class="cms-image-controls">
      <input
        type="file"
        id="{{ $inputId }}"
        name="image[{{ $name }}]"
        accept="image/png,image/jpeg,image/webp,image/gif,image/svg+xml"
        class="cms-input"
      />
      <input type="hidden" name="content[{{ $name }}]" value="{{ $current }}" />

      @if ($current)
        <label class="cms-image-remove">
          <input type="checkbox" name="remove_image[]" value="{{ $name }}" />
          Remove current image
        </label>
      @endif
    </div>
  </div>
</div>
