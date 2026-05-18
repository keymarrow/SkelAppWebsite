@php
  use Illuminate\Support\Arr;
  $value = $value ?? Arr::get($content ?? [], $name, '');
  $inputId = $id ?? 'field-'.str_replace(['.', '[', ']'], '-', $name);
@endphp
<div class="cms-field">
  <label for="{{ $inputId }}" class="cms-field-label">{{ $label }}</label>
  @isset($hint)<p class="cms-field-hint">{{ $hint }}</p>@endisset
  <input
    type="text"
    id="{{ $inputId }}"
    name="content[{{ $name }}]"
    value="{{ $value }}"
    class="cms-input"
    @isset($placeholder) placeholder="{{ $placeholder }}" @endisset
  />
</div>
