@php
  $homeUrl = url('/');
  $faqGroups = [
    [
      'id' => 'getting-started',
      'label' => 'Getting Started',
      'questions' => [
        [
          'question' => 'How quickly can I start using SkelApp in my shop?',
          'answer' => 'Most retailers can get started the same day. Once your products, prices, users, and payment setup are ready, your team can begin selling immediately.',
        ],
        [
          'question' => 'Do I need a POS machine before signing up?',
          'answer' => 'No. You can start with the devices you already have and then decide whether you want a dedicated SkelApp POS machine, receipt printer, or barcode scanner.',
        ],
        [
          'question' => 'Can SkelApp work for one store and multiple branches?',
          'answer' => 'Yes. SkelApp is built for both single-location shops and growing retail operations. You can track each branch independently while keeping reporting in one place.',
        ],
        [
          'question' => 'Will you help me move from my current POS or manual books?',
          'answer' => 'Yes. Our team can guide you through product setup, opening stock, price lists, and the best way to move your operation into SkelApp without disrupting sales.',
        ],
        [
          'question' => 'Can I keep selling when the internet is unstable?',
          'answer' => 'Yes. SkelApp is designed for real store conditions and supports smooth selling workflows even when connectivity is not perfect.',
        ],
        [
          'question' => 'What training do cashiers and managers receive?',
          'answer' => 'We provide practical onboarding for the people who will use the system every day, from cashiers handling sales to managers reviewing stock and reports.',
        ],
      ],
    ],
    [
      'id' => 'inventory-catalog',
      'label' => 'Inventory & Catalog',
      'questions' => [
        [
          'question' => 'How do I add products, variants, and selling prices?',
          'answer' => 'You can create products with SKUs, categories, sizes, and price levels directly inside SkelApp, then keep them organised in a way that matches how your store operates.',
        ],
        [
          'question' => 'Can I track stock across categories and branches?',
          'answer' => 'Yes. SkelApp helps you monitor stock movement by product, category, and branch so you always know what is selling and what needs replenishment.',
        ],
        [
          'question' => 'Will SkelApp warn me when stock is running low?',
          'answer' => 'Yes. Low-stock visibility is built into the inventory workflow so you can restock before items go out of stock.',
        ],
        [
          'question' => 'Can I update multiple prices at once?',
          'answer' => 'Yes. Bulk updates make it easier to adjust prices, margins, or product details without editing items one by one.',
        ],
      ],
    ],
    [
      'id' => 'staff-branches',
      'label' => 'Staff & Branches',
      'questions' => [
        [
          'question' => 'Can I create different roles for cashiers, managers, and owners?',
          'answer' => 'Yes. Access can be structured around responsibilities, so each user only sees the tools and settings they need for their role.',
        ],
        [
          'question' => 'How many staff accounts can I add to my business?',
          'answer' => 'That depends on your plan and setup, but SkelApp is designed to support growing teams without forcing you into awkward workarounds.',
        ],
        [
          'question' => 'Can I view sales and stock for each branch separately?',
          'answer' => 'Yes. Branch-level visibility is built into the platform, which makes it easier to compare performance and manage operations across locations.',
        ],
      ],
    ],
    [
      'id' => 'payments',
      'label' => 'Payments',
      'questions' => [
        [
          'question' => 'Which payment methods can I record in SkelApp?',
          'answer' => 'You can manage common retail payment flows such as cash, bank transfers, card payments, and mobile money, while keeping each sale clearly recorded.',
        ],
        [
          'question' => 'Does SkelApp support split payments or partial payments?',
          'answer' => 'Yes. When a customer pays using more than one method or completes part of a balance first, SkelApp can help you keep that transaction accurate.',
        ],
        [
          'question' => 'Can I separate cash, bank, and mobile money totals in reports?',
          'answer' => 'Yes. Payment method reporting is available so you can reconcile daily totals faster and see how customers prefer to pay.',
        ],
        [
          'question' => 'How are refunds and voids handled?',
          'answer' => 'Refunds and voids are tracked inside the system so you maintain a clear audit trail and reduce confusion at the end of the day.',
        ],
      ],
    ],
    [
      'id' => 'devices-printing',
      'label' => 'Devices & Printing',
      'questions' => [
        [
          'question' => 'Which POS machines, printers, and scanners work with SkelApp?',
          'answer' => 'SkelApp works best with our recommended hardware setup, and we can advise you on printers, barcode scanners, and POS devices that fit your store.',
        ],
        [
          'question' => 'Can I print receipts and kitchen or order tickets?',
          'answer' => 'Yes. Depending on your setup, SkelApp can support receipt printing and other operational print flows needed at checkout or in-store fulfilment.',
        ],
        [
          'question' => 'Does SkelApp work on both phone and desktop?',
          'answer' => 'Yes. Teams can manage the business across supported devices while keeping sales, stock, and reporting synced inside the same platform.',
        ],
        [
          'question' => 'What happens if a device is lost or damaged?',
          'answer' => 'Because your data lives in your SkelApp account, you can regain access from another supported device and continue operating with minimal disruption.',
        ],
      ],
    ],
    [
      'id' => 'privacy-security',
      'label' => 'Privacy & Security',
      'questions' => [
        [
          'question' => 'Is my business data secure on SkelApp?',
          'answer' => 'We take security seriously and use practical safeguards to help protect your account, your team access, and your business records.',
        ],
        [
          'question' => 'Can I control who sees reports or sensitive settings?',
          'answer' => 'Yes. Permissions can be assigned so that only the right people have access to sensitive business information and critical controls.',
        ],
        [
          'question' => 'Does SkelApp back up my data?',
          'answer' => 'Yes. Data protection and continuity are part of how the platform is managed, so your records are not tied to a single device in your shop.',
        ],
      ],
    ],
  ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FAQ – SkelApp</title>
  <link rel="icon" href="{{ asset('assets/skel.svg') }}" type="image/x-icon" />
  <link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="faq-page-body">
  @include('partials.site-nav')

  <main class="faq-page">
    <div class="faq-page-shell">
      <aside class="faq-page-sidebar">
        <h1 class="faq-page-title">FAQs</h1>

        <div class="faq-page-categories" role="navigation" aria-label="FAQ categories">
          @foreach ($faqGroups as $group)
            <a
              href="#{{ $group['id'] }}"
              class="faq-page-category-link{{ $loop->first ? ' is-active' : '' }}"
              data-faq-nav-link
              aria-current="{{ $loop->first ? 'location' : 'false' }}"
            >
              {{ $group['label'] }}
            </a>
          @endforeach
        </div>
      </aside>

      <div class="faq-page-content">
        @foreach ($faqGroups as $group)
          <section
            id="{{ $group['id'] }}"
            class="faq-page-section"
            data-faq-page-section
            aria-labelledby="{{ $group['id'] }}-heading"
          >
            <header class="faq-page-section-header">
              <h2 id="{{ $group['id'] }}-heading" class="faq-page-section-heading">{{ $group['label'] }}</h2>
            </header>

            <div class="faq-page-accordion" data-faq-accordion-group>
              @foreach ($group['questions'] as $item)
                <article class="faq-item">
                  <button class="faq-question" type="button" aria-expanded="false">
                    <span>{{ $item['question'] }}</span>
                    <svg class="faq-icon" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                      <line x1="10" y1="4" x2="10" y2="16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                      <line x1="4" y1="10" x2="16" y2="10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                  </button>
                  <div class="faq-answer">
                    <p>{{ $item['answer'] }}</p>
                  </div>
                </article>
              @endforeach
            </div>
          </section>
        @endforeach
      </div>
    </div>
  </main>

  @include('partials.site-footer')

  <script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
</body>
</html>
