@component('admin.pages.fields.section', ['title' => 'Hero'])
  @include('admin.pages.fields.text', ['name' => 'hero.eyebrow', 'label' => 'Eyebrow label'])
  @include('admin.pages.fields.text_typography', ['name' => 'hero.title', 'label' => 'Hero title', 'preset' => 'h1-hero', 'multiline' => true, 'rows' => 3])
  @include('admin.pages.fields.text_typography', ['name' => 'hero.subtitle', 'label' => 'Hero subtitle', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])
  @include('admin.pages.fields.image', ['name' => 'hero.image', 'label' => 'Hero background image'])
  @include('admin.pages.fields.text', ['name' => 'hero.primary_label', 'label' => 'Primary button label'])
  @include('admin.pages.fields.text', ['name' => 'hero.secondary_label', 'label' => 'Secondary button label'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Categories'])
  <p class="cms-field-hint" style="margin-bottom: 12px;">
    For integration slugs, use one slug per line in the order you want them to appear.
  </p>

  @include('admin.pages.fields.repeater', [
    'name' => 'categories',
    'fields' => [
      ['key' => 'title', 'label' => 'Category title'],
      ['key' => 'subtitle', 'label' => 'Category subtitle', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'slugs_text', 'label' => 'Integration slugs', 'type' => 'textarea', 'rows' => 5],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Integration detail pages', 'collapsed' => true])
  <p class="cms-field-hint" style="margin-bottom: 12px;">
    One row per integration. For feature and FAQ lists, use one item per line in the format <code>Title | Description</code> or <code>Question | Answer</code>.
  </p>

  @include('admin.pages.fields.repeater', [
    'name' => 'items',
    'fields' => [
      ['key' => 'slug', 'label' => 'Integration slug'],
      ['key' => 'name', 'label' => 'Integration name'],
      ['key' => 'category', 'label' => 'Category label'],
      ['key' => 'color', 'label' => 'Brand color'],
      ['key' => 'logo', 'label' => 'Logo image', 'type' => 'image'],
      ['key' => 'summary', 'label' => 'Summary copy', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'detail_title', 'label' => 'Detail page title'],
      ['key' => 'detail_copy', 'label' => 'Detail page copy', 'type' => 'textarea', 'rows' => 4],
      ['key' => 'website_url', 'label' => 'Website URL'],
      ['key' => 'hero_image', 'label' => 'Detail hero image', 'type' => 'image'],
      ['key' => 'features_text', 'label' => 'Feature list (Title | Description)', 'type' => 'textarea', 'rows' => 8],
      ['key' => 'faq_text', 'label' => 'FAQ list (Question | Answer)', 'type' => 'textarea', 'rows' => 6],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Logo strip and FAQ'])
  @include('admin.pages.fields.text', ['name' => 'strip.label', 'label' => 'Logo strip label'])
  @include('admin.pages.fields.text_typography', ['name' => 'faq.heading', 'label' => 'FAQ heading', 'preset' => 'h2-section'])
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
