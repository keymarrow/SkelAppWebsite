@php
  $isCollapsed = $collapsed ?? false;
@endphp
<details class="cms-section" {{ $isCollapsed ? '' : 'open' }}>
  <summary class="cms-section-summary">
    <span>{{ $title }}</span>
    @isset($subtitle)<small>{{ $subtitle }}</small>@endisset
  </summary>
  <div class="cms-section-body">
    {{ $slot }}
  </div>
</details>
