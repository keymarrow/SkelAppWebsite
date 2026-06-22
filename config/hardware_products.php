<?php

/*
|--------------------------------------------------------------------------
| Hardware product pages
|--------------------------------------------------------------------------
| Content for the dedicated /hardware/{slug} product pages. One blade
| (hardware-product) renders any product from this data. `shared` holds the
| sections that are identical across every device (testimonial, ecosystem,
| FAQ); each product supplies its own hero, feature showcase and specs.
*/

$shared = [
    // Green testimonial band (same voice across the lineup).
    'testimonial' => [
        'quote_lead'  => 'Setup was painless and the team was up and selling the same afternoon. The hardware just works — even when the power goes, with',
        'quote_brand' => 'SkelApp.',
        'support'     => 'Amina runs three branches across Dar es Salaam. Reliable hardware and SkelApp running offline mean her counters never stop — every sale, stock count and payment tracked in one place.',
        'author'      => 'Amina Hassan',
        'role'        => 'Owner, Bloom Retail · Tanzania',
        'image'       => 'assets/client.webp',
        'primary_label'   => 'Start for free',
        'secondary_label' => 'Book a demo',
        'stats' => [
            ['value' => '5 min', 'label' => 'To set up'],
            ['value' => '3 shops', 'label' => 'One dashboard'],
            ['value' => '100%', 'label' => 'Offline-ready'],
        ],
    ],

    // "Connected tools. Better profit." — the SkelApp ecosystem (retail).
    'ecosystem' => [
        'title'        => 'Connected tools.',
        'title_accent' => 'Better profit.',
        'subtitle'     => 'Every part of SkelApp works together, so your sales, stock and customers stay in sync across the whole business.',
        'features' => [
            ['name' => 'Point of sale',  'body' => 'A fast, friendly till that is easy to learn and keeps selling through power cuts and weak networks. Ring up any sale, take any payment and print a receipt in seconds — the dependable core every SkelApp device is built around.', 'image' => 'assets/CHECKOUTPOS.png', 'link_label' => 'Explore SkelApp POS', 'link_url' => '#'],
            ['name' => 'Hardware',       'body' => 'Terminals, registers and tablets that all run the same SkelApp — pick the device that fits your counter today and add more as you grow. Reliable, locally supported and ready to sell straight out of the box.', 'image' => 'assets/PosSystemRegister.webp', 'link_label' => 'Explore hardware', 'link_url' => '#'],
            ['name' => 'Integration',    'body' => 'Connect the tools you already use — mobile money, cards, bank settlement and accounting — so payments and records flow into one place automatically, with no double entry at the end of the day.', 'image' => 'assets/card.webp', 'link_label' => 'Explore integrations', 'link_url' => '#'],
            ['name' => 'Features',       'body' => 'Inventory, customers and reports all work together in one app. Track stock across branches, keep your regulars coming back and see exactly where your business stands every single morning.', 'image' => 'assets/DASHBOARD.png', 'link_label' => 'Explore features', 'link_url' => '#'],
        ],
    ],

    'faq' => [
        'title'        => 'Questions,',
        'title_accent' => 'answered.',
        'items' => [
            ['q' => 'Does the hardware work offline?',                 'a' => 'Yes. Every SkelApp device keeps selling during power cuts or weak network — sales, stock and payments sync automatically the moment you reconnect.'],
            ['q' => 'How long does setup take?',                       'a' => 'Most shops are live the same day. Unbox it, sign in to SkelApp, add your products and prices, and start selling — usually in under five minutes.'],
            ['q' => 'Which payments can it take?',                     'a' => 'Cash, M-Pesa, Airtel Money, Tigo Pesa, cards (tap, chip and PIN) and bank transfer — all recorded automatically and printed on one receipt.'],
            ['q' => 'Can I manage more than one branch?',              'a' => 'Absolutely. Run every branch from one dashboard — monitor sales and stock by location and give each shop its own logins and permissions.'],
            ['q' => 'Is there a warranty and local support?',          'a' => 'Every device ships with a one-year warranty. Our team in Dar es Salaam supports you on WhatsApp, by phone and on-site when you need it.'],
        ],
    ],
];

