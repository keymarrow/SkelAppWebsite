@php
  use Illuminate\Support\Arr;
  $rows = Arr::get($content ?? [], $name, []);
  $isFlatList = ($flat ?? false) && count($fields ?? []) === 1;
  if (! is_array($rows)) { $rows = []; }
  if (count($rows) === 0) { $rows = [[]]; } // start with one blank row so user can fill it
@endphp
<div
  class="cms-repeater"
  data-cms-repeater
  data-repeater-name="{{ $name }}"
  data-repeater-template="{{ $templateName ?? 'row' }}"
>
  <div class="cms-repeater-rows" data-repeater-rows>
    @foreach ($rows as $index => $row)
      <div class="cms-repeater-row" data-repeater-row>
        <div class="cms-repeater-row-body">
          @foreach ($fields as $field)
            @php
              $fieldKey = $field['key'];
              $fieldName = $isFlatList ? $name.'.'.$index : $name.'.'.$index.'.'.$fieldKey;
              $fieldValue = $isFlatList
                ? (is_array($row) ? ($row[$fieldKey] ?? '') : $row)
                : (is_array($row) ? ($row[$fieldKey] ?? '') : '');
              $fieldType = $field['type'] ?? 'text';
              $fieldLabel = $field['label'] ?? $fieldKey;
            @endphp
            @if ($fieldType === 'textarea')
              @include('admin.pages.fields.textarea', ['name' => $fieldName, 'label' => $fieldLabel, 'value' => $fieldValue, 'rows' => $field['rows'] ?? 3, 'content' => null])
            @elseif ($fieldType === 'checkbox')
              <div class="cms-field cms-field-checkbox">
                <label>
                  <input type="hidden" name="content[{{ $fieldName }}]" value="0" />
                  <input type="checkbox" name="content[{{ $fieldName }}]" value="1" {{ $fieldValue ? 'checked' : '' }} />
                  {{ $fieldLabel }}
                </label>
              </div>
            @elseif ($fieldType === 'image')
              @include('admin.pages.fields.image', ['name' => $fieldName, 'label' => $fieldLabel, 'current' => $fieldValue, 'content' => null, 'hint' => $field['hint'] ?? null])
            @else
              @include('admin.pages.fields.text', ['name' => $fieldName, 'label' => $fieldLabel, 'value' => $fieldValue, 'content' => null])
            @endif
          @endforeach
        </div>
        <button type="button" class="cms-btn cms-btn-ghost cms-repeater-remove" data-repeater-remove>Remove</button>
      </div>
    @endforeach
  </div>

  <button type="button" class="cms-btn cms-btn-ghost cms-repeater-add" data-repeater-add>+ Add row</button>
</div>
