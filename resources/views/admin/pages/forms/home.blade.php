@component('admin.pages.fields.section', ['title' => 'Hero'])
  @include('admin.pages.fields.text', ['name' => 'hero.title', 'label' => 'Headline (big H1)'])
  @include('admin.pages.fields.textarea', ['name' => 'hero.subtitle', 'label' => 'Subtitle paragraph', 'rows' => 3])
  @include('admin.pages.fields.text', ['name' => 'hero.cta_label', 'label' => 'CTA button label'])
  @include('admin.pages.fields.text', ['name' => 'hero.cta_url', 'label' => 'CTA button URL'])
  @include('admin.pages.fields.textarea', ['name' => 'hero.testimonial_quote', 'label' => 'Testimonial quote', 'rows' => 3])
  @include('admin.pages.fields.text', ['name' => 'hero.testimonial_attribution', 'label' => 'Testimonial attribution'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'App showcase ("POS, But 1% Better")'])
  @include('admin.pages.fields.text', ['name' => 'showcase.title', 'label' => 'Section heading'])
  @include('admin.pages.fields.textarea', ['name' => 'showcase.subtitle_primary', 'label' => 'Primary subtitle', 'rows' => 3])
  @include('admin.pages.fields.textarea', ['name' => 'showcase.subtitle_secondary', 'label' => 'Secondary subtitle (desktop)', 'rows' => 2])
  @include('admin.pages.fields.textarea', ['name' => 'showcase.subtitle_mobile', 'label' => 'Mobile subtitle (replaces both on small screens)', 'rows' => 3])

  @include('admin.pages.fields.repeater', [
    'name' => 'showcase.points',
    'fields' => [
      ['key' => 'title', 'label' => 'Point title'],
      ['key' => 'body', 'label' => 'Point body'],
      ['key' => 'icon', 'label' => 'Icon filename (in /assets, e.g. speed.svg)'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Retailers carousel'])
  @include('admin.pages.fields.text', ['name' => 'retailers.title', 'label' => 'Section heading'])
  @include('admin.pages.fields.textarea', ['name' => 'retailers.subtitle', 'label' => 'Section subtitle', 'rows' => 2])
  @include('admin.pages.fields.text', ['name' => 'retailers.cta_label', 'label' => '"Talk to our Team" button label'])
  @include('admin.pages.fields.text', ['name' => 'retailers.bottom_title', 'label' => 'Bottom section title'])
  @include('admin.pages.fields.textarea', ['name' => 'retailers.bottom_copy', 'label' => 'Bottom section copy', 'rows' => 2])

  @include('admin.pages.fields.repeater', [
    'name' => 'retailers.cards',
    'fields' => [
      ['key' => 'image', 'label' => 'Image filename (in /assets, e.g. boutique.png — .webp version loaded automatically)'],
      ['key' => 'title', 'label' => 'Card title'],
      ['key' => 'copy', 'label' => 'Card copy'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Features grid (4 hero cards)', 'collapsed' => true])
  <p class="cms-field-hint" style="margin-bottom: 12px;">Four feature cards in a 2×2 grid.</p>

  <h4 style="margin: 8px 0;">Top-left card (Catalogue)</h4>
  @include('admin.pages.fields.text', ['name' => 'features.top_left.title', 'label' => 'Title'])
  @include('admin.pages.fields.textarea', ['name' => 'features.top_left.body', 'label' => 'Body', 'rows' => 3])

  <h4 style="margin: 16px 0 8px;">Top-right card (Mobile App)</h4>
  @include('admin.pages.fields.text', ['name' => 'features.top_right.title', 'label' => 'Title (line 1)'])
  @include('admin.pages.fields.text', ['name' => 'features.top_right.title_line_2', 'label' => 'Title (line 2)'])
  @include('admin.pages.fields.textarea', ['name' => 'features.top_right.body', 'label' => 'Body', 'rows' => 3])

  <h4 style="margin: 16px 0 8px;">Bottom-left card (Multi-device)</h4>
  @include('admin.pages.fields.text', ['name' => 'features.bottom_left.title', 'label' => 'Title'])
  @include('admin.pages.fields.textarea', ['name' => 'features.bottom_left.body', 'label' => 'Body', 'rows' => 3])

  <h4 style="margin: 16px 0 8px;">Bottom-right card (Reporting)</h4>
  @include('admin.pages.fields.text', ['name' => 'features.bottom_right.title', 'label' => 'Title'])
  @include('admin.pages.fields.textarea', ['name' => 'features.bottom_right.body', 'label' => 'Body', 'rows' => 3])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'All features grid (6 cards)'])
  @include('admin.pages.fields.text', ['name' => 'allfeatures.title_line_1', 'label' => 'Heading line 1'])
  @include('admin.pages.fields.text', ['name' => 'allfeatures.title_line_2', 'label' => 'Heading line 2'])
  @include('admin.pages.fields.textarea', ['name' => 'allfeatures.copy', 'label' => 'Intro paragraph', 'rows' => 3])
  @include('admin.pages.fields.text', ['name' => 'allfeatures.cta_label', 'label' => 'CTA button label'])
  @include('admin.pages.fields.text', ['name' => 'allfeatures.cta_url', 'label' => 'CTA button URL'])

  @include('admin.pages.fields.repeater', [
    'name' => 'allfeatures.cards',
    'fields' => [
      ['key' => 'image', 'label' => 'Image filename (in /assets, .webp loaded)'],
      ['key' => 'title', 'label' => 'Card title'],
      ['key' => 'copy', 'label' => 'Card copy', 'type' => 'textarea', 'rows' => 3],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'How it works (3 steps)'])
  @include('admin.pages.fields.text', ['name' => 'howitworks.title', 'label' => 'Section title'])
  @include('admin.pages.fields.textarea', ['name' => 'howitworks.copy', 'label' => 'Section copy', 'rows' => 2])
  @include('admin.pages.fields.text', ['name' => 'howitworks.cta_label', 'label' => 'CTA button label'])
  @include('admin.pages.fields.text', ['name' => 'howitworks.cta_url', 'label' => 'CTA button URL'])

  @include('admin.pages.fields.repeater', [
    'name' => 'howitworks.steps',
    'fields' => [
      ['key' => 'image', 'label' => 'Image filename (in /assets)'],
      ['key' => 'title', 'label' => 'Step title'],
      ['key' => 'copy', 'label' => 'Step copy', 'type' => 'textarea', 'rows' => 3],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Hardware section'])
  @include('admin.pages.fields.text', ['name' => 'hardware.label', 'label' => 'Eyebrow label'])
  @include('admin.pages.fields.text', ['name' => 'hardware.title', 'label' => 'Title'])
  @include('admin.pages.fields.textarea', ['name' => 'hardware.copy', 'label' => 'Body copy', 'rows' => 3])
  @include('admin.pages.fields.text', ['name' => 'hardware.cta_label', 'label' => 'CTA button label'])
  @include('admin.pages.fields.text', ['name' => 'hardware.cta_url', 'label' => 'CTA button URL'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Pricing summary (the small pricing block on the home page)', 'collapsed' => true])
  @include('admin.pages.fields.text', ['name' => 'pricing_summary.intro', 'label' => 'Top intro line'])
  @include('admin.pages.fields.text', ['name' => 'pricing_summary.title_line_1', 'label' => 'Title line 1'])
  @include('admin.pages.fields.text', ['name' => 'pricing_summary.title_line_2', 'label' => 'Title line 2'])
  @include('admin.pages.fields.textarea', ['name' => 'pricing_summary.description', 'label' => 'Description', 'rows' => 3])

  @include('admin.pages.fields.repeater', [
    'name' => 'pricing_summary.features',
    'fields' => [['key' => 'value', 'label' => 'Feature line']],
  ])

  @include('admin.pages.fields.text', ['name' => 'pricing_summary.currency', 'label' => 'Currency (e.g. TZS)'])
  @include('admin.pages.fields.text', ['name' => 'pricing_summary.price_main', 'label' => 'Price (e.g. 15,000)'])
  @include('admin.pages.fields.text', ['name' => 'pricing_summary.price_period', 'label' => 'Period text (e.g. /month · billed annually)'])
  @include('admin.pages.fields.text', ['name' => 'pricing_summary.payment_label', 'label' => 'Payment label'])
  @include('admin.pages.fields.text', ['name' => 'pricing_summary.cta_label', 'label' => 'CTA button label'])
  @include('admin.pages.fields.text', ['name' => 'pricing_summary.cta_url', 'label' => 'CTA button URL'])

  @include('admin.pages.fields.repeater', [
    'name' => 'pricing_summary.benefits',
    'fields' => [['key' => 'value', 'label' => 'Benefit text']],
  ])

  @include('admin.pages.fields.text', ['name' => 'pricing_summary.benefit_mobile_2', 'label' => 'Second benefit — mobile-only override (shorter)'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Image CTA (bottom banner)'])
  @include('admin.pages.fields.textarea', ['name' => 'image_cta.heading', 'label' => 'Heading', 'rows' => 2])
  @include('admin.pages.fields.text', ['name' => 'image_cta.cta_label', 'label' => 'CTA button label'])
  @include('admin.pages.fields.text', ['name' => 'image_cta.cta_url', 'label' => 'CTA button URL'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'SEO', 'collapsed' => true])
  @include('admin.pages.fields.text', ['name' => 'meta.title', 'label' => 'Browser tab title'])
  @include('admin.pages.fields.textarea', ['name' => 'meta.description', 'label' => 'Meta description', 'rows' => 2])
@endcomponent
