@component('admin.pages.fields.section', ['title' => 'Hero section'])
  @include('admin.pages.fields.text', ['name' => 'hero.eyebrow', 'label' => 'Eyebrow pill text', 'hint' => 'Small green badge above the headline.'])
  @include('admin.pages.fields.text_typography', ['name' => 'hero.title', 'label' => 'Headline (use line breaks for stacked title)', 'preset' => 'h1-display', 'multiline' => true, 'rows' => 2])
  @include('admin.pages.fields.text_typography', ['name' => 'hero.subtitle', 'label' => 'Subtitle paragraph', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])
  @include('admin.pages.fields.image', ['name' => 'hero.image', 'label' => 'Hero composition image', 'hint' => 'Large landscape image showing the app in context.'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Detailed overview header'])
  @include('admin.pages.fields.text', ['name' => 'detail.eyebrow', 'label' => 'Eyebrow pill text'])
  @include('admin.pages.fields.text_typography', ['name' => 'detail.title', 'label' => 'Section heading', 'preset' => 'h2-section', 'multiline' => true, 'rows' => 2])
  @include('admin.pages.fields.text_typography', ['name' => 'detail.subtitle', 'label' => 'Section subtitle', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Feature sections'])
  <p class="cms-field-hint" style="margin-bottom: 10px;">
    These rows power the detailed outline on the public features page. Use one section per major feature area. For "Detailed rows", enter one item per line in the format: <code>Title | Description</code>.
  </p>

  @include('admin.pages.fields.repeater', [
    'name' => 'detail.cards',
    'fields' => [
      ['key' => 'slug', 'label' => 'Section slug (used for anchor links)'],
      ['key' => 'nav_label', 'label' => 'Left navigation label'],
      ['key' => 'eyebrow', 'label' => 'Small section label'],
      ['key' => 'title', 'label' => 'Section title', 'type' => 'text_typography', 'preset' => 'h3-card'],
      ['key' => 'overview', 'label' => 'Overview paragraph', 'type' => 'textarea', 'rows' => 4],
      ['key' => 'note_label', 'label' => 'Side note label'],
      ['key' => 'note_value', 'label' => 'Side note copy', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'image', 'label' => 'Section image', 'type' => 'image'],
      ['key' => 'capabilities_text', 'label' => 'Detailed rows (Title | Description)', 'type' => 'textarea', 'rows' => 7],
      ['key' => 'cta_label', 'label' => 'CTA button label (optional)'],
      ['key' => 'cta_url', 'label' => 'CTA button URL'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'POS FAQ'])
  @include('admin.pages.fields.text', ['name' => 'faq.eyebrow', 'label' => 'Eyebrow pill text'])
  @include('admin.pages.fields.text_typography', ['name' => 'faq.title', 'label' => 'Section title', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text_typography', ['name' => 'faq.subtitle', 'label' => 'Section subtitle', 'preset' => 'body', 'multiline' => true, 'rows' => 2])

  @include('admin.pages.fields.repeater', [
    'name' => 'faq.items',
    'fields' => [
      ['key' => 'question', 'label' => 'Question'],
      ['key' => 'answer', 'label' => 'Answer', 'type' => 'textarea', 'rows' => 4],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Bottom CTA'])
  @include('admin.pages.fields.text_typography', ['name' => 'cta.title', 'label' => 'CTA title', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text', ['name' => 'cta.title_accent', 'label' => 'Accent phrase inside the title', 'hint' => 'The exact substring of the title to style in green italic NewBlack (e.g. "1% better"). Leave blank for no accent.'])
  @include('admin.pages.fields.text_typography', ['name' => 'cta.subtitle', 'label' => 'CTA subtitle', 'preset' => 'body', 'multiline' => true, 'rows' => 2])
  @include('admin.pages.fields.text', ['name' => 'cta.primary_label', 'label' => 'Primary button label'])
  @include('admin.pages.fields.text', ['name' => 'cta.primary_url', 'label' => 'Primary button URL'])
  @include('admin.pages.fields.text', ['name' => 'cta.secondary_label', 'label' => 'Secondary button label (links to Contact)'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'SEO', 'collapsed' => true])
  @include('admin.pages.fields.text', ['name' => 'meta.title', 'label' => 'Browser tab title'])
  @include('admin.pages.fields.textarea', ['name' => 'meta.description', 'label' => 'Meta description', 'rows' => 2])
@endcomponent
