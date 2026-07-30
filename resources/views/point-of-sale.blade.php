@php
  $shared = $shared ?? config('hardware_products.shared', []);
  $tm  = $shared['testimonial'] ?? [];
  $faq = $shared['faq'] ?? [];

  $arrowSvg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>';

  // ── "Do it all" money cards (reuses the home "afford" layout) ───────
  $posAffordCards = content_list('pos.affordable.cards', [
    ['variant' => 'light', 'title' => 'Every shilling in, every shilling out.', 'copy' => 'Sales, expenses, purchases and orders — recorded the moment they happen, not at midnight from memory.', 'link_label' => 'See features', 'link_url' => '/features', 'image' => 'assets/CASHFLOW.png'],
    ['variant' => 'photo', 'title' => "However they pay, it's on record.", 'copy' => 'Cash, mobile money, bank or card — mark how each customer paid, and payment accounts keep it all reconciled.', 'link_label' => 'Learn more', 'link_url' => '/features', 'image' => 'assets/paymentmethod.png'],
    ['variant' => 'tint', 'title' => 'Profit and cashflow, at a glance.', 'copy' => 'Not just what you sold — what you actually made, and where the money stands right now.', 'link_label' => 'Learn more', 'link_url' => '/features#reports-and-profits', 'image' => 'assets/DASHBOARD.png'],
  ]);

  // ── What SkelApp can do (grouped tab + accordion explorer) ─────────
  $defaultPosFeatureGroups = [
    [
      'tab' => 'Cashflow',
      'title' => 'Cashflow that stays close to every sale.',
      'body' => 'Use the same SkelApp on iOS, Android and web, across one shop or many locations, with every money movement recorded in one live system.',
      'link_label' => 'Explore cashflow',
      'link_url' => '/features#reports-and-profits',
      'image' => 'assets/CASHFLOW.png',
      'items' => [
        ['title' => 'Multi-device', 'body' => 'Owners, attendants and managers can all work from the device that fits them best while staying inside one synced SkelApp account.', 'image' => 'assets/card.webp'],
        ['title' => 'Multi-location', 'body' => 'Run one branch or many, then split sales, stock and staff activity by location without losing the full business view.', 'image' => 'assets/CHECKOUTPOS.png'],
        ['title' => 'Sales ledger', 'body' => 'Every shilling in and out is captured the moment it happens, so the day never needs rebuilding from memory.', 'image' => 'assets/CASHFLOW.png'],
        ['title' => 'Profit view', 'body' => 'Open the dashboard and understand what sold, what moved out and where the money stands right now.', 'image' => 'assets/DASHBOARD.png'],
        ['title' => 'Debts & credits', 'body' => 'Track both sides of the relationship, from customer balances to supplier credits and the payment accounts that keep them organised.', 'image' => 'assets/crm.webp'],
        ['title' => 'Payment accounts', 'body' => 'Split cash, bank, card and mobile money into the right accounts so reconciliation is clean at closing time.', 'image' => 'assets/paymentmethod.png'],
      ],
    ],
    [
      'tab' => 'Inventory',
      'title' => 'Inventory that keeps the whole shop in sync.',
      'body' => 'Move beyond simple stock counts with location-aware controls, batch tracking and the daily signals that tell you what needs attention next.',
      'link_label' => 'Explore inventory',
      'link_url' => '/features#inventory-and-stock',
      'image' => 'assets/STOCKLIST.png',
      'items' => [
        ['title' => 'Advanced stock', 'body' => 'Keep day-to-day stock handling inside one workflow instead of spreading it across notebooks, spreadsheets and memory.', 'image' => 'assets/STOCKLIST.png'],
        ['title' => 'Batch tracking', 'body' => 'Track stock by batch so you know what came in, what sold first and what needs acting on next.', 'image' => 'assets/hardware.webp'],
        ['title' => 'Transfers', 'body' => 'Move items from one branch to another with a clear record of where stock left, where it landed and who handled it.', 'image' => 'assets/grocery.webp'],
        ['title' => 'Adjustments', 'body' => 'Correct counts, process returns and keep the stock ledger accurate without hiding what changed.', 'image' => 'assets/kitchenware.webp'],
        ['title' => 'Stock analysis', 'body' => 'Stock analysis helps you spot the products that are moving fastest, running low or nearing expiry before they become a problem.', 'image' => 'assets/autospare.webp'],
        ['title' => 'Returns', 'body' => 'Handle customer and supplier returns without breaking stock accuracy or losing the paper trail behind each change.', 'image' => 'assets/Pos System 04.png'],
      ],
    ],
    [
      'tab' => 'Reports',
      'title' => 'Reports, online orders and your wider retail stack.',
      'body' => 'Run the back office from one place, launch online ordering quickly and connect the finance and messaging tools your business already uses.',
      'link_label' => 'Explore reports',
      'link_url' => '/features#reports-and-profits',
      'image' => 'assets/DASHBOARD.png',
      'items' => [
        ['title' => 'Online orders', 'body' => 'Turn your live stock list into a simple online store, share the link and let customer orders land straight inside SkelApp.', 'image' => 'assets/Mobilehomeview.png'],
        ['title' => 'Integrations', 'body' => 'Connect Zoho Books, QuickBooks, Xero, Sage, bulk SMS providers and WhatsApp so records and customer updates stay in sync.', 'image' => 'assets/quickbooks.png'],
        ['title' => 'Reports', 'body' => 'Review P&L, cashflow, sales by item, customer and attendant, plus stock and expense reports without waiting for manual summaries.', 'image' => 'assets/DASHBOARD.png'],
        ['title' => 'Attendants', 'body' => 'Give every attendant their own login, control access and see who sold what, when and from which location.', 'image' => 'assets/attendants.webp'],
        ['title' => 'P&L', 'body' => 'See profit and loss without rebuilding the numbers manually at the end of the day or month.', 'image' => 'assets/DASHBOARD.png'],
        ['title' => 'Sales insights', 'body' => 'Break sales down by item, customer, attendant or branch so the next decision is based on real numbers.', 'image' => 'assets/CASHFLOW.png'],
      ],
    ],
  ];

  $posFeatureGroups = collect(content_list('pos.feature_groups', $defaultPosFeatureGroups))
    ->map(function ($group, $index) use ($defaultPosFeatureGroups) {
      $group = is_array($group) ? $group : [];
      $defaults = $defaultPosFeatureGroups[$index] ?? [];
      $items = $group['items'] ?? [];

      if ((! is_array($items) || $items === []) && is_string($group['items_text'] ?? null)) {
        $items = collect(preg_split('/\r?\n/', trim((string) $group['items_text'])))
          ->map(function ($line) {
            $line = trim((string) $line);

            if ($line === '') {
              return null;
            }

            [$title, $body, $image] = array_pad(array_map('trim', explode('|', $line, 3)), 3, '');

            return ['title' => $title, 'body' => $body, 'image' => $image];
          })
          ->filter()
          ->values()
          ->all();
      }

      if (! is_array($items) || $items === []) {
        $items = $defaults['items'] ?? [];
      }

      $defaultItems = $defaults['items'] ?? [];

      $items = collect($items)
        ->map(function ($item, $itemIndex) use ($defaultItems, $group) {
          $defaultItem = is_array($defaultItems[$itemIndex] ?? null) ? $defaultItems[$itemIndex] : [];

          if (is_string($item)) {
            return ['title' => trim($item), 'body' => '', 'image' => $defaultItem['image'] ?? ($group['image'] ?? '')];
          }

          if (! is_array($item)) {
            return null;
          }

          $title = trim((string) ($item['title'] ?? ($defaultItem['title'] ?? '')));
          $body = trim((string) ($item['body'] ?? ($defaultItem['body'] ?? '')));
          $image = trim((string) ($item['image'] ?? ($defaultItem['image'] ?? ($group['image'] ?? ''))));

          return $title !== '' ? ['title' => $title, 'body' => $body, 'image' => $image] : null;
        })
        ->filter()
        ->values()
        ->all();

      return array_merge($defaults, $group, ['items' => $items]);
    })
    ->values()
    ->all();

  // ── Devices (reuses the home "products" layout) ─────────────────────
  $posProductCards = content_list('pos.products.cards', [
    ['eyebrow' => 'Skel Register', 'title' => 'The full counter, built to last.', 'body' => 'A desktop cash register with a big, bright screen, built-in receipt printer and cash drawer — running the same SkelApp your phone does.', 'link_label' => 'See the Skel Register', 'link_url' => '/hardware/skel-register', 'image' => 'assets/PosSystemRegister.webp'],
    ['eyebrow' => 'Skel Tablet', 'title' => 'Your whole shop on a tablet.', 'body' => 'Turn any tablet or iPad into a fast, friendly mobile POS — light enough to carry around the shop, big enough to see every line at a glance.', 'link_label' => 'See the Skel Tablet', 'link_url' => '/hardware', 'image' => 'assets/poswithtab.webp'],
  ]);

  // ── Offline-mode "steps + media" band ───────────────────────────────
  $offline = content('pos.offline', [
    'title' => 'Keep selling when the power or network drops',
    'subtitle' => "SkelApp is an offline-first POS. Here's what happens in a blackout:",
    'media_image' => 'assets/CASHFLOW.png',
    'media_caption' => 'Selling straight through a blackout.',
  ]);
  $offlineSteps = content_list('pos.offline.steps', [
    ['body' => 'Offline mode switches on by itself the moment power or internet goes down. No button, no panic.'],
    ['body' => 'Sales, payments and receipts keep working right on the device — your queue never notices.'],
    ['body' => "The moment you're back online, every sale, payment and stock change syncs — nothing lost, nothing typed twice."],
  ]);

  // ── Online-store "steps + media" band ───────────────────────────────
  $online = content('pos.online', [
    'title' => 'Turn your shop into an online store in a tap',
    'subtitle' => 'Your stock becomes your online store — no web designer, no monthly website bill.',
    'media_image' => 'assets/Mobilehomeview.png',
    'media_caption' => 'Your shop, open online.',
  ]);
  $onlineSteps = content_list('pos.online.steps', [
    ['body' => 'Switch on online ordering and SkelApp builds the store from your stock list — prices and photos included.'],
    ['body' => 'Share the link in your Instagram bio, WhatsApp status or anywhere your customers are.'],
    ['body' => "Customers see only what's actually in stock — every order lands in SkelApp for delivery or pickup."],
  ]);

  $stroke = 'width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"';
  $offlineIcons = [
    '<svg '.$stroke.'><path d="M1 1l22 22"/><path d="M16.72 11.06A10.94 10.94 0 0 1 19 12.55"/><path d="M5 12.55a10.94 10.94 0 0 1 5.17-2.39"/><path d="M10.71 5.05A16 16 0 0 1 22.58 9"/><path d="M1.42 9a15.91 15.91 0 0 1 4.7-2.88"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>',
    '<svg '.$stroke.'><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>',
    '<svg '.$stroke.'><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>',
  ];
  $onlineIcons = [
    '<svg '.$stroke.'><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
    '<svg '.$stroke.'><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>',
    '<svg '.$stroke.'><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>',
  ];

  // ── "After the sale" back-office band (4 cards) ─────────────────────
  $syncCards = content_list('pos.sync.cards', [
    ['title' => 'Inventory that thinks ahead.', 'body' => 'Batch tracking, stock transfers between locations, adjustments and returns — plus analysis that flags fast movers, low stock and items expiring soon.', 'link_label' => 'Explore inventory', 'link_url' => '/features#inventory-and-stock', 'image' => 'assets/STOCKLIST.png'],
    ['title' => 'Reports that write themselves.', 'body' => 'Profit & loss, cashflow, sales by item, customer or attendant, stock and expense reports — the full picture, ready every morning.', 'link_label' => 'Explore reports', 'link_url' => '/features#reports-and-profits', 'image' => 'assets/DASHBOARD.png'],
    ['title' => 'Madeni, both directions.', 'body' => 'What customers owe you and what you owe suppliers — one clear record, updated with every sale and purchase.', 'link_label' => 'Explore customers', 'link_url' => '/features#customers-and-loyalty', 'image' => 'assets/crm.webp'],
    ['title' => 'Your team, accountable.', 'body' => 'Every attendant gets their own account — see who sold what and when, without standing at the counter.', 'link_label' => 'Explore attendants', 'link_url' => '/features#staff-and-branches', 'image' => 'assets/attendants.webp'],
  ]);

  // ── Integrations band logo row ──────────────────────────────────────
  $integrationLogos = content_list('pos.integrations.logos', [
    ['name' => 'Zoho Books', 'image' => 'assets/zoho.png'],
    ['name' => 'QuickBooks', 'image' => 'assets/quickbooks.png'],
    ['name' => 'Xero', 'image' => 'assets/xero-logo.png'],
    ['name' => 'Sage', 'image' => 'assets/sage.png'],
    ['name' => 'WhatsApp', 'image' => 'assets/whatsapp-logo.png'],
    ['name' => 'Bulk SMS', 'image' => 'assets/notify-logo-bulksms.png'],
  ]);

  // ── Retailers carousel (reuses the home "retailers" layout) ─────────
  $posRetailerCards = content_list('pos.retailers.cards', [
    ['image' => 'assets/boutique.webp', 'category' => 'Fashion', 'title' => 'Boutique', 'copy' => 'Simple inventory management', 'link_url' => '#'],
    ['image' => 'assets/cosmetics.webp', 'category' => 'Beauty', 'title' => 'Cosmetics', 'copy' => 'Track stock with ease', 'link_url' => '#'],
    ['image' => 'assets/grocery.webp', 'category' => 'Food', 'title' => 'Grocery', 'copy' => 'Fresh inventory management', 'link_url' => '#'],
    ['image' => 'assets/hardware.webp', 'category' => 'Hardware', 'title' => 'Hardware', 'copy' => 'Track stock, suppliers, and bulk sales', 'link_url' => '#'],
    ['image' => 'assets/kitchenware.webp', 'category' => 'Homeware', 'title' => 'Kitchenware', 'copy' => 'Organise products neatly', 'link_url' => '#'],
    ['image' => 'assets/autospare.webp', 'category' => 'Automotive', 'title' => 'Auto Spare Shop', 'copy' => 'Manage fast-moving parts', 'link_url' => '#'],
    ['image' => 'assets/techshop.webp', 'category' => 'Electronics', 'title' => 'Tech Shop', 'copy' => 'Built for modern retail', 'link_url' => '#'],
  ]);

  $tm = content('pos.testimonial', $tm);
  $defaultTmSlides = [
    [
      'quote_lead' => $tm['quote_lead'] ?? '',
      'quote_brand' => $tm['quote_brand'] ?? '',
      'support' => $tm['support'] ?? '',
      'author' => $tm['author'] ?? '',
      'role' => $tm['role'] ?? '',
      'image' => $tm['image'] ?? 'assets/client.webp',
    ],
    [
      'quote_lead' => 'We stopped closing the day with paper notes and calculator work. Now every branch sees the same numbers inside',
      'quote_brand' => 'SkelApp.',
      'support' => 'Neema manages a growing mini-market with multiple counters. One shared dashboard means stock, sales and payments stay aligned without end-of-day guesswork.',
      'author' => 'Neema Joseph',
      'role' => 'Manager, Kijani Mart · Tanzania',
      'image' => 'assets/grocery.webp',
    ],
    [
      'quote_lead' => 'The team learned it fast, the receipts stayed clean, and even during outages we kept serving customers with',
      'quote_brand' => 'SkelApp.',
      'support' => 'Baraka runs a busy hardware shop where speed matters. Offline selling and live stock movement mean the counter keeps moving without losing control of inventory.',
      'author' => 'Baraka Mushi',
      'role' => 'Owner, Mlimani Hardware · Tanzania',
      'image' => 'assets/hardware.webp',
    ],
  ];
  $tmSlides = collect(content_list('pos.testimonials', $defaultTmSlides))
    ->map(function ($slide) use ($tm) {
      if (! is_array($slide)) {
        return null;
      }

      $quoteLead = trim((string) ($slide['quote_lead'] ?? ($tm['quote_lead'] ?? '')));
      $quoteBrand = trim((string) ($slide['quote_brand'] ?? ($tm['quote_brand'] ?? '')));
      $support = trim((string) ($slide['support'] ?? ($tm['support'] ?? '')));
      $author = trim((string) ($slide['author'] ?? ($tm['author'] ?? '')));
      $role = trim((string) ($slide['role'] ?? ($tm['role'] ?? '')));
      $image = trim((string) ($slide['image'] ?? ($tm['image'] ?? 'assets/client.webp')));

      return $quoteLead !== '' ? [
        'quote_lead' => $quoteLead,
        'quote_brand' => $quoteBrand,
        'support' => $support,
        'author' => $author,
        'role' => $role,
        'image' => $image,
      ] : null;
    })
    ->filter()
    ->values()
    ->all();
  if ($tmSlides === [] && ! empty($tm)) {
    $tmSlides = [[
      'quote_lead' => $tm['quote_lead'] ?? '',
      'quote_brand' => $tm['quote_brand'] ?? '',
      'support' => $tm['support'] ?? '',
      'author' => $tm['author'] ?? '',
      'role' => $tm['role'] ?? '',
      'image' => $tm['image'] ?? 'assets/client.webp',
    ]];
  }
  $faq = content('pos.faq', $faq);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ content('pos.meta.title', 'Point of Sale') }}</title>
  <meta name="description" content="{{ content('pos.meta.description', 'SkelApp Point of Sale — fast checkout, every payment, works offline. The POS built for how Tanzania sells.') }}">
  @include('partials.seo', [
    'seoTitle'       => content('pos.meta.title', 'Point of Sale'),
    'seoDescription' => content('pos.meta.description', 'SkelApp Point of Sale and Inventory Management, Track every sale, every expense, and every purchase. The POS built for how Tanzania sells.'),
    'seoType'        => 'website',
    'seoImage'       => content_image('pos.hero.image', content_image('home.hero.background_image_desktop', asset('assets/HeroImage.webp'))),
    'seoPageType'    => 'WebPage',
    'seoSoftware'    => true,
    'seoFaqs'        => $faq['items'] ?? [],
    'seoBreadcrumbs' => [
      ['name' => 'Home', 'url' => url('/')],
      ['name' => 'Point of Sale', 'url' => route('pos.show')],
    ],
  ])
  <link href="{{ asset('css/skel.css') }}?v={{ @filemtime(public_path('css/skel.css')) }}" rel="stylesheet" />
