<?php

/*
|--------------------------------------------------------------------------
| Retailer / industry pages
|--------------------------------------------------------------------------
| One blade (`retailer`) renders any industry from this data. Boutique is
| fully populated as the reference; the other verticals follow the same
| shape (hero, features grid, detail features, spotlight, faq, cta).
*/

$retailers = [

    'boutique' => [
        'name'  => 'Boutique',
        'hero'  => [
            'eyebrow'        => 'Boutique & fashion POS',
            'title'          => "The point of sale made for\nyour boutique",
            'subtitle'       => 'From the fitting room to the till — ring up sales, track every garment and keep regulars coming back, all from one simple app.',
            'image'          => 'assets/HeroImage.webp',
            'primary_label'  => 'Get a demo',
            'secondary_label'=> 'See pricing',
        ],

        // ── Features grid (home "products cards" style) ──────────────
        'features_header' => [
            'title'    => 'Built for fashion retail',
            'subtitle' => 'Everything your boutique needs to sell faster, track every garment and keep customers coming back.',
        ],
        'features' => [
            ['eyebrow' => 'Checkout',  'title' => 'Fast, friendly checkout', 'body' => 'Ring up any item in seconds — by name, barcode or a tap — and take cash, mobile money or card without slowing the queue.', 'image' => 'assets/CHECKOUTPOS.png'],
            ['eyebrow' => 'Inventory', 'title' => 'Every size and colour, tracked', 'body' => 'Track stock by style, size and colour in real time, with low-stock alerts before your best sellers run out.', 'image' => 'assets/STOCKLIST.png'],
            ['eyebrow' => 'Customers', 'title' => 'Keep regulars coming back', 'body' => 'Build a customer list, remember what they love and reward loyalty to turn first-time shoppers into regulars.', 'image' => 'assets/CRM-phone-feature.png'],
            ['eyebrow' => 'Reports',   'title' => 'Know what sells', 'body' => 'See your best sellers, slow movers and daily takings at a glance — so you buy smarter every season.', 'image' => 'assets/DASHBOARD.png'],
        ],

        // ── Detail features (scroll-pinned showcase) ─────────────────
        'detail_header' => [
            'title'    => 'A boutique counter, sorted',
            'subtitle' => 'The day-to-day tools that keep your shop floor moving.',
        ],
        'detail_features' => [
            ['name' => 'Sell by size & colour',   'body' => 'Set up each garment once with its sizes and colours, then ring it up in a tap — no hunting through a list, no mistakes at the till.', 'image' => 'assets/CHECKOUTPOS.png'],
            ['name' => 'Take every payment',       'body' => 'Cash, M-Pesa, Airtel Money, Tigo Pesa and cards — all from one screen and recorded automatically against the right sale.', 'image' => 'assets/card.webp'],
            ['name' => 'Loyalty built in',         'body' => 'Capture a number at checkout, track what each customer buys, and bring them back with offers they actually want.', 'image' => 'assets/CRM-phone-feature.png'],
            ['name' => 'Stock that stays accurate', 'body' => 'Every sale updates your inventory instantly across all your devices, so what you see always matches the rail.', 'image' => 'assets/STOCKLIST.png'],
        ],

        // ── Hardware spotlight (single row) ──────────────────────────
        'spotlight' => [
            'headline' => 'The Skel Tab, built for your boutique — and perfect at the counter.',
            'eyebrow' => 'Recommended for boutiques',
            'name'    => 'Skel Tab',
            'copy'    => 'A tidy tablet checkout that looks right at home on a boutique counter — swivel it to the customer, take payment and print or text a receipt.',
            'image'   => 'assets/Moc-tab.webp',
            'points'  => ['Slim, counter-friendly design', 'Swivel screen for customers', 'Takes cash, mobile money & cards', 'Works offline, syncs later'],
            'product' => 'skel-tab',
        ],

        // ── FAQ ──────────────────────────────────────────────────────
        'faq' => [
            'title'        => 'Boutique POS',
            'title_accent' => 'questions',
            'items' => [
                ['q' => 'Can I track sizes and colours?',   'a' => 'Yes — set each style up once with its sizes and colours, and SkelApp keeps an accurate count of every variant as you sell.'],
                ['q' => 'Does it work without internet?',   'a' => 'Absolutely. Keep selling during power cuts or weak network; every sale syncs automatically the moment you reconnect.'],
                ['q' => 'Can I build a customer list?',      'a' => 'Yes. Capture a phone number at checkout, see what each customer buys, and reward your regulars with loyalty.'],
                ['q' => 'What payments can I take?',         'a' => 'Cash, M-Pesa, Airtel Money, Tigo Pesa and cards — all recorded automatically and printed on one receipt.'],
            ],
        ],

        // ── Closing CTA ──────────────────────────────────────────────
        'cta' => [
            'title' => 'Run your boutique 1% better',
            'note'  => 'Start selling on SkelApp today — set up in minutes, and add hardware whenever you’re ready.',
            'label' => 'Start for free',
            'image' => 'assets/PosSystem.webp',
        ],
    ],

    'cosmetics' => [
        'name'  => 'Cosmetics Store',
        'hero'  => [
            'eyebrow'         => 'Cosmetics & beauty POS',
            'title'           => "The point of sale made for\nyour beauty store",
            'subtitle'        => 'Ring up products fast, track every shade and SKU, and keep your regulars coming back — all from one simple app.',
            'image'           => 'assets/HeroImage.webp',
            'primary_label'   => 'Get a demo',
            'secondary_label' => 'See pricing',
        ],
        'detail_header'   => ['title' => 'A beauty counter, sorted', 'subtitle' => 'The day-to-day tools that keep your store moving.'],
        'detail_features' => [
            ['name' => 'Track every shade & SKU', 'body' => 'Set up products with their shades, sizes and brands, then ring them up in a tap — and always know exactly what’s in stock.', 'image' => 'assets/STOCKLIST.png'],
            ['name' => 'Take every payment',      'body' => 'Cash, M-Pesa, Airtel Money, Tigo Pesa and cards — all from one screen and recorded automatically against the sale.', 'image' => 'assets/card.webp'],
            ['name' => 'Reward your regulars',    'body' => 'Capture a number at checkout, see what each customer loves, and bring them back with loyalty they’ll use.', 'image' => 'assets/CRM-phone-feature.png'],
            ['name' => 'Know your best sellers',  'body' => 'See which lines fly off the shelf and which sit, so you reorder the right products every single time.', 'image' => 'assets/DASHBOARD.png'],
        ],
        'spotlight' => [
            'headline' => 'The Skel Tab, made for your beauty counter — bright, tidy and fast.',
            'eyebrow'  => 'Recommended for cosmetics',
            'name'     => 'Skel Tab',
            'copy'     => 'A slim tablet checkout that looks at home on a beauty counter — swivel it to the customer, take payment and print or text a receipt.',
            'image'    => 'assets/Moc-tab.webp',
            'points'   => ['Slim, counter-friendly design', 'Swivel screen for customers', 'Takes cash, mobile money & cards', 'Works offline, syncs later'],
            'product'  => 'skel-tab',
        ],
        'faq' => [
            'title' => 'Cosmetics POS', 'title_accent' => 'questions',
            'items' => [
                ['q' => 'Can I track shades and brands?', 'a' => 'Yes — set each product up with its shade, size and brand, and SkelApp keeps an accurate count of every line as you sell.'],
                ['q' => 'Does it work without internet?', 'a' => 'Absolutely. Keep selling during power cuts or weak network; everything syncs the moment you reconnect.'],
                ['q' => 'Can I run loyalty?',             'a' => 'Yes. Capture a phone number at checkout, track what each customer buys, and reward your regulars.'],
                ['q' => 'What payments can I take?',      'a' => 'Cash, M-Pesa, Airtel Money, Tigo Pesa and cards — all recorded automatically and printed on one receipt.'],
            ],
        ],
        'cta' => ['title' => 'Run your beauty store 1% better', 'note' => 'Start selling on SkelApp today — set up in minutes, and add hardware whenever you’re ready.', 'label' => 'Start for free', 'image' => 'assets/PosSystem.webp'],
    ],

    'grocery' => [
        'name'  => 'Grocery Store',
        'hero'  => [
            'eyebrow'         => 'Grocery & minimart POS',
            'title'           => "The point of sale made for\nyour grocery store",
            'subtitle'        => 'Scan, weigh and ring up fast, keep shelves stocked, and never lose a sale — even when the power or network drops.',
            'image'           => 'assets/techshop.webp',
            'primary_label'   => 'Get a demo',
            'secondary_label' => 'See pricing',
        ],
        'detail_header'   => ['title' => 'A busy till, handled', 'subtitle' => 'Everything a fast grocery counter needs.'],
        'detail_features' => [
            ['name' => 'Scan & sell in seconds', 'body' => 'Ring up items by barcode or a quick tap and keep the queue moving, even at your very busiest.', 'image' => 'assets/CHECKOUTPOS.png'],
            ['name' => 'Takes every payment',    'body' => 'Cash, M-Pesa, Airtel Money, Tigo Pesa and cards — recorded automatically against each and every sale.', 'image' => 'assets/card.webp'],
            ['name' => 'Stock that never runs dry', 'body' => 'Track every product in real time with low-stock alerts before your fast movers run out on you.', 'image' => 'assets/STOCKLIST.png'],
            ['name' => 'Keeps selling offline',  'body' => 'Power cut or weak network? Keep ringing up sales; everything syncs the moment you reconnect.', 'image' => 'assets/CASHFLOW.png'],
        ],
        'spotlight' => [
            'headline' => 'The Skel Register, built for a busy grocery counter — big screen, fast and reliable.',
            'eyebrow'  => 'Recommended for grocery',
            'name'     => 'Skel Register',
            'copy'     => 'A full checkout station that keeps queues moving — bright screen, built-in receipt printing and every payment in one place.',
            'image'    => 'assets/PosSystemRegister.webp',
            'points'   => ['Big, bright touchscreen', 'Built-in receipt printer', 'Takes cash, mobile money & cards', 'Works offline, syncs later'],
            'product'  => 'skel-register',
        ],
        'faq' => [
            'title' => 'Grocery POS', 'title_accent' => 'questions',
            'items' => [
                ['q' => 'Can I sell items by weight?',      'a' => 'Yes — set up priced-by-weight items and ring them up quickly at the till.'],
                ['q' => 'Does it work offline?',            'a' => 'Absolutely. Keep selling during outages; sales sync automatically the moment you reconnect.'],
                ['q' => 'Can I track stock across branches?', 'a' => 'Yes. Run every branch from one dashboard and watch stock by location in real time.'],
                ['q' => 'What payments can I take?',        'a' => 'Cash, M-Pesa, Airtel Money, Tigo Pesa and cards — all recorded and printed on one receipt.'],
            ],
        ],
        'cta' => ['title' => 'Run your grocery 1% better', 'note' => 'Start selling on SkelApp today — set up in minutes, and add hardware whenever you’re ready.', 'label' => 'Start for free', 'image' => 'assets/PosSystem.webp'],
    ],

    'hardware-shop' => [
        'name'  => 'Hardware Shop',
        'hero'  => [
            'eyebrow'         => 'Hardware & building supplies POS',
            'title'           => "The point of sale made for\nyour hardware shop",
            'subtitle'        => 'Sell from the counter or the aisle, track thousands of parts, and take any payment — built for the way hardware shops trade.',
            'image'           => 'assets/hardware.webp',
            'primary_label'   => 'Get a demo',
            'secondary_label' => 'See pricing',
        ],
        'detail_header'   => ['title' => 'Every part, every sale — tracked', 'subtitle' => 'Tools for a shop with thousands of SKUs.'],
        'detail_features' => [
            ['name' => 'Find any part fast', 'body' => 'Search a huge catalogue by name or code and ring it up in seconds — no hunting, no guesswork.', 'image' => 'assets/CHECKOUTPOS.png'],
            ['name' => 'Sell from the aisle', 'body' => 'Take a handheld down the aisle to check stock, quote a price and close the sale right on the spot.', 'image' => 'assets/STOCKLIST.png'],
            ['name' => 'Takes every payment', 'body' => 'Cash, mobile money and cards — recorded automatically and printed neatly on one receipt.', 'image' => 'assets/card.webp'],
            ['name' => 'Stock you can trust', 'body' => 'Every sale updates inventory instantly, so the count on screen always matches the shelf.', 'image' => 'assets/DASHBOARD.png'],
        ],
        'spotlight' => [
            'headline' => 'The Skel Terminal, built for the aisle — check stock, quote and sell anywhere.',
            'eyebrow'  => 'Recommended for hardware shops',
            'name'     => 'Skel Terminal',
            'copy'     => 'A rugged handheld that takes the whole shop down the aisle — scan, check stock, take payment and print on the spot.',
            'image'    => 'assets/Pos System 04.png',
            'points'   => ['Rugged, all-day battery', 'Fast barcode scanner', 'Takes cash, mobile money & cards', 'Works offline, syncs later'],
            'product'  => 'skel-terminal',
        ],
        'faq' => [
            'title' => 'Hardware POS', 'title_accent' => 'questions',
            'items' => [
                ['q' => 'Can it handle thousands of products?', 'a' => 'Yes — SkelApp is built for big catalogues; search any item by name or code and sell it in seconds.'],
                ['q' => 'Can I sell from the aisle?',           'a' => 'Yes, with a handheld you can check stock, quote and close a sale anywhere in the shop.'],
                ['q' => 'Does it work offline?',                'a' => 'Absolutely. Keep selling during outages; everything syncs the moment you reconnect.'],
                ['q' => 'What payments can I take?',            'a' => 'Cash, M-Pesa, Airtel Money, Tigo Pesa and cards — all on one receipt.'],
            ],
        ],
        'cta' => ['title' => 'Run your hardware shop 1% better', 'note' => 'Start selling on SkelApp today — set up in minutes, and add hardware whenever you’re ready.', 'label' => 'Start for free', 'image' => 'assets/PosSystem.webp'],
    ],

    'kitchenware' => [
        'name'  => 'Kitchenware Store',
        'hero'  => [
            'eyebrow'         => 'Kitchenware & homeware POS',
            'title'           => "The point of sale made for\nyour kitchenware store",
            'subtitle'        => 'Ring up every pot, pan and gadget fast, track your range, and keep customers coming back — all from one simple app.',
            'image'           => 'assets/HeroImage.webp',
            'primary_label'   => 'Get a demo',
            'secondary_label' => 'See pricing',
        ],
        'detail_header'   => ['title' => 'A homeware counter, sorted', 'subtitle' => 'The everyday tools that keep your shop moving.'],
        'detail_features' => [
            ['name' => 'Ring up any item fast', 'body' => 'Search or scan your whole range and take payment in seconds — by name, barcode or a quick tap.', 'image' => 'assets/CHECKOUTPOS.png'],
            ['name' => 'Track your whole range', 'body' => 'Know what’s in stock across sets, sizes and brands, with alerts before your bestsellers run out.', 'image' => 'assets/STOCKLIST.png'],
            ['name' => 'Takes every payment',   'body' => 'Cash, mobile money and cards — all recorded automatically against the right sale.', 'image' => 'assets/card.webp'],
            ['name' => 'Know what sells',        'body' => 'See your best sellers and slow movers at a glance, so you buy smarter every season.', 'image' => 'assets/DASHBOARD.png'],
        ],
        'spotlight' => [
            'headline' => 'The Skel Tab, made for your homeware counter — roomy, tidy and fast.',
            'eyebrow'  => 'Recommended for kitchenware',
            'name'     => 'Skel Tab',
            'copy'     => 'A roomy tablet checkout that fits a homeware counter — swivel it to the customer, take payment and print or text a receipt.',
            'image'    => 'assets/Moc-tab.webp',
            'points'   => ['Roomy, counter-friendly design', 'Swivel screen for customers', 'Takes cash, mobile money & cards', 'Works offline, syncs later'],
            'product'  => 'skel-tab',
        ],
        'faq' => [
            'title' => 'Kitchenware POS', 'title_accent' => 'questions',
            'items' => [
                ['q' => 'Can I track sets and sizes?',  'a' => 'Yes — set products up once with their sets, sizes and brands, and SkelApp keeps an accurate count as you sell.'],
                ['q' => 'Does it work without internet?', 'a' => 'Absolutely. Keep selling during power cuts or weak network; everything syncs when you reconnect.'],
                ['q' => 'Can I build a customer list?',  'a' => 'Yes. Capture a number at checkout, see what each customer buys, and reward your regulars.'],
                ['q' => 'What payments can I take?',     'a' => 'Cash, M-Pesa, Airtel Money, Tigo Pesa and cards — all recorded and printed on one receipt.'],
            ],
        ],
        'cta' => ['title' => 'Run your kitchenware store 1% better', 'note' => 'Start selling on SkelApp today — set up in minutes, and add hardware whenever you’re ready.', 'label' => 'Start for free', 'image' => 'assets/PosSystem.webp'],
    ],

    'autospare' => [
        'name'  => 'AutoSpare Shop',
        'hero'  => [
            'eyebrow'         => 'Auto spares & parts POS',
            'title'           => "The point of sale made for\nyour auto spares shop",
            'subtitle'        => 'Look up parts in seconds, track thousands of SKUs, and take any payment — built for the way spares shops trade.',
            'image'           => 'assets/hardware.webp',
            'primary_label'   => 'Get a demo',
            'secondary_label' => 'See pricing',
        ],
        'detail_header'   => ['title' => 'Every part, matched and sold', 'subtitle' => 'Tools for a shop that lives by its catalogue.'],
        'detail_features' => [
            ['name' => 'Find the right part fast', 'body' => 'Search a huge catalogue by part name or number and ring it up in seconds — every time.', 'image' => 'assets/CHECKOUTPOS.png'],
            ['name' => 'Sell from counter or bay', 'body' => 'Take a handheld to the shelves or the bay to check stock and close the sale on the spot.', 'image' => 'assets/STOCKLIST.png'],
            ['name' => 'Takes every payment',     'body' => 'Cash, mobile money and cards — recorded automatically and printed on one clear receipt.', 'image' => 'assets/card.webp'],
            ['name' => 'Stock you can trust',     'body' => 'Every sale updates inventory instantly, so the count always matches the shelf.', 'image' => 'assets/DASHBOARD.png'],
        ],
        'spotlight' => [
            'headline' => 'The Skel Terminal, built for the parts shelf — look up, quote and sell anywhere.',
            'eyebrow'  => 'Recommended for auto spares',
            'name'     => 'Skel Terminal',
            'copy'     => 'A rugged handheld that brings the catalogue to the shelves — look up a part, check stock, take payment and print on the spot.',
            'image'    => 'assets/Pos System 04.png',
            'points'   => ['Rugged, all-day battery', 'Fast part & barcode lookup', 'Takes cash, mobile money & cards', 'Works offline, syncs later'],
            'product'  => 'skel-terminal',
        ],
        'faq' => [
            'title' => 'Auto spares POS', 'title_accent' => 'questions',
            'items' => [
                ['q' => 'Can it handle a big parts catalogue?', 'a' => 'Yes — search any part by name or number and sell it in seconds, even with thousands of SKUs.'],
                ['q' => 'Can I sell from the shelves?',         'a' => 'Yes, with a handheld you can look up a part, check stock and close the sale anywhere.'],
                ['q' => 'Does it work offline?',                'a' => 'Absolutely. Keep selling during outages; everything syncs the moment you reconnect.'],
                ['q' => 'What payments can I take?',            'a' => 'Cash, M-Pesa, Airtel Money, Tigo Pesa and cards — all on one receipt.'],
            ],
        ],
        'cta' => ['title' => 'Run your spares shop 1% better', 'note' => 'Start selling on SkelApp today — set up in minutes, and add hardware whenever you’re ready.', 'label' => 'Start for free', 'image' => 'assets/PosSystem.webp'],
    ],

    'tech-shop' => [
        'name'  => 'Tech Shop',
        'hero'  => [
            'eyebrow'         => 'Electronics & tech POS',
            'title'           => "The point of sale made for\nyour tech shop",
            'subtitle'        => 'Sell phones, gadgets and accessories fast, track stock and serials, and take any payment — all from one app.',
            'image'           => 'assets/techshop.webp',
            'primary_label'   => 'Get a demo',
            'secondary_label' => 'See pricing',
        ],
        'detail_header'   => ['title' => 'A tech counter, sorted', 'subtitle' => 'Everything a fast electronics shop needs.'],
        'detail_features' => [
            ['name' => 'Sell fast, look pro', 'body' => 'Ring up devices and accessories in seconds on a bright, modern screen your team learns in minutes.', 'image' => 'assets/CHECKOUTPOS.png'],
            ['name' => 'Track stock & serials', 'body' => 'Keep an accurate count of every device and accessory, with low-stock alerts before you run out.', 'image' => 'assets/STOCKLIST.png'],
            ['name' => 'Takes every payment',  'body' => 'Cash, mobile money and cards — all recorded automatically against the right sale.', 'image' => 'assets/card.webp'],
            ['name' => 'Know your numbers',    'body' => 'See your best sellers, margins and daily takings at a glance to buy and price smarter.', 'image' => 'assets/DASHBOARD.png'],
        ],
        'spotlight' => [
            'headline' => 'The Skel Register, built for a modern tech counter — bright, fast and reliable.',
            'eyebrow'  => 'Recommended for tech shops',
            'name'     => 'Skel Register',
            'copy'     => 'A full checkout station that looks the part — bright screen, built-in receipt printing and every payment in one tidy place.',
            'image'    => 'assets/PosSystemRegister.webp',
            'points'   => ['Big, bright touchscreen', 'Built-in receipt printer', 'Takes cash, mobile money & cards', 'Works offline, syncs later'],
            'product'  => 'skel-register',
        ],
        'faq' => [
            'title' => 'Tech shop POS', 'title_accent' => 'questions',
            'items' => [
                ['q' => 'Can I track serial numbers?',   'a' => 'Yes — keep an accurate record of every device and accessory, including serials, as you sell.'],
                ['q' => 'Does it work without internet?', 'a' => 'Absolutely. Keep selling during outages; everything syncs the moment you reconnect.'],
                ['q' => 'Can I run more than one branch?', 'a' => 'Yes. Run every branch from one dashboard and see stock and sales by location.'],
                ['q' => 'What payments can I take?',      'a' => 'Cash, M-Pesa, Airtel Money, Tigo Pesa and cards — all recorded and printed on one receipt.'],
            ],
        ],
        'cta' => ['title' => 'Run your tech shop 1% better', 'note' => 'Start selling on SkelApp today — set up in minutes, and add hardware whenever you’re ready.', 'label' => 'Start for free', 'image' => 'assets/PosSystem.webp'],
    ],

];

return [
    'retailers' => $retailers,
];
