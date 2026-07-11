<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->updatePage('home', $this->homeSource());
        $this->updatePage('faq', $this->faqSource());
    }

    public function down(): void
    {
        // Intentionally left blank: this migration rewrites CMS copy in-place.
    }

    private function updatePage(string $slug, array $source): void
    {
        $page = DB::table('pages')->where('slug', $slug)->first();

        if (! $page) {
            return;
        }

        $draft = $this->decode($page->draft_content) ?: $this->decode($page->published_content);
        $published = $this->decode($page->published_content);

        DB::table('pages')
            ->where('id', $page->id)
            ->update([
                'draft_content' => $this->encode($this->merge($draft, $source)),
                'published_content' => $this->encode($this->merge($published, $source)),
                'published_at' => $page->published_at ?: now(),
                'updated_at' => now(),
            ]);

        Cache::forget("page:{$slug}");
    }

    private function decode(?string $json): array
    {
        if (! is_string($json) || trim($json) === '') {
            return [];
        }

        $decoded = json_decode($json, true);

        return is_array($decoded) ? $decoded : [];
    }

    private function encode(array $content): string
    {
        return json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function merge(mixed $current, mixed $source): mixed
    {
        if (is_array($source)) {
            if (array_is_list($source)) {
                $result = [];

                foreach ($source as $index => $item) {
                    $existing = is_array($current) && array_key_exists($index, $current) ? $current[$index] : null;
                    $result[$index] = $this->merge($existing, $item);
                }

                return $result;
            }

            $result = is_array($current) && ! array_is_list($current) ? $current : [];

            foreach ($source as $key => $value) {
                $existing = $result[$key] ?? null;
                $result[$key] = $this->merge($existing, $value);
            }

            return $result;
        }

        if (is_array($current) && array_key_exists('text', $current)) {
            $current['text'] = $source;

            return $current;
        }

        return $source;
    }

    private function homeSource(): array
    {
        return [
            'meta' => [
                'title' => 'SkelApp — POS System & Inventory Management for Small Businesses in Tanzania',
                'description' => 'SkelApp is the point of sale and inventory management app for small businesses in Tanzania — every sale, expense, and stock item tracked in real time.',
            ],
            'hero' => [
                'title' => 'Run your business <br>like you mean it.',
                'subtitle' => 'SkelApp is Tanzania\'s simplest way to run a shop — every sale, every expense, every debt tracked from your phone. Nothing missing, nothing guessed.',
                'cta_label' => 'Start for free',
                'cta_url' => '#',
                'cta2_label' => 'See it work',
                'cta2_url' => '/contact',
                'background_image_desktop' => 'assets/HeroImage.webp',
                'background_image_mobile' => 'assets/HeroImage.jpg',
                'testimonial_quote' => 'Anyone can record a sale. SkelApp shows your real profit.',
                'testimonial_attribution' => 'Profit, debts, and every payment — one screen, no guesswork.',
                'testimonial_stars_image' => 'assets/Stars.svg',
            ],
            'showcase' => [
                'title' => 'POS System, But Actually Simple',
                'subtitle_primary' => 'SkelApp is the point of sale and inventory management app for small businesses in Tanzania — every sale, expense, and stock item in real time.',
                'subtitle_secondary' => 'No accountant needed. No complexity. Honest tools for real shops.',
                'subtitle_mobile' => 'SkelApp is the point of sale and inventory management app for small businesses in Tanzania — every sale, expense, and stock item in real time. No accountant needed. No complexity. Honest tools for real shops.',
                'device_image_desktop' => 'assets/devicemockup.webp',
                'device_image_mobile' => 'assets/Mobilehomeview.png',
                'points' => [
                    ['icon' => 'assets/speed.svg', 'title' => 'Ready in Minutes', 'body' => 'Set up and selling — no training day.'],
                    ['icon' => 'assets/retail.svg', 'title' => 'Made for Tanzanian Retail', 'body' => 'Sales, stock, expenses — in shillings.'],
                    ['icon' => 'assets/scale.svg', 'title' => 'Grows With You', 'body' => 'One duka today, five branches tomorrow.'],
                ],
            ],
            'affordable' => [
                'eyebrow' => 'Profit, debts, and every payment — one screen, no guesswork.',
                'title' => 'Anyone can record a sale. SkelApp shows your real profit.',
                'subtitle' => 'Profit, debts, and every payment — one screen, no guesswork.',
                'cards' => [
                    [
                        'variant' => 'light',
                        'title' => 'Know your profit, not just your sales.',
                        'copy' => 'SkelApp does the profit math live — so busy days become paid days.',
                        'link_label' => 'See pricing',
                        'link_url' => '/pricing',
                        'image' => 'assets/poswithtab.webp',
                        'overlay_big' => '',
                        'overlay_small' => '',
                    ],
                    [
                        'variant' => 'photo',
                        'title' => 'Get your debts out of the notebook.',
                        'copy' => 'Who owes you, how much, since when — tracked, not scribbled and forgotten.',
                        'link_label' => 'Learn more',
                        'link_url' => '/features',
                        'image' => 'assets/attendants.webp',
                        'price_label' => '',
                        'price' => '',
                        'badge' => '',
                    ],
                    [
                        'variant' => 'tint',
                        'title' => 'However they pay, it counts.',
                        'copy' => 'Cash, mobile money, or bank transfer — every payment lands in one record.',
                        'link_label' => 'Learn more',
                        'link_url' => '/features',
                        'image' => 'assets/Mobilehomeview.png',
                    ],
                ],
            ],
            'products' => [
                'eyebrow' => 'Hardware',
                'title' => 'One system. Two ways to run it.',
                'subtitle' => 'At the counter or on the move — your shop\'s numbers stay with you.',
                'cards' => [
                    [
                        'eyebrow' => 'SkelApp Counter',
                        'title' => 'Your shop\'s command center.',
                        'body' => 'Sales, purchases, stock, and debts — the full picture from one screen at the counter.',
                        'link_label' => 'See how it works',
                        'link_url' => '#',
                        'image' => 'assets/Moc-lap-phone-02.webp',
                    ],
                    [
                        'eyebrow' => 'SkelApp Terminal',
                        'title' => 'The whole counter, in your pocket.',
                        'body' => 'Record sales, check stock, and chase debts from your phone — at the market, at the supplier, anywhere.',
                        'link_label' => 'See how it works',
                        'link_url' => '#',
                        'image' => 'assets/Mobilehomeview.png',
                    ],
                ],
            ],
            'retailers' => [
                'title' => 'Built for Every Kind of Shop',
                'subtitle' => 'From boutiques in Dar es Salaam to hardware shops in Arusha — SkelApp is built for how Tanzanian retailers actually work.',
                'cta_label' => 'Talk to our Team',
                'bottom_title' => 'One app. Every number in your business — tracked.',
                'bottom_copy' => 'Set up in under 5 minutes. No IT person, no training course, no headaches — just open the app and start.',
                'cards' => [
                    ['image' => 'assets/boutique.webp', 'category' => 'Fashion', 'title' => 'Boutique', 'copy' => 'Simple inventory management', 'link_url' => '#'],
                    ['image' => 'assets/cosmetics.webp', 'category' => 'Beauty', 'title' => 'Cosmetics', 'copy' => 'Track stock with ease', 'link_url' => '#'],
                    ['image' => 'assets/grocery.webp', 'category' => 'Food', 'title' => 'Grocery', 'copy' => 'Fresh inventory management', 'link_url' => '#'],
                    ['image' => 'assets/hardware.webp', 'category' => 'Hardware', 'title' => 'Hardware', 'copy' => 'Track stock, suppliers, and bulk sales', 'link_url' => '#'],
                    ['image' => 'assets/kitchenware.webp', 'category' => 'Homeware', 'title' => 'Kitchenware', 'copy' => 'Organise products neatly', 'link_url' => '#'],
                    ['image' => 'assets/autospare.webp', 'category' => 'Automotive', 'title' => 'Auto Spare Shop', 'copy' => 'Manage fast-moving parts', 'link_url' => '#'],
                    ['image' => 'assets/techshop.webp', 'category' => 'Electronics', 'title' => 'Tech Shop', 'copy' => 'Built for modern retail', 'link_url' => '#'],
                ],
            ],
            'features' => [
                'top_left' => [
                    'title' => 'Record every sale in seconds',
                    'body' => 'Your full catalogue — searchable, priced, always current. Tap the product, take the payment, done.',
                    'image' => 'assets/Moc-tab.webp',
                ],
                'top_right' => [
                    'title' => 'Any phone. Any tablet.',
                    'title_line_2' => 'No hardware bill.',
                    'body' => 'iPhone or Android — SkelApp runs on the device already in your pocket. Add a printer or terminal only when you want one.',
                    'image' => 'assets/PosSystem.webp',
                ],
                'bottom_left' => [
                    'title' => 'Your whole team, always in sync.',
                    'body' => 'Every attendant\'s sales land in the same record, live. You see everything, from anywhere.',
                    'image' => 'assets/poswithtab.webp',
                ],
                'bottom_right' => [
                    'title' => 'Know where you stand, every morning.',
                    'body' => 'Yesterday\'s sales, today\'s stock, this month\'s profit — before you\'ve had your chai.',
                    'image' => 'assets/Moc-lap-phone-02.webp',
                ],
            ],
            'allfeatures' => [
                'title_line_1' => 'Run Your Shop',
                'title_line_2' => 'the Way It Actually Works',
                'copy' => 'Five jobs every shop owner does daily. One app that does them all in shillings, not spreadsheets.',
                'cta_label' => 'Download Now',
                'cta_url' => '/features#features-detail',
                'feature_image' => 'assets/techshop.webp',
                'feature_label' => 'Point of sale',
                'feature_desc' => 'Sales, stock, and daily reports from one screen. Open the app, you\'re at work.',
                'feature_link_url' => '/features#point-of-sale',
                'tagline' => 'Everything above, in one app. Nothing above, in a notebook.',
                'cards' => [
                    ['image' => 'assets/crm.webp', 'label' => 'Customers', 'title' => 'See who buys most, who\'s gone quiet, and who\'s worth a call.', 'copy' => 'See who buys most, who\'s gone quiet, and who\'s worth a call.', 'link_url' => '/features#customers-and-loyalty'],
                    ['image' => 'assets/fastbill.webp', 'label' => 'Fast checkout', 'title' => 'Record a sale and print a receipt in under 30 seconds.', 'copy' => 'Record a sale and print a receipt in under 30 seconds.', 'link_url' => '/features#point-of-sale'],
                    ['image' => 'assets/catalog.webp', 'label' => 'Catalog', 'title' => 'Organise products, pricing and categories for faster checkout.', 'copy' => 'Organise products, pricing and categories for faster checkout.', 'link_url' => '/features#catalog-and-products'],
                    ['image' => 'assets/inventorytrack.webp', 'label' => 'Inventory', 'title' => 'Low-stock alerts before you run out, not after the customer walks out.', 'copy' => 'Low-stock alerts before you run out, not after the customer walks out.', 'link_url' => '/features#inventory-and-stock'],
                    ['image' => 'assets/report.webp', 'label' => 'Profits', 'title' => 'What you made, what you spent, which products carry the shop.', 'copy' => 'What you made, what you spent, which products carry the shop.', 'link_url' => '/features#reports-and-profits'],
                    ['image' => 'assets/attendants.webp', 'label' => 'Team control', 'title' => 'Give each staff member the right access and track who sold what.', 'copy' => 'Give each staff member the right access and track who sold what.', 'link_url' => '/features#staff-and-branches'],
                ],
            ],
            'howitworks' => [
                'title' => 'If they can WhatsApp, they can SkelApp.',
                'copy' => 'Download, add your products, start selling — you\'re running today, not next week.',
                'cta_label' => 'Download Now',
                'cta_url' => '/features#features-detail',
                'steps' => [
                    ['image' => 'assets/rw.jpeg', 'title' => 'Add your products', 'copy' => 'Enter your items, set your prices. A typical shop\'s catalogue is done before the kettle boils.'],
                    ['image' => 'assets/pix.jpeg', 'title' => 'Start selling', 'copy' => 'Tap the product, take the payment, receipt done. Every sale lands in your records automatically.'],
                    ['image' => 'assets/sto.jpeg', 'title' => 'Watch the numbers', 'copy' => 'Sales, stock, and profit update themselves. Open the app tomorrow and you\'ll know exactly where you stand.'],
                ],
            ],
            'hardware' => [
                'label' => 'Multi-location tools',
                'title' => 'Ready for the counter? Meet the Skel Register.',
                'copy' => 'A full point-of-sale station — screen, printer, cash drawer — running the same SkelApp your phone does. Upgrade when the queue gets longer.',
                'cta_label' => 'See the Skel Register',
                'cta_url' => '/hardware/skel-register',
                'image' => 'assets/PosSystemRegister.webp',
            ],
            'pricing_summary' => [
                'intro' => 'Untracked stock costs millions a year. SkelApp? Jero kwa siku.',
                'title_line_1' => 'One price.',
                'title_line_2' => 'Everything included.',
                'description' => 'Point of sale, inventory, debt tracking, reports — the whole app, every feature, one subscription. No tiers, no locked features, no surprises.',
                'features' => [
                    'Products, pricing, purchases, and restocking — one app',
                    'Every branch and every staff account included',
                    'Daily reports: sales, expenses, profit',
                    'Customer and supplier debts, tracked automatically',
                ],
                'currency' => 'TZS',
                'price_main' => '150,000/year',
                'price_period' => '— two months free · or TZS 15,000 month-to-month. Cancel anytime.',
                'payment_label' => 'Flexible payment options',
                'payment_methods_image' => 'assets/paymentmethod.png',
                'card_image' => 'assets/card.webp',
                'thumbnails' => [
                    ['image' => 'assets/card.webp', 'alt' => 'SkelApp Subscription Card'],
                    ['image' => 'assets/Moc-tab.webp', 'alt' => 'Products'],
                    ['image' => 'assets/Pos System 04.png', 'alt' => 'Mobile'],
                    ['image' => 'assets/Moc-tab-02.webp', 'alt' => 'Reports'],
                ],
                'cta_label' => 'Start for free',
                'cta_url' => '/contact',
                'benefits' => [
                    'Cancel anytime',
                    'Works on mobile & tablet',
                    'Setup in minutes',
                ],
                'benefit_mobile_2' => 'Works on mobile & tablet',
            ],
            'image_cta' => [
                'heading' => 'Simple tools. Real people. Businesses that grow.',
                'cta_label' => 'Download SkelApp Today',
                'cta_url' => '#',
                'background_image' => 'assets/client.webp',
            ],
        ];
    }

    private function faqSource(): array
    {
        return [
            'home_preview' => [
                'heading' => 'Frequently Asked Questions',
                'subtitle' => 'How it works',
                'read_more_label' => 'Read more',
                'items' => [
                    ['question' => 'What exactly does SkelApp do?', 'answer' => 'SkelApp is a retail business management app for Tanzanian shop owners. It records your sales, purchases, and expenses, tracks who owes you and who you owe, monitors your stock levels, and shows you profit reports — all from your phone or tablet. SkelApp does not process payments itself; it works alongside however your customers already pay you — cash, mobile money, bank transfer, or invoice — and simply keeps an accurate record of it.'],
                    ['question' => 'Does SkelApp support inventory management?', 'answer' => 'Yes, SkelApp includes advanced inventory management. Track stock levels in real-time, manage product variants, set low-stock alerts, and monitor inventory across multiple locations from one dashboard.'],
                    ['question' => 'Can I use SkelApp on mobile and POS terminals?', 'answer' => 'Absolutely! SkelApp works seamlessly on mobile devices (iOS and Android) and tablets. Your data syncs across all devices in real-time, so you can manage your business from anywhere.'],
                    ['question' => 'Is SkelApp suitable for boutiques and medium-sized shops?', 'answer' => 'Yes — SkelApp is designed specifically for small and medium retail businesses and boutiques in Tanzania. Whether you have one location or several branches, SkelApp gives you full visibility of your business without needing an accountant.'],
                    ['question' => 'Do I need training to use SkelApp?', 'answer' => 'No training needed. If you can use a smartphone, you can use SkelApp. Most users are fully set up and recording their first sale within 5 minutes of downloading.'],
                    ['question' => 'Is SkelApp available across Tanzania?', 'answer' => 'Yes. SkelApp works for retail businesses in Dar es Salaam, Arusha, Zanzibar, Moshi, Dodoma, Mwanza, and everywhere else in Tanzania. It works on any Android or iOS device with or without a stable internet connection.'],
                    ['question' => 'Does the POS work offline?', 'answer' => 'Yes, SkelApp can record sales offline. When your internet connection is restored, all transactions automatically sync to the cloud. This ensures you never miss a sale, even during internet outages.'],
                ],
            ],
        ];
    }
};
