@component('admin.pages.fields.section', ['title' => 'Full FAQ page (/faq)'])
  @include('admin.pages.fields.text_typography', ['name' => 'page.title', 'label' => 'Sidebar heading (e.g. "FAQs")', 'preset' => 'h1-display'])

  @include('admin.pages.fields.repeater', [
    'name' => 'page.groups',
    'fields' => [
      ['key' => 'id', 'label' => 'URL anchor ID (e.g. "getting-started" — no spaces)'],
      ['key' => 'label', 'label' => 'Group label (shown in sidebar)'],
    ],
    'hint' => 'Each group is a category in the sidebar. Add the actual Q&A inside each group below.',
  ])

  <div class="cms-note">
    <strong>Heads-up:</strong> the questions inside each group are kept inline (Q&A pairs per group are deeply nested). To add a new question, open the rendered <code>/admin/pages/faq</code> page below — or edit JSON directly via tinker until we add a nested editor. For now the seeded defaults are used.
  </div>
@endcomponent

@component('admin.pages.fields.section', ['title' => 'Home-page FAQ preview', 'subtitle' => 'The short accordion shown on the home + pricing pages.'])
  @include('admin.pages.fields.text_typography', ['name' => 'home_preview.heading', 'label' => 'Section heading', 'preset' => 'h2-section'])
  @include('admin.pages.fields.text_typography', ['name' => 'home_preview.subtitle', 'label' => 'Subtitle eyebrow (e.g. "How it works")', 'preset' => 'label'])
  @include('admin.pages.fields.text', ['name' => 'home_preview.read_more_label', 'label' => '"Read more" link label'])

  @include('admin.pages.fields.repeater', [
    'name' => 'home_preview.items',
    'fields' => [
      ['key' => 'question', 'label' => 'Question'],
      ['key' => 'answer', 'label' => 'Answer', 'type' => 'textarea', 'rows' => 4],
    ],
  ])
@endcomponent

@component('admin.pages.fields.section', ['title' => 'SEO', 'collapsed' => true])
  @include('admin.pages.fields.text', ['name' => 'meta.title', 'label' => 'Browser tab title'])
  @include('admin.pages.fields.textarea', ['name' => 'meta.description', 'label' => 'Meta description', 'rows' => 2])
@endcomponent
