@php
  $homeUrl = url('/');
  $privacyIntros = content_list('privacy.intro_paragraphs', []);
  $privacySections = content_list('privacy.sections', []);

  if (empty($privacyIntros)) {
    $privacyIntros = [
      'This Privacy Policy explains how SkelApp collects, uses, stores, and protects personal information when you visit our website, request a demo, contact our team, or use related services.',
      'By accessing our website or sharing your information with us, you acknowledge the practices described in this policy. If you do not agree with this policy, please do not use the website or submit personal information through it.',
    ];
  }

  if (empty($privacySections)) {
    $privacySections = [
      [
        'title' => 'Information We Collect',
        'intro' => 'We may collect information directly from you, automatically through your use of the website, or from service interactions with our team.',
        'bullets' => [
          ['label' => 'Contact details', 'text' => 'Your name, email address, phone number, business name, and any details you submit through contact forms or waitlists.'],
          ['label' => 'Technical data', 'text' => 'Information such as IP address, browser type, device details, and basic usage information collected for security, analytics, and service improvement.'],
          ['label' => 'Communications', 'text' => 'Any information you provide when you email us, request support, book a demo, or respond to our team.'],
        ],
      ],
      [
        'title' => 'How We Use Information',
        'intro' => 'We use collected information to operate the website and support legitimate business communication.',
        'bullets' => [
          ['label' => 'Service delivery', 'text' => 'To respond to enquiries, schedule demos, follow up on requests, and provide information about SkelApp products and services.'],
          ['label' => 'Operations and improvement', 'text' => 'To monitor website performance, improve content, understand interest in our services, and maintain the reliability of our systems.'],
          ['label' => 'Security and compliance', 'text' => 'To detect misuse, prevent fraud, maintain records, and comply with legal or regulatory obligations where required.'],
        ],
      ],
      [
        'title' => 'How We Share Information',
        'intro' => 'We do not sell your personal information. We may share information only where necessary for normal business operations.',
        'bullets' => [
          ['label' => 'Service providers', 'text' => 'With trusted vendors who help us host the website, send communications, or support our systems, subject to appropriate confidentiality obligations.'],
          ['label' => 'Legal reasons', 'text' => 'Where disclosure is required to comply with applicable law, regulation, court order, or a lawful government request.'],
          ['label' => 'Business protection', 'text' => 'Where reasonably necessary to protect our rights, users, systems, property, or safety.'],
        ],
      ],
      [
        'title' => 'Data Retention',
        'intro' => 'We retain personal information only for as long as reasonably necessary for the purpose it was collected, including follow-up, recordkeeping, security, and legal compliance.',
      ],
      [
        'title' => 'Cookies and Analytics',
        'intro' => 'Our website may use cookies or similar technologies to support site functionality, measure traffic, and improve user experience. You may manage cookie preferences through your browser settings, though some functionality may be affected.',
      ],
      [
        'title' => 'Security',
        'intro' => 'We use reasonable technical and organisational measures to protect the personal information we hold. However, no method of transmission over the internet or electronic storage is completely secure, and we cannot guarantee absolute security.',
      ],
      [
        'title' => 'Your Choices',
        'intro' => 'You may contact us to request updates or corrections to the information you have submitted to us, or to opt out of non-essential communications where applicable.',
      ],
      [
        'title' => 'Updates to This Policy',
        'intro' => 'We may revise this Privacy Policy from time to time. Any updates will be reflected on this page together with the effective or last-updated date.',
      ],
      [
        'title' => 'Contact Us',
        'intro' => 'If you have questions about this Privacy Policy or how we handle personal information, please contact us using the details provided on our Contact page.',
      ],
    ];
  }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>{{ content('privacy.meta.title', 'Privacy Policy | SkelApp') }}</title>
<meta name="description" content="{{ content('privacy.meta.description', 'Read SkelApp\'s privacy policy and how we collect, use, and protect personal information.') }}">
<link rel="icon" href="{{ content_image('global.brand.favicon', asset('assets/skel.svg')) }}" sizes="any" />
<link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="terms-page privacy-page">
@include('partials.site-nav')

<main class="terms-main">
  <section class="terms-hero">
    <h1 class="{{ content_typography_class('privacy.hero.heading') }}" style="{{ content_typography_vars('privacy.hero.heading') }}">{{ content_text('privacy.hero.heading', 'Privacy Policy') }}</h1>
    <p class="terms-updated {{ content_typography_class('privacy.hero.last_updated') }}" style="{{ content_typography_vars('privacy.hero.last_updated') }}">{{ content_text('privacy.hero.last_updated', 'Last updated: June 13, 2026') }}</p>
  </section>

  <section class="terms-card">
    <div class="terms-intro">
      @foreach ($privacyIntros as $para)
        @php $text = is_array($para) ? ($para['value'] ?? '') : $para; @endphp
        <p>{{ $text }}</p>
      @endforeach
    </div>

    @foreach ($privacySections as $section)
      @php
        $bulletRows = $section['bullets'] ?? [];
        if (empty($bulletRows) && ! empty($section['bullets_text'])) {
          $bulletRows = collect(preg_split('/\r?\n/', (string) $section['bullets_text']))
            ->map(fn ($line) => trim((string) $line))
            ->filter()
            ->map(function (string $line) {
              [$label, $text] = array_pad(array_map('trim', explode('|', $line, 2)), 2, '');

              if ($text === '') {
                return ['label' => '', 'text' => $label];
              }

              return ['label' => $label, 'text' => $text];
            })
            ->values()
            ->all();
        }
      @endphp
      <section class="terms-section">
        <h2>{{ $section['title'] ?? '' }}</h2>

        @if (!empty($section['intro']))
          <p>{{ $section['intro'] }}</p>
        @endif

        @if (!empty($bulletRows))
          <ul class="terms-list">
            @foreach ($bulletRows as $bullet)
              <li>
                @if (!empty($bullet['label']))
                  <strong>{{ $bullet['label'] }}</strong> - {{ $bullet['text'] ?? '' }}
                @else
                  {{ $bullet['text'] ?? '' }}
                @endif
              </li>
            @endforeach
          </ul>
        @endif
      </section>
    @endforeach
  </section>
</main>

@include('partials.site-footer')

  <script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
</body>
</html>
