@component('admin.pages.fields.section', ['title' => 'Hero banner'])
  @include('admin.pages.fields.text', ['name' => 'hero.eyebrow', 'label' => 'Eyebrow label'])
  @include('admin.pages.fields.text_typography', ['name' => 'hero.title', 'label' => 'Hero title', 'preset' => 'h1-hero', 'multiline' => true, 'rows' => 3])
  @include('admin.pages.fields.text_typography', ['name' => 'hero.subtitle', 'label' => 'Hero subtitle', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])
  {{-- Hero photo mirrors the home page hero; edit it under Home → Hero. --}}
  @include('admin.pages.fields.text', ['name' => 'hero.primary_label', 'label' => 'Primary button label'])
  @include('admin.pages.fields.text', ['name' => 'hero.primary_url', 'label' => 'Primary button URL'])
  @include('admin.pages.fields.text', ['name' => 'hero.secondary_label', 'label' => 'Secondary button label'])
  @include('admin.pages.fields.text', ['name' => 'hero.secondary_url', 'label' => 'Secondary button URL'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Why we build SkelApp', 'subtitle' => 'Heading + the numbered reason cards below it'])
  @include('admin.pages.fields.text_typography', ['name' => 'build.title', 'label' => 'Section title', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text_typography', ['name' => 'build.subtitle', 'label' => 'Section subtitle', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 2])
  @include('admin.pages.fields.repeater', [
    'name' => 'build.reasons',
    'label' => 'Reasons (numbered automatically)',
    'fields' => [
      ['key' => 'title', 'label' => 'Reason title'],
      ['key' => 'body', 'label' => 'Reason copy', 'type' => 'textarea', 'rows' => 3],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Photo grid', 'subtitle' => 'Heading + a large photo over two smaller ones'])
  @include('admin.pages.fields.textarea', ['name' => 'gallery.title', 'label' => 'Gallery title HTML', 'rows' => 3])
  @include('admin.pages.fields.textarea', ['name' => 'gallery.subtitle', 'label' => 'Gallery subtitle', 'rows' => 2])
  @include('admin.pages.fields.image', ['name' => 'gallery.image_main', 'label' => 'Large photo (top)'])
  @include('admin.pages.fields.image', ['name' => 'gallery.image_a', 'label' => 'Bottom-left photo'])
  @include('admin.pages.fields.image', ['name' => 'gallery.image_b', 'label' => 'Bottom-right photo'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'People section'])
  @include('admin.pages.fields.text_typography', ['name' => 'people.title', 'label' => 'Section title', 'preset' => 'h2-section', 'multiline' => true, 'rows' => 2])
  @include('admin.pages.fields.text_typography', ['name' => 'people.copy', 'label' => 'Section copy', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Story cards'])
  @include('admin.pages.fields.repeater', [
    'name' => 'story.cards',
    'fields' => [
      ['key' => 'num', 'label' => 'Number label'],
      ['key' => 'title', 'label' => 'Card title'],
      ['key' => 'body', 'label' => 'Card body', 'type' => 'textarea', 'rows' => 4],
      ['key' => 'image', 'label' => 'Card image', 'type' => 'image'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Product spotlight'])
  @include('admin.pages.fields.image', ['name' => 'product.image', 'label' => 'Product image'])
  @include('admin.pages.fields.text_typography', ['name' => 'product.title', 'label' => 'Section title', 'preset' => 'h2-section', 'multiline' => true, 'rows' => 2])
  @include('admin.pages.fields.text_typography', ['name' => 'product.subtitle', 'label' => 'Section copy', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 4])
  @include('admin.pages.fields.text', ['name' => 'product.cta_label', 'label' => 'Button label'])
  @include('admin.pages.fields.text', ['name' => 'product.cta_url', 'label' => 'Button URL'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Support showcase'])
  @include('admin.pages.fields.text_typography', ['name' => 'support.title', 'label' => 'Section title', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text', ['name' => 'support.title_accent', 'label' => 'Accent phrase'])
  @include('admin.pages.fields.text_typography', ['name' => 'support.subtitle', 'label' => 'Section subtitle', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])

  @include('admin.pages.fields.repeater', [
    'name' => 'support.features',
    'fields' => [
      ['key' => 'name', 'label' => 'Feature title'],
      ['key' => 'body', 'label' => 'Feature copy', 'type' => 'textarea', 'rows' => 4],
      ['key' => 'image', 'label' => 'Feature image', 'type' => 'image'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Culture CTA'])
  @include('admin.pages.fields.text_typography', ['name' => 'culture.title', 'label' => 'Section title', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text', ['name' => 'culture.title_accent', 'label' => 'Accent phrase'])
  @include('admin.pages.fields.text', ['name' => 'culture.link_label', 'label' => 'Link label'])
  @include('admin.pages.fields.text', ['name' => 'culture.link_url', 'label' => 'Link URL'])
  @include('admin.pages.fields.text_typography', ['name' => 'culture.note', 'label' => 'Closing note', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'SEO', 'collapsed' => true])
  @include('admin.pages.fields.text', ['name' => 'meta.title', 'label' => 'Browser tab title'])
  @include('admin.pages.fields.textarea', ['name' => 'meta.description', 'label' => 'Meta description', 'rows' => 2])
@endcomponent
