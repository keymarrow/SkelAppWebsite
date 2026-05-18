@component('admin.pages.fields.section', ['title' => 'Page header', 'subtitle' => 'Big stacked title + supporting paragraph'])
  @include('admin.pages.fields.repeater', [
    'name' => 'header.title_lines',
    'fields' => [
      ['key' => 'text', 'label' => 'Line text'],
      ['key' => 'accent', 'label' => 'Style as green accent line', 'type' => 'checkbox'],
    ],
  ])

  @include('admin.pages.fields.textarea', ['name' => 'header.subtitle', 'label' => 'Subtitle paragraph', 'rows' => 3])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Features box', 'subtitle' => 'Left-hand card on the pricing layout'])
  @include('admin.pages.fields.text', ['name' => 'features_box.title', 'label' => 'Box heading'])
  @include('admin.pages.fields.text', ['name' => 'features_box.subtitle', 'label' => 'Box subtitle'])

  @include('admin.pages.fields.repeater', [
    'name' => 'features',
    'fields' => [
      ['key' => 'value', 'label' => 'Feature'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Pricing plans', 'subtitle' => 'Right-hand card with the radio plans'])
  @include('admin.pages.fields.text', ['name' => 'plans_box.title', 'label' => 'Box heading'])
  @include('admin.pages.fields.text', ['name' => 'plans_box.subtitle', 'label' => 'Box subtitle'])

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
  @include('admin.pages.fields.text', ['name' => 'getstarted.title_top', 'label' => 'Title — first line'])
  @include('admin.pages.fields.text', ['name' => 'getstarted.title_bottom', 'label' => 'Title — second line'])
  @include('admin.pages.fields.text', ['name' => 'getstarted.cta_label', 'label' => 'Button text'])
  @include('admin.pages.fields.text', ['name' => 'getstarted.cta_url', 'label' => 'Button URL'])
  @include('admin.pages.fields.textarea', ['name' => 'getstarted.copy', 'label' => 'Supporting paragraph', 'rows' => 3])
  @include('admin.pages.fields.image', ['name' => 'getstarted.phone_image', 'label' => 'Phone mockup image', 'hint' => 'PNG or WebP recommended.'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'SEO', 'subtitle' => 'Browser tab title + search-result description', 'collapsed' => true])
  @include('admin.pages.fields.text', ['name' => 'meta.title', 'label' => 'Browser tab title'])
  @include('admin.pages.fields.textarea', ['name' => 'meta.description', 'label' => 'Meta description', 'rows' => 2])
@endcomponent
