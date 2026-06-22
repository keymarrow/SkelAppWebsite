@component('admin.pages.fields.section', ['title' => 'Page intro'])
  @include('admin.pages.fields.text_typography', ['name' => 'application.title', 'label' => 'Page title', 'preset' => 'h1-display'])
  @include('admin.pages.fields.text_typography', ['name' => 'application.intro', 'label' => 'Intro copy', 'preset' => 'body-lead', 'multiline' => true, 'rows' => 4])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Helpers and legal copy'])
  @include('admin.pages.fields.textarea', ['name' => 'application.email_helper', 'label' => 'Email helper text', 'rows' => 2])
  @include('admin.pages.fields.textarea', ['name' => 'application.phone_helper', 'label' => 'Phone helper text', 'rows' => 2])
  @include('admin.pages.fields.text', ['name' => 'application.promotion_title', 'label' => 'Promotion section title'])
  @include('admin.pages.fields.textarea', ['name' => 'application.promotion_intro', 'label' => 'Promotion intro', 'rows' => 3])
  @include('admin.pages.fields.text', ['name' => 'application.marketing_details_label', 'label' => 'Marketing details label'])
  @include('admin.pages.fields.textarea', ['name' => 'application.marketing_details_placeholder', 'label' => 'Marketing details placeholder', 'rows' => 2])
  @include('admin.pages.fields.textarea', ['name' => 'application.agreement_label', 'label' => 'Agreement label', 'rows' => 2])
  @include('admin.pages.fields.text', ['name' => 'application.agreement_link_label', 'label' => 'Agreement link label'])
  @include('admin.pages.fields.text', ['name' => 'application.terms_link_label', 'label' => 'Terms link label'])
  @include('admin.pages.fields.text', ['name' => 'application.privacy_link_label', 'label' => 'Privacy link label'])
  @include('admin.pages.fields.textarea', ['name' => 'application.marketing_label', 'label' => 'Marketing opt-in label', 'rows' => 2])
  @include('admin.pages.fields.textarea', ['name' => 'application.eligibility_label', 'label' => 'Eligibility label', 'rows' => 2])
  @include('admin.pages.fields.textarea', ['name' => 'application.success_message', 'label' => 'Success message', 'rows' => 2])
  @include('admin.pages.fields.text', ['name' => 'application.subject_prefix', 'label' => 'Email subject prefix'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Form labels'])
  @include('admin.pages.fields.text', ['name' => 'form.first_name_label', 'label' => 'First name label'])
  @include('admin.pages.fields.text', ['name' => 'form.first_name_placeholder', 'label' => 'First name placeholder'])
  @include('admin.pages.fields.text', ['name' => 'form.last_name_label', 'label' => 'Last name label'])
  @include('admin.pages.fields.text', ['name' => 'form.last_name_placeholder', 'label' => 'Last name placeholder'])
  @include('admin.pages.fields.text', ['name' => 'form.email_label', 'label' => 'Email label'])
  @include('admin.pages.fields.text', ['name' => 'form.email_placeholder', 'label' => 'Email placeholder'])
  @include('admin.pages.fields.text', ['name' => 'form.phone_label', 'label' => 'Phone label'])
  @include('admin.pages.fields.text', ['name' => 'form.phone_placeholder', 'label' => 'Phone placeholder'])
  @include('admin.pages.fields.text', ['name' => 'form.country_label', 'label' => 'Country label'])
  @include('admin.pages.fields.text', ['name' => 'form.country_placeholder', 'label' => 'Country placeholder'])
  @include('admin.pages.fields.text', ['name' => 'form.primary_method_label', 'label' => 'Primary method label'])
  @include('admin.pages.fields.text', ['name' => 'form.primary_method_placeholder', 'label' => 'Primary method placeholder'])
  @include('admin.pages.fields.text', ['name' => 'form.discovery_label', 'label' => 'Discovery label'])
  @include('admin.pages.fields.text', ['name' => 'form.discovery_placeholder', 'label' => 'Discovery placeholder'])
  @include('admin.pages.fields.text', ['name' => 'form.captcha_label', 'label' => 'Captcha label'])
  @include('admin.pages.fields.text', ['name' => 'form.captcha_placeholder', 'label' => 'Captcha placeholder'])
  @include('admin.pages.fields.text', ['name' => 'form.submit_label', 'label' => 'Submit button label'])
  @include('admin.pages.fields.text', ['name' => 'form.reset_label', 'label' => 'Reset button label'])
  @include('admin.pages.fields.text', ['name' => 'form.back_label', 'label' => 'Back button label'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Selectable options'])
  @include('admin.pages.fields.repeater', [
    'name' => 'application.country_codes',
    'flat' => true,
    'fields' => [['key' => 'value', 'label' => 'Country code']],
  ])

  @include('admin.pages.fields.repeater', [
    'name' => 'application.countries',
    'flat' => true,
    'fields' => [['key' => 'value', 'label' => 'Country']],
  ])

  @include('admin.pages.fields.repeater', [
    'name' => 'application.promotion_methods',
    'flat' => true,
    'fields' => [['key' => 'value', 'label' => 'Promotion method']],
  ])

  @include('admin.pages.fields.repeater', [
    'name' => 'application.discovery_sources',
    'flat' => true,
    'fields' => [['key' => 'value', 'label' => 'Discovery source']],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'SEO', 'collapsed' => true])
  @include('admin.pages.fields.text', ['name' => 'meta.title', 'label' => 'Browser tab title'])
  @include('admin.pages.fields.textarea', ['name' => 'meta.description', 'label' => 'Meta description', 'rows' => 2])
@endcomponent
