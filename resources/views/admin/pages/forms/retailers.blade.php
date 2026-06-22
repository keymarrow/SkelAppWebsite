@component('admin.pages.fields.section', ['title' => 'Hero'])
  @include('admin.pages.fields.text', ['name' => 'hero.eyebrow', 'label' => 'Eyebrow label'])
  @include('admin.pages.fields.text_typography', ['name' => 'hero.title', 'label' => 'Hero title', 'preset' => 'h1-hero', 'multiline' => true, 'rows' => 3])
  @include('admin.pages.fields.text_typography', ['name' => 'hero.subtitle', 'label' => 'Hero subtitle', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])
  @include('admin.pages.fields.image', ['name' => 'hero.image', 'label' => 'Hero background image'])
  @include('admin.pages.fields.text', ['name' => 'hero.primary_label', 'label' => 'Primary button label'])
  @include('admin.pages.fields.text', ['name' => 'hero.secondary_label', 'label' => 'Secondary button label'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Pinned showcase'])
  @include('admin.pages.fields.text_typography', ['name' => 'showcase.headline', 'label' => 'Showcase headline', 'preset' => 'h2-section', 'multiline' => true, 'rows' => 2])

  @include('admin.pages.fields.repeater', [
    'name' => 'showcase.steps',
    'fields' => [
      ['key' => 'title', 'label' => 'Step title'],
      ['key' => 'body', 'label' => 'Step body', 'type' => 'textarea', 'rows' => 4],
      ['key' => 'image', 'label' => 'Step image', 'type' => 'image'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Retailer types carousel'])
  @include('admin.pages.fields.text_typography', ['name' => 'types.title', 'label' => 'Section title', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text_typography', ['name' => 'types.subtitle', 'label' => 'Section subtitle', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])

  @include('admin.pages.fields.repeater', [
    'name' => 'types.cards',
    'fields' => [
      ['key' => 'title', 'label' => 'Card title'],
      ['key' => 'category', 'label' => 'Card category'],
      ['key' => 'slug', 'label' => 'Retailer slug'],
      ['key' => 'image', 'label' => 'Card image', 'type' => 'image'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Retailer detail pages', 'collapsed' => true])
  <p class="cms-field-hint" style="margin-bottom: 12px;">
    One row per retailer page. Why items use <code>Title | Description</code>. FAQ items use <code>Question | Answer</code>. Spotlight points use one line per point.
  </p>

  @include('admin.pages.fields.repeater', [
    'name' => 'pages',
    'fields' => [
      ['key' => 'slug', 'label' => 'Retailer slug'],
      ['key' => 'name', 'label' => 'Retailer name'],
      ['key' => 'meta_title', 'label' => 'Meta title'],
      ['key' => 'meta_description', 'label' => 'Meta description', 'type' => 'textarea', 'rows' => 2],
      ['key' => 'hero_eyebrow', 'label' => 'Hero eyebrow'],
      ['key' => 'hero_title', 'label' => 'Hero title', 'type' => 'textarea', 'rows' => 2],
      ['key' => 'hero_subtitle', 'label' => 'Hero subtitle', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'hero_image', 'label' => 'Hero image', 'type' => 'image'],
      ['key' => 'hero_primary_label', 'label' => 'Hero primary button label'],
      ['key' => 'hero_secondary_label', 'label' => 'Hero secondary button label'],
      ['key' => 'detail_title', 'label' => 'Feature section heading'],
      ['key' => 'detail_subtitle', 'label' => 'Feature section subtitle', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'feature_1_title', 'label' => 'Feature 1 title'],
      ['key' => 'feature_1_body', 'label' => 'Feature 1 body', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'feature_1_image', 'label' => 'Feature 1 image', 'type' => 'image'],
      ['key' => 'feature_2_title', 'label' => 'Feature 2 title'],
      ['key' => 'feature_2_body', 'label' => 'Feature 2 body', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'feature_2_image', 'label' => 'Feature 2 image', 'type' => 'image'],
      ['key' => 'feature_3_title', 'label' => 'Feature 3 title'],
      ['key' => 'feature_3_body', 'label' => 'Feature 3 body', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'feature_3_image', 'label' => 'Feature 3 image', 'type' => 'image'],
      ['key' => 'feature_4_title', 'label' => 'Feature 4 title'],
      ['key' => 'feature_4_body', 'label' => 'Feature 4 body', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'feature_4_image', 'label' => 'Feature 4 image', 'type' => 'image'],
      ['key' => 'why_title', 'label' => 'Why section title'],
      ['key' => 'why_subtitle', 'label' => 'Why section subtitle', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'why_items_text', 'label' => 'Why cards (Title | Description)', 'type' => 'textarea', 'rows' => 6],
      ['key' => 'spotlight_headline', 'label' => 'Spotlight headline', 'type' => 'textarea', 'rows' => 2],
      ['key' => 'spotlight_eyebrow', 'label' => 'Spotlight eyebrow'],
      ['key' => 'spotlight_name', 'label' => 'Spotlight product name'],
      ['key' => 'spotlight_copy', 'label' => 'Spotlight copy', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'spotlight_image', 'label' => 'Spotlight image', 'type' => 'image'],
      ['key' => 'spotlight_points_text', 'label' => 'Spotlight points', 'type' => 'textarea', 'rows' => 5],
      ['key' => 'spotlight_product', 'label' => 'Linked hardware product slug'],
      ['key' => 'spotlight_button_label', 'label' => 'Spotlight button prefix'],
      ['key' => 'faq_title', 'label' => 'FAQ title'],
      ['key' => 'faq_title_accent', 'label' => 'FAQ title accent'],
      ['key' => 'faq_items_text', 'label' => 'FAQ items (Question | Answer)', 'type' => 'textarea', 'rows' => 6],
      ['key' => 'cta_title', 'label' => 'CTA title'],
      ['key' => 'cta_note', 'label' => 'CTA note', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'cta_label', 'label' => 'CTA button label'],
      ['key' => 'cta_image', 'label' => 'CTA image', 'type' => 'image'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'FAQ'])
  @include('admin.pages.fields.text_typography', ['name' => 'faq.title', 'label' => 'FAQ heading', 'preset' => 'h2-section'])
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
  @include('admin.pages.fields.text', ['name' => 'cta.link_label', 'label' => 'CTA link label'])
  @include('admin.pages.fields.text_typography', ['name' => 'cta.note', 'label' => 'CTA note', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])
  @include('admin.pages.fields.text', ['name' => 'cta.primary_label', 'label' => 'Primary button label'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'SEO', 'collapsed' => true])
  @include('admin.pages.fields.text', ['name' => 'meta.title', 'label' => 'Browser tab title'])
  @include('admin.pages.fields.textarea', ['name' => 'meta.description', 'label' => 'Meta description', 'rows' => 2])
@endcomponent
