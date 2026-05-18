@component('admin.pages.fields.section', ['title' => 'Brand'])
  @include('admin.pages.fields.image', ['name' => 'brand.favicon', 'label' => 'Favicon', 'hint' => 'Shown in the browser tab. SVG or PNG recommended.'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Top navigation'])
  @include('admin.pages.fields.image', ['name' => 'nav.logo', 'label' => 'Nav logo'])
  @include('admin.pages.fields.image', ['name' => 'nav.call_icon', 'label' => 'Phone call icon (next to phone number)'])
  @include('admin.pages.fields.image', ['name' => 'nav.mobile_menu_icon', 'label' => 'Mobile-menu hamburger icon'])
  @include('admin.pages.fields.text', ['name' => 'nav.phone_display', 'label' => 'Phone (display)', 'hint' => 'How it appears in the nav button.'])
  @include('admin.pages.fields.text', ['name' => 'nav.phone_tel', 'label' => 'Phone (tel: link)', 'hint' => 'Digits only, no spaces.'])
  @include('admin.pages.fields.text', ['name' => 'nav.contact_label', 'label' => 'Contact button label'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'App store badges (shared by hero + footer)'])
  @include('admin.pages.fields.image', ['name' => 'app_badges.apple_image', 'label' => 'Apple App Store badge'])
  @include('admin.pages.fields.text', ['name' => 'app_badges.apple_url', 'label' => 'Apple App Store link URL'])
  @include('admin.pages.fields.image', ['name' => 'app_badges.google_image', 'label' => 'Google Play badge'])
  @include('admin.pages.fields.text', ['name' => 'app_badges.google_url', 'label' => 'Google Play link URL'])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Footer'])
  @include('admin.pages.fields.image', ['name' => 'footer.logo', 'label' => 'Footer logo'])
  @include('admin.pages.fields.text', ['name' => 'footer.tagline', 'label' => 'Tagline (big sell-1%-better line)'])
  @include('admin.pages.fields.text', ['name' => 'footer.download_label', 'label' => '"DOWNLOAD THE" label'])

  @include('admin.pages.fields.repeater', [
    'name' => 'footer.address_lines',
    'flat' => true,
    'fields' => [['key' => 'value', 'label' => 'Address line']],
  ])

  @include('admin.pages.fields.text', ['name' => 'footer.email', 'label' => 'Contact email'])
  @include('admin.pages.fields.text', ['name' => 'footer.phone_display', 'label' => 'Phone (display)'])
  @include('admin.pages.fields.text', ['name' => 'footer.phone_tel', 'label' => 'Phone (tel: link)'])

  @include('admin.pages.fields.textarea', ['name' => 'footer.ai_quote', 'label' => 'AI recommendation quote', 'rows' => 2])

  @include('admin.pages.fields.repeater', [
    'name' => 'footer.ai_badges',
    'fields' => [
      ['key' => 'image', 'label' => 'Badge image', 'type' => 'image'],
      ['key' => 'alt', 'label' => 'Alt text (e.g. Claude)'],
    ],
  ])

  @include('admin.pages.fields.text', ['name' => 'footer.copyright', 'label' => 'Copyright line'])
  @include('admin.pages.fields.text', ['name' => 'footer.credit_text', 'label' => 'Credit line text', 'hint' => 'The Flashnet credit at the very bottom.'])
  @include('admin.pages.fields.text', ['name' => 'footer.credit_link_label', 'label' => 'Credit link label'])
  @include('admin.pages.fields.text', ['name' => 'footer.credit_link_url', 'label' => 'Credit link URL'])
@endcomponent
