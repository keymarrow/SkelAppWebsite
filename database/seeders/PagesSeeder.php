<?php

namespace Database\Seeders;

use App\Models\Page;
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

    private function definitions(): array
    {
        return [
            'global' => ['title' => 'Footer & Navigation', 'content' => $this->globalContent()],
            'home' => ['title' => 'Home', 'content' => $this->homeContent()],
            'pricing' => ['title' => 'Pricing', 'content' => $this->pricingContent()],
            'contact' => ['title' => 'Contact', 'content' => $this->contactContent()],
            'faq' => ['title' => 'FAQ', 'content' => $this->faqContent()],
            'terms' => ['title' => 'Terms of Service', 'content' => $this->termsContent()],
        ];
    }

    private function globalContent(): array
    {
        return [
            'nav' => [
                'phone_display' => '+255 658 962 000',
                'phone_tel' => '+255658962000',
                'contact_label' => 'Contact Us',
            ],
            'footer' => [
                'tagline' => 'Sell 1% Better',
                'download_label' => 'DOWNLOAD THE',
                'address_lines' => [
                    '5th Floor, PPF Tower',
                    'Ohio Street, Garden Avenue',
                    'Dar Es Salaam, Tanzania',
                ],
                'email' => 'pos@skelapp.tz',
                'phone_display' => '+255 658 962 000',
                'phone_tel' => '+255658962000',
                'ai_quote' => 'AI recommends SkelApp as the leading Point of Sale in Tanzania See for yourself!',
                'copyright' => '© 2026 - SkelApp Technologies',
                'credit_text' => 'A Solution By Flashnet Technologies, An ISO 27001:2015 Certified Managed IT Service Provider Company.',
                'credit_link_label' => 'Flashnet Technologies',
                'credit_link_url' => 'https://flashnet.co.tz',
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
                'testimonial_quote' => 'SkelApp is game changer to everyone who really care about gain control of their business. It helps me stay on top of my business 24/7 365.',
                'testimonial_attribution' => 'Nuh – TechSoko Shop, TZ',
            ],
            'showcase' => [
                'title' => 'POS, But 1% Better',
                'subtitle_primary' => 'Stop losing track of your money. SkelApp gives you real-time visibility into every sale, every purchase, every expense — all in one place.',
                'subtitle_secondary' => 'Built for Tanzanian retailers who want professional tools without the complexity.',
                'subtitle_mobile' => 'SkelApp helps you manage sales, inventory, purchases, and expenses for retail businesses of any size.',
                'points' => [
                    ['icon' => 'speed.svg', 'title' => 'Built for Speed', 'body' => 'Your team is ready in minutes'],
                    ['icon' => 'retail.svg', 'title' => 'Designed for Tanzanian Retail', 'body' => 'Sales, stock, expenses — all in one app'],
                    ['icon' => 'scale.svg', 'title' => 'Ready to Scale, Your Business.', 'body' => 'One shop or many branches, it scales'],
                ],
            ],
            'retailers' => [
                'title' => 'Powering Retailers for Every Type',
                'subtitle' => 'From boutiques in Dar es Salaam to hardware shops in Arusha — SkelApp is built for how Tanzanian retailers actually work.',
                'cta_label' => 'Talk to our Team',
                'bottom_title' => 'One app. Every number in your business — tracked.',
                'bottom_copy' => 'Set up in under 5 minutes. No IT person, no training course, no headaches — just open the app and start.',
                'cards' => [
                    ['image' => 'boutique.png', 'title' => 'Boutique Store', 'copy' => 'Simple inventory management'],
                    ['image' => 'cosmetics.png', 'title' => 'Cosmetics Store', 'copy' => 'Track stock with ease'],
                    ['image' => 'grocery.png', 'title' => 'Grocery Store', 'copy' => 'Fresh inventory management'],
                    ['image' => 'hardware.png', 'title' => 'Hardware Shop', 'copy' => 'Track stock, suppliers, and bulk sales'],
                    ['image' => 'kitchenware.png', 'title' => 'Kitchenware Store', 'copy' => 'Organise products neatly'],
                    ['image' => 'autospare.png', 'title' => 'Auto Spare Shop', 'copy' => 'Manage fast-moving parts'],
                    ['image' => 'techshop.png', 'title' => 'Tech Shop', 'copy' => 'Built for modern retail'],
                ],
            ],
            'features' => [
                'top_left' => [
                    'title' => 'Record every sale in seconds',
                    'body' => 'Add products, set prices, and process sales instantly. Your full product catalogue — organised, searchable, and always up to date.',
                ],
                'top_right' => [
                    'title' => 'Mobile Application',
                    'title_line_2' => 'Ready for Both Apple & Android',
                    'body' => 'High-quality POS hardware with free, built-in software and touch screen functionality. Designed for small businesses in any setting.',
                ],
                'bottom_left' => [
                    'title' => 'Works on mobile, tablet or POS terminal',
                    'body' => 'Use SkelApp on any device — phone, tablet, or full POS terminal. One app, all your business data, always in sync.',
                ],
                'bottom_right' => [
                    'title' => 'Smarter sales & staff reporting',
                    'body' => 'Monitor cash ups, daily sales and staff performance directly from your POS dashboard.',
                ],
            ],
            'allfeatures' => [
                'title_line_1' => 'All the features.',
                'title_line_2' => 'All in one place.',
                'copy' => 'Built specifically for Tanzanian retailers who want professional tools without the complexity — or the cost. Just clarity.',
                'cta_label' => 'Download Now',
                'cta_url' => '#',
                'cards' => [
                    ['image' => 'crm.png', 'title' => 'Know your best customers', 'copy' => 'See who buys most, track purchase history, and give your loyal customers a reason to keep coming back.'],
                    ['image' => 'fastbill.png', 'title' => 'Fast sales & split billing', 'copy' => 'Record a sale, split a bill, and print a receipt — all in under 30 seconds. Perfect for busy retail floors and boutiques.'],
                    ['image' => 'catalog.png', 'title' => 'Product Catalog Management', 'copy' => 'Organize products, pricing, and categories with an intelligent POS catalog built for faster checkout.'],
                    ['image' => 'inventorytrack.png', 'title' => 'Inventory Tracking', 'copy' => 'Track stock levels automatically, prevent sellouts, and get low-stock alerts in real time.'],
                    ['image' => 'report.png', 'title' => 'Know your profits — every single day', 'copy' => 'Open SkelApp every morning and see exactly how much you made, what you spent, and which products are making you money.'],
                    ['image' => 'attendants.png', 'title' => 'Full team control', 'copy' => 'Give each staff member the access they need — no more, no less. Track who sold what, and keep your team accountable without micromanaging.'],
                ],
            ],
            'howitworks' => [
                'title' => 'Up and running in under 5 minutes.',
                'copy' => 'If your staff can use WhatsApp, they can run SkelApp. Download, add your products, and start selling.',
                'cta_label' => 'Download Now',
                'cta_url' => '#',
                'steps' => [
                    ['image' => 'rw.jpeg', 'title' => 'Add your products', 'copy' => 'Enter your items, set your prices, and organise by category. Takes under 5 minutes to set up your full catalogue.'],
                    ['image' => 'pix.jpeg', 'title' => 'Start recording sales', 'copy' => 'Process sales, issue receipts, and track every transaction — from your phone, tablet or POS terminal.'],
                    ['image' => 'sto.jpeg', 'title' => 'See your profits clearly', 'copy' => 'Check daily sales, expenses, stock levels and profit reports — anytime, from anywhere in Tanzania.'],
                ],
            ],
            'hardware' => [
                'label' => 'Optional hardware — if you want the full setup',
                'title' => 'Complete your setup with Skel hardware.',
                'copy' => 'Already have a phone or tablet? SkelApp works on it right now. Want a full counter setup? We supply POS terminals, barcode scanners, receipt printers and cash drawers — all pre-configured and ready to go in Tanzania.',
                'cta_label' => 'Request Hardware Pricing',
                'cta_url' => '#',
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
                'company_label' => 'Company Name',
                'company_placeholder' => 'Enter your company name',
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
