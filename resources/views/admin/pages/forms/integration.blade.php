@component('admin.pages.fields.section', ['title' => 'Meta and hero labels'])
  @include('admin.pages.fields.text', ['name' => 'meta.title_suffix', 'label' => 'Browser title suffix'])
  @include('admin.pages.fields.textarea', ['name' => 'meta.description_fallback', 'label' => 'Meta description fallback', 'rows' => 2])
  @include('admin.pages.fields.text', ['name' => 'hero.kicker_suffix', 'label' => 'Hero kicker suffix'])
  @include('admin.pages.fields.text', ['name' => 'hero.primary_label', 'label' => 'Primary button label'])
  @include('admin.pages.fields.text', ['name' => 'hero.secondary_label', 'label' => 'Secondary button label'])
  @include('admin.pages.fields.text', ['name' => 'back_label', 'label' => 'Back link label'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Feature and waitlist copy'])
  @include('admin.pages.fields.text', ['name' => 'features.kicker', 'label' => 'Features kicker'])
  @include('admin.pages.fields.text', ['name' => 'features.title_prefix', 'label' => 'Features title prefix'])
  @include('admin.pages.fields.text', ['name' => 'interest.kicker', 'label' => 'Waitlist kicker'])
  @include('admin.pages.fields.text', ['name' => 'interest.title_prefix', 'label' => 'Waitlist title prefix'])
  @include('admin.pages.fields.textarea', ['name' => 'interest.copy', 'label' => 'Waitlist copy', 'rows' => 3])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Waitlist avatars'])
  @include('admin.pages.fields.repeater', [
    'name' => 'interest.avatars',
    'fields' => [
      ['key' => 'src', 'label' => 'Avatar image', 'type' => 'image'],
      ['key' => 'alt', 'label' => 'Avatar alt text'],
      ['key' => 'position', 'label' => 'Object position'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Waitlist form labels'])
  @include('admin.pages.fields.text', ['name' => 'form.full_name_label', 'label' => 'Full name label'])
  @include('admin.pages.fields.text', ['name' => 'form.full_name_placeholder', 'label' => 'Full name placeholder'])
  @include('admin.pages.fields.text', ['name' => 'form.email_label', 'label' => 'Email label'])
  @include('admin.pages.fields.text', ['name' => 'form.email_placeholder', 'label' => 'Email placeholder'])
  @include('admin.pages.fields.text', ['name' => 'form.submit_label', 'label' => 'Submit button label'])
  @include('admin.pages.fields.text', ['name' => 'form.note_prefix', 'label' => 'Consent note prefix'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'FAQ labels'])
  @include('admin.pages.fields.text', ['name' => 'faq.title_suffix', 'label' => 'FAQ title suffix'])
  @include('admin.pages.fields.text', ['name' => 'faq.subtitle_suffix', 'label' => 'FAQ subtitle suffix'])
  @include('admin.pages.fields.text', ['name' => 'faq.read_more_label', 'label' => 'Read more label'])
@endcomponent
