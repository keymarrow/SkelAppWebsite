@component('admin.pages.fields.section', ['title' => 'Hero'])
  @include('admin.pages.fields.text', ['name' => 'hero.eyebrow', 'label' => 'Eyebrow label'])
  @include('admin.pages.fields.text_typography', ['name' => 'hero.title', 'label' => 'Hero title', 'preset' => 'h1-hero'])
  @include('admin.pages.fields.text', ['name' => 'hero.title_accent', 'label' => 'Accent phrase'])
  @include('admin.pages.fields.text_typography', ['name' => 'hero.subtitle', 'label' => 'Hero subtitle', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])
  @include('admin.pages.fields.image', ['name' => 'hero.image', 'label' => 'Hero image'])
  @include('admin.pages.fields.text', ['name' => 'hero.primary_label', 'label' => 'Primary button label'])
  @include('admin.pages.fields.text', ['name' => 'hero.primary_url', 'label' => 'Primary button URL'])
  @include('admin.pages.fields.text', ['name' => 'hero.secondary_label', 'label' => 'Secondary button label'])
  @include('admin.pages.fields.text', ['name' => 'hero.secondary_url', 'label' => 'Secondary button URL'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Core POS features'])
  @include('admin.pages.fields.text_typography', ['name' => 'detail.title', 'label' => 'Section title', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text_typography', ['name' => 'detail.subtitle', 'label' => 'Section subtitle', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])

  @include('admin.pages.fields.repeater', [
    'name' => 'features',
    'fields' => [
      ['key' => 'name', 'label' => 'Feature title'],
      ['key' => 'body', 'label' => 'Feature copy', 'type' => 'textarea', 'rows' => 4],
      ['key' => 'image', 'label' => 'Feature image', 'type' => 'image'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Connected tools showcase'])
  @include('admin.pages.fields.text', ['name' => 'ecosystem.title', 'label' => 'Section title'])
  @include('admin.pages.fields.text', ['name' => 'ecosystem.title_accent', 'label' => 'Accent phrase'])
  @include('admin.pages.fields.textarea', ['name' => 'ecosystem.subtitle', 'label' => 'Section subtitle', 'rows' => 3])

  @include('admin.pages.fields.repeater', [
    'name' => 'ecosystem.features',
    'fields' => [
      ['key' => 'name', 'label' => 'Feature title'],
      ['key' => 'body', 'label' => 'Feature copy', 'type' => 'textarea', 'rows' => 4],
      ['key' => 'image', 'label' => 'Feature image', 'type' => 'image'],
      ['key' => 'link_label', 'label' => 'Feature link label'],
      ['key' => 'link_url', 'label' => 'Feature link URL'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Testimonial'])
  @include('admin.pages.fields.textarea', ['name' => 'testimonial.quote_lead', 'label' => 'Quote lead', 'rows' => 3])
  @include('admin.pages.fields.text', ['name' => 'testimonial.quote_brand', 'label' => 'Quote brand highlight'])
  @include('admin.pages.fields.textarea', ['name' => 'testimonial.support', 'label' => 'Support copy', 'rows' => 3])
  @include('admin.pages.fields.text', ['name' => 'testimonial.author', 'label' => 'Author'])
  @include('admin.pages.fields.text', ['name' => 'testimonial.role', 'label' => 'Author role'])
  @include('admin.pages.fields.image', ['name' => 'testimonial.image', 'label' => 'Author image'])
  @include('admin.pages.fields.text', ['name' => 'testimonial.primary_label', 'label' => 'Primary button label'])
  @include('admin.pages.fields.text', ['name' => 'testimonial.secondary_label', 'label' => 'Secondary button label'])

  @include('admin.pages.fields.repeater', [
    'name' => 'testimonial.stats',
    'fields' => [
      ['key' => 'value', 'label' => 'Stat value'],
      ['key' => 'label', 'label' => 'Stat label'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Pricing CTA'])
  @include('admin.pages.fields.text_typography', ['name' => 'pricing.title', 'label' => 'Section title', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text', ['name' => 'pricing.title_accent', 'label' => 'Accent phrase'])
  @include('admin.pages.fields.text', ['name' => 'pricing.link_label', 'label' => 'Link label'])
  @include('admin.pages.fields.text', ['name' => 'pricing.card_name', 'label' => 'Card name'])
  @include('admin.pages.fields.text', ['name' => 'pricing.card_name_accent', 'label' => 'Card name accent'])
  @include('admin.pages.fields.text', ['name' => 'pricing.price_was', 'label' => 'Previous price'])
  @include('admin.pages.fields.text', ['name' => 'pricing.price_was_suffix', 'label' => 'Previous price suffix'])
  @include('admin.pages.fields.text', ['name' => 'pricing.price_now', 'label' => 'Current price'])
  @include('admin.pages.fields.text', ['name' => 'pricing.price_now_suffix', 'label' => 'Current price suffix'])
  @include('admin.pages.fields.textarea', ['name' => 'pricing.note', 'label' => 'Pricing note', 'rows' => 2])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'FAQ'])
  @include('admin.pages.fields.text', ['name' => 'faq.title', 'label' => 'FAQ heading'])
  @include('admin.pages.fields.text', ['name' => 'faq.title_accent', 'label' => 'FAQ heading accent'])
  @include('admin.pages.fields.text', ['name' => 'faq.subtitle', 'label' => 'FAQ subtitle'])
  @include('admin.pages.fields.text', ['name' => 'faq.read_more_label', 'label' => 'Read more label'])

  @include('admin.pages.fields.repeater', [
    'name' => 'faq.items',
    'fields' => [
      ['key' => 'q', 'label' => 'Question'],
      ['key' => 'a', 'label' => 'Answer', 'type' => 'textarea', 'rows' => 4],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Closing CTA'])
  @include('admin.pages.fields.text_typography', ['name' => 'cta.title', 'label' => 'CTA title', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text', ['name' => 'cta.title_accent', 'label' => 'CTA title accent'])
  @include('admin.pages.fields.text', ['name' => 'cta.link_label', 'label' => 'Secondary link label'])
  @include('admin.pages.fields.textarea', ['name' => 'cta.note', 'label' => 'CTA note', 'rows' => 2])
  @include('admin.pages.fields.text', ['name' => 'cta.primary_label', 'label' => 'Primary button label'])
  @include('admin.pages.fields.text', ['name' => 'cta.primary_url', 'label' => 'Primary button URL'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'SEO', 'collapsed' => true])
  @include('admin.pages.fields.text', ['name' => 'meta.title', 'label' => 'Browser tab title'])
  @include('admin.pages.fields.textarea', ['name' => 'meta.description', 'label' => 'Meta description', 'rows' => 2])
@endcomponent
