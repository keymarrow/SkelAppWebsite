@component('admin.pages.fields.section', ['title' => 'Page hero'])
  @include('admin.pages.fields.text_typography', ['name' => 'hero.heading', 'label' => 'Big heading (top of page)', 'preset' => 'h1-hero'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Left-side info panel'])
  @include('admin.pages.fields.text_typography', ['name' => 'info.heading', 'label' => 'Panel heading (e.g. "Contact Us:")', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text_typography', ['name' => 'info.intro', 'label' => 'Intro paragraph', 'preset' => 'body', 'multiline' => true, 'rows' => 2])
  @include('admin.pages.fields.text_typography', ['name' => 'info.social_heading', 'label' => 'Heading above social icons', 'preset' => 'body'])

  @include('admin.pages.fields.repeater', [
    'name' => 'cards',
    'fields' => [
      ['key' => 'type', 'label' => 'Type — "email" or "tel"'],
      ['key' => 'label', 'label' => 'Label (e.g. Email Us, Talk or chat with PK)'],
      ['key' => 'value', 'label' => 'Value (email address or phone number)'],
    ],
  ])

  @include('admin.pages.fields.repeater', [
    'name' => 'socials',
    'fields' => [
      ['key' => 'platform', 'label' => 'Platform — instagram | youtube | linkedin | facebook (fallback only)'],
      ['key' => 'url', 'label' => 'Profile URL (used only if Global > Brand social links are empty)'],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Right-side demo form'])
  @include('admin.pages.fields.text_typography', ['name' => 'form.heading', 'label' => 'Form heading', 'preset' => 'h2-section'])

  @include('admin.pages.fields.text', ['name' => 'form.first_name_label', 'label' => 'First name field label'])
  @include('admin.pages.fields.text', ['name' => 'form.first_name_placeholder', 'label' => 'First name placeholder'])
  @include('admin.pages.fields.text', ['name' => 'form.last_name_label', 'label' => 'Last name field label'])
  @include('admin.pages.fields.text', ['name' => 'form.last_name_placeholder', 'label' => 'Last name placeholder'])
  @include('admin.pages.fields.text', ['name' => 'form.email_label', 'label' => 'Email field label'])
  @include('admin.pages.fields.text', ['name' => 'form.email_placeholder', 'label' => 'Email placeholder'])
  @include('admin.pages.fields.text', ['name' => 'form.phone_label', 'label' => 'Phone field label'])
  @include('admin.pages.fields.text', ['name' => 'form.phone_placeholder', 'label' => 'Phone placeholder'])
  @include('admin.pages.fields.text', ['name' => 'form.company_label', 'label' => 'Company field label'])
  @include('admin.pages.fields.text', ['name' => 'form.company_placeholder', 'label' => 'Company placeholder'])

  @include('admin.pages.fields.text', ['name' => 'form.cancel_label', 'label' => '"Cancel" button label'])
  @include('admin.pages.fields.text', ['name' => 'form.submit_label', 'label' => '"Submit" button label'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Form submission'])
  @include('admin.pages.fields.text', ['name' => 'form.recipient_email', 'label' => 'Recipient email (where demo requests are sent)', 'hint' => 'The team email that receives every demo submission.'])
  @include('admin.pages.fields.text', ['name' => 'form.subject_prefix', 'label' => 'Email subject prefix', 'hint' => 'Becomes "<prefix> – {first} {last} ({company})".'])
  @include('admin.pages.fields.textarea', ['name' => 'form.success_message', 'label' => 'Success flash message (shown after submit)', 'rows' => 2, 'hint' => 'Use "{first_name}" to insert the submitter\'s first name.'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'SEO', 'collapsed' => true])
  @include('admin.pages.fields.text', ['name' => 'meta.title', 'label' => 'Browser tab title'])
  @include('admin.pages.fields.textarea', ['name' => 'meta.description', 'label' => 'Meta description', 'rows' => 2])
@endcomponent
