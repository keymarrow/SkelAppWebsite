@component('admin.pages.fields.section', ['title' => 'Hero'])
  @include('admin.pages.fields.text', ['name' => 'hero.eyebrow', 'label' => 'Eyebrow label'])
  @include('admin.pages.fields.text', ['name' => 'hero.title_lead', 'label' => 'Title lead'])
  @include('admin.pages.fields.text', ['name' => 'hero.title_accent', 'label' => 'Title accent'])
  @include('admin.pages.fields.text', ['name' => 'hero.title_trail', 'label' => 'Title trail'])
  @include('admin.pages.fields.textarea', ['name' => 'hero.copy', 'label' => 'Hero copy', 'rows' => 4])
  @include('admin.pages.fields.textarea', ['name' => 'hero.note', 'label' => 'Hero note', 'rows' => 2])
  @include('admin.pages.fields.image', ['name' => 'hero.image', 'label' => 'Hero image'])
  @include('admin.pages.fields.text', ['name' => 'hero.image_alt', 'label' => 'Hero image alt text'])
  @include('admin.pages.fields.text', ['name' => 'hero.primary_label', 'label' => 'Primary button label'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Program steps'])
  @include('admin.pages.fields.repeater', [
    'name' => 'steps',
    'fields' => [
      ['key' => 'label', 'label' => 'Step label'],
      ['key' => 'title', 'label' => 'Step title'],
      ['key' => 'copy', 'label' => 'Step copy', 'type' => 'textarea', 'rows' => 3],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Referral industries'])
  @include('admin.pages.fields.text', ['name' => 'referrals.eyebrow', 'label' => 'Eyebrow line'])
  @include('admin.pages.fields.text', ['name' => 'referrals.title_rest', 'label' => 'Headline remainder'])
  @include('admin.pages.fields.textarea', ['name' => 'referrals.copy', 'label' => 'Section copy', 'rows' => 3])

  @include('admin.pages.fields.repeater', [
    'name' => 'referrals.cards',
    'fields' => [
      ['key' => 'slug', 'label' => 'Retailer slug'],
      ['key' => 'title', 'label' => 'Card title'],
      ['key' => 'category', 'label' => 'Card category'],
      ['key' => 'image', 'label' => 'Card image', 'type' => 'image'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Partner profiles'])
  @include('admin.pages.fields.text', ['name' => 'partners.eyebrow', 'label' => 'Eyebrow label'])
  @include('admin.pages.fields.text', ['name' => 'partners.title', 'label' => 'Section title'])
  @include('admin.pages.fields.textarea', ['name' => 'partners.copy', 'label' => 'Section copy', 'rows' => 3])

  @include('admin.pages.fields.repeater', [
    'name' => 'partners.cards',
    'fields' => [
      ['key' => 'title', 'label' => 'Card title'],
      ['key' => 'category', 'label' => 'Card category'],
      ['key' => 'copy', 'label' => 'Card copy', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'image', 'label' => 'Card image', 'type' => 'image'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Bottom CTA'])
  @include('admin.pages.fields.text', ['name' => 'cta.title', 'label' => 'CTA title'])
  @include('admin.pages.fields.textarea', ['name' => 'cta.copy', 'label' => 'CTA copy', 'rows' => 3])
  @include('admin.pages.fields.text', ['name' => 'cta.button_label', 'label' => 'CTA button label'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'SEO', 'collapsed' => true])
  @include('admin.pages.fields.text', ['name' => 'meta.title', 'label' => 'Browser tab title'])
  @include('admin.pages.fields.textarea', ['name' => 'meta.description', 'label' => 'Meta description', 'rows' => 2])
@endcomponent
