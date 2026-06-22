@component('admin.pages.fields.section', ['title' => 'Page hero'])
  @include('admin.pages.fields.text_typography', ['name' => 'hero.heading', 'label' => 'Big page heading', 'preset' => 'h1-display'])
  @include('admin.pages.fields.text_typography', ['name' => 'hero.last_updated', 'label' => '"Last updated" line', 'preset' => 'label'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Intro paragraphs'])
  @include('admin.pages.fields.repeater', [
    'name' => 'intro_paragraphs',
    'flat' => true,
    'fields' => [
      ['key' => 'value', 'label' => 'Paragraph text', 'type' => 'textarea', 'rows' => 4],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Policy sections'])
  <p class="cms-field-hint" style="margin-bottom: 12px;">
    For bullet lines, use one line per bullet in the format <code>Label | Body</code>. If a bullet does not need a label, enter just the body text.
  </p>

  @include('admin.pages.fields.repeater', [
    'name' => 'sections',
    'fields' => [
      ['key' => 'title', 'label' => 'Section title'],
      ['key' => 'intro', 'label' => 'Section intro paragraph', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'bullets_text', 'label' => 'Bullet lines', 'type' => 'textarea', 'rows' => 6],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'SEO', 'collapsed' => true])
  @include('admin.pages.fields.text', ['name' => 'meta.title', 'label' => 'Browser tab title'])
  @include('admin.pages.fields.textarea', ['name' => 'meta.description', 'label' => 'Meta description', 'rows' => 2])
@endcomponent