</head>
<body class="hardware-product-body why-page-body pos-page-body">
  @include('partials.site-nav')

  <main class="hwp why-page">

    {{-- ══════════════ HERO (centered + image banner) ══════════════ --}}
    <section class="pos-hero">
      <div class="pos-hero-copy">
        <span class="pos-hero-eyebrow">{{ content('pos.hero.eyebrow', 'Point of sale') }}</span>
        <h1 class="pos-hero-title {{ content_typography_class('pos.hero.title') }}" style="{{ content_typography_vars('pos.hero.title') }}"><strong>{{ content_text('pos.hero.title', 'The point of sale made for') }} {{ content('pos.hero.title_accent', 'how Tanzania sells.') }}</strong></h1>
        <p class="pos-hero-sub {{ content_typography_class('pos.hero.subtitle') }}" style="{{ content_typography_vars('pos.hero.subtitle') }}">{{ content_text('pos.hero.subtitle', "Ring up sales in seconds, record every payment however it's made, and keep selling when the power drops — a POS system your team learns in minutes.") }}</p>
        <div class="pos-hero-actions">
          <a href="{{ content('pos.hero.primary_url', route('contact.show')) }}" class="hwp-hero-btn hwp-hero-btn--solid">{{ content('pos.hero.primary_label', 'Start for free') }}</a>
          <a href="{{ content('pos.hero.secondary_url', route('pricing.show')) }}" class="hwp-hero-btn hwp-hero-btn--ghost">{{ content('pos.hero.secondary_label', 'See pricing') }}</a>
        </div>
      </div>
      <div class="pos-hero-media">
        <img src="{{ content_image('pos.hero.image', content_image('home.hero.background_image_desktop', asset('assets/HeroImage.webp'))) }}" alt="SkelApp Point of Sale" class="pos-hero-image" loading="eager" decoding="async">
      </div>
    </section>

    {{-- ══════════════ ALL-IN-ONE POS (afford cards) ══════════════ --}}
    <section class="whyus-section afford-section pos-afford" id="pos-allinone">
      <div class="container">
        <div class="afford-head products-header">
          <h2 class="afford-title products-title {{ content_typography_class('pos.affordable.title') }}" style="{{ content_typography_vars('pos.affordable.title') }}">{!! content_text_html('pos.affordable.title', 'Do it all. And then some.') !!}</h2>
          <p class="afford-subtitle products-subtitle {{ content_typography_class('pos.affordable.subtitle') }}" style="{{ content_typography_vars('pos.affordable.subtitle') }}">{!! content_text_html('pos.affordable.subtitle', "Sales, expenses, purchases, orders — the whole day's money, one record.") !!}</p>
        </div>

        <div class="afford-grid" data-afford-reveal>
          @foreach ($posAffordCards as $idx => $card)
            @php
              $variant  = $card['variant'] ?? 'light';
              $titleKey = "pos.affordable.cards.{$idx}.title";
              $img      = cms_image($card['image'] ?? null, asset('assets/PosSystemRegister.webp'));
            @endphp

            @if ($variant === 'photo')
              <article class="afford-card afford-card--photo" style="background-image: linear-gradient(180deg, rgba(8,14,11,0.12) 0%, rgba(8,14,11,0.80) 100%), url('{{ $img }}');">
                <div class="afford-card-top">
                  <h3 class="afford-card-title">{!! content_text_html($titleKey, $card['title'] ?? '') !!}</h3>
                  @if (! empty($card['copy']))<p class="afford-card-copy">{{ $card['copy'] }}</p>@endif
                  <a href="{{ $card['link_url'] ?? '#' }}" class="afford-card-link">{{ $card['link_label'] ?? 'Learn more' }}{!! $arrowSvg !!}</a>
                </div>
              </article>
            @else
              <article class="afford-card afford-card--{{ $variant }}">
                <div class="afford-card-top">
                  <h3 class="afford-card-title">{!! content_text_html($titleKey, $card['title'] ?? '') !!}</h3>
                  @if (! empty($card['copy']))<p class="afford-card-copy">{{ $card['copy'] }}</p>@endif
                  <a href="{{ $card['link_url'] ?? '#' }}" class="afford-card-link">{{ $card['link_label'] ?? 'Learn more' }}{!! $arrowSvg !!}</a>
                </div>
                <div class="afford-card-media">
                  <img src="{{ $img }}" alt="{{ content_text($titleKey, $card['title'] ?? '') }}" loading="lazy" decoding="async">
                </div>
              </article>
            @endif
          @endforeach
        </div>
      </div>
    </section>

    {{-- ══════════════ OFFLINE MODE (steps + media band) ══════════════ --}}
    @include('partials.pos-steps', ['prefix' => 'pos.offline', 'data' => $offline, 'steps' => $offlineSteps, 'icons' => $offlineIcons, 'variant' => 'pos-steps--offline'])

    {{-- ══════════════ WHAT SKELAPP CAN DO ══════════════ --}}
    <section class="pos-feature-showcase" id="pos-detail">
      <div class="hwp-shell">
        <div class="hwp-section-header pos-feature-header">
          <h2 class="hwp-section-title products-title {{ content_typography_class('pos.detail.title') }}" style="{{ content_typography_vars('pos.detail.title') }}">{!! content_text_html('pos.detail.title', 'What SkelApp can do') !!}</h2>
          <p class="hwp-section-subtitle products-subtitle {{ content_typography_class('pos.detail.subtitle') }}" style="{{ content_typography_vars('pos.detail.subtitle') }}">{!! content_text_html('pos.detail.subtitle', 'Cashflow, inventory, online orders, reports and team control — one retail system, built in Skel style for real trading days.') !!}</p>
        </div>
        @include('partials.pos-feature-showcase', ['featureGroups' => $posFeatureGroups])
      </div>
    </section>

    {{-- ══════════════ TESTIMONIAL ══════════════ --}}
    @if (!empty($tm) && !empty($tmSlides))
    <section class="hwp-testimonial">
      {{-- Header stays inside the shell; only the carousel below runs full-bleed. --}}
      <div class="hwp-shell">
        <div class="hwp-section-header">
          <h2 class="hwp-section-title {{ content_typography_class('pos.testimonial.section_title') }}" style="{{ content_typography_vars('pos.testimonial.section_title') }}">{!! content_text_html('pos.testimonial.section_title', 'What real business owners say about SkelApp') !!}</h2>
          <p class="hwp-section-subtitle {{ content_typography_class('pos.testimonial.section_subtitle') }}" style="{{ content_typography_vars('pos.testimonial.section_subtitle') }}">{!! content_text_html('pos.testimonial.section_subtitle', 'Shop owners across Tanzania on what changed at their counters — in their own words.') !!}</p>
        </div>
      </div>

      <div class="pos-tm-carousel" data-pos-tm-carousel tabindex="0" role="region" aria-label="Customer stories">
        <div class="pos-tm-viewport">
          <div class="pos-tm-track">
            @foreach ($tmSlides as $idx => $slide)
              @php
                $slideState = 'is-hidden-right';
                if ($idx === 0) {
                  $slideState = 'is-active';
                } elseif ($idx === count($tmSlides) - 1) {
                  $slideState = 'is-prev';
                } elseif ($idx === 1) {
                  $slideState = 'is-next';
                }
              @endphp
              <article class="pos-tm-slide {{ $slideState }}" data-pos-tm-slide="{{ $idx }}" aria-hidden="{{ $idx === 0 ? 'false' : 'true' }}">
                <div class="hwp-tm-card">
                  <figure class="hwp-tm-media">
                    <img src="{{ cms_image($slide['image'] ?? null, asset('assets/client.webp')) }}" alt="{{ $slide['author'] ?? 'SkelApp customer' }}" loading="lazy" decoding="async">
                    <figcaption class="hwp-tm-person">
                      <span class="hwp-tm-name">{{ $slide['author'] ?? '' }}</span>
                      <span class="hwp-tm-role">{{ $slide['role'] ?? '' }}</span>
                    </figcaption>
                  </figure>
                  <div class="hwp-tm-body">
                    <svg class="hwp-tm-quotemark" width="46" height="38" viewBox="0 0 46 38" fill="currentColor" aria-hidden="true"><path d="M0 38V22.8C0 9.6 7.2 1.2 20.4 0l1.8 6.6C15 8.4 11.4 12 11.1 18h7.5V38H0zm25.2 0V22.8C25.2 9.6 32.4 1.2 45.6 0l1.8 6.6C40.2 8.4 36.6 12 36.3 18h7.5V38H25.2z"/></svg>
                    <blockquote class="hwp-tm-quote">{{ $slide['quote_lead'] ?? '' }} <span class="hwp-tm-brand">{{ $slide['quote_brand'] ?? '' }}</span></blockquote>
                    <p class="hwp-tm-support">{{ $slide['support'] ?? '' }}</p>
                    <div class="hwp-tm-actions">
                      <a href="{{ route('contact.show') }}" class="hwp-tm-btn hwp-tm-btn--solid">{{ $tm['primary_label'] }}</a>
                      <a href="{{ route('contact.show') }}" class="hwp-tm-btn hwp-tm-btn--ghost">{{ $tm['secondary_label'] }}</a>
                    </div>
                    <ul class="hwp-tm-stats">
                      @foreach (($tm['stats'] ?? []) as $s)
                        <li><strong>{{ $s['value'] }}</strong><span>{{ $s['label'] }}</span></li>
                      @endforeach
                    </ul>
                  </div>
                </div>
              </article>
            @endforeach
          </div>
        </div>
      </div>
    </section>
    @endif

    {{-- ══════════════ DEVICES (products) ══════════════ --}}
    <section class="products-section pos-products" id="pos-devices">
      <div class="container">
        <div class="products-header">
          <h2 class="products-title {{ content_typography_class('pos.products.title') }}" style="{{ content_typography_vars('pos.products.title') }}">{!! content_text_html('pos.products.title', 'One SkelApp. Every kind of counter.') !!}</h2>
          <p class="products-subtitle {{ content_typography_class('pos.products.subtitle') }}" style="{{ content_typography_vars('pos.products.subtitle') }}">{!! content_text_html('pos.products.subtitle', 'Pick the device that fits your shop today — add more as you grow.') !!}</p>
        </div>

        <div class="products-grid">
          @foreach ($posProductCards as $idx => $card)
            @php
              $eyebrowKey = "pos.products.cards.{$idx}.eyebrow";
              $titleKey = "pos.products.cards.{$idx}.title";
              $bodyKey = "pos.products.cards.{$idx}.body";
              $linkLabelKey = "pos.products.cards.{$idx}.link_label";
            @endphp
            <article class="product-card">
              <div class="product-card-body">
                <span class="product-card-eyebrow {{ content_typography_class($eyebrowKey) }}" style="{{ content_typography_vars($eyebrowKey) }}">{!! content_text_html($eyebrowKey, $card['eyebrow'] ?? '') !!}</span>
                <h3 class="product-card-title {{ content_typography_class($titleKey) }}" style="{{ content_typography_vars($titleKey) }}">{!! content_text_html($titleKey, $card['title'] ?? '') !!}</h3>
                <p class="product-card-copy {{ content_typography_class($bodyKey) }}" style="{{ content_typography_vars($bodyKey) }}">{!! content_text_html($bodyKey, $card['body'] ?? '') !!}</p>
                <a href="{{ content("pos.products.cards.{$idx}.link_url", $card['link_url'] ?? '#') }}" class="product-card-link">
                  {!! content_text_html($linkLabelKey, $card['link_label'] ?? 'See how it works') !!}
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
              </div>
              <div class="product-card-media">
                <img src="{{ cms_image($card['image'] ?? null, asset('assets/Moc-lap-phone-02.webp')) }}" alt="{{ content_text($titleKey, $card['title'] ?? '') }}" loading="lazy" decoding="async">
              </div>
            </article>
          @endforeach
        </div>
      </div>
    </section>

    {{-- ══════════════ ONLINE ORDERING (steps + media band) ══════════════ --}}
    @include('partials.pos-steps', ['prefix' => 'pos.online', 'data' => $online, 'steps' => $onlineSteps, 'icons' => $onlineIcons, 'variant' => 'pos-steps--online'])

    {{-- ══════════════ FULLY IN SYNC ══════════════ --}}
    <section class="pos-sync" id="pos-sync">
      <div class="container">
        <div class="pos-sync-head">
          <h2 class="pos-sync-title {{ content_typography_class('pos.sync.title') }}" style="{{ content_typography_vars('pos.sync.title') }}">{!! content_text_html('pos.sync.title', 'The sale is just the start.') !!}</h2>
          <p class="pos-sync-sub {{ content_typography_class('pos.sync.subtitle') }}" style="{{ content_typography_vars('pos.sync.subtitle') }}">{!! content_text_html('pos.sync.subtitle', 'After the customer walks out, SkelApp keeps working — every sale, every device, every branch, one live picture.') !!}</p>
        </div>
        <div class="pos-sync-grid">
          @foreach ($syncCards as $idx => $card)
            @php
              $titleKey = "pos.sync.cards.{$idx}.title";
              $bodyKey = "pos.sync.cards.{$idx}.body";
              $linkLabelKey = "pos.sync.cards.{$idx}.link_label";
            @endphp
            <article class="pos-sync-card">
              <div class="pos-sync-card-media">
                <img src="{{ cms_image($card['image'] ?? null, asset('assets/DASHBOARD.png')) }}" alt="{{ content_text($titleKey, $card['title'] ?? '') }}" loading="lazy" decoding="async">
              </div>
              <div class="pos-sync-card-body">
                <h3 class="pos-sync-card-title {{ content_typography_class($titleKey) }}" style="{{ content_typography_vars($titleKey) }}">{!! content_text_html($titleKey, $card['title'] ?? '') !!}</h3>
                <p class="pos-sync-card-copy {{ content_typography_class($bodyKey) }}" style="{{ content_typography_vars($bodyKey) }}">{!! content_text_html($bodyKey, $card['body'] ?? '') !!}</p>
                <a href="{{ content("pos.sync.cards.{$idx}.link_url", $card['link_url'] ?? '#') }}" class="pos-sync-card-link">{!! content_text_html($linkLabelKey, $card['link_label'] ?? 'Learn more') !!}{!! $arrowSvg !!}</a>
              </div>
            </article>
          @endforeach
        </div>
      </div>
    </section>

    {{-- ══════════════ RETAILERS ══════════════ --}}
    <section class="retailers-section pos-retailers" id="pos-retailers">
      <div class="container">
        <div class="retailer-head">
          <div class="section-header retailer-head-copy">
            <h2 class="{{ content_typography_class('pos.retailers.title') }}" style="{{ content_typography_vars('pos.retailers.title') }}">{!! content_text_html('pos.retailers.title', 'Built for Every Kind of Shop') !!}</h2>
            <p class="{{ content_typography_class('pos.retailers.subtitle') }}" style="{{ content_typography_vars('pos.retailers.subtitle') }}">{!! content_text_html('pos.retailers.subtitle', 'From boutiques in Dar es Salaam to hardware shops in Arusha — SkelApp is built for how Tanzanian retailers actually work.') !!}</p>
          </div>

          <div class="carousel-slider" aria-label="Retailer carousel controls">
            <button class="carousel-slider-button" type="button" data-carousel-prev aria-label="Previous retailer">
              <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M14.5 5.5 8 12l6.5 6.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </button>
            <button class="carousel-slider-button" type="button" data-carousel-next aria-label="Next retailer">
              <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M9.5 5.5 16 12l-6.5 6.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" /></svg>
            </button>
            <div class="carousel-slider-dots" role="tablist" aria-label="Retailer slides" aria-hidden="true">
              @foreach ($posRetailerCards as $card)
                @php $dotLabel = content_text("pos.retailers.cards.{$loop->index}.title", $card['title'] ?? ''); @endphp
                <button class="carousel-slider-dot{{ $loop->index === 1 ? ' is-active' : '' }}" type="button" role="tab" data-carousel-dot="{{ $loop->index }}" aria-label="Go to {{ $dotLabel }}" aria-current="{{ $loop->index === 1 ? 'true' : 'false' }}" tabindex="-1"></button>
              @endforeach
            </div>
          </div>
        </div>

        <div class="carousel-container" data-drag-scroll data-carousel-default-index="0">
          <div class="carousel-track">
            @foreach ($posRetailerCards as $idx => $card)
              @php
                $titleKey = "pos.retailers.cards.{$idx}.title";
                $catKey = "pos.retailers.cards.{$idx}.category";
                $cat = content_text($catKey, $card['category'] ?? '');
              @endphp
              <div class="retailer-card">
                <div class="card-image">
                  <img src="{{ cms_image($card['image'] ?? null, asset('assets/boutique.webp')) }}" alt="{{ content_text($titleKey, $card['title'] ?? '') }}" draggable="false" loading="lazy" decoding="async">
                  @if ($cat !== '')
                    <span class="retailer-card-pill {{ content_typography_class($catKey) }}" style="{{ content_typography_vars($catKey) }}">{!! cms_text_html($cat) !!}</span>
                  @endif
                  <div class="card-overlay">
                    <h3 class="{{ content_typography_class($titleKey) }}" style="{{ content_typography_vars($titleKey) }}">{!! content_text_html($titleKey, $card['title'] ?? '') !!}</h3>
                  </div>
                  <a href="{{ content("pos.retailers.cards.{$idx}.link_url", $card['link_url'] ?? '#') }}" class="retailer-card-link" aria-label="{{ content_text($titleKey, $card['title'] ?? '') }}"></a>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </section>

    {{-- ══════════════ INTEGRATIONS BAND ══════════════ --}}
    <section class="pos-integrations" id="pos-integrations">
      <div class="pos-integrations-panel">
        <span class="pos-integrations-eyebrow">{{ content_text('pos.integrations.eyebrow', 'Integrations') }}</span>
        <h2 class="pos-integrations-title {{ content_typography_class('pos.integrations.title') }}" style="{{ content_typography_vars('pos.integrations.title') }}">{!! content_text_html('pos.integrations.title', 'Plays well with your accountant.') !!}</h2>
        <p class="pos-integrations-body {{ content_typography_class('pos.integrations.body') }}" style="{{ content_typography_vars('pos.integrations.body') }}">{!! content_text_html('pos.integrations.body', 'Your sales, stock and receipts flow to the tools you already use.') !!}</p>

        <div class="pos-integrations-logos-wrap">
          <ul class="pos-integrations-logos">
            @foreach ($integrationLogos as $idx => $logo)
              <li class="pos-integrations-logo">
                <span class="pos-integrations-logo-tile">
                  <img src="{{ cms_image($logo['image'] ?? null, asset('assets/zoho.png')) }}" alt="{{ content_text("pos.integrations.logos.{$idx}.name", $logo['name'] ?? '') }}" loading="lazy" decoding="async">
                </span>
                <span class="pos-integrations-logo-name">{{ content_text("pos.integrations.logos.{$idx}.name", $logo['name'] ?? '') }}</span>
              </li>
            @endforeach
          </ul>
        </div>

        <a href="{{ content('pos.integrations.more_url', '/integrations') }}" class="pos-integrations-more">{{ content_text('pos.integrations.more_label', 'More Integrations') }}</a>
      </div>
    </section>

    {{-- ══════════════ FAQ ══════════════ --}}
    @if (!empty($faq['items']))
    <section class="faq-section">
        <div class="faq-container">
          <div class="faq-header">
            <h2>{{ $faq['title'] ?? 'Questions shop owners' }} {{ $faq['title_accent'] ?? 'actually ask' }}</h2>
            <a href="{{ route('faq.show') }}" class="faq-read-more">{{ content('pos.faq.read_more_label', 'Read more') }}</a>
          </div>
          <div class="faq-layout">
            <p class="faq-subtitle">{{ content('pos.faq.subtitle', 'POS FAQ') }}</p>
          <div class="faq-list">
            @foreach ($faq['items'] as $i => $item)
              <div class="faq-item @if($i === 0) active @endif">
                <button class="faq-question" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                  <span>{{ $item['q'] ?? '' }}</span>
                  <svg class="faq-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                  </svg>
                </button>
                <div class="faq-answer">
                  <p>{{ $item['a'] ?? '' }}</p>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </section>
    @endif

    {{-- ══════════════ PRICING BANNER ══════════════ --}}
    <section class="hwp-cta">
      <div class="hwp-pricing-shell">
        <div class="hwp-pricing-head">
          <h2 class="hwp-pricing-title {{ content_typography_class('pos.pricing.title') }}" style="{{ content_typography_vars('pos.pricing.title') }}">{{ content_text('pos.pricing.title', 'Run your whole shop for') }} {{ content('pos.pricing.title_accent', 'nearly nothing a day') }}</h2>
          <a href="{{ route('pricing.show') }}" class="hwp-pricing-link">
            {{ content('pos.pricing.link_label', 'Explore all pricing plans') }}
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </a>
        </div>
        <a href="{{ route('pricing.show') }}" class="hwp-pricing-card">
          <div class="hwp-pricing-product">
            <span class="hwp-pricing-icon" aria-hidden="true">
              <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <rect x="6" y="2.5" width="12" height="19" rx="2.5"/><line x1="10" y1="6" x2="14" y2="6"/><circle cx="12" cy="15" r="2.5"/>
              </svg>
            </span>
            <span class="hwp-pricing-name">{{ content_text('pos.pricing.card_name', 'SkelApp') }} <strong>{{ content('pos.pricing.card_name_accent', 'POS') }}</strong></span>
          </div>
          <div class="hwp-pricing-figures">
            <span class="hwp-pricing-now">{{ content_text('pos.pricing.price_now', '500 TZS') }}<small>{{ content('pos.pricing.price_now_suffix', '/day') }}</small></span>
          </div>
        </a>
        <p class="hwp-pricing-note">{{ content_text('pos.pricing.note', 'Karibu sifuri kwa siku — about 500 TZS a day runs your entire shop, less than a cup of chai.') }}</p>
      </div>
    </section>

  </main>

  @include('partials.site-footer')

  <script src="{{ asset('js/skel.js') }}?v={{ @filemtime(public_path('js/skel.js')) }}" defer></script>
  <script>
    (function () {
      // Hero media zoom: the more you scroll, the more the whole banner zooms in.
      (function () {
        var hero = document.querySelector('.pos-hero');
        var media = document.querySelector('.pos-hero-media');
        if (!hero || !media) return;
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        media.style.transformOrigin = 'center center';
        media.style.willChange = 'transform';
        var ticking = false;
        var update = function () {
          var h = hero.offsetHeight || 1;
          var progress = Math.min(Math.max(window.scrollY / h, 0), 1);
          media.style.transform = 'scale(' + (1 - progress * 0.18).toFixed(4) + ')';
          ticking = false;
        };
        window.addEventListener('scroll', function () {
          if (!ticking) { window.requestAnimationFrame(update); ticking = true; }
        }, { passive: true });
        update();
      })();

      // POS feature explorer: top pills switch panels, and the active accordion
      // item controls the image on the right.
      document.querySelectorAll('[data-pos-feature]').forEach(function (group) {
        var tabs = group.querySelectorAll('[data-pos-feature-tab]');
        var panels = group.querySelectorAll('[data-pos-feature-panel]');
        var media = group.querySelectorAll('[data-pos-feature-media]');

        function getActiveItemIndex(panel) {
          if (!panel) return 0;
          var openItem = panel.querySelector('[data-pos-feature-item][open]');
          if (openItem) return parseInt(openItem.getAttribute('data-pos-feature-item'), 10) || 0;
          var firstItem = panel.querySelector('[data-pos-feature-item]');
          return firstItem ? (parseInt(firstItem.getAttribute('data-pos-feature-item'), 10) || 0) : 0;
        }

        function syncPanelItems(panel, activeItemIndex) {
          if (!panel) return;

          panel.querySelectorAll('[data-pos-feature-item]').forEach(function (item) {
            var itemIndex = parseInt(item.getAttribute('data-pos-feature-item'), 10) || 0;
            var active = itemIndex === activeItemIndex;
            item.classList.toggle('is-open', active && item.open);
          });
        }

        function activate(groupIndex, itemIndex) {
          tabs.forEach(function (tab, tabIndex) {
            var active = tabIndex === groupIndex;
            tab.classList.toggle('is-active', active);
            tab.setAttribute('aria-selected', active ? 'true' : 'false');
          });

          panels.forEach(function (panel, panelIndex) {
            var active = panelIndex === groupIndex;
            panel.classList.toggle('is-active', active);
            panel.hidden = !active;
          });

          var activePanel = panels[groupIndex];
          syncPanelItems(activePanel, itemIndex);

          media.forEach(function (image) {
            var mediaGroupIndex = parseInt(image.getAttribute('data-pos-feature-media-group'), 10) || 0;
            var mediaItemIndex = parseInt(image.getAttribute('data-pos-feature-media-item'), 10) || 0;
            image.classList.toggle('is-active', mediaGroupIndex === groupIndex && mediaItemIndex === itemIndex);
          });
        }

        tabs.forEach(function (tab) {
          tab.addEventListener('click', function () {
            var groupIndex = parseInt(tab.getAttribute('data-pos-feature-tab'), 10) || 0;
            activate(groupIndex, getActiveItemIndex(panels[groupIndex]));
          });
        });

        group.querySelectorAll('[data-pos-feature-panel]').forEach(function (panel) {
          panel.querySelectorAll('[data-pos-feature-item]').forEach(function (item) {
            item.addEventListener('toggle', function () {
              item.classList.toggle('is-open', item.open);

              if (!item.open) return;

              panel.querySelectorAll('[data-pos-feature-item]').forEach(function (other) {
                if (other !== item) {
                  other.open = false;
                  other.classList.remove('is-open');
                }
              });

              activate(
                parseInt(panel.getAttribute('data-pos-feature-panel'), 10) || 0,
                parseInt(item.getAttribute('data-pos-feature-item'), 10) || 0
              );
            });
          });
        });

        activate(0, getActiveItemIndex(panels[0]));
      });

      document.querySelectorAll('[data-pos-tm-carousel]').forEach(function (carousel) {
        var slides = Array.from(carousel.querySelectorAll('[data-pos-tm-slide]'));
        var current = slides.findIndex(function (slide) { return slide.classList.contains('is-active'); });
        var pointerStart = null;
        var lastWheelSwitch = 0;
        var wheelCarry = 0;

        if (slides.length < 2) return;
        if (current < 0) current = 0;

        function mod(index, length) {
          return ((index % length) + length) % length;
        }

        function update() {
          var prevIndex = mod(current - 1, slides.length);
          var nextIndex = mod(current + 1, slides.length);

          slides.forEach(function (slide, index) {
            slide.classList.remove('is-active', 'is-prev', 'is-next', 'is-hidden-left', 'is-hidden-right');

            if (index === current) slide.classList.add('is-active');
            else if (index === prevIndex) slide.classList.add('is-prev');
            else if (index === nextIndex) slide.classList.add('is-next');
            else if (mod(index - current, slides.length) < slides.length / 2) slide.classList.add('is-hidden-right');
            else slide.classList.add('is-hidden-left');

            slide.setAttribute('aria-hidden', index === current ? 'false' : 'true');

            // Mark the CARD inert rather than the slide. Inert content can't be
            // clicked or tabbed to, so inerting the slide itself also swallowed
            // the tap that is meant to bring that slide forward. Inerting the card
            // still keeps the side cards' buttons untabbable and out of the a11y
            // tree, while the tap falls through to the slide below it.
            var card = slide.firstElementChild;
            if (card && 'inert' in card) card.inert = index !== current;
            if ('inert' in slide) slide.inert = false;
          });
        }

        function goTo(index) {
          current = mod(index, slides.length);
          update();
        }

        function step(delta) {
          goTo(current + delta);
        }

        slides.forEach(function (slide, index) {
          slide.addEventListener('click', function () {
            if (index !== current) goTo(index);
          });
        });

        carousel.addEventListener('wheel', function (event) {
          var absX = Math.abs(event.deltaX);
          var absY = Math.abs(event.deltaY);
          var horizontalIntent = event.shiftKey && absX < 1 ? event.deltaY : event.deltaX;
          var isMostlyHorizontal = Math.abs(horizontalIntent) > Math.max(26, absY * 1.45);

          if (!isMostlyHorizontal) {
            wheelCarry = 0;
            return;
          }

          event.preventDefault();
          wheelCarry += horizontalIntent;

          if (Date.now() - lastWheelSwitch < 260) return;
          if (Math.abs(wheelCarry) < 72) return;

          lastWheelSwitch = Date.now();
          step(wheelCarry > 0 ? 1 : -1);
          wheelCarry = 0;
        }, { passive: false });

        carousel.addEventListener('pointerdown', function (event) {
          if (event.pointerType === 'mouse' && event.button !== 0) return;
          pointerStart = { x: event.clientX, y: event.clientY };
        });

        carousel.addEventListener('pointerup', function (event) {
          if (!pointerStart) return;

          var dx = event.clientX - pointerStart.x;
          var dy = event.clientY - pointerStart.y;
          pointerStart = null;

          if (Math.abs(dx) < 48 || Math.abs(dx) <= Math.abs(dy)) return;
          step(dx < 0 ? 1 : -1);
        });

        carousel.addEventListener('pointercancel', function () {
          pointerStart = null;
        });

        carousel.addEventListener('keydown', function (event) {
          if (event.key === 'ArrowLeft') {
            event.preventDefault();
            step(-1);
          }

          if (event.key === 'ArrowRight') {
            event.preventDefault();
            step(1);
          }
        });

        update();
      });
    })();
  </script>
</body>
</html>
