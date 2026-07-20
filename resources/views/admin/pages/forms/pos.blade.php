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

@component('admin.pages.fields.section', ['title' => 'Do it all — money cards'])
  @include('admin.pages.fields.text_typography', ['name' => 'affordable.title', 'label' => 'Section title', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text_typography', ['name' => 'affordable.subtitle', 'label' => 'Section subtitle', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 2])

  @include('admin.pages.fields.repeater', [
    'name' => 'affordable.cards',
    'fields' => [
      ['key' => 'variant', 'label' => 'Card style (light / photo / tint)'],
      ['key' => 'title', 'label' => 'Card title'],
      ['key' => 'copy', 'label' => 'Card copy', 'type' => 'textarea', 'rows' => 2],
      ['key' => 'link_label', 'label' => 'Link label'],
      ['key' => 'link_url', 'label' => 'Link URL'],
      ['key' => 'image', 'label' => 'Card image', 'type' => 'image'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'What SkelApp can do', 'subtitle' => 'Use one row per top tab. In "Accordion items", write one item per line. Optional format: Title | Description | Image'])
  @include('admin.pages.fields.text_typography', ['name' => 'detail.title', 'label' => 'Section title', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text_typography', ['name' => 'detail.subtitle', 'label' => 'Section subtitle', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])

  @include('admin.pages.fields.repeater', [
    'name' => 'feature_groups',
    'fields' => [
      ['key' => 'tab', 'label' => 'Tab label'],
      ['key' => 'title', 'label' => 'Panel title'],
      ['key' => 'body', 'label' => 'Panel intro copy', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'link_label', 'label' => 'Panel link label'],
      ['key' => 'link_url', 'label' => 'Panel link URL'],
      ['key' => 'image', 'label' => 'Panel image', 'type' => 'image'],
      ['key' => 'items_text', 'label' => 'Accordion items', 'type' => 'textarea', 'rows' => 8],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Offline mode band'])
  @include('admin.pages.fields.text_typography', ['name' => 'offline.title', 'label' => 'Section title (wrap words in <strong> to bold)', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text', ['name' => 'offline.subtitle', 'label' => 'Section subtitle'])
  @include('admin.pages.fields.image', ['name' => 'offline.media_image', 'label' => 'Media image'])
  @include('admin.pages.fields.text', ['name' => 'offline.media_caption', 'label' => 'Media caption'])

  @include('admin.pages.fields.repeater', [
    'name' => 'offline.steps',
    'fields' => [
      ['key' => 'body', 'label' => 'Step text', 'type' => 'textarea', 'rows' => 2],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Devices'])
  @include('admin.pages.fields.text_typography', ['name' => 'products.title', 'label' => 'Section title', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text_typography', ['name' => 'products.subtitle', 'label' => 'Section subtitle', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 2])

  @include('admin.pages.fields.repeater', [
    'name' => 'products.cards',
    'fields' => [
      ['key' => 'eyebrow', 'label' => 'Device name'],
      ['key' => 'title', 'label' => 'Card title'],
      ['key' => 'body', 'label' => 'Card copy', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'link_label', 'label' => 'Link label'],
      ['key' => 'link_url', 'label' => 'Link URL'],
      ['key' => 'image', 'label' => 'Card image', 'type' => 'image'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Online ordering band'])
  @include('admin.pages.fields.text_typography', ['name' => 'online.title', 'label' => 'Section title (wrap words in <strong> to bold)', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text', ['name' => 'online.subtitle', 'label' => 'Section subtitle'])
  @include('admin.pages.fields.image', ['name' => 'online.media_image', 'label' => 'Media image'])
  @include('admin.pages.fields.text', ['name' => 'online.media_caption', 'label' => 'Media caption'])

  @include('admin.pages.fields.repeater', [
    'name' => 'online.steps',
    'fields' => [
      ['key' => 'body', 'label' => 'Step text', 'type' => 'textarea', 'rows' => 2],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'After the sale — back office'])
  @include('admin.pages.fields.text_typography', ['name' => 'sync.title', 'label' => 'Section title', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text_typography', ['name' => 'sync.subtitle', 'label' => 'Section subtitle', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 2])

  @include('admin.pages.fields.repeater', [
    'name' => 'sync.cards',
    'fields' => [
      ['key' => 'title', 'label' => 'Card title'],
      ['key' => 'body', 'label' => 'Card copy', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'link_label', 'label' => 'Link label'],
      ['key' => 'link_url', 'label' => 'Link URL'],
      ['key' => 'image', 'label' => 'Card image', 'type' => 'image'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Retailers carousel'])
  @include('admin.pages.fields.text_typography', ['name' => 'retailers.title', 'label' => 'Section title', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text_typography', ['name' => 'retailers.subtitle', 'label' => 'Section subtitle', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 2])

  @include('admin.pages.fields.repeater', [
    'name' => 'retailers.cards',
    'fields' => [
      ['key' => 'category', 'label' => 'Category pill'],
      ['key' => 'title', 'label' => 'Shop title'],
      ['key' => 'copy', 'label' => 'Shop copy'],
      ['key' => 'link_url', 'label' => 'Link URL'],
      ['key' => 'image', 'label' => 'Shop image', 'type' => 'image'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Integrations band'])
  @include('admin.pages.fields.text', ['name' => 'integrations.eyebrow', 'label' => 'Eyebrow label'])
  @include('admin.pages.fields.text_typography', ['name' => 'integrations.title', 'label' => 'Section title', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text_typography', ['name' => 'integrations.body', 'label' => 'Subtitle', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 2])
  @include('admin.pages.fields.text', ['name' => 'integrations.more_label', 'label' => 'Footer link label'])
  @include('admin.pages.fields.text', ['name' => 'integrations.more_url', 'label' => 'Footer link URL'])

  @include('admin.pages.fields.repeater', [
    'name' => 'integrations.logos',
    'fields' => [
      ['key' => 'name', 'label' => 'Brand name'],
      ['key' => 'image', 'label' => 'Logo image', 'type' => 'image'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Connected tools (shared — shown on hardware & retailer pages, not this one)', 'collapsed' => true])
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
  @include('admin.pages.fields.text', ['name' => 'testimonial.section_title', 'label' => 'Section title'])
  @include('admin.pages.fields.textarea', ['name' => 'testimonial.section_subtitle', 'label' => 'Section subtitle', 'rows' => 2])
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

  @include('admin.pages.fields.repeater', [
    'name' => 'testimonials',
    'label' => 'Carousel slides',
    'fields' => [
      ['key' => 'quote_lead', 'label' => 'Slide quote lead', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'quote_brand', 'label' => 'Slide brand highlight'],
      ['key' => 'support', 'label' => 'Slide support copy', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'author', 'label' => 'Slide author'],
      ['key' => 'role', 'label' => 'Slide role'],
      ['key' => 'image', 'label' => 'Slide image', 'type' => 'image'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Pricing banner'])
  @include('admin.pages.fields.text_typography', ['name' => 'pricing.title', 'label' => 'Section title', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text', ['name' => 'pricing.title_accent', 'label' => 'Accent phrase'])
  @include('admin.pages.fields.text', ['name' => 'pricing.link_label', 'label' => 'Link label'])
  @include('admin.pages.fields.text', ['name' => 'pricing.card_name', 'label' => 'Card name'])
  @include('admin.pages.fields.text', ['name' => 'pricing.card_name_accent', 'label' => 'Card name accent'])
  @include('admin.pages.fields.text', ['name' => 'pricing.price_was', 'label' => 'Previous price (shared with hardware page — not shown here)'])
  @include('admin.pages.fields.text', ['name' => 'pricing.price_was_suffix', 'label' => 'Previous price suffix'])
  @include('admin.pages.fields.text', ['name' => 'pricing.price_now', 'label' => 'Price'])
  @include('admin.pages.fields.text', ['name' => 'pricing.price_now_suffix', 'label' => 'Price suffix'])
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

@component('admin.pages.fields.section', ['title' => 'SEO', 'collapsed' => true])
  @include('admin.pages.fields.text', ['name' => 'meta.title', 'label' => 'Browser tab title'])
  @include('admin.pages.fields.textarea', ['name' => 'meta.description', 'label' => 'Meta description', 'rows' => 2])
@endcomponent
