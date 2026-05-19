@component('admin.pages.fields.section', ['title' => 'Hero'])
  @include('admin.pages.fields.text_typography', ['name' => 'hero.title', 'label' => 'Headline (big H1)', 'preset' => 'h1-hero'])
  @include('admin.pages.fields.text_typography', ['name' => 'hero.subtitle', 'label' => 'Subtitle paragraph', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])
  @include('admin.pages.fields.text', ['name' => 'hero.cta_label', 'label' => 'CTA button label'])
  @include('admin.pages.fields.text', ['name' => 'hero.cta_url', 'label' => 'CTA button URL'])
  @include('admin.pages.fields.image', ['name' => 'hero.background_image_desktop', 'label' => 'Hero background image — desktop', 'hint' => 'Used at min-width: 901px. WebP recommended.'])
  @include('admin.pages.fields.image', ['name' => 'hero.background_image_mobile', 'label' => 'Hero background image — mobile', 'hint' => 'Used at max-width: 900px.'])
  @include('admin.pages.fields.text_typography', ['name' => 'hero.testimonial_quote', 'label' => 'Testimonial quote', 'preset' => 'body', 'multiline' => true, 'rows' => 3])
  @include('admin.pages.fields.text_typography', ['name' => 'hero.testimonial_attribution', 'label' => 'Testimonial attribution', 'preset' => 'label'])
  @include('admin.pages.fields.image', ['name' => 'hero.testimonial_stars_image', 'label' => 'Testimonial star rating image'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'App showcase ("POS, But 1% Better")'])
  @include('admin.pages.fields.text_typography', ['name' => 'showcase.title', 'label' => 'Section heading', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text_typography', ['name' => 'showcase.subtitle_primary', 'label' => 'Primary subtitle', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])
  @include('admin.pages.fields.text_typography', ['name' => 'showcase.subtitle_secondary', 'label' => 'Secondary subtitle (desktop)', 'preset' => 'body', 'multiline' => true, 'rows' => 2])
  @include('admin.pages.fields.text_typography', ['name' => 'showcase.subtitle_mobile', 'label' => 'Mobile subtitle (replaces both on small screens)', 'preset' => 'body', 'multiline' => true, 'rows' => 3])
  @include('admin.pages.fields.image', ['name' => 'showcase.device_image_desktop', 'label' => 'Device mockup — desktop'])
  @include('admin.pages.fields.image', ['name' => 'showcase.device_image_mobile', 'label' => 'Device mockup — mobile'])

  @include('admin.pages.fields.repeater', [
    'name' => 'showcase.points',
    'fields' => [
      ['key' => 'title', 'label' => 'Point title', 'type' => 'text_typography', 'preset' => 'h3-card'],
      ['key' => 'body', 'label' => 'Point body', 'type' => 'text_typography', 'preset' => 'body', 'multiline' => true, 'rows' => 2],
      ['key' => 'icon', 'label' => 'Icon image', 'type' => 'image'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Retailers carousel'])
  @include('admin.pages.fields.text_typography', ['name' => 'retailers.title', 'label' => 'Section heading', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text_typography', ['name' => 'retailers.subtitle', 'label' => 'Section subtitle', 'preset' => 'body', 'multiline' => true, 'rows' => 2])
  @include('admin.pages.fields.text', ['name' => 'retailers.cta_label', 'label' => '"Talk to our Team" button label'])
  @include('admin.pages.fields.text_typography', ['name' => 'retailers.bottom_title', 'label' => 'Bottom section title', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text_typography', ['name' => 'retailers.bottom_copy', 'label' => 'Bottom section copy', 'preset' => 'body', 'multiline' => true, 'rows' => 2])

  @include('admin.pages.fields.repeater', [
    'name' => 'retailers.cards',
    'fields' => [
      ['key' => 'image', 'label' => 'Card image', 'type' => 'image'],
      ['key' => 'title', 'label' => 'Card title', 'type' => 'text_typography', 'preset' => 'h3-card'],
      ['key' => 'copy', 'label' => 'Card copy', 'type' => 'text_typography', 'preset' => 'body', 'multiline' => true, 'rows' => 2],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Features grid (4 hero cards)', 'collapsed' => true])
  <p class="cms-field-hint" style="margin-bottom: 12px;">Four feature cards in a 2×2 grid.</p>

  <h4 style="margin: 8px 0;">Top-left card (Catalogue)</h4>
  @include('admin.pages.fields.text_typography', ['name' => 'features.top_left.title', 'label' => 'Title', 'preset' => 'h3-card'])
  @include('admin.pages.fields.text_typography', ['name' => 'features.top_left.body', 'label' => 'Body', 'preset' => 'body', 'multiline' => true, 'rows' => 3])
  @include('admin.pages.fields.image', ['name' => 'features.top_left.image', 'label' => 'Card image'])

  <h4 style="margin: 16px 0 8px;">Top-right card (Mobile App)</h4>
  @include('admin.pages.fields.text_typography', ['name' => 'features.top_right.title', 'label' => 'Title (line 1)', 'preset' => 'h3-card'])
  @include('admin.pages.fields.text', ['name' => 'features.top_right.title_line_2', 'label' => 'Title (line 2)'])
  @include('admin.pages.fields.text_typography', ['name' => 'features.top_right.body', 'label' => 'Body', 'preset' => 'body', 'multiline' => true, 'rows' => 3])
  @include('admin.pages.fields.image', ['name' => 'features.top_right.image', 'label' => 'Card image'])

  <h4 style="margin: 16px 0 8px;">Bottom-left card (Multi-device)</h4>
  @include('admin.pages.fields.text_typography', ['name' => 'features.bottom_left.title', 'label' => 'Title', 'preset' => 'h3-card'])
  @include('admin.pages.fields.text_typography', ['name' => 'features.bottom_left.body', 'label' => 'Body', 'preset' => 'body', 'multiline' => true, 'rows' => 3])
  @include('admin.pages.fields.image', ['name' => 'features.bottom_left.image', 'label' => 'Card image'])

  <h4 style="margin: 16px 0 8px;">Bottom-right card (Reporting)</h4>
  @include('admin.pages.fields.text_typography', ['name' => 'features.bottom_right.title', 'label' => 'Title', 'preset' => 'h3-card'])
  @include('admin.pages.fields.text_typography', ['name' => 'features.bottom_right.body', 'label' => 'Body', 'preset' => 'body', 'multiline' => true, 'rows' => 3])
  @include('admin.pages.fields.image', ['name' => 'features.bottom_right.image', 'label' => 'Card image'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'All features grid (6 cards)'])
  @include('admin.pages.fields.text_typography', ['name' => 'allfeatures.title_line_1', 'label' => 'Heading line 1', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text', ['name' => 'allfeatures.title_line_2', 'label' => 'Heading line 2'])
  @include('admin.pages.fields.text_typography', ['name' => 'allfeatures.copy', 'label' => 'Intro paragraph', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])
  @include('admin.pages.fields.text', ['name' => 'allfeatures.cta_label', 'label' => 'CTA button label'])
  @include('admin.pages.fields.text', ['name' => 'allfeatures.cta_url', 'label' => 'CTA button URL'])

  @include('admin.pages.fields.repeater', [
    'name' => 'allfeatures.cards',
    'fields' => [
      ['key' => 'image', 'label' => 'Card image', 'type' => 'image'],
      ['key' => 'title', 'label' => 'Card title', 'type' => 'text_typography', 'preset' => 'h3-card'],
      ['key' => 'copy', 'label' => 'Card copy', 'type' => 'text_typography', 'preset' => 'body', 'multiline' => true, 'rows' => 3],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'How it works (3 steps)'])
  @include('admin.pages.fields.text_typography', ['name' => 'howitworks.title', 'label' => 'Section title', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text_typography', ['name' => 'howitworks.copy', 'label' => 'Section copy', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 2])
  @include('admin.pages.fields.text', ['name' => 'howitworks.cta_label', 'label' => 'CTA button label'])
  @include('admin.pages.fields.text', ['name' => 'howitworks.cta_url', 'label' => 'CTA button URL'])

  @include('admin.pages.fields.repeater', [
    'name' => 'howitworks.steps',
    'fields' => [
      ['key' => 'image', 'label' => 'Step image', 'type' => 'image'],
      ['key' => 'title', 'label' => 'Step title', 'type' => 'text_typography', 'preset' => 'h3-card'],
      ['key' => 'copy', 'label' => 'Step copy', 'type' => 'text_typography', 'preset' => 'body', 'multiline' => true, 'rows' => 3],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Hardware section'])
  @include('admin.pages.fields.text_typography', ['name' => 'hardware.label', 'label' => 'Eyebrow label', 'preset' => 'label'])
  @include('admin.pages.fields.text_typography', ['name' => 'hardware.title', 'label' => 'Title', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text_typography', ['name' => 'hardware.copy', 'label' => 'Body copy', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])
  @include('admin.pages.fields.text', ['name' => 'hardware.cta_label', 'label' => 'CTA button label'])
  @include('admin.pages.fields.text', ['name' => 'hardware.cta_url', 'label' => 'CTA button URL'])
  @include('admin.pages.fields.image', ['name' => 'hardware.image', 'label' => 'Hardware image (right side)'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Pricing summary (the small pricing block on the home page)', 'collapsed' => true])
  @include('admin.pages.fields.text_typography', ['name' => 'pricing_summary.intro', 'label' => 'Top intro line', 'preset' => 'label'])
  @include('admin.pages.fields.text_typography', ['name' => 'pricing_summary.title_line_1', 'label' => 'Title line 1', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text', ['name' => 'pricing_summary.title_line_2', 'label' => 'Title line 2'])
  @include('admin.pages.fields.text_typography', ['name' => 'pricing_summary.description', 'label' => 'Description', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])

  @include('admin.pages.fields.image', ['name' => 'pricing_summary.card_image', 'label' => 'Main preview card image'])

  @include('admin.pages.fields.repeater', [
    'name' => 'pricing_summary.thumbnails',
    'fields' => [
      ['key' => 'image', 'label' => 'Thumbnail image', 'type' => 'image'],
      ['key' => 'alt', 'label' => 'Alt text'],
    ],
  ])

  @include('admin.pages.fields.repeater', [
    'name' => 'pricing_summary.features',
    'flat' => true,
    'fields' => [['key' => 'value', 'label' => 'Feature line']],
  ])

  @include('admin.pages.fields.text', ['name' => 'pricing_summary.currency', 'label' => 'Currency (e.g. TZS)'])
  @include('admin.pages.fields.text', ['name' => 'pricing_summary.price_main', 'label' => 'Price (e.g. 15,000)'])
  @include('admin.pages.fields.text', ['name' => 'pricing_summary.price_period', 'label' => 'Period text (e.g. /month · billed annually)'])
  @include('admin.pages.fields.text', ['name' => 'pricing_summary.payment_label', 'label' => 'Payment label'])
  @include('admin.pages.fields.image', ['name' => 'pricing_summary.payment_methods_image', 'label' => 'Payment methods image'])
  @include('admin.pages.fields.text', ['name' => 'pricing_summary.cta_label', 'label' => 'CTA button label'])
  @include('admin.pages.fields.text', ['name' => 'pricing_summary.cta_url', 'label' => 'CTA button URL'])

  @include('admin.pages.fields.repeater', [
    'name' => 'pricing_summary.benefits',
    'flat' => true,
    'fields' => [['key' => 'value', 'label' => 'Benefit text']],
  ])

  @include('admin.pages.fields.text', ['name' => 'pricing_summary.benefit_mobile_2', 'label' => 'Second benefit — mobile-only override (shorter)'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Image CTA (bottom banner)'])
  @include('admin.pages.fields.text_typography', ['name' => 'image_cta.heading', 'label' => 'Heading', 'preset' => 'h2-section', 'multiline' => true, 'rows' => 2])
  @include('admin.pages.fields.text', ['name' => 'image_cta.cta_label', 'label' => 'CTA button label'])
  @include('admin.pages.fields.text', ['name' => 'image_cta.cta_url', 'label' => 'CTA button URL'])
  @include('admin.pages.fields.image', ['name' => 'image_cta.background_image', 'label' => 'Background image'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'SEO', 'collapsed' => true])
  @include('admin.pages.fields.text', ['name' => 'meta.title', 'label' => 'Browser tab title'])
  @include('admin.pages.fields.textarea', ['name' => 'meta.description', 'label' => 'Meta description', 'rows' => 2])
@endcomponent
