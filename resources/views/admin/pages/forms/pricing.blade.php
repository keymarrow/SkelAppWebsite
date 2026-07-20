@component('admin.pages.fields.section', ['title' => 'Hero banner', 'subtitle' => 'Full-width photo banner at the top of the page'])
  @include('admin.pages.fields.image', ['name' => 'hero.image', 'label' => 'Background image'])
  @include('admin.pages.fields.text', ['name' => 'hero.eyebrow', 'label' => 'Eyebrow (small label above the title)'])
  @include('admin.pages.fields.text_typography', ['name' => 'hero.title', 'label' => 'Hero title', 'preset' => 'h1-hero', 'multiline' => true, 'rows' => 2, 'hint' => 'Each line break starts a new line of the headline.'])

  @include('admin.pages.fields.repeater', [
    'name' => 'hero.benefits',
    'label' => 'Benefit row (under the title)',
    'fields' => [
      ['key' => 'text', 'label' => 'Benefit text'],
      ['key' => 'icon', 'label' => 'Icon — one of: tag, bolt, setup, support (anything else shows a tick)'],
    ],
  ])

  @include('admin.pages.fields.text', ['name' => 'hero.alt_prefix', 'label' => 'Footer line — lead-in text'])
  @include('admin.pages.fields.text', ['name' => 'hero.alt_label', 'label' => 'Footer line — link text'])
  @include('admin.pages.fields.text', ['name' => 'hero.alt_url', 'label' => 'Footer line — link URL'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Billing toggle', 'subtitle' => 'The Monthly / Yearly switch above the tier cards. Yearly is selected by default.'])
  @include('admin.pages.fields.text', ['name' => 'billing.monthly_label', 'label' => 'Monthly button label'])
  @include('admin.pages.fields.text', ['name' => 'billing.yearly_label', 'label' => 'Yearly button label'])
  @include('admin.pages.fields.text', ['name' => 'billing.yearly_hint', 'label' => 'Yearly badge (e.g. "Save 17%") — leave blank to hide'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Pricing tiers', 'subtitle' => 'The three plan cards. Add a row per tier — they render left to right in this order.'])
  @include('admin.pages.fields.text', ['name' => 'tiers_features_heading', 'label' => 'Heading above each feature list (e.g. "What you get")'])

  @include('admin.pages.fields.repeater', [
    'name' => 'tiers',
    'label' => 'Tiers',
    'fields' => [
      ['key' => 'name', 'label' => 'Tier name'],
      ['key' => 'description', 'label' => 'Short description under the name', 'type' => 'textarea', 'rows' => 2],
      ['key' => 'price', 'label' => 'Yearly price — shown by default (e.g. "Free", "TZS 12,500", "Custom pricing")'],
      ['key' => 'price_monthly', 'label' => 'Monthly price — leave blank to reuse the yearly price (Free / Custom)'],
      ['key' => 'price_suffix', 'label' => 'Price suffix (e.g. "/month") — leave blank to hide'],
      ['key' => 'price_note', 'label' => 'Yearly small print under the price — leave blank to hide'],
      ['key' => 'price_note_monthly', 'label' => 'Monthly small print — leave blank to reuse the yearly note'],
      ['key' => 'cta_label', 'label' => 'Button label'],
      ['key' => 'cta_url', 'label' => 'Button URL (blank = Contact page)'],
      ['key' => 'features', 'label' => 'Features — ONE PER LINE', 'type' => 'textarea', 'rows' => 10],
      ['key' => 'badge', 'label' => 'Badge text (e.g. "Best value") — leave blank to hide'],
      ['key' => 'is_featured', 'label' => 'Highlight this tier (green border + solid button)', 'type' => 'checkbox'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => '"Get Started" footer block', 'subtitle' => 'Phone mockup + download CTA at the bottom of the page'])
  @include('admin.pages.fields.text_typography', ['name' => 'getstarted.title_top', 'label' => 'Title — first line', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text', ['name' => 'getstarted.title_bottom', 'label' => 'Title — second line'])
  @include('admin.pages.fields.text', ['name' => 'getstarted.cta_label', 'label' => 'Button text'])
  @include('admin.pages.fields.text', ['name' => 'getstarted.cta_url', 'label' => 'Button URL'])
  @include('admin.pages.fields.text_typography', ['name' => 'getstarted.copy', 'label' => 'Supporting paragraph', 'preset' => 'body', 'multiline' => true, 'rows' => 3])
  @include('admin.pages.fields.image', ['name' => 'getstarted.phone_image', 'label' => 'Phone mockup image', 'hint' => 'PNG or WebP recommended.'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'SEO', 'subtitle' => 'Browser tab title + search-result description', 'collapsed' => true])
  @include('admin.pages.fields.text', ['name' => 'meta.title', 'label' => 'Browser tab title'])
  @include('admin.pages.fields.textarea', ['name' => 'meta.description', 'label' => 'Meta description', 'rows' => 2])
@endcomponent
