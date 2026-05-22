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

@component('admin.pages.fields.section', ['title' => 'Feature cards'])
  <p class="cms-field-hint" style="margin-bottom: 10px;">
    Each card shows in the 2-column grid below the detailed-overview header. Use the variant to switch between light, dark, or green cards for visual variety.
  </p>

  @include('admin.pages.fields.repeater', [
    'name' => 'detail.cards',
    'fields' => [
      ['key' => 'image', 'label' => 'Card image', 'type' => 'image'],
      ['key' => 'title', 'label' => 'Card title', 'type' => 'text_typography', 'preset' => 'h3-card'],
      ['key' => 'body', 'label' => 'Card body', 'type' => 'text_typography', 'preset' => 'body', 'multiline' => true, 'rows' => 4],
      ['key' => 'icon', 'label' => 'Decorative icon (optional, sits over the top-left corner)', 'type' => 'image'],
      ['key' => 'variant', 'label' => 'Card style — light, dark, or green'],
      ['key' => 'layout', 'label' => 'Card layout — stack (default) or wide (spans both columns, body on left + image on right)'],
      ['key' => 'cta_label', 'label' => 'CTA button label (leave blank for no button)'],
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
