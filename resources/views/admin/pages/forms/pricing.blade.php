@component('admin.pages.fields.section', ['title' => 'Hero banner', 'subtitle' => 'Full-width photo banner at the top of the page'])
  @include('admin.pages.fields.image', ['name' => 'hero.image', 'label' => 'Background image'])
  @include('admin.pages.fields.text', ['name' => 'hero.trust_label', 'label' => 'Trust line — prefix (e.g. "Trusted by")'])
  @include('admin.pages.fields.text', ['name' => 'hero.trust_count', 'label' => 'Trust line — count (e.g. "10,000+")'])
  @include('admin.pages.fields.text', ['name' => 'hero.trust_suffix', 'label' => 'Trust line — suffix (e.g. "small businesses")'])
  @include('admin.pages.fields.text_typography', ['name' => 'hero.title', 'label' => 'Hero title', 'preset' => 'h1-hero', 'multiline' => true, 'rows' => 2])
  @include('admin.pages.fields.text_typography', ['name' => 'hero.subtitle', 'label' => 'Hero subtitle', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 2])
  @include('admin.pages.fields.text', ['name' => 'hero.primary_label', 'label' => 'Primary button label'])
  @include('admin.pages.fields.text', ['name' => 'hero.primary_url', 'label' => 'Primary button URL'])
  @include('admin.pages.fields.text', ['name' => 'hero.secondary_label', 'label' => 'Secondary button label'])
  @include('admin.pages.fields.text', ['name' => 'hero.secondary_url', 'label' => 'Secondary button URL'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Page header', 'subtitle' => 'Big stacked title + supporting paragraph'])
  @include('admin.pages.fields.repeater', [
    'name' => 'header.title_lines',
    'fields' => [
      ['key' => 'text', 'label' => 'Line text'],
      ['key' => 'accent', 'label' => 'Style as green accent line', 'type' => 'checkbox'],
    ],
  ])

  @include('admin.pages.fields.text_typography', ['name' => 'header.subtitle', 'label' => 'Subtitle paragraph', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Features box', 'subtitle' => 'Left-hand card on the pricing layout'])
  @include('admin.pages.fields.text_typography', ['name' => 'features_box.title', 'label' => 'Box heading', 'preset' => 'h3-card'])
  @include('admin.pages.fields.text_typography', ['name' => 'features_box.subtitle', 'label' => 'Box subtitle', 'preset' => 'body'])

  @include('admin.pages.fields.repeater', [
    'name' => 'features',
    'flat' => true,
    'fields' => [
      ['key' => 'value', 'label' => 'Feature'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Pricing plans', 'subtitle' => 'Right-hand card with the radio plans'])
  @include('admin.pages.fields.text_typography', ['name' => 'plans_box.title', 'label' => 'Box heading', 'preset' => 'h3-card'])
  @include('admin.pages.fields.text_typography', ['name' => 'plans_box.subtitle', 'label' => 'Box subtitle', 'preset' => 'body'])

  @include('admin.pages.fields.repeater', [
    'name' => 'plans',
    'fields' => [
      ['key' => 'id', 'label' => 'Internal ID (no spaces — used for the radio value)'],
      ['key' => 'label', 'label' => 'Plan name (e.g. Monthly)'],
      ['key' => 'note', 'label' => 'Note / savings line'],
      ['key' => 'price', 'label' => 'Headline price'],
      ['key' => 'sub', 'label' => 'Sub-text (e.g. billed monthly)'],
      ['key' => 'is_default', 'label' => 'Preselected by default', 'type' => 'checkbox'],
    ],
  ])

  @include('admin.pages.fields.text', ['name' => 'cta.prefix', 'label' => 'CTA button text prefix (the plan name is appended automatically)'])
  @include('admin.pages.fields.text', ['name' => 'cta.url', 'label' => 'CTA URL override (leave blank to use the Contact page)'])
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
