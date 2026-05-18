@php
  use Illuminate\Support\Arr;
  $value = $value ?? Arr::get($content ?? [], $name, '');
  $inputId = $id ?? 'field-'.str_replace(['.', '[', ']'], '-', $name);
@endphp
<div class="cms-field">
  <label for="{{ $inputId }}" class="cms-field-label">{{ $label }}</label>
  @isset($hint)<p class="cms-field-hint">{{ $hint }}</p>@endisset
  <textarea
    id="{{ $inputId }}"
    name="content[{{ $name }}]"
    class="cms-input cms-textarea"
    rows="{{ $rows ?? 4 }}"
    @isset($placeholder) placeholder="{{ $placeholder }}" @endisset
  >{{ $value }}</textarea>
</div>