$products = [

    'skel-register' => [
        'name'       => 'Skel Register',
        'eyebrow'    => 'Retail POS station',
        'hero'       => [
            'subtitle' => 'Built for busy counters, Skel Register delivers a big, bright screen and fast, reliable checkout that keeps every queue moving.',
            'image'    => 'assets/PosSystemRegister.webp',
        ],
        'intro_title'        => 'A POS station made for',
        'intro_title_accent' => 'high-volume retail',
        'intro_subtitle'     => 'Everything the busiest counters need, built into one reliable station that keeps queues moving all day.',
        'features' => [
            ['name' => 'Responsive touchscreen', 'body' => 'A bright, tilting touchscreen shows more of your menu at a glance, so staff find items and ring up orders in seconds. The display is built for all-day use behind a busy counter — fast, smooth and easy to read even under bright shop lighting, with a second customer-facing view so shoppers can follow every line as it is added.', 'image' => 'assets/CHECKOUTPOS.png'],
            ['name' => 'Built-in receipt printing', 'body' => 'A fast thermal printer is built right into the station, so there are no extra boxes, power bricks or cables cluttering your counter. Receipts print crisp and clear every time — the moment a sale ends — and you can switch to digital receipts over SMS or email whenever a customer prefers to skip the paper.', 'image' => 'assets/Pos System 04.png'],
            ['name' => 'Takes every payment', 'body' => 'Cash, mobile money and cards all run through one tidy station — tap, chip and PIN, plus M-Pesa, Airtel Money and Tigo Pesa. Every payment is captured automatically against the right sale and reconciled for you, so the day balances at closing without anyone keying figures in by hand.', 'image' => 'assets/card.webp'],
            ['name' => 'Keeps selling offline', 'body' => 'Power cut or weak network? Skel Register keeps ringing up sales without missing a beat, holding everything safely on the device. The moment you reconnect, every order, payment and stock change syncs automatically to the cloud and your other branches — so you never lose a sale or a number to a dropped connection.', 'image' => 'assets/CASHFLOW.png'],
        ],
        'specs' => [
            ['label' => 'Display',            'rows' => [['k' => 'Size', 'v' => '15.6" Full HD'], ['k' => 'Touch', 'v' => '10-point multi-touch'], ['k' => 'Brightness', 'v' => '350 nits']]],
            ['label' => 'Processor & memory', 'rows' => [['k' => 'CPU', 'v' => 'Octa-core'], ['k' => 'RAM', 'v' => '4 GB'], ['k' => 'Storage', 'v' => '64 GB']]],
            ['label' => 'Connectivity',       'rows' => [['k' => 'Wi-Fi', 'v' => 'Dual-band'], ['k' => 'Cellular', 'v' => '4G LTE (optional)'], ['k' => 'Bluetooth', 'v' => '5.0']]],
            ['label' => 'Payments',           'rows' => [['k' => 'Methods', 'v' => 'Tap · Chip · PIN'], ['k' => 'Mobile money', 'v' => 'M-Pesa, Airtel, Tigo']]],
            ['label' => 'Operating environment', 'rows' => [['k' => 'Temperature', 'v' => '0–40 °C'], ['k' => 'Power', 'v' => '100–240V, surge-tolerant']]],
        ],
    ],

    'skel-terminal' => [
        'name'       => 'Skel Terminal',
        'eyebrow'    => 'Handheld retail POS',
        'hero'       => [
            'subtitle' => 'Your whole shop in one hand. Scan, sell, take mobile money and print a receipt from the aisle, the doorway or anywhere on the floor.',
            'image'    => 'assets/Pos System 04.png',
        ],
        'intro_title'        => 'A handheld POS made for',
        'intro_title_accent' => 'selling anywhere',
        'intro_subtitle'     => 'A complete shop that fits in one hand and goes wherever the sale does — aisle, doorway or out on the floor.',
        'features' => [
            ['name' => 'All-day battery', 'body' => 'A full shift on a single charge means Skel Terminal keeps selling from open to close without anyone hunting for a power point. The battery is sized for real trading days — long queues, deliveries and market runs included — and tops up fast between sessions, so it is always ready when the next customer walks up.', 'image' => 'assets/Mobilehomeview.png'],
            ['name' => 'Scan & sell in one hand', 'body' => 'Built for comfortable one-handed use, Skel Terminal pairs a fast barcode scanner with a responsive screen so you can ring up items, take payment and print a receipt without ever setting it down. Serve customers in the aisle, at the door or out on the floor — wherever the sale happens, the whole checkout travels with you.', 'image' => 'assets/CHECKOUTPOS.png'],
            ['name' => 'Mobile money ready', 'body' => 'M-Pesa, Airtel Money and Tigo Pesa are built right in, alongside card payments, so customers pay however they like from a single handheld. Each payment is matched to its sale and recorded automatically, giving you a clean record of every shilling that comes in — no manual logging, no end-of-day guesswork.', 'image' => 'assets/card.webp'],
            ['name' => 'Live stock in your hand', 'body' => 'Check prices and stock levels right where you are standing, then watch every sale update your inventory the instant it is rung up. Low-stock alerts reach you before shelves run empty, and because the count is shared across all your devices, what you see on the Terminal always matches the rest of your business.', 'image' => 'assets/STOCKLIST.png'],
        ],
        'specs' => [
            ['label' => 'Display',            'rows' => [['k' => 'Size', 'v' => '6" HD touchscreen'], ['k' => 'Glass', 'v' => 'Toughened, glove-friendly']]],
            ['label' => 'Battery',            'rows' => [['k' => 'Capacity', 'v' => '5,000 mAh'], ['k' => 'Life', 'v' => 'Full-shift, all-day']]],
            ['label' => 'Scanner',            'rows' => [['k' => 'Type', 'v' => '1D / 2D imager'], ['k' => 'Speed', 'v' => 'Instant focus']]],
            ['label' => 'Connectivity',       'rows' => [['k' => 'Wi-Fi', 'v' => 'Dual-band'], ['k' => 'Cellular', 'v' => '4G LTE'], ['k' => 'Bluetooth', 'v' => '5.0']]],
            ['label' => 'Durability',         'rows' => [['k' => 'Body', 'v' => 'Rugged, dust-resistant'], ['k' => 'Drop', 'v' => '1.2 m to concrete']]],
        ],
    ],

    'skel-tab' => [
        'name'       => 'Skel Tab',
        'eyebrow'    => 'Tablet retail POS',
        'hero'       => [
            'subtitle' => 'Turn a tablet into a complete point of sale. Skel Tab drops into a sturdy stand and runs your whole counter with room to grow.',
            'image'    => 'assets/Moc-tab.webp',
        ],
        'intro_title'        => 'A tablet POS made for',
        'intro_title_accent' => 'flexible counters',
        'intro_subtitle'     => 'One adaptable tablet that runs the till today and grows with your shop tomorrow.',
        'features' => [
            ['name' => 'Big, flexible screen', 'body' => 'A roomy, high-resolution display gives you space to work and swivels smoothly between staff and customer when it is time to confirm an order or capture a signature. It suits boutiques, pharmacies and service counters alike — big enough to browse a full catalogue, yet light enough to turn, tilt and share across the counter all day.', 'image' => 'assets/Moc-tab-02.webp'],
            ['name' => 'Counter or handheld', 'body' => 'Dock Skel Tab on its stand for a tidy fixed checkout, then lift it off in one motion to count stock, line-bust a queue or sell out on the floor. It is the same screen doing two jobs, so your team learns one tool and uses it everywhere — from the till to the stockroom to the pavement outside.', 'image' => 'assets/poswithtab.webp'],
            ['name' => 'Grows with your shop', 'body' => 'Start simple and add a receipt printer, barcode scanner, cash drawer or card reader whenever you are ready — each one connects in minutes. SkelApp ties the whole setup together behind the scenes, so the tablet you buy today keeps pace as your shop adds lines, staff and branches tomorrow.', 'image' => 'assets/DASHBOARD.png'],
            ['name' => 'Takes every payment', 'body' => 'Cash, mobile money and cards all flow through one tablet, with M-Pesa, Airtel Money and Tigo Pesa alongside tap, chip and PIN. Every payment is recorded automatically against the sale and printed on a single, clear receipt — one device handling the whole transaction from first scan to final change.', 'image' => 'assets/card.webp'],
        ],
        'specs' => [
            ['label' => 'Display',            'rows' => [['k' => 'Size', 'v' => '10.1" Full HD'], ['k' => 'Touch', 'v' => '10-point multi-touch']]],
            ['label' => 'Processor & memory', 'rows' => [['k' => 'CPU', 'v' => 'Octa-core'], ['k' => 'RAM', 'v' => '4 GB'], ['k' => 'Storage', 'v' => '64 GB']]],
            ['label' => 'Mounting',           'rows' => [['k' => 'Stand', 'v' => 'Swivel + tilt'], ['k' => 'Release', 'v' => 'Quick-detach']]],
            ['label' => 'Connectivity',       'rows' => [['k' => 'Wi-Fi', 'v' => 'Dual-band'], ['k' => 'Bluetooth', 'v' => '5.0'], ['k' => 'Cellular', 'v' => '4G (optional)']]],
            ['label' => 'Operating environment', 'rows' => [['k' => 'Temperature', 'v' => '0–40 °C'], ['k' => 'Battery', 'v' => 'All-day on a charge']]],
        ],
    ],

    'skel-phone' => [
        'name'       => 'Skel Phone',
        'eyebrow'    => 'POS on your phone',
        'hero'       => [
            'subtitle' => 'Run your whole shop from the phone in your pocket. Scan, sell and print with SkelApp — no extra hardware needed to get started.',
            'image'    => 'assets/Mobilehomeview.png',
        ],
        'intro_title'        => 'A pocket POS made for',
        'intro_title_accent' => 'getting started fast',
        'intro_subtitle'     => 'Turn the phone in your pocket into a full point of sale — no extra kit required to start selling.',
        'features' => [
            ['name' => 'Start with what you have', 'body' => 'There is no new box to buy to get going — download SkelApp on the Android or iPhone already in your pocket and you can start selling the same day. It is the lowest-risk way to bring your shop online, and as business grows you can add a printer, scanner or full station whenever the time is right.', 'image' => 'assets/Mobilehomeview.png'],
            ['name' => 'Sell from anywhere', 'body' => 'Markets, deliveries, pop-up stalls or the shop floor — take a sale and collect payment wherever your customer happens to be. Your prices, products and stock travel with you on the phone, so a sale made on the road is treated exactly like one rung up at the counter, with nothing to re-enter later.', 'image' => 'assets/CHECKOUTPOS.png'],
            ['name' => 'Everything in sync', 'body' => 'A sale made on your phone shows up instantly across every other device and branch, giving you one live view of the whole business. Stock, takings and customer records stay current everywhere at once — so whether you check from the counter or from home, the numbers you see are always up to the minute.', 'image' => 'assets/CASHFLOW.png'],
            ['name' => 'Print receipts anywhere', 'body' => 'Pair a pocket Bluetooth thermal printer and hand over a proper printed receipt on the spot — at the market, the doorway or the table. When paper is not needed, send the same receipt by SMS or email in a tap, so every customer leaves with a clear record however they prefer to receive it.', 'image' => 'assets/possd.png'],
        ],
        'specs' => [
            ['label' => 'Compatibility',      'rows' => [['k' => 'Android', 'v' => '9.0 and newer'], ['k' => 'iOS', 'v' => '14 and newer']]],
            ['label' => 'Payments',           'rows' => [['k' => 'Mobile money', 'v' => 'M-Pesa, Airtel, Tigo'], ['k' => 'Cards', 'v' => 'With Skel card reader']]],
            ['label' => 'Printing',           'rows' => [['k' => 'Receipts', 'v' => 'Bluetooth thermal'], ['k' => 'Format', 'v' => '58mm / 80mm']]],
            ['label' => 'Offline',            'rows' => [['k' => 'Mode', 'v' => 'Full offline selling'], ['k' => 'Sync', 'v' => 'Automatic on reconnect']]],
        ],
    ],
];

return [
    'shared'   => $shared,
    'products' => $products,
];
