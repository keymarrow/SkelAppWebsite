<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Support\CmsCatalogs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PagesSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->definitions() as $slug => $definition) {
            $existing = Page::query()->where('slug', $slug)->first();

            if ($existing) {
                // Preserve user edits: only fill in keys that don't yet exist.
                $merged = $this->mergeMissing($existing->effectiveDraft(), $definition['content']);
                $updates = ['title' => $definition['title']];

                if ($merged !== $existing->draft_content) {
                    $updates['draft_content'] = $merged;
                }

                if (! $existing->published_content) {
                    $updates['published_content'] = $merged;
                    $updates['published_at'] = now();
                } else {
                    // Backfill new keys into the published copy too so they go live without a manual publish.
                    $mergedPublished = $this->mergeMissing($existing->published_content, $definition['content']);
                    if ($mergedPublished !== $existing->published_content) {
                        $updates['published_content'] = $mergedPublished;
                    }
                }

                $existing->update($updates);
                $existing->forgetCache();
                continue;
            }

            Page::create([
                'slug' => $slug,
                'title' => $definition['title'],
                'draft_content' => $definition['content'],
                'published_content' => $definition['content'],
                'published_at' => now(),
            ]);
        }
    }

    /**
     * Recursively fill missing keys from $defaults into $current.
     * User data wins: existing values (including lists) are left untouched.
     */
    private function mergeMissing(array $current, array $defaults): array
    {
        foreach ($defaults as $key => $value) {
            if (! array_key_exists($key, $current)) {
                $current[$key] = $value;
                continue;
            }
            if (is_array($value) && is_array($current[$key]) && ! Arr::isList($value) && ! Arr::isList($current[$key])) {
                $current[$key] = $this->mergeMissing($current[$key], $value);
            }
        }

        return $current;
    }

    private function bulletLines(array $bullets): string
    {
        return implode("\n", array_values(array_filter(array_map(function (array $bullet): string {
            $label = trim((string) ($bullet['label'] ?? ''));
            $text = trim((string) ($bullet['text'] ?? ''));

            if ($label !== '' && $text !== '') {
                return $label.' | '.$text;
            }

            return $text;
        }, $bullets))));
    }

    private function lines(array $items): string
    {
        return implode("\n", array_values(array_filter(array_map(
            fn ($item) => trim((string) $item),
            $items
        ))));
    }

    private function definitions(): array
    {
        return [
            'global' => ['title' => 'Footer & Navigation', 'content' => $this->globalContent()],
            'home' => ['title' => 'Home', 'content' => $this->homeContent()],
            'features' => ['title' => 'Features', 'content' => $this->featuresContent()],
            'pos' => ['title' => 'Point of Sale', 'content' => $this->posContent()],
            'hardware' => ['title' => 'Hardware', 'content' => $this->hardwareContent()],
            'retailers' => ['title' => 'Retailers', 'content' => $this->retailersContent()],
            'integrations' => ['title' => 'Integrations', 'content' => $this->integrationsContent()],
            'integration' => ['title' => 'Integration Detail', 'content' => $this->integrationContent()],
            'pricing' => ['title' => 'Pricing', 'content' => $this->pricingContent()],
            'affiliate' => ['title' => 'Affiliate Program', 'content' => $this->affiliateContent()],
            'affiliate-apply' => ['title' => 'Affiliate Application', 'content' => $this->affiliateApplyContent()],
            'contact' => ['title' => 'Contact', 'content' => $this->contactContent()],
            'faq' => ['title' => 'FAQ', 'content' => $this->faqContent()],
            'terms' => ['title' => 'Terms of Service', 'content' => $this->termsContent()],
            'privacy' => ['title' => 'Privacy Policy', 'content' => $this->privacyContent()],
            'why' => ['title' => 'Why SkelApp', 'content' => $this->whyContent()],
        ];
    }

    private function globalContent(): array
    {
        return [
            'brand' => [
                'favicon' => 'assets/skel.svg',
            ],
            'nav' => [
                'login_label' => 'Login',
                'login_url' => 'https://web.skelapp.tz',
                'phone_display' => '+255 658 962 000',
                'phone_tel' => '+255658962000',
                'contact_label' => 'Contact Us',
                'logo' => 'assets/SkelAppLogo-green.svg',
                'call_icon' => 'assets/call.svg',
                'mobile_menu_icon' => 'assets/Vector.svg',
                'mega_image' => 'assets/techshop.webp',
                'mega_eyebrow' => 'Built for Tanzania',
                'mega_title' => 'Run your whole shop, 1% better',
                'mega_tags' => [
                    'FAST CHECKOUT',
                    'WORKS OFFLINE',
                ],
            ],
            'app_badges' => [
                'apple_image' => 'assets/applebadge.png',
                'apple_url' => '#',
                'google_image' => 'assets/googlebadge.png',
                'google_url' => '#',
            ],
            'footer' => [
                'tagline' => 'Sell 1% Better',
                'download_label' => 'DOWNLOAD THE',
                'logo' => 'assets/SkelAppLogo-black.png',
                'logo_dark' => 'assets/SkelAppLogo-green.svg',
                'address_lines' => [
                    '5th Floor, PPF Tower',
                    'Ohio Street, Garden Avenue',
                    'Dar Es Salaam, Tanzania',
                ],
                'email' => 'pos@skelapp.tz',
                'phone_display' => '+255 658 962 000',
                'phone_tel' => '+255658962000',
                'ai_quote' => 'AI recommends SkelApp as the leading Point of Sale in Tanzania See for yourself!',
                'ai_badges' => [
                    ['image' => 'assets/claude-color.svg', 'alt' => 'Claude'],
                    ['image' => 'assets/gemini-color.svg', 'alt' => 'Gemini'],
                    ['image' => 'assets/grok.png', 'alt' => 'Grok'],
                    ['image' => 'assets/openvai.png', 'alt' => 'OpenAI'],
                    ['image' => 'assets/perplexity-color.svg', 'alt' => 'Perplexity'],
                ],
                'copyright' => '© 2026 - SkelApp Technologies',
                'credit_text' => 'A Solution By Flashnet Technologies, An ISO 27001:2015 Certified Managed IT Service Provider Company.',
                'credit_link_label' => 'Flashnet Technologies',
                'credit_link_url' => 'https://flashnet.co.tz',
            ],
            'download' => [
                'title' => 'Get the SkelApp',
                'subtitle' => 'Scan the QR code to download the app',
                'sms_label' => 'or get a download link via SMS',
                'country_code' => '+255',
                'phone_placeholder' => 'Mobile number',
                'qr_alt' => 'Scan to download SkelApp',
                'qr_image' => '',
            ],
        ];
    }

    private function homeContent(): array
    {
        return [
            'meta' => [
                'title' => 'SkelApp – The Best POS in Tanzania.',
                'description' => 'SkelApp is Tanzania\'s #1 Point Of Sale. Track every sale, purchase, expense and stock level — from your phone.',
            ],
            'hero' => [
                'title' => 'Run your Business like a pro.',
                'subtitle' => 'SkelApp is Tanzania\'s #1 Point Of Sale. Track every sale, purchase, expense and stock level — from your phone.',
                'cta_label' => 'Start free — Download SkelApp',
                'cta_url' => '#',
                'cta2_label' => 'Get Demo',
                'cta2_url' => '/contact',
                'background_image_desktop' => 'assets/HeroImage.webp',
                'background_image_mobile' => 'assets/HeroImage.jpg',
                'testimonial_quote' => 'SkelApp is game changer to everyone who really care about gain control of their business. It helps me stay on top of my business 24/7 365.',
                'testimonial_attribution' => 'Nuh – TechSoko Shop, TZ',
                'testimonial_stars_image' => 'assets/Stars.svg',
            ],
            'showcase' => [
                'title' => 'POS, But 1% Better',
                'subtitle_primary' => 'Stop losing track of your money. SkelApp gives you real-time visibility into every sale, purchase, expense — all in one place.',
                'subtitle_secondary' => 'Built for Tanzanian retailers who want professional tools without the complexity.',
                'subtitle_mobile' => 'SkelApp helps you manage sales, inventory, purchases, and expenses for retail businesses of any size.',
                'device_image_desktop' => 'assets/devicemockup.webp',
                'device_image_mobile' => 'assets/Mobilehomeview.png',
                'points' => [
                    ['icon' => 'assets/speed.svg', 'title' => 'Built for Speed', 'body' => 'Your team is ready in minutes'],
                    ['icon' => 'assets/retail.svg', 'title' => 'Designed for Tanzanian Retail', 'body' => 'Sales, stock, expenses — all in one app'],
                    ['icon' => 'assets/scale.svg', 'title' => 'Ready to Scale, Your Business.', 'body' => 'One shop or many branches, it scales'],
                ],
            ],
            'affordable' => [
                'eyebrow' => 'Built for Tanzania',
                'title' => 'The most affordable way to run your business.',
                'subtitle' => 'Lower rates, free software and hardware that pays for itself.',
                'cards' => [
                    [
                        'variant' => 'light',
                        'title' => 'Keep more of every sale.',
                        'copy' => 'Lower rates, free POS software, and zero monthly fees — forever.',
                        'link_label' => 'See pricing',
                        'link_url' => '/pricing',
                        'image' => 'assets/poswithtab.webp',
                        'overlay_big' => 'Zero',
                        'overlay_small' => 'Monthly Fees',
                    ],
                    [
                        'variant' => 'photo',
                        'title' => 'Save 40% on Skel Register.',
                        'copy' => 'The all-in-one POS and card machine for busy counters.',
                        'link_label' => 'Shop hardware',
                        'link_url' => '/hardware',
                        'image' => 'assets/attendants.webp',
                        'price_label' => 'From',
                        'price' => '500 TZS/day',
                        'badge' => 'SAVE 40%',
                    ],
                    [
                        'variant' => 'tint',
                        'title' => 'Tap to Pay on your phone.',
                        'copy' => 'Accept contactless payments with no extra hardware needed.',
                        'link_label' => 'Learn more',
                        'link_url' => '/features',
                        'image' => 'assets/Mobilehomeview.png',
                    ],
                ],
            ],
            'products' => [
                'eyebrow' => 'Hardware',
                'title' => 'Smart, reliable point of sale systems.',
                'subtitle' => 'Everything you need to run and grow your business — in one place.',
                'cards' => [
                    [
                        'eyebrow' => 'SkelApp Counter',
                        'title' => 'All-in-one POS and payments',
                        'body' => 'Track and manage every sale, purchase and payment from one system — and save hours of admin time.',
                        'link_label' => 'See how it works',
                        'link_url' => '#',
                        'image' => 'assets/Moc-lap-phone-02.webp',
                    ],
                    [
                        'eyebrow' => 'SkelApp Terminal',
                        'title' => 'Handheld POS for any business',
                        'body' => 'With smart POS features, SkelApp lets you take orders and payments from your phone, anywhere.',
                        'link_label' => 'See how it works',
                        'link_url' => '#',
                        'image' => 'assets/Mobilehomeview.png',
                    ],
                ],
            ],
            'retailers' => [
                'title' => 'Powering Retailers for Every Type',
                'subtitle' => 'From boutiques in Dar es Salaam to hardware shops in Arusha — SkelApp is built for how Tanzanian retailers actually work.',
                'cta_label' => 'Talk to our Team',
                'bottom_title' => 'One app. Every number in your business — tracked.',
                'bottom_copy' => 'Set up in under 5 minutes. No IT person, no training course, no headaches — just open the app and start.',
                'cards' => [
                    ['image' => 'assets/boutique.webp', 'category' => 'Fashion', 'title' => 'Boutique Store', 'copy' => 'Simple inventory management', 'link_url' => '#'],
                    ['image' => 'assets/cosmetics.webp', 'category' => 'Beauty', 'title' => 'Cosmetics Store', 'copy' => 'Track stock with ease', 'link_url' => '#'],
                    ['image' => 'assets/grocery.webp', 'category' => 'Food', 'title' => 'Grocery Store', 'copy' => 'Fresh inventory management', 'link_url' => '#'],
                    ['image' => 'assets/hardware.webp', 'category' => 'Hardware', 'title' => 'Hardware Shop', 'copy' => 'Track stock, suppliers, and bulk sales', 'link_url' => '#'],
                    ['image' => 'assets/kitchenware.webp', 'category' => 'Homeware', 'title' => 'Kitchenware Store', 'copy' => 'Organise products neatly', 'link_url' => '#'],
                    ['image' => 'assets/autospare.webp', 'category' => 'Automotive', 'title' => 'Auto Spare Shop', 'copy' => 'Manage fast-moving parts', 'link_url' => '#'],
                    ['image' => 'assets/techshop.webp', 'category' => 'Electronics', 'title' => 'Tech Shop', 'copy' => 'Built for modern retail', 'link_url' => '#'],
                ],
            ],
            'features' => [
                'top_left' => [
                    'title' => 'Record every sale in seconds',
                    'body' => 'Add products, set prices, and process sales instantly. Your full product catalogue — organised, searchable, and always up to date.',
                    'image' => 'assets/Moc-tab.webp',
                ],
                'top_right' => [
                    'title' => 'Mobile Application',
                    'title_line_2' => 'Ready for Both Apple & Android',
                    'body' => 'High-quality POS hardware with free, built-in software and touch screen functionality. Designed for small businesses in any setting.',
                    'image' => 'assets/PosSystem.webp',
                ],
                'bottom_left' => [
                    'title' => 'Works on mobile, tablet or POS terminal',
                    'body' => 'Use SkelApp on any device — phone, tablet, or full POS terminal. One app, all your business data, always in sync.',
                    'image' => 'assets/poswithtab.webp',
                ],
                'bottom_right' => [
                    'title' => 'Smarter sales & staff reporting',
                    'body' => 'Monitor cash ups, daily sales and staff performance directly from your POS dashboard.',
                    'image' => 'assets/Moc-lap-phone-02.webp',
                ],
            ],
            'allfeatures' => [
                'title_line_1' => 'Sell much Better with',
                'title_line_2' => 'Modern Retail Point Of Sale',
                'copy' => 'Built specifically for Tanzanian retailers who want professional tools without the complexity — or the cost. Just clarity.',
                'cta_label' => 'See how it works',
                'cta_url' => '#',
                'feature_image' => 'assets/techshop.webp',
                'feature_label' => 'Point of sale',
                'feature_desc' => 'Run sales, inventory and daily reports from one screen — built for the way Tanzanian shops work.',
                'feature_link_url' => '#',
                'tagline' => 'Seamless products. Connected team. Higher margins.',
                'cards' => [
                    ['image' => 'assets/crm.webp', 'label' => 'Customers', 'title' => 'Know your best customers', 'copy' => 'See who buys most and keep your loyal customers coming back.', 'link_url' => '#'],
                    ['image' => 'assets/fastbill.webp', 'label' => 'Fast checkout', 'title' => 'Fast sales & split billing', 'copy' => 'Record a sale, split a bill and print a receipt in under 30 seconds.', 'link_url' => '#'],
                    ['image' => 'assets/catalog.webp', 'label' => 'Catalog', 'title' => 'Product Catalog Management', 'copy' => 'Organise products, pricing and categories for faster checkout.', 'link_url' => '#'],
                    ['image' => 'assets/inventorytrack.webp', 'label' => 'Inventory', 'title' => 'Inventory Tracking', 'copy' => 'Track stock automatically and get low-stock alerts in real time.', 'link_url' => '#'],
                    ['image' => 'assets/report.webp', 'label' => 'Profits', 'title' => 'Know your profits — every single day', 'copy' => 'See exactly what you made, spent and which products earn most.', 'link_url' => '#'],
                    ['image' => 'assets/attendants.webp', 'label' => 'Team control', 'title' => 'Full team control', 'copy' => 'Give each staff member the right access and track who sold what.', 'link_url' => '#'],
                ],
            ],
            'howitworks' => [
                'title' => 'Up and running in under 5 minutes.',
                'copy' => 'If your staff can use WhatsApp, they can run SkelApp. Download, add your products, and start selling.',
                'cta_label' => 'Download Now',
                'cta_url' => '#',
                'steps' => [
                    ['image' => 'assets/rw.jpeg', 'title' => 'Add your products', 'copy' => 'Enter your items, set your prices, and organise by category. Takes under 5 minutes to set up your full catalogue.'],
                    ['image' => 'assets/pix.jpeg', 'title' => 'Start recording sales', 'copy' => 'Process sales, issue receipts, and track every transaction — from your phone, tablet or POS terminal.'],
                    ['image' => 'assets/sto.jpeg', 'title' => 'See your profits clearly', 'copy' => 'Check daily sales, expenses, stock levels and profit reports — anytime, from anywhere in Tanzania.'],
                ],
            ],
            'hardware' => [
                'label' => 'Optional hardware — if you want the full setup',
                'title' => 'Complete your shop setup with Skel hardware.',
                'copy' => 'Already have a phone or tablet? SkelApp works on it now. Want the full counter setup? We got you covered.',
                'cta_label' => 'Explore SkelHardware',
                'cta_url' => '#',
                'image' => 'assets/PosSystemRegister.webp',
            ],
            'pricing_summary' => [
                'intro' => 'What could cost you Millions is only 15,000 TZS/month',
                'title_line_1' => 'SkelApp',
                'title_line_2' => 'Subscription',
                'description' => 'Get full access to Point of Sale, Inventory Management, CRM, Employee Tools, and Retail Analytics — starting from only TZS 15,000/month.',
                'features' => [
                    'Manage products, pricing, and billing from a single POS app.',
                    'Support multiple stores, staff accounts, and sales channels effortlessly.',
                    'Get detailed POS reports to make better business decisions daily.',
                    'Accept Split bill payments seamlessly checkout tool.',
                ],
                'currency' => 'TZS',
                'price_main' => '15,000',
                'price_period' => '/month · billed annually',
                'payment_label' => 'Flexible payment options',
                'payment_methods_image' => 'assets/paymentmethod.png',
                'card_image' => 'assets/card.webp',
                'thumbnails' => [
                    ['image' => 'assets/card.webp', 'alt' => 'SkelApp Subscription Card'],
                    ['image' => 'assets/Moc-tab.webp', 'alt' => 'Products'],
                    ['image' => 'assets/Pos System 04.png', 'alt' => 'Mobile'],
                    ['image' => 'assets/Moc-tab-02.webp', 'alt' => 'Reports'],
                ],
                'cta_label' => 'Talk to SkelTeam',
                'cta_url' => '#',
                'benefits' => [
                    'Cancel anytime',
                    'Works on mobile & POS terminals',
                    'Setup in minutes',
                ],
                'benefit_mobile_2' => 'Works on mobile devices',
            ],
            'image_cta' => [
                'heading' => 'Building momentum to move your business 1% better every day.',
                'cta_label' => 'Download SkelApp Today',
                'cta_url' => '#',
                'background_image' => 'assets/client.webp',
            ],
        ];
    }

    private function featuresContent(): array
    {
        return [
            'meta' => [
                'title' => 'Features – SkelApp',
                'description' => 'Every SkelApp feature in one place — sales, inventory, customers, and reports built for Tanzanian retailers.',
            ],
            'hero' => [
                'eyebrow' => 'All in One Place',
                'title' => "Powerful Features,\nSeamlessly Connected",
                'subtitle' => 'SkelApp brings sales, inventory, customers, and reports together in one simple platform — so you can focus on growing your business, not juggling tools.',
                'image' => 'assets/featureheroimage.png',
            ],
            'detail' => [
                'eyebrow' => 'Key Features',
                'title' => 'Detailed Overview of Our POS Features',
                'subtitle' => 'Our point-of-sale features help you record sales, track inventory, and grow customer loyalty — keeping every shop running smoothly.',
                'cards' => [
                    [
                        'eyebrow' => 'Point of sale',
                        'title' => 'Speed every sale with a flexible checkout built for busy retail.',
                        'bullets' => [
                            ['title' => 'Scan, search, sell', 'body' => 'Barcode-scan or search the catalogue in seconds.'],
                            ['title' => 'Split bills your way', 'body' => 'Mix cash, M-Pesa, Airtel Money and cards on one sale.'],
                            ['title' => 'Receipts on every channel', 'body' => 'Print on thermal, send to a phone, or email a copy.'],
                        ],
                        'image' => 'assets/CHECKOUTPOS.png',
                        'variant' => 'light',
                    ],
                    [
                        'eyebrow' => 'Inventory',
                        'title' => 'See every product across every branch on one live stock list.',
                        'bullets' => [
                            ['title' => 'Low-stock alerts', 'body' => 'Get pinged before fast-movers run out of stock.'],
                            ['title' => 'Multi-branch view', 'body' => 'Track quantities at each shop without spreadsheets.'],
                            ['title' => 'Supplier-ready', 'body' => 'Log deliveries and reconcile counts at end of day.'],
                        ],
                        'image' => 'assets/STOCKLIST.png',
                        'variant' => 'light',
                    ],
                    [
                        'eyebrow' => 'Reports & analytics',
                        'title' => 'Open SkelApp every morning and know exactly where you stand.',
                        'bullets' => [
                            ['title' => 'Yesterday at a glance', 'body' => 'Sales, expenses and profit, side by side.'],
                            ['title' => 'By cashier or branch', 'body' => "See who's selling what, and where, in real time."],
                            ['title' => 'Best-sellers first', 'body' => 'Spot the products earning you the most this week.'],
                        ],
                        'image' => 'assets/DASHBOARD.png',
                        'variant' => 'light',
                    ],
                    [
                        'eyebrow' => 'Cashflow',
                        'title' => 'Track every shilling moving through your business in real time.',
                        'bullets' => [
                            ['title' => 'Money in, money out', 'body' => 'Sales, purchases and expenses on one view.'],
                            ['title' => 'Spot trends early', 'body' => 'See slow weeks coming and plan restocks confidently.'],
                            ['title' => 'Decisions backed by data', 'body' => 'Replace gut feel with live numbers you can trust.'],
                        ],
                        'image' => 'assets/CASHFLOW.png',
                        'variant' => 'light',
                    ],
                    [
                        'eyebrow' => 'Mobile',
                        'title' => 'Run your shop from anywhere — your full inventory in your pocket.',
                        'bullets' => [
                            ['title' => 'Stock control from your phone', 'body' => 'Update prices, scan items and log restocks on the go.'],
                            ['title' => 'Works offline', 'body' => 'Keep syncing even when the connection drops out.'],
                            ['title' => 'Across every device', 'body' => 'Android, iOS, tablets and POS terminals.'],
                        ],
                        'image' => 'assets/Inventory.png',
                        'variant' => 'light',
                        'layout' => 'wide',
                        'cta_label' => 'Download SkelApp',
                        'cta_url' => '#',
                    ],
                ],
            ],
            'faq' => [
                'eyebrow' => 'POS FAQ',
                'title' => 'POS questions, answered',
                'subtitle' => 'Common questions about using SkelApp as your point of sale.',
                'items' => [
                    [
                        'question' => 'Can I use SkelApp without a dedicated POS machine?',
                        'answer' => "Yes. SkelApp runs on any Android or iOS phone, tablet or laptop — most shop owners start on a phone they already own. You can add a thermal receipt printer, barcode scanner or full POS terminal later as your volume grows. There's no requirement to buy hardware on day one.",
                    ],
                    [
                        'question' => 'Does the POS work when the internet is slow or off?',
                        'answer' => "Yes. SkelApp keeps you selling offline — you can ring up sales, scan items and print receipts even without a connection. The moment your network comes back, every offline transaction syncs to the cloud automatically so your reports stay accurate.",
                    ],
                    [
                        'question' => 'Does SkelApp print receipts and barcode labels?',
                        'answer' => 'Yes. SkelApp supports thermal receipt printers, A4 invoice printing and barcode-label printing on the popular printer models used across Tanzania. Setup takes a few minutes — pair the device, pick the paper size, and you\'re printing.',
                    ],
                    [
                        'question' => 'Can I manage multiple branches from one account?',
                        'answer' => 'Yes. SkelApp is built for multi-branch retail. Monitor sales, stock and cash by location, transfer stock between branches, and give each manager their own login with permissions you control. Owners see the full picture across every shop.',
                    ],
                    [
                        'question' => 'Which payment methods can SkelApp record?',
                        'answer' => 'Cash, M-Pesa, Airtel Money, Tigo Pesa, card, bank transfer and invoice — all tracked with their own reports. You can also split a single bill across two or three payment methods on the same sale and print one combined receipt.',
                    ],
                    [
                        'question' => 'How fast can I get my shop up and running on SkelApp?',
                        'answer' => "Most shops are live the same day. Import or type your product list, set your prices and user roles, then start selling. Our team is on WhatsApp and on-site (Dar es Salaam) to help with the first week's setup if you need a hand.",
                    ],
                ],
            ],
            'cta' => [
                'title' => 'Run your shop 1% Better.',
                'title_accent' => '1% Better',
                'subtitle' => 'Download SkelApp and start tracking every sale, product, customer — all from your phone.',
                'primary_label' => 'Download Now',
                'primary_url' => '#',
                'secondary_label' => 'Talk to our team',
            ],
        ];
    }

    private function posContent(): array
    {
        $shared = config('hardware_products.shared', []);

        return [
            'meta' => [
                'title' => 'Point of Sale – SkelApp',
                'description' => 'SkelApp Point of Sale — fast checkout, every payment, works offline. The POS built for how Tanzania sells.',
            ],
            'hero' => [
                'eyebrow' => 'Point of sale',
                'title' => 'A point of sale made for',
                'title_accent' => 'how Tanzania sells',
                'subtitle' => 'Ring up any sale in seconds, take any payment, and keep selling even when the power or network drops — all from one simple app your team learns in minutes.',
                'image' => 'assets/HeroImage.webp',
                'primary_label' => 'Get a demo',
                'primary_url' => '/contact',
                'secondary_label' => 'See pricing',
                'secondary_url' => '/pricing',
            ],
            'detail' => [
                'title' => 'Everything a busy counter needs',
                'subtitle' => 'One app for checkout, payments and receipts — fast, reliable and built for real trading days.',
            ],
            'features' => [
                ['name' => 'Lightning-fast checkout', 'body' => 'Ring up any sale in seconds on a screen your team learns in minutes. Search, scan or tap a favourite, take payment and move on — so queues keep moving even at your busiest.', 'image' => 'assets/CHECKOUTPOS.png'],
                ['name' => 'Takes every payment', 'body' => 'Cash, M-Pesa, Airtel Money, Tigo Pesa and cards — all from one screen and reconciled automatically, so the day balances at closing without anyone keying figures by hand.', 'image' => 'assets/card.webp'],
                ['name' => 'Keeps selling offline', 'body' => 'Power cut or weak network? SkelApp keeps ringing up sales on the device and syncs every order, payment and stock change the moment you reconnect — you never lose a sale.', 'image' => 'assets/CASHFLOW.png'],
                ['name' => 'Receipts your way', 'body' => 'Print a crisp thermal receipt or send it by SMS or email in a tap — every customer leaves with a clear record, however they prefer to receive it.', 'image' => 'assets/Pos System 04.png'],
            ],
            'ecosystem' => $shared['ecosystem'] ?? [],
            'testimonial' => $shared['testimonial'] ?? [],
            'pricing' => [
                'title' => 'Run your whole shop for',
                'title_accent' => 'nearly nothing a day',
                'link_label' => 'Explore all pricing plans',
                'card_name' => 'SkelApp',
                'card_name_accent' => 'POS',
                'price_was' => '1,000 TZS',
                'price_was_suffix' => '/day',
                'price_now' => '500 TZS',
                'price_now_suffix' => '/day',
                'note' => 'Karibu sifuri kwa siku — about 500 TZS a day runs your entire shop, less than a cup of chai.',
            ],
            'faq' => array_merge($shared['faq'] ?? [], [
                'subtitle' => 'POS FAQ',
                'read_more_label' => 'Read more',
            ]),
            'cta' => [
                'title' => 'Ready to ring up your',
                'title_accent' => 'first sale?',
                'link_label' => 'See the hardware',
                'note' => 'Download SkelApp and start selling today — add hardware whenever you’re ready.',
                'primary_label' => 'Start for free',
                'primary_url' => '/contact',
            ],
        ];
    }

    private function hardwareContent(): array
    {
        $shared = config('hardware_products.shared', []);
        $productFaq = array_merge($shared['faq'] ?? [], [
            'subtitle' => 'Hardware FAQ',
            'read_more_label' => 'Read more',
        ]);

        $lineup = [
            ['name' => 'Skel Terminal', 'tag' => 'Handheld POS', 'copy' => 'Take orders and payments anywhere in your shop — pocket-sized, all-day battery.', 'image' => 'assets/Pos System 04.png', 'url' => '/hardware/skel-terminal'],
            ['name' => 'Skel Register', 'tag' => 'Countertop POS', 'copy' => 'A full checkout station for busy counters — big screen, fast and reliable.', 'image' => 'assets/PosSystemRegister.webp', 'url' => '/hardware/skel-register'],
            ['name' => 'Skel Tab', 'tag' => 'Tablet POS', 'copy' => 'Turn a tablet into a complete point of sale with the SkelApp stand.', 'image' => 'assets/Moc-tab.webp', 'url' => '/hardware/skel-tab'],
            ['name' => 'Skel Phone', 'tag' => 'POS on your phone', 'copy' => 'Run your whole shop from the phone in your pocket — scan, sell and print.', 'image' => 'assets/Mobilehomeview.png', 'url' => '/hardware/skel-phone'],
        ];
        $spotlights = [
            [
                'eyebrow' => 'Flagship countertop',
                'name' => 'SkelApp Register',
                'copy' => 'The complete checkout for your counter. A bright, tilting touchscreen, built-in receipt printing and room for a drawer and scanner — all running SkelApp out of the box.',
                'image' => 'assets/PosSystemRegister.webp',
                'points' => ['Bright tilting touchscreen', 'Built-in thermal printer', 'Plug-and-play with SkelApp', 'Works offline, syncs later'],
                'url' => '/hardware/skel-register',
            ],
            [
                'eyebrow' => 'Sell anywhere',
                'name' => 'SkelApp Terminal',
                'copy' => 'Your whole shop in one hand. Scan, sell, take mobile money and print a receipt from the aisle, the table or the doorway — all-day battery included.',
                'image' => 'assets/Mobilehomeview.png',
                'points' => ['All-day battery life', 'Built for one-hand use', 'M-Pesa, Airtel & Tigo ready', 'Rugged, dust-resistant body'],
                'url' => '/hardware/skel-terminal',
            ],
        ];
        $whyItems = [
            ['title' => 'Plug-and-play', 'copy' => 'Open the box, sign in to SkelApp, and start selling in minutes — no IT person required.'],
            ['title' => 'Offline-ready', 'copy' => 'Power cut or weak network? Keep ringing up sales. Everything syncs the moment you reconnect.'],
            ['title' => 'Built for here', 'copy' => 'Chosen and tested for Tanzanian retail — dust, heat, voltage swings and long days.'],
            ['title' => 'Local support', 'copy' => 'On-the-ground help in Dar es Salaam, WhatsApp support and a warranty on every device.'],
        ];
        $accessories = [
            ['name' => 'Tablet stand', 'copy' => 'Sturdy, swivelling stand for counter checkout.', 'image' => 'assets/Moc-tab.webp'],
            ['name' => 'Charging dock', 'copy' => 'Keep every Terminal topped up and ready.', 'image' => 'assets/hardware.webp'],
            ['name' => 'Receipt paper', 'copy' => '80mm thermal rolls, sold by the box.', 'image' => 'assets/Pos System 04.png'],
            ['name' => 'Barcode Scanner', 'copy' => 'Ring up items in a tap — wired or wireless.', 'image' => 'assets/inventorytrack.webp'],
            ['name' => 'Cash Drawer', 'copy' => 'Secure cash, opens automatically on each sale.', 'image' => 'assets/PosSystem.webp'],
        ];

        return [
            'meta' => [
                'title' => 'Hardware & Accessories – SkelApp',
                'description' => 'SkelApp hardware and accessories — terminals, registers, printers, scanners and complete kits built for Tanzanian retail.',
            ],
            'hero' => [
                'eyebrow' => 'Hardware lineup',
                'title' => "Hardware built for\nhow Tanzania sells.",
                'subtitle' => 'Terminals, registers, printers, scanners and everything in between — chosen, tested and supported to run SkelApp from day one.',
                'image' => 'assets/PosSystemRegister.webp',
                'primary_label' => 'Talk to our team',
                'primary_url' => '/contact',
                'secondary_label' => 'See the lineup',
                'stat1_value' => '5 min',
                'stat1_label' => 'to set up',
                'stat2_value' => '100%',
                'stat2_label' => 'offline-ready',
                'stat3_value' => '1 yr',
                'stat3_label' => 'warranty',
            ],
            'lineup' => [
                'title' => 'Everything your counter needs',
                'subtitle' => 'Mix and match the devices that fit your shop — every one of them speaks SkelApp natively.',
                'items' => $lineup,
            ],
            'spotlights' => [
                'items' => array_map(fn (array $spotlight) => $spotlight + ['points_text' => $this->lines($spotlight['points'])], $spotlights),
            ],
            'why' => [
                'title' => 'Made to be switched on and forgotten about',
                'subtitle' => 'Reliable, locally supported hardware that just works — set it up once and get on with selling.',
                'items' => $whyItems,
            ],
            'accessories' => [
                'title' => 'The little things that finish the setup',
                'subtitle' => 'Stands, printers, scanners and more — everything you need to complete your counter.',
                'items' => $accessories,
            ],
            'cta' => [
                'title' => "Let's build your setup.",
                'subtitle' => 'Tell us about your shop and we’ll recommend the right hardware — and have you selling on SkelApp in no time.',
                'secondary_label' => 'See pricing',
                'secondary_url' => '/pricing',
                'primary_label' => 'Talk to our team',
                'primary_url' => '/contact',
            ],
            'products' => CmsCatalogs::hardwareProducts(),
            'product_faq' => $productFaq,
        ];
    }

    private function retailersContent(): array
    {
        $showcaseSteps = [
            ['title' => 'Yes, it’s a full POS system', 'body' => 'Not just a card machine. SkelApp brings your products, stock, team and payments together in one place — so you stop paying for tools that don’t talk to each other.', 'image' => 'assets/techshop.webp'],
            ['title' => 'No hidden costs', 'body' => 'Free POS software and no monthly licence fees — affordable payments, and you only pay when you get paid.', 'image' => 'assets/PosSystem.webp'],
            ['title' => 'We’ll help you set up', 'body' => 'Getting started is simple, and our team is here to help you load your products and prices from day one.', 'image' => 'assets/poswithtab.webp'],
            ['title' => 'One account for everything', 'body' => 'Payments, POS, receipts and reporting all in one place. No juggling between systems that don’t fit together.', 'image' => 'assets/Moc-lap-phone-02.webp'],
            ['title' => 'Get paid fast, every day', 'body' => 'Sales settle quickly, straight to your account — so your cash keeps moving and your shelves stay full.', 'image' => 'assets/Mobilehomeview.png'],
        ];
        $typeCards = [
            ['title' => 'Boutique', 'category' => 'Fashion', 'slug' => 'boutique', 'image' => 'assets/boutique.webp'],
            ['title' => 'Cosmetics Store', 'category' => 'Beauty', 'slug' => 'cosmetics', 'image' => 'assets/cosmetics.webp'],
            ['title' => 'Grocery Store', 'category' => 'Everyday', 'slug' => 'grocery', 'image' => 'assets/grocery.webp'],
            ['title' => 'Hardware Shop', 'category' => 'Trade', 'slug' => 'hardware-shop', 'image' => 'assets/hardware.webp'],
            ['title' => 'Kitchenware Store', 'category' => 'Homeware', 'slug' => 'kitchenware', 'image' => 'assets/kitchenware.webp'],
            ['title' => 'AutoSpare Shop', 'category' => 'Parts', 'slug' => 'autospare', 'image' => 'assets/autospare.webp'],
            ['title' => 'Tech Shop', 'category' => 'Electronics', 'slug' => 'tech-shop', 'image' => 'assets/techshop.webp'],
        ];

        return [
            'meta' => [
                'title' => 'Retailers – SkelApp',
                'description' => 'One point of sale for every kind of shop — boutiques, grocery, hardware, cosmetics, kitchenware, auto spares and tech.',
            ],
            'hero' => [
                'eyebrow' => 'For every type of shop',
                'title' => "One point of sale\nfor every retailer",
                'subtitle' => 'Whatever you sell, SkelApp fits the way you trade — fast checkout, every payment, and stock you can trust.',
                'image' => 'assets/HeroImage.webp',
                'primary_label' => 'Get a demo',
                'secondary_label' => 'See pricing',
            ],
            'showcase' => [
                'headline' => 'Everything your business needs, all in one place',
                'steps' => $showcaseSteps,
            ],
            'types' => [
                'title' => 'Powering retailers of every type',
                'subtitle' => 'From small shops to large chains — SkelApp scales with your business.',
                'cards' => $typeCards,
            ],
            'faq' => [
                'title' => 'Questions, answered',
                'subtitle' => 'Retailers FAQ',
                'read_more_label' => 'Read more',
                'items' => [
                    ['q' => 'Will SkelApp work for my type of shop?', 'a' => 'Yes — from boutiques to grocery, hardware to tech, SkelApp adapts to how you sell. Pick your business type to see how.'],
                    ['q' => 'Do I need special hardware to start?', 'a' => 'No. Start on the phone or tablet you already have, and add a register, terminal or printer whenever you’re ready.'],
                    ['q' => 'Does it work without internet?', 'a' => 'Absolutely. Keep selling during power cuts or weak network; everything syncs the moment you reconnect.'],
                    ['q' => 'What payments can I take?', 'a' => 'Cash, M-Pesa, Airtel Money, Tigo Pesa and cards — all recorded automatically and printed on one receipt.'],
                ],
            ],
            'cta' => [
                'title' => 'Find the SkelApp built for',
                'title_accent' => 'your shop',
                'link_label' => 'See pricing',
                'note' => 'Whatever you sell, there’s a SkelApp setup that fits. Start free today — add hardware whenever you’re ready.',
                'primary_label' => 'Start for free',
            ],
            'pages' => CmsCatalogs::retailerPages(),
        ];
    }

    private function integrationsContent(): array
    {
        $config = config('integrations', []);

        return [
            'meta' => [
                'title' => 'Integrations - SkelApp',
                'description' => 'Connect SkelApp with Zoho Books, Xero, QuickBooks, Sage, WhatsApp and Notify Africa BulkSMS.',
            ],
            'hero' => $config['hero'] ?? [],
            'categories' => array_map(function (array $category) {
                return $category + [
                    'slugs_text' => $this->lines($category['slugs'] ?? []),
                ];
            }, $config['categories'] ?? []),
            'strip' => [
                'label' => 'Also integrates with',
            ],
            'faq' => [
                'heading' => 'Integration questions, answered',
                'subtitle' => 'Integrations FAQ',
                'read_more_label' => 'Read more',
                'items' => $config['faq'] ?? [],
            ],
            'items' => CmsCatalogs::integrationItems(),
        ];
    }

    private function integrationContent(): array
    {
        return [
            'meta' => [
                'title_suffix' => 'integration - SkelApp',
                'description_fallback' => 'SkelApp integration details.',
            ],
            'hero' => [
                'kicker_suffix' => 'integration',
                'primary_label' => 'Talk to an expert',
                'secondary_label' => 'Visit website',
            ],
            'features' => [
                'kicker' => 'Key features',
                'title_prefix' => 'What you can expect from',
            ],
            'interest' => [
                'kicker' => 'Coming soon',
                'title_prefix' => 'Join the waitlist for',
                'copy' => 'Share your details and we’ll let you know as soon as this integration becomes available for your team.',
                'avatars' => [
                    ['src' => 'assets/client.png', 'alt' => 'SkelApp merchant profile 1', 'position' => '50% 26%'],
                    ['src' => 'assets/pix.jpeg', 'alt' => 'SkelApp merchant profile 2', 'position' => '48% 24%'],
                    ['src' => 'assets/attendants.png', 'alt' => 'SkelApp merchant profile 3', 'position' => '50% 22%'],
                    ['src' => 'assets/sto.jpeg', 'alt' => 'SkelApp merchant profile 4', 'position' => '58% 18%'],
                    ['src' => 'assets/local.jpeg', 'alt' => 'SkelApp merchant profile 5', 'position' => '72% 20%'],
                ],
            ],
            'form' => [
                'full_name_label' => 'Full name',
                'full_name_placeholder' => 'Provide your full name',
                'email_label' => 'Email',
                'email_placeholder' => 'Provide your email address',
                'submit_label' => 'Join waitlist',
                'note_prefix' => 'We’ll only use these details to contact you about',
            ],
            'faq' => [
                'title_suffix' => 'questions',
                'subtitle_suffix' => 'FAQ',
                'read_more_label' => 'Read more',
            ],
            'back_label' => 'Back to integration page',
        ];
    }

    private function affiliateContent(): array
    {
        $config = config('affiliate_program', []);

        return [
            'meta' => $config['meta'] ?? [],
            'hero' => $config['hero'] ?? [],
            'steps' => $config['steps'] ?? [],
            'referrals' => $config['referrals'] ?? [],
            'partners' => $config['partners'] ?? [],
            'cta' => $config['cta'] ?? [],
        ];
    }

    private function affiliateApplyContent(): array
    {
        $config = config('affiliate_program', []);

        return [
            'meta' => [
                'title' => 'Affiliate Application | SkelApp',
                'description' => $config['meta']['description'] ?? 'Apply to join the SkelApp affiliate program.',
            ],
            'application' => ($config['application'] ?? []) + [
                'agreement_link_label' => 'affiliate agreement',
                'terms_link_label' => 'Terms of Service',
                'privacy_link_label' => 'Privacy Policy',
            ],
            'form' => [
                'first_name_label' => 'First Name',
                'first_name_placeholder' => 'Enter your first name',
                'last_name_label' => 'Last Name',
                'last_name_placeholder' => 'Enter your last name',
                'email_label' => 'Email',
                'email_placeholder' => 'Enter your email address',
                'phone_label' => 'Phone Number',
                'phone_placeholder' => '712 345 678',
                'country_label' => 'Country',
                'country_placeholder' => 'Select country',
                'primary_method_label' => 'Primary Promotional Method',
                'primary_method_placeholder' => '-None-',
                'discovery_label' => 'How did you hear about the program?',
                'discovery_placeholder' => 'Select one',
                'captcha_label' => 'Enter the captcha',
                'captcha_placeholder' => 'Type the code shown below',
                'submit_label' => 'Submit',
                'reset_label' => 'Reset',
                'back_label' => 'Back',
            ],
        ];
    }

    private function privacyContent(): array
    {
        $sections = [
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
            ['title' => 'Data Retention', 'intro' => 'We retain personal information only for as long as reasonably necessary for the purpose it was collected, including follow-up, recordkeeping, security, and legal compliance.'],
            ['title' => 'Cookies and Analytics', 'intro' => 'Our website may use cookies or similar technologies to support site functionality, measure traffic, and improve user experience. You may manage cookie preferences through your browser settings, though some functionality may be affected.'],
            ['title' => 'Security', 'intro' => 'We use reasonable technical and organisational measures to protect the personal information we hold. However, no method of transmission over the internet or electronic storage is completely secure, and we cannot guarantee absolute security.'],
            ['title' => 'Your Choices', 'intro' => 'You may contact us to request updates or corrections to the information you have submitted to us, or to opt out of non-essential communications where applicable.'],
            ['title' => 'Updates to This Policy', 'intro' => 'We may revise this Privacy Policy from time to time. Any updates will be reflected on this page together with the effective or last-updated date.'],
            ['title' => 'Contact Us', 'intro' => 'If you have questions about this Privacy Policy or how we handle personal information, please contact us using the details provided on our Contact page.'],
        ];

        return [
            'meta' => [
                'title' => 'Privacy Policy | SkelApp',
                'description' => 'Read SkelApp\'s privacy policy and how we collect, use, and protect personal information.',
            ],
            'hero' => [
                'heading' => 'Privacy Policy',
                'last_updated' => 'Last updated: June 13, 2026',
            ],
            'intro_paragraphs' => [
                'This Privacy Policy explains how SkelApp collects, uses, stores, and protects personal information when you visit our website, request a demo, contact our team, or use related services.',
                'By accessing our website or sharing your information with us, you acknowledge the practices described in this policy. If you do not agree with this policy, please do not use the website or submit personal information through it.',
            ],
            'sections' => array_map(fn (array $section) => $section + ['bullets_text' => $this->bulletLines($section['bullets'] ?? [])], $sections),
        ];
    }

    private function whyContent(): array
    {
        return [
            'meta' => [
                'title' => 'Why SkelApp – About Us',
                'description' => 'SkelApp is on a mission to transform Tanzanian small businesses — built by people who show up, with products and support that just work.',
            ],
            'hero' => [
                'eyebrow' => 'About SkelApp',
                'title' => "Transforming Tanzania's small businesses to grow — and communities to thrive.",
                'subtitle' => 'We put powerful, affordable tools in the hands of every shop — so businesses grow stronger and the people around them thrive.',
                'image' => 'assets/HeroImage.webp',
                'primary_label' => 'Talk to us',
                'primary_url' => '/contact',
                'secondary_label' => 'Explore SkelApp',
                'secondary_url' => '/features',
            ],
            'gallery' => [
                'eyebrow' => 'Why SkelApp?',
                'title' => 'We genuinely<br><span class="af-accent">care</span>',
            ],
            'people' => [
                'title' => "People who\nshow up",
                'copy' => 'We take the time to understand what really matters to you. From the first discussion, we\'ve got your back — and we see it through until it\'s right.',
            ],
            'story' => [
                'cards' => [
                    ['num' => '01', 'title' => 'The problem', 'body' => 'It\'s too hard for small shops to track sales, stock and cash — and grow without the numbers they need.', 'image' => 'assets/techshop.webp'],
                    ['num' => '02', 'title' => 'The idea', 'body' => 'Put a simple, reliable point of sale in every shop — one that works offline and fits how Tanzania sells.', 'image' => 'assets/client.webp'],
                    ['num' => '03', 'title' => 'The outcome', 'body' => 'Thousands of businesses now run on SkelApp — selling faster, wasting less and growing with confidence.', 'image' => 'assets/HeroImage.webp'],
                ],
            ],
            'product' => [
                'image' => 'assets/PosSystemRegister.webp',
                'title' => "Products that fuel\nefficiency",
                'subtitle' => 'We build products that make your job easier and more profitable. From taking orders and payments to managing stock and staff — everything works together so you can focus on your customers.',
                'cta_label' => 'Get a demo',
                'cta_url' => '/contact',
            ],
            'support' => [
                'title' => 'Support',
                'title_accent' => 'that\'s always there',
                'subtitle' => 'When something matters, you reach a real person who knows your business — fast, local and on your side.',
                'features' => [
                    ['name' => 'Always-on help', 'body' => 'Reach us on WhatsApp, phone or email — real people who know retail, ready the moment you need them.', 'image' => 'assets/CRM-phone-feature.png'],
                    ['name' => 'Set up in minutes', 'body' => 'Hands-on onboarding and training so your whole team is selling confidently from the very first day.', 'image' => 'assets/CHECKOUTPOS.png'],
                    ['name' => 'On the ground', 'body' => 'A local team in Dar es Salaam for setup, repairs and advice — not a call centre on the other side of the world.', 'image' => 'assets/techshop.webp'],
                    ['name' => 'Warranty & care', 'body' => 'Every device is covered, and we keep you running with fast repairs and replacements when you need them.', 'image' => 'assets/hardware.webp'],
                ],
            ],
            'culture' => [
                'title' => 'A culture that',
                'title_accent' => 'shows up',
                'link_label' => 'Work with us',
                'link_url' => '/contact',
                'note' => 'We\'re builders, problem-solvers and people who care. We move fast, stay close to our customers, and take pride in work that lasts — kazi safi, kila siku.',
            ],
        ];
    }

    private function pricingContent(): array
    {
        return [
            'meta' => [
                'title' => 'Pricing – SkelApp',
                'description' => 'SkelApp pricing — clear, affordable, no hidden costs. Choose Monthly, 6-month, or 12-month plans and save more the longer you commit.',
            ],
            'hero' => [
                'image' => 'assets/HeroImage.webp',
                'trust_label' => 'Trusted by',
                'trust_count' => '10,000+',
                'trust_suffix' => 'small businesses',
                'title' => "Get more value\nfrom every payment",
                'subtitle' => 'We’ve got flexible plans to suit any growing business.',
                'primary_url' => '#pricing-plans',
                'primary_label' => 'See plans',
                'secondary_url' => '/contact',
                'secondary_label' => 'Contact sales',
            ],
            'header' => [
                'title_lines' => [
                    ['text' => 'One price.', 'accent' => false],
                    ['text' => 'Every feature.', 'accent' => false],
                    ['text' => 'No surprises.', 'accent' => true],
                ],
                'subtitle' => "SkelApp's pricing is as simple as your business should be — one flat rate, everything included, cancel anytime. No tiers. No hidden fees. No confusion.",
            ],
            'features_box' => [
                'title' => 'Features',
                'subtitle' => 'This subscription includes the following features.',
            ],
            'features' => [
                'Sales recording & receipts',
                'Purchase & expense tracking',
                'Inventory & stock alerts',
                'Profit & loss reports',
                'Split billing',
                'Staff & cashier management',
                'Customer relationship tools',
                'Mobile & tablet access',
            ],
            'plans_box' => [
                'title' => 'Pricing Plan',
                'subtitle' => 'Choose the plan that best suits your needs.',
            ],
            'plans' => [
                ['id' => 'monthly', 'label' => 'Monthly', 'note' => '', 'price' => 'TZS 15,000', 'sub' => 'billed monthly', 'is_default' => false],
                ['id' => 'sixmonth', 'label' => '6 months', 'note' => 'Save TZS 15,000', 'price' => 'TZS 75,000', 'sub' => 'TZS 12,500 / month', 'is_default' => false],
                ['id' => 'yearly', 'label' => '12 months', 'note' => 'Best value · Save TZS 30,000', 'price' => 'TZS 150,000', 'sub' => 'TZS 12,500 / month', 'is_default' => true],
            ],
            'cta' => [
                'prefix' => 'Get started with',
                'url' => null,
            ],
            'getstarted' => [
                'title_top' => 'Get Started',
                'title_bottom' => 'with the App',
                'cta_label' => 'Download Now',
                'cta_url' => '#',
                'copy' => "Using SkelApp is simple and intuitive. Open it, follow the guided steps to set up your shop, and explore every feature. Got questions? Our team has you covered.",
                'phone_image' => 'assets/Mobilehomeview.png',
            ],
        ];
    }

    private function contactContent(): array
    {
        return [
            'meta' => [
                'title' => 'Contact Us – SkelApp',
                'description' => 'Get in touch with SkelApp.',
            ],
            'hero' => [
                'heading' => "Ready to get started? We're just a message away!",
            ],
            'info' => [
                'heading' => 'Contact Us:',
                'intro' => 'To get more information about us, you can reach us using the following:',
                'social_heading' => 'Learn More about us through our social media channels:',
            ],
            'cards' => [
                ['type' => 'email', 'label' => 'Email Us', 'value' => 'pos@skelapp.tz'],
                ['type' => 'tel', 'label' => 'Talk or chat with PK', 'value' => '+255 658 962 000'],
                ['type' => 'tel', 'label' => 'Talk or chat with JP', 'value' => '+255 659 310 909'],
            ],
            'socials' => [
                ['platform' => 'tiktok', 'url' => '#'],
                ['platform' => 'facebook', 'url' => '#'],
                ['platform' => 'instagram', 'url' => '#'],
                ['platform' => 'linkedin', 'url' => '#'],
            ],
            'form' => [
                'heading' => 'Book a Demo',
                'first_name_label' => 'First Name',
                'first_name_placeholder' => 'Enter your first name',
                'last_name_label' => 'Last Name',
                'last_name_placeholder' => 'Enter your last name',
                'email_label' => 'Email',
                'email_placeholder' => 'Enter your email address',
                'phone_label' => 'Phone Number',
                'phone_placeholder' => 'Enter your phone number',
                'company_label' => 'Business Name',
                'company_placeholder' => 'Enter your business name',
                'cancel_label' => 'Cancel',
                'submit_label' => 'Submit',
                'recipient_email' => 'pos@skelapp.tz',
                'subject_prefix' => 'Demo Request',
                'success_message' => "Thank you! We've received your request and will be in touch shortly.",
            ],
        ];
    }

    private function faqContent(): array
    {
        return [
            'meta' => [
                'title' => 'FAQ – SkelApp',
                'description' => 'Frequently asked questions about SkelApp.',
            ],
            'page' => [
                'title' => 'FAQs',
                'groups' => [
                    [
                        'id' => 'getting-started',
                        'label' => 'Getting Started',
                        'questions' => [
                            ['question' => 'How quickly can I start using SkelApp in my shop?', 'answer' => 'Most retailers can get started the same day. Once your products, prices, users, and payment setup are ready, your team can begin selling immediately.'],
                            ['question' => 'Do I need a POS machine before signing up?', 'answer' => 'No. You can start with the devices you already have and then decide whether you want a dedicated SkelApp POS machine, receipt printer, or barcode scanner.'],
                            ['question' => 'Can SkelApp work for one store and multiple branches?', 'answer' => 'Yes. SkelApp is built for both single-location shops and growing retail operations. You can track each branch independently while keeping reporting in one place.'],
                            ['question' => 'Will you help me move from my current POS or manual books?', 'answer' => 'Yes. Our team can guide you through product setup, opening stock, price lists, and the best way to move your operation into SkelApp without disrupting sales.'],
                            ['question' => 'Can I keep selling when the internet is unstable?', 'answer' => 'Yes. SkelApp is designed for real store conditions and supports smooth selling workflows even when connectivity is not perfect.'],
                            ['question' => 'What training do cashiers and managers receive?', 'answer' => 'We provide practical onboarding for the people who will use the system every day, from cashiers handling sales to managers reviewing stock and reports.'],
                        ],
                    ],
                    [
                        'id' => 'inventory-catalog',
                        'label' => 'Inventory & Catalog',
                        'questions' => [
                            ['question' => 'How do I add products, variants, and selling prices?', 'answer' => 'You can create products with SKUs, categories, sizes, and price levels directly inside SkelApp, then keep them organised in a way that matches how your store operates.'],
                            ['question' => 'Can I track stock across categories and branches?', 'answer' => 'Yes. SkelApp helps you monitor stock movement by product, category, and branch so you always know what is selling and what needs replenishment.'],
                            ['question' => 'Will SkelApp warn me when stock is running low?', 'answer' => 'Yes. Low-stock visibility is built into the inventory workflow so you can restock before items go out of stock.'],
                            ['question' => 'Can I update multiple prices at once?', 'answer' => 'Yes. Bulk updates make it easier to adjust prices, margins, or product details without editing items one by one.'],
                        ],
                    ],
                    [
                        'id' => 'staff-branches',
                        'label' => 'Staff & Branches',
                        'questions' => [
                            ['question' => 'Can I create different roles for cashiers, managers, and owners?', 'answer' => 'Yes. Access can be structured around responsibilities, so each user only sees the tools and settings they need for their role.'],
                            ['question' => 'How many staff accounts can I add to my business?', 'answer' => 'That depends on your plan and setup, but SkelApp is designed to support growing teams without forcing you into awkward workarounds.'],
                            ['question' => 'Can I view sales and stock for each branch separately?', 'answer' => 'Yes. Branch-level visibility is built into the platform, which makes it easier to compare performance and manage operations across locations.'],
                        ],
                    ],
                    [
                        'id' => 'payments',
                        'label' => 'Payments',
                        'questions' => [
                            ['question' => 'Which payment methods can I record in SkelApp?', 'answer' => 'You can manage common retail payment flows such as cash, bank transfers, card payments, and mobile money, while keeping each sale clearly recorded.'],
                            ['question' => 'Does SkelApp support split payments or partial payments?', 'answer' => 'Yes. When a customer pays using more than one method or completes part of a balance first, SkelApp can help you keep that transaction accurate.'],
                            ['question' => 'Can I separate cash, bank, and mobile money totals in reports?', 'answer' => 'Yes. Payment method reporting is available so you can reconcile daily totals faster and see how customers prefer to pay.'],
                            ['question' => 'How are refunds and voids handled?', 'answer' => 'Refunds and voids are tracked inside the system so you maintain a clear audit trail and reduce confusion at the end of the day.'],
                        ],
                    ],
                    [
                        'id' => 'devices-printing',
                        'label' => 'Devices & Printing',
                        'questions' => [
                            ['question' => 'Which POS machines, printers, and scanners work with SkelApp?', 'answer' => 'SkelApp works best with our recommended hardware setup, and we can advise you on printers, barcode scanners, and POS devices that fit your store.'],
                            ['question' => 'Can I print receipts and kitchen or order tickets?', 'answer' => 'Yes. Depending on your setup, SkelApp can support receipt printing and other operational print flows needed at checkout or in-store fulfilment.'],
                            ['question' => 'Does SkelApp work on both phone and desktop?', 'answer' => 'Yes. Teams can manage the business across supported devices while keeping sales, stock, and reporting synced inside the same platform.'],
                            ['question' => 'What happens if a device is lost or damaged?', 'answer' => 'Because your data lives in your SkelApp account, you can regain access from another supported device and continue operating with minimal disruption.'],
                        ],
                    ],
                    [
                        'id' => 'privacy-security',
                        'label' => 'Privacy & Security',
                        'questions' => [
                            ['question' => 'Is my business data secure on SkelApp?', 'answer' => 'We take security seriously and use practical safeguards to help protect your account, your team access, and your business records.'],
                            ['question' => 'Can I control who sees reports or sensitive settings?', 'answer' => 'Yes. Permissions can be assigned so that only the right people have access to sensitive business information and critical controls.'],
                            ['question' => 'Does SkelApp back up my data?', 'answer' => 'Yes. Data protection and continuity are part of how the platform is managed, so your records are not tied to a single device in your shop.'],
                        ],
                    ],
                ],
            ],
            'home_preview' => [
                'heading' => 'Frequently Asked Questions',
                'subtitle' => 'How it works',
                'read_more_label' => 'Read more',
                'items' => [
                    ['question' => 'What exactly does SkelApp do?', 'answer' => 'SkelApp is a retail business management app for Tanzanian shop owners. It records your sales, tracks your purchases and expenses, monitors your stock levels, and shows you profit reports — all from your phone or tablet. It is not a payment terminal. It works alongside however you currently accept payment (cash, invoice, etc.).'],
                    ['question' => 'Does SkelApp support inventory management?', 'answer' => 'Yes, SkelApp includes comprehensive inventory management features. Track stock levels in real-time, manage product variants, set low-stock alerts, and monitor inventory across multiple locations from one dashboard.'],
                    ['question' => 'Can I use SkelApp on mobile and POS terminals?', 'answer' => 'Absolutely! SkelApp works seamlessly on mobile devices (iOS and Android), tablets, and traditional POS terminals. Your data syncs across all devices in real-time, so you can manage your business from anywhere.'],
                    ['question' => 'Is SkelApp suitable for boutiques and medium-sized shops?', 'answer' => 'Yes — SkelApp is designed specifically for medium retail businesses and boutiques in Tanzania. Whether you have one location or several branches, SkelApp gives you full visibility of your business without needing an accountant.'],
                    ['question' => 'Do I need training to use SkelApp?', 'answer' => 'No training needed. If you can use a smartphone, you can use SkelApp. Most users are fully set up and recording their first sale within 5 minutes of downloading.'],
                    ['question' => 'Is SkelApp available across Tanzania?', 'answer' => 'Yes. SkelApp works for retail businesses in Dar es Salaam, Arusha, Zanzibar, Moshi, Dodoma, Mwanza, and everywhere else in Tanzania. It works on any Android or iOS device with or without a stable internet connection.'],
                    ['question' => 'Does the POS work offline?', 'answer' => 'Yes, SkelApp can process sales offline. When your internet connection is restored, all transactions automatically sync to the cloud. This ensures you never miss a sale, even during internet outages.'],
                ],
            ],
        ];
    }

    private function termsContent(): array
    {
        return [
            'meta' => [
                'title' => 'Terms of Service | SkelApp',
                'description' => 'SkelApp terms of service.',
            ],
            'hero' => [
                'heading' => 'Terms of Service',
                'last_updated' => 'Last Updated: 28th January 2026',
            ],
            'intro_paragraphs' => [
                'These Terms of Service ("Terms") govern your access to and use of the SkelApp platform ("SkelApp" or "the Service"), including the dashboard, mobile applications, POS interfaces, APIs, hardware integrations, and related tools made available by SkelApp.',
                'By creating an account, accessing the Platform, purchasing a subscription or device, or using any part of the Service, you agree to be bound by these Terms. If you do not agree, you must not use the Service.',
            ],
            'sections' => [
                [
                    'title' => '1. Definitions',
                    'intro' => '',
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
                        ['label' => '', 'text' => 'Be legally capable of entering into contracts under Tanzanian law.'],
                        ['label' => '', 'text' => 'Provide accurate, complete, and truthful registration and business information.'],
                        ['label' => '', 'text' => 'Keep your login credentials, device access, and API keys secure and confidential.'],
                        ['label' => '', 'text' => 'Ensure all activity carried out under your account complies with these Terms and all applicable laws.'],
                        ['label' => '', 'text' => 'Promptly update your details if your business information, billing details, or authorised users change.'],
                    ],
                ],
                [
                    'title' => '3. Use of the Service',
                    'intro' => 'SkelApp grants you a limited, non-exclusive, non-transferable, and revocable right to use the Service for your internal business operations, subject to these Terms. You agree that you will not:',
                    'bullets' => [
                        ['label' => '', 'text' => 'Copy, reproduce, reverse engineer, or attempt to extract the source code of any part of the Platform except where the law expressly allows it.'],
                        ['label' => '', 'text' => 'Resell, sublicense, lease, or make the Service available to unauthorised third parties without our written approval.'],
                        ['label' => '', 'text' => 'Use the Service for unlawful, misleading, fraudulent, or abusive activity.'],
                        ['label' => '', 'text' => 'Interfere with the normal operation, security, or performance of the Platform or related infrastructure.'],
                    ],
                ],
                [
                    'title' => '4. Fees, Billing & Taxes',
                    'intro' => 'Some parts of the Service are paid. By purchasing a subscription, plan, hardware, or add-on, you agree that:',
                    'bullets' => [
                        ['label' => '', 'text' => 'All fees are payable in the currency and billing cycle communicated to you at the time of purchase or in your quotation, invoice, or agreement.'],
                        ['label' => '', 'text' => 'Recurring subscriptions may renew automatically unless cancelled before the next billing period.'],
                        ['label' => '', 'text' => 'You are responsible for applicable taxes, duties, levies, or regulatory charges unless we explicitly state otherwise.'],
                        ['label' => '', 'text' => 'Late, failed, or disputed payments may result in restricted access, suspension, or termination of some or all of the Service.'],
                        ['label' => '', 'text' => 'Third-party payment gateway fees, mobile money fees, banking fees, or connectivity charges may apply separately.'],
                    ],
                ],
                [
                    'title' => '5. Data, Privacy & Security',
                    'intro' => 'Your business data remains your responsibility. By using the Service, you acknowledge that:',
                    'bullets' => [
                        ['label' => '', 'text' => 'You retain ownership of the data, records, and content you submit to the Platform.'],
                        ['label' => '', 'text' => 'You authorise SkelApp to process and store that data as necessary to provide, maintain, support, secure, and improve the Service.'],
                        ['label' => '', 'text' => 'You are responsible for ensuring you have a lawful basis to collect, upload, and process customer, employee, or transaction data through the Platform.'],
                        ['label' => '', 'text' => 'We use reasonable administrative, technical, and organisational safeguards, but no system can be guaranteed to be completely uninterrupted or perfectly secure.'],
                        ['label' => '', 'text' => 'You should keep your own backups and operational safeguards where your business requires them.'],
                    ],
                ],
                [
                    'title' => '6. Acceptable Use',
                    'intro' => 'You must use the Service responsibly. You may not:',
                    'bullets' => [
                        ['label' => '', 'text' => 'Upload, transmit, or store unlawful, harmful, defamatory, infringing, or fraudulent content.'],
                        ['label' => '', 'text' => 'Attempt to gain unauthorised access to other accounts, devices, networks, or backend systems.'],
                        ['label' => '', 'text' => 'Use the Platform to send spam, distribute malware, or interfere with other users.'],
                        ['label' => '', 'text' => 'Reuse, repackage, or redistribute SkelApp content, branding, or documentation without authorisation.'],
                    ],
                ],
                [
                    'title' => '7. Intellectual Property',
                    'intro' => 'SkelApp, its brand, software, design, documentation, code, and all related intellectual property remain the property of SkelApp and its licensors. Nothing in these Terms transfers ownership or grants you rights beyond the limited usage rights described above.',
                    'bullets' => [],
                ],
                [
                    'title' => '8. Suspension & Termination',
                    'intro' => 'We may suspend or terminate your access to the Service at any time if:',
                    'bullets' => [
                        ['label' => '', 'text' => 'You breach these Terms or any related policies.'],
                        ['label' => '', 'text' => 'Required by law, regulation, or a competent authority.'],
                        ['label' => '', 'text' => 'There is suspected fraud, misuse, or risk to other users or to our infrastructure.'],
                        ['label' => '', 'text' => 'Required for maintenance, security, or operational reasons.'],
                    ],
                ],
                [
                    'title' => '9. Disclaimers & Limitation of Liability',
                    'intro' => 'The Service is provided "as is" and "as available." To the maximum extent permitted by law:',
                    'bullets' => [
                        ['label' => '', 'text' => 'We disclaim all implied warranties, including merchantability, fitness for a particular purpose, and non-infringement.'],
                        ['label' => '', 'text' => 'We are not liable for indirect, incidental, special, consequential, or punitive damages, including lost profits, lost data, or business interruption.'],
                        ['label' => '', 'text' => 'Our total cumulative liability shall not exceed the amount you paid us for the Service in the twelve (12) months preceding the event giving rise to the claim.'],
                    ],
                ],
                [
                    'title' => '10. Governing Law, Changes & Contact',
                    'intro' => 'These Terms are governed by the laws of the United Republic of Tanzania.',
                    'bullets' => [
                        ['label' => '', 'text' => 'Material updates will take effect when posted on this page unless a different effective date is stated.'],
                        ['label' => '', 'text' => 'Your continued use of the Service after updated Terms take effect means you accept the revised Terms.'],
                        ['label' => '', 'text' => 'If you have questions about these Terms, contact us at pos@skelapp.tz, call +255 658 962 000, or visit us in Dar Es Salaam, Tanzania.'],
                    ],
                ],
            ],
        ];
    }
}
