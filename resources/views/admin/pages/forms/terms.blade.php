@component('admin.pages.fields.section', ['title' => 'Page hero'])
  @include('admin.pages.fields.text', ['name' => 'hero.heading', 'label' => 'Big page heading'])
  @include('admin.pages.fields.text', ['name' => 'hero.last_updated', 'label' => '"Last updated" line'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Intro paragraphs (before numbered sections)'])
  @include('admin.pages.fields.repeater', [
    'name' => 'intro_paragraphs',
    'flat' => true,
    'fields' => [
      ['key' => 'value', 'label' => 'Paragraph text', 'type' => 'textarea', 'rows' => 4],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Numbered sections (1. Definitions, 2. Eligibility, …)', 'collapsed' => true])
  @include('admin.pages.fields.repeater', [
    'name' => 'sections',
    'fields' => [
      ['key' => 'title', 'label' => 'Section title (e.g. "1. Definitions")'],
      ['key' => 'intro', 'label' => 'Intro paragraph (optional)', 'type' => 'textarea', 'rows' => 3],
    ],
  ])

  <div class="cms-note">
    <strong>Bullet points</strong> inside each section are not exposed as repeaters in this form (they\'re deeply nested). Edit existing bullets via the JSON or ask to add a bullet-level editor.
  </div>
@endcomponent

@component('admin.pages.fields.section', ['title' => 'SEO', 'collapsed' => true])
  @include('admin.pages.fields.text', ['name' => 'meta.title', 'label' => 'Browser tab title'])
  @include('admin.pages.fields.textarea', ['name' => 'meta.description', 'label' => 'Meta description', 'rows' => 2])
@endcomponent
