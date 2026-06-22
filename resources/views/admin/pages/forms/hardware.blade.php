@component('admin.pages.fields.section', ['title' => 'Hero'])
  @include('admin.pages.fields.text', ['name' => 'hero.eyebrow', 'label' => 'Eyebrow label'])
  @include('admin.pages.fields.text_typography', ['name' => 'hero.title', 'label' => 'Hero title', 'preset' => 'h1-hero', 'multiline' => true, 'rows' => 3])
  @include('admin.pages.fields.text_typography', ['name' => 'hero.subtitle', 'label' => 'Hero subtitle', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])
  @include('admin.pages.fields.image', ['name' => 'hero.image', 'label' => 'Hero image'])
  @include('admin.pages.fields.text', ['name' => 'hero.primary_label', 'label' => 'Primary button label'])
  @include('admin.pages.fields.text', ['name' => 'hero.primary_url', 'label' => 'Primary button URL'])
  @include('admin.pages.fields.text', ['name' => 'hero.secondary_label', 'label' => 'Secondary button label'])
  @include('admin.pages.fields.text', ['name' => 'hero.stat1_value', 'label' => 'Stat 1 value'])
  @include('admin.pages.fields.text', ['name' => 'hero.stat1_label', 'label' => 'Stat 1 label'])
  @include('admin.pages.fields.text', ['name' => 'hero.stat2_value', 'label' => 'Stat 2 value'])
  @include('admin.pages.fields.text', ['name' => 'hero.stat2_label', 'label' => 'Stat 2 label'])
  @include('admin.pages.fields.text', ['name' => 'hero.stat3_value', 'label' => 'Stat 3 value'])
  @include('admin.pages.fields.text', ['name' => 'hero.stat3_label', 'label' => 'Stat 3 label'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Lineup'])
  @include('admin.pages.fields.text_typography', ['name' => 'lineup.title', 'label' => 'Section title', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text_typography', ['name' => 'lineup.subtitle', 'label' => 'Section subtitle', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])

  @include('admin.pages.fields.repeater', [
    'name' => 'lineup.items',
    'fields' => [
      ['key' => 'name', 'label' => 'Product name'],
      ['key' => 'tag', 'label' => 'Product tag'],
      ['key' => 'copy', 'label' => 'Product copy', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'image', 'label' => 'Product image', 'type' => 'image'],
      ['key' => 'url', 'label' => 'Product link URL'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Spotlights'])
  <p class="cms-field-hint" style="margin-bottom: 12px;">
    For feature points, use one line per point.
  </p>

  @include('admin.pages.fields.repeater', [
    'name' => 'spotlights.items',
    'fields' => [
      ['key' => 'eyebrow', 'label' => 'Eyebrow label'],
      ['key' => 'name', 'label' => 'Product name'],
      ['key' => 'copy', 'label' => 'Description', 'type' => 'textarea', 'rows' => 4],
      ['key' => 'points_text', 'label' => 'Feature points', 'type' => 'textarea', 'rows' => 5],
      ['key' => 'image', 'label' => 'Product image', 'type' => 'image'],
      ['key' => 'url', 'label' => 'Button URL'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Why section'])
  @include('admin.pages.fields.text_typography', ['name' => 'why.title', 'label' => 'Section title', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text_typography', ['name' => 'why.subtitle', 'label' => 'Section subtitle', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])

  @include('admin.pages.fields.repeater', [
    'name' => 'why.items',
    'fields' => [
      ['key' => 'title', 'label' => 'Card title'],
      ['key' => 'copy', 'label' => 'Card copy', 'type' => 'textarea', 'rows' => 3],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Accessories'])
  @include('admin.pages.fields.text_typography', ['name' => 'accessories.title', 'label' => 'Section title', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text_typography', ['name' => 'accessories.subtitle', 'label' => 'Section subtitle', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])

  @include('admin.pages.fields.repeater', [
    'name' => 'accessories.items',
    'fields' => [
      ['key' => 'name', 'label' => 'Accessory name'],
      ['key' => 'copy', 'label' => 'Accessory copy', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'image', 'label' => 'Accessory image', 'type' => 'image'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Bottom CTA'])
  @include('admin.pages.fields.text_typography', ['name' => 'cta.title', 'label' => 'CTA title', 'preset' => 'h2-section', 'multiline' => true, 'rows' => 2])
  @include('admin.pages.fields.text_typography', ['name' => 'cta.subtitle', 'label' => 'CTA copy', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 3])
  @include('admin.pages.fields.text', ['name' => 'cta.secondary_label', 'label' => 'Secondary link label'])
  @include('admin.pages.fields.text', ['name' => 'cta.secondary_url', 'label' => 'Secondary link URL'])
  @include('admin.pages.fields.text', ['name' => 'cta.primary_label', 'label' => 'Primary button label'])
  @include('admin.pages.fields.text', ['name' => 'cta.primary_url', 'label' => 'Primary button URL'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Hardware product pages', 'collapsed' => true])
  <p class="cms-field-hint" style="margin-bottom: 12px;">
    One row per hardware product. Spec rows use one line per item in the format <code>Label | Value</code>.
  </p>

  @include('admin.pages.fields.repeater', [
    'name' => 'products',
    'fields' => [
      ['key' => 'slug', 'label' => 'Product slug'],
      ['key' => 'name', 'label' => 'Product name'],
      ['key' => 'meta_title', 'label' => 'Meta title'],
      ['key' => 'meta_description', 'label' => 'Meta description', 'type' => 'textarea', 'rows' => 2],
      ['key' => 'hero_badge', 'label' => 'Hero badge'],
      ['key' => 'hero_primary_label', 'label' => 'Hero primary button label'],
      ['key' => 'hero_secondary_label', 'label' => 'Hero secondary button label'],
      ['key' => 'hero_subtitle', 'label' => 'Hero subtitle', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'hero_image', 'label' => 'Hero image', 'type' => 'image'],
      ['key' => 'intro_title', 'label' => 'Intro title prefix'],
      ['key' => 'intro_title_accent', 'label' => 'Intro title accent'],
      ['key' => 'detail_title_prefix', 'label' => 'Detail section title prefix'],
      ['key' => 'detail_title_accent', 'label' => 'Detail section title accent'],
      ['key' => 'detail_subtitle', 'label' => 'Detail section subtitle', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'feature_1_title', 'label' => 'Feature 1 title'],
      ['key' => 'feature_1_body', 'label' => 'Feature 1 body', 'type' => 'textarea', 'rows' => 4],
      ['key' => 'feature_1_image', 'label' => 'Feature 1 image', 'type' => 'image'],
      ['key' => 'feature_2_title', 'label' => 'Feature 2 title'],
      ['key' => 'feature_2_body', 'label' => 'Feature 2 body', 'type' => 'textarea', 'rows' => 4],
      ['key' => 'feature_2_image', 'label' => 'Feature 2 image', 'type' => 'image'],
      ['key' => 'feature_3_title', 'label' => 'Feature 3 title'],
      ['key' => 'feature_3_body', 'label' => 'Feature 3 body', 'type' => 'textarea', 'rows' => 4],
      ['key' => 'feature_3_image', 'label' => 'Feature 3 image', 'type' => 'image'],
      ['key' => 'feature_4_title', 'label' => 'Feature 4 title'],
      ['key' => 'feature_4_body', 'label' => 'Feature 4 body', 'type' => 'textarea', 'rows' => 4],
      ['key' => 'feature_4_image', 'label' => 'Feature 4 image', 'type' => 'image'],
      ['key' => 'specs_title_prefix', 'label' => 'Specs title prefix'],
      ['key' => 'specs_title_accent', 'label' => 'Specs title accent'],
      ['key' => 'specs_subtitle', 'label' => 'Specs subtitle', 'type' => 'textarea', 'rows' => 3],
      ['key' => 'spec_1_label', 'label' => 'Spec section 1 label'],
      ['key' => 'spec_1_rows_text', 'label' => 'Spec section 1 rows', 'type' => 'textarea', 'rows' => 5],
      ['key' => 'spec_2_label', 'label' => 'Spec section 2 label'],
      ['key' => 'spec_2_rows_text', 'label' => 'Spec section 2 rows', 'type' => 'textarea', 'rows' => 5],
      ['key' => 'spec_3_label', 'label' => 'Spec section 3 label'],
      ['key' => 'spec_3_rows_text', 'label' => 'Spec section 3 rows', 'type' => 'textarea', 'rows' => 5],
      ['key' => 'spec_4_label', 'label' => 'Spec section 4 label'],
      ['key' => 'spec_4_rows_text', 'label' => 'Spec section 4 rows', 'type' => 'textarea', 'rows' => 5],
      ['key' => 'spec_5_label', 'label' => 'Spec section 5 label'],
      ['key' => 'spec_5_rows_text', 'label' => 'Spec section 5 rows', 'type' => 'textarea', 'rows' => 5],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Product page FAQ'])
  @include('admin.pages.fields.text', ['name' => 'product_faq.title', 'label' => 'FAQ heading'])
  @include('admin.pages.fields.text', ['name' => 'product_faq.title_accent', 'label' => 'FAQ heading accent'])
  @include('admin.pages.fields.text', ['name' => 'product_faq.subtitle', 'label' => 'FAQ subtitle'])
  @include('admin.pages.fields.text', ['name' => 'product_faq.read_more_label', 'label' => 'Read more label'])

  @include('admin.pages.fields.repeater', [
    'name' => 'product_faq.items',
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
