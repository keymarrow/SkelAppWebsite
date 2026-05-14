@php
  $homeUrl = url('/');
  $sections = [
    [
      'title' => '1. Definitions',
      'intro' => null,
      'bullets' => [
        ['label' => 'Platform', 'text' => 'The SkelApp dashboard, mobile applications, POS interfaces, APIs, documentation, devices, and all related digital tools and services we make available.'],
        ['label' => 'User', 'text' => 'Any individual, company, employee, contractor, or organisation accessing or using the Service.'],
        ['label' => 'Account', 'text' => 'The registered profile and credentials used to access SkelApp.'],
        ['label' => 'Content', 'text' => 'Any transaction data, product data, customer data, reports, messages, files, or other material submitted to or generated through the Platform.'],
        ['label' => 'Subscription', 'text' => 'Any paid plan, module, hardware bundle, or recurring billing arrangement that grants access to the Service.'],
        ['label' => 'Enterprise Client', 'text' => 'A customer operating under a separate commercial agreement, master services agreement, or negotiated order form with SkelApp.'],
      ],
    ],
    [
      'title' => '2. Eligibility & Account Registration',
      'intro' => 'To access the Service, you must:',
      'bullets' => [
        ['label' => null, 'text' => 'Be legally capable of entering into contracts under Tanzanian law.'],
        ['label' => null, 'text' => 'Provide accurate, complete, and truthful registration and business information.'],
        ['label' => null, 'text' => 'Keep your login credentials, device access, and API keys secure and confidential.'],
        ['label' => null, 'text' => 'Ensure all activity carried out under your account complies with these Terms and all applicable laws.'],
        ['label' => null, 'text' => 'Promptly update your details if your business information, billing details, or authorised users change.'],
      ],
    ],
    [
      'title' => '3. Use of the Service',
      'intro' => 'SkelApp grants you a limited, non-exclusive, non-transferable, and revocable right to use the Service for your internal business operations, subject to these Terms. You agree that you will not:',
      'bullets' => [
        ['label' => null, 'text' => 'Copy, reproduce, reverse engineer, or attempt to extract the source code of any part of the Platform except where the law expressly allows it.'],
        ['label' => null, 'text' => 'Resell, sublicense, lease, or make the Service available to unauthorised third parties without our written approval.'],
        ['label' => null, 'text' => 'Use the Service for unlawful, misleading, fraudulent, or abusive activity.'],
        ['label' => null, 'text' => 'Interfere with the normal operation, security, or performance of the Platform or related infrastructure.'],
      ],
    ],
    [
      'title' => '4. Fees, Billing & Taxes',
      'intro' => 'Some parts of the Service are paid. By purchasing a subscription, plan, hardware, or add-on, you agree that:',
      'bullets' => [
        ['label' => null, 'text' => 'All fees are payable in the currency and billing cycle communicated to you at the time of purchase or in your quotation, invoice, or agreement.'],
        ['label' => null, 'text' => 'Recurring subscriptions may renew automatically unless cancelled before the next billing period.'],
        ['label' => null, 'text' => 'You are responsible for applicable taxes, duties, levies, or regulatory charges unless we explicitly state otherwise.'],
        ['label' => null, 'text' => 'Late, failed, or disputed payments may result in restricted access, suspension, or termination of some or all of the Service.'],
        ['label' => null, 'text' => 'Third-party payment gateway fees, mobile money fees, banking fees, or connectivity charges may apply separately.'],
      ],
    ],
    [
      'title' => '5. Data, Privacy & Security',
      'intro' => 'Your business data remains your responsibility. By using the Service, you acknowledge that:',
      'bullets' => [
        ['label' => null, 'text' => 'You retain ownership of the data, records, and content you submit to the Platform.'],
        ['label' => null, 'text' => 'You authorise SkelApp to process and store that data as necessary to provide, maintain, support, secure, and improve the Service.'],
        ['label' => null, 'text' => 'You are responsible for ensuring you have a lawful basis to collect, upload, and process customer, employee, or transaction data through the Platform.'],
        ['label' => null, 'text' => 'We use reasonable administrative, technical, and organisational safeguards, but no system can be guaranteed to be completely uninterrupted or perfectly secure.'],
        ['label' => null, 'text' => 'You should keep your own backups and operational safeguards where your business requires them.'],
      ],
    ],
    [
      'title' => '6. Acceptable Use',
      'intro' => 'You must not use SkelApp to:',
      'bullets' => [
        ['label' => null, 'text' => 'Transmit malware, harmful code, or materials that compromise the Service or any connected system.'],
        ['label' => null, 'text' => 'Infringe the intellectual property, privacy, publicity, or contractual rights of any person or organisation.'],
        ['label' => null, 'text' => 'Bypass account limits, exploit vulnerabilities, or attempt unauthorised access to accounts, networks, or data.'],
        ['label' => null, 'text' => 'Use the Platform in a way that could harm SkelApp, other users, payment partners, or end customers.'],
      ],
    ],
    [
      'title' => '7. Intellectual Property',
      'intro' => 'SkelApp and its licensors retain all rights, title, and interest in the Platform, including all software, interfaces, branding, design assets, content, and related intellectual property. Except for the limited rights granted in these Terms, no ownership rights are transferred to you.',
      'bullets' => [
        ['label' => null, 'text' => 'You may not use SkelApp trademarks, logos, or brand assets without prior written permission except as allowed by law or our brand guidelines.'],
        ['label' => null, 'text' => 'Any feedback, suggestions, or ideas you submit may be used by SkelApp without restriction or compensation to you.'],
      ],
    ],
    [
      'title' => '8. Suspension & Termination',
      'intro' => 'We may suspend or terminate your access to the Service, in whole or in part, if we reasonably believe that:',
      'bullets' => [
        ['label' => null, 'text' => 'You have breached these Terms or any applicable law.'],
        ['label' => null, 'text' => 'Your use of the Platform creates a security risk, payment risk, operational risk, or legal exposure for SkelApp or others.'],
        ['label' => null, 'text' => 'Required fees remain unpaid after notice.'],
        ['label' => null, 'text' => 'We are required to do so by law, regulator, network provider, or payment partner.'],
        ['label' => null, 'text' => 'You may stop using the Service at any time, but accrued fees and obligations up to the termination date remain payable.'],
      ],
    ],
    [
      'title' => '9. Disclaimers & Limitation of Liability',
      'intro' => 'The Service is provided on an "as available" and "as is" basis to the extent permitted by law. SkelApp does not guarantee that the Service will always be uninterrupted, error-free, or suitable for every business use case.',
      'bullets' => [
        ['label' => null, 'text' => 'To the maximum extent allowed by law, SkelApp is not liable for indirect, incidental, special, consequential, or punitive damages, including loss of profits, business interruption, lost data, or reputational harm.'],
        ['label' => null, 'text' => 'Our total liability arising out of or relating to the Service will not exceed the fees paid by you to SkelApp for the affected Service during the twelve months immediately preceding the event giving rise to the claim.'],
        ['label' => null, 'text' => 'Nothing in these Terms excludes liability where exclusion is not permitted under applicable law.'],
      ],
    ],
    [
      'title' => '10. Governing Law, Changes & Contact',
      'intro' => 'These Terms are governed by the laws of the United Republic of Tanzania. We may update these Terms from time to time to reflect changes to the Service, our commercial model, or applicable legal and regulatory requirements.',
      'bullets' => [
        ['label' => null, 'text' => 'Material updates will take effect when posted on this page unless a different effective date is stated.'],
        ['label' => null, 'text' => 'Your continued use of the Service after updated Terms take effect means you accept the revised Terms.'],
        ['label' => null, 'text' => 'If you have questions about these Terms, contact us at pos@skelapp.tz, call +255 658 962 000, or visit us in Dar Es Salaam, Tanzania.'],
      ],
    ],
  ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Terms of Service | SkelApp</title>
<link rel="icon" href="{{ asset('assets/skel.png') }}" type="image/x-icon" />
<link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="terms-page">
@include('partials.site-nav')

<main class="terms-main">
  <section class="terms-hero">
    <h1>Terms of Service</h1>
    <p class="terms-updated">Last Updated: 28th January 2026</p>
  </section>

  <section class="terms-card">
    <div class="terms-intro">
      <p>These Terms of Service (“Terms”) govern your access to and use of the SkelApp platform (“SkelApp” or “the Service”), including the dashboard, mobile applications, POS interfaces, APIs, hardware integrations, and related tools made available by SkelApp.</p>
      <p>By creating an account, accessing the Platform, purchasing a subscription or device, or using any part of the Service, you agree to be bound by these Terms. If you do not agree, you must not use the Service.</p>
    </div>

    @foreach ($sections as $section)
      <section class="terms-section">
        <h2>{{ $section['title'] }}</h2>

        @if (!empty($section['intro']))
          <p>{{ $section['intro'] }}</p>
        @endif

        <ul class="terms-list">
          @foreach ($section['bullets'] as $bullet)
            <li>
              @if (!empty($bullet['label']))
                <strong>{{ $bullet['label'] }}</strong> — {{ $bullet['text'] }}
              @else
                {{ $bullet['text'] }}
              @endif
            </li>
          @endforeach
        </ul>
      </section>
    @endforeach
  </section>
</main>

@include('partials.site-footer')

  <script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
</body>
</html>
