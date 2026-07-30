<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Legacy News Articles
    |--------------------------------------------------------------------------
    |
    | Source of truth for the legacy news import (App\Services\LegacyNewsImporter).
    | The importer matches on `slug`, so if a slug changes here it must also be
    | changed on the corresponding news_posts row, otherwise the import creates a
    | duplicate post instead of updating the existing one.
    |
    */

    'articles' => [

        [
            'slug' => 'small-business-pos-tanzania',
            'title' => 'Small Business POS in Tanzania',
            'summary' => 'A practical guide to choosing a small business POS in Tanzania: what it should do, what it costs, and how to start selling on a free plan without buying hardware first.',
            'date' => '2026-05-01',
            'read_time' => '5:30',
            'categories' => ['Product', 'Retail Guide'],
            'card_label' => 'Small Business',
            'card_colors' => ['#eaf7ff', '#7fc8ff', '#6e82ff'],
            'sections' => [
                [
                    'heading' => null,
                    'paragraphs' => [
                        'Most small shops in Tanzania start the same way: a notebook, a calculator, and a very good memory. It works, right up until it does not. A page goes missing. Two people write in the same column. Stock leaves the shelf and nobody records it. By the end of the month you know roughly what came in, but you could not say with confidence which products actually made you money, or which customer still owes you for goods taken on credit in the second week.',
                        'A point of sale system replaces that notebook with something that remembers on your behalf. This guide covers what a small business POS in Tanzania should actually do, what it reasonably costs in 2026, and how to start without spending anything up front or buying a single piece of equipment.',
                    ],
                ],
                [
                    'heading' => 'What a small business POS actually does',
                    'paragraphs' => [
                        'It helps to be precise, because the term gets used loosely. A point of sale is not simply a digital till that adds up numbers. Its real job is to capture every transaction in enough detail that the rest of your business can be reconstructed from it later.',
                        'When you record a sale, three things should happen at once. The sale itself is logged with the products, quantities, price and payment method. The stock level for each item drops automatically, so what the system says you have is what is actually on the shelf. And the transaction attaches itself to a customer record where one applies, so history builds up over time instead of evaporating.',
                        'That third part is what separates a POS from a calculator. After six months, the accumulated history is what tells you which lines sell fastest, which sit dead on the shelf tying up cash, which customers come back, and where your margin is quietly leaking. None of that is available if the only record is a handwritten total.',
                        '![SkelApp point of sale dashboard showing sales and stock](/storage/news/body/20260517073905-yFsKREzlHtc12gUttlE8.png)',
                    ],
                ],
                [
                    'heading' => 'Why cost has been the real barrier',
                    'paragraphs' => [
                        'The technology has existed for decades. The reason so many Tanzanian shops still run on paper is that traditional POS software was priced for supermarkets, not for a duka or a boutique with two staff. Licences were sold per terminal, contracts ran annually, and installation was a separate line item that often cost more than the software itself.',
                        'For a shop turning over modest daily figures, none of that arithmetic worked. Paying a large sum up front to find out whether a system suited you was a risk most owners sensibly declined to take.',
                        'Three things changed that. Software moved to the cloud, so there is nothing to install and no server to maintain. The phone already in your pocket became a capable terminal, removing the hardware requirement. And pricing shifted to monthly subscriptions with genuine free tiers, so you can test a system with real transactions before committing money to it.',
                    ],
                ],
                [
                    'heading' => 'What you get without paying',
                    'paragraphs' => [
                        'SkelApp starts on a free plan called WingaMode, intended for a business run by a single owner. It covers one user and allows ten POS transactions a month, ten online store orders and ten purchase orders or bills.',
                        'The transaction cap is the limit, not the feature set. Inventory management, customer management, sales management, purchase management, and real time reports and dashboards are all included. That matters, because it means the free plan is a genuine trial of how the system works rather than a stripped shell designed to frustrate you into upgrading.',
                        'Ten transactions a month will not run a busy shop, and it is not meant to. It is enough to load your product catalogue, process real sales for a few days, see how the reports read, and decide whether the workflow fits how you actually trade before any money changes hands.',
                    ],
                ],
                [
                    'heading' => 'When it makes sense to move up',
                    'paragraphs' => [
                        'The next tier, BiasharaPlus, costs TZS 15,000 per month billed annually, which works out at a saving of roughly 17 percent against paying month to month. It lifts the ceiling in the places a growing shop hits first.',
                        'You get three users instead of one, which covers an owner plus two attendants. POS transactions become unlimited. Online store orders and purchase orders each rise to 500 a month. Serial and batch tracking arrives, which matters if you sell anything with an expiry date or a warranty. Multi location management appears, so a second branch does not mean a second system.',
                        'The plan also adds customisable and scheduled reports, custom views and custom fields, automation such as email alerts and field updates, price management, and 24 hour support. For most shops the trigger to upgrade is simply the transaction limit, and everything else arrives as a bonus alongside it.',
                        'Businesses running several branches can move to a custom Build Your Own plan, which removes the user and location caps entirely and adds custom integrations, data migration, dedicated onboarding and staff training, and priority support.',
                    ],
                ],
                [
                    'heading' => 'The features that matter most in a small shop',
                    'paragraphs' => [
                        'Feature lists are easy to pad, so it is worth naming the handful that genuinely change day to day work in a small Tanzanian retail business.',
                        'Inventory control with batch and expiry tracking, stock adjustments, returns and low stock alerts is the first. It is the difference between discovering a shortage during a stock count and being warned before you run out of your best selling line. Transfers and transfer orders extend the same control across branches once you have more than one.',
                        'Customer and supplier records carrying outstanding balances is the second. Selling on credit is normal practice here, and the moment those balances live in a system rather than in somebody memory, chasing them stops being awkward guesswork.',
                        'Flexible payment recording is the third. A single sale in Tanzania is frequently settled part in cash and part by mobile money, and a POS that cannot represent that cleanly will quietly corrupt your books. Payment account tracking then shows you what actually landed where.',
                    ],
                ],
                [
                    'heading' => 'You do not need to buy hardware first',
                    'paragraphs' => [
                        'This is the point that surprises owners most often. SkelApp runs on iOS, Android and the web, which means the phone you already own is a working terminal on day one. No counter machine, no cabling, no installation visit.',
                        'Purpose built hardware exists when you want it, and there is a reasonable case for it once volume grows. Skel Register, Skel Terminal, Skel Tab and Skel Phone cover different counter styles, and accessories such as barcode scanners, cash drawers, tablet stands, charging docks and receipt paper fill in the rest. All of it is designed to be plug and play and offline ready, with local support behind it.',
                        'The important part is the sequencing. Prove the software suits your shop first, using equipment you already have. Buy hardware afterwards, when you know exactly which parts of the counter it needs to serve.',
                    ],
                ],
                [
                    'heading' => 'Is your shop ready for one?',
                    'paragraphs' => [
                        'A few signs tend to appear together. You are no longer certain what stock you hold without physically counting it. You cannot say which products drive your profit rather than merely your turnover. Credit balances are tracked informally and occasionally forgotten. You have staff selling when you are not present, and you would like to know what happened while you were away.',
                        'If two or more of those describe your situation, the notebook has reached its limit. Setup and staff training are included, support runs around the clock, and the free plan means the only real cost of finding out whether it works for you is an afternoon of your time.',
                    ],
                ],
            ],
        ],

        [
            'slug' => 'best-pos-system-tanzania',
            'title' => 'Best POS System in Tanzania | SkelApp',
            'summary' => 'There is no single best POS system in Tanzania, only the one that fits your shop. Here are the criteria worth checking before you pay for anything.',
            'date' => '2026-05-05',
            'read_time' => '5:45',
            'categories' => ['Retail Guide', 'Product'],
            'card_label' => 'Buyer Guide',
            'card_colors' => ['#fff2e8', '#ffb37f', '#ff7a59'],
            'sections' => [
                [
                    'heading' => null,
                    'paragraphs' => [
                        'Search for the best POS system in Tanzania and you will find a great deal of confident marketing and very little that helps you decide. Every vendor claims the top spot, including this one, which is precisely why the claim carries no information.',
                        'A more useful question is which system is best for your shop, at your size, given how you actually trade. This guide sets out the criteria that separate systems in practice, so you can judge any option including SkelApp on evidence rather than adjectives.',
                    ],
                ],
                [
                    'heading' => 'Start by describing your own shop honestly',
                    'paragraphs' => [
                        'Before comparing anything, write down four numbers: how many transactions you process on a normal day, how many people need to log in, how many locations you operate, and how many distinct products you carry.',
                        'These four numbers eliminate most of the market immediately. A system priced per terminal is wrong for a single owner with a phone. A system with no multi location support is wrong if a second branch opens next year. A system built for hospitality is wrong for a hardware shop carrying four thousand line items.',
                        'Almost every bad POS purchase traces back to skipping this step and buying on a demonstration instead. Demonstrations are designed to look good. Your four numbers are designed to be true.',
                    ],
                ],
                [
                    'heading' => 'Offline selling is not a bonus feature',
                    'paragraphs' => [
                        'This is the criterion that belongs at the top of any Tanzanian buying list, and it is routinely buried at the bottom of vendor comparison tables written elsewhere.',
                        'Power cuts happen. Mobile data drops. If your point of sale stops working when connectivity does, then it has not replaced your notebook, it has added a dependency your notebook never had. A queue at the counter does not pause while a router reboots.',
                        'Ask any vendor directly what happens to a sale during an outage, and whether transactions recorded offline reconcile automatically once the connection returns. SkelApp is built to keep selling through power cuts, and its hardware range is offline ready by design. Whatever you choose, do not accept a vague answer on this point.',
                    ],
                ],
                [
                    'heading' => 'Check the per user and per branch maths',
                    'paragraphs' => [
                        'Headline pricing is often quoted for a single user at a single location. That is rarely the shape of a real business, and the gap between the advertised figure and your actual monthly cost can be substantial.',
                        'Work out what you would pay with your real staff count and your real branch count, then compare that number rather than the one on the poster. Ask specifically whether additional users cost extra, whether each location carries its own fee, and whether the price changes when transaction volume grows.',
                        'For reference, SkelApp publishes three tiers. WingaMode is free and covers one user with ten POS transactions a month. BiasharaPlus is TZS 15,000 per month billed annually and covers three users with unlimited POS transactions, 500 online store orders and 500 purchase orders or bills. Build Your Own is custom priced for multi branch operations and removes the user and location limits entirely.',
                    ],
                ],
                [
                    'heading' => 'Inventory depth, not just a product list',
                    'paragraphs' => [
                        'Every POS can hold a list of products and prices. The differences appear in what happens to that list under pressure.',
                        'Look for batch and expiry tracking if you sell food, cosmetics, or anything with a shelf life, because without it you will discover expiry dates by finding them. Look for stock adjustments and returns, because both happen constantly and a system that cannot record them cleanly will drift out of step with reality within weeks.',
                        'Look for low stock alerts, which turn inventory from a report you must remember to run into a warning that arrives on its own. And if you run or plan to run more than one location, look for transfers and transfer orders, so moving goods between branches is a recorded event rather than a phone call and a hope.',
                    ],
                ],
                [
                    'heading' => 'Reports you will actually open',
                    'paragraphs' => [
                        'Reporting is where buyers are most easily impressed and most often disappointed. A system can generate forty report types and still fail to answer the question you care about, which is usually some version of where did the money go.',
                        'The reports that earn their place tend to be few: profit by product, cash flow, outstanding customer balances, and performance by payment account. Those four cover the majority of decisions a retail owner makes in a given month.',
                        'Scheduled reports are worth asking about too. A monthly summary that arrives by email without anyone remembering to produce it is worth more than a dashboard nobody visits. On SkelApp, customisable and scheduled reports arrive with the BiasharaPlus tier.',
                    ],
                ],
                [
                    'heading' => 'Where your data lives, and whether it can leave',
                    'paragraphs' => [
                        'Two questions matter here and both deserve a straight answer. Can you export your own data, and does the system connect to the accounting software your bookkeeper already uses?',
                        'Export matters because it is your insurance policy. A vendor that makes leaving difficult is telling you something about how it expects to retain customers. Integration matters because manual re-entry between your POS and your accounts is both a recurring cost and a reliable source of errors.',
                        'SkelApp integrates with Zoho Books, Xero, QuickBooks and Sage on the accounting side, and with WhatsApp and Notify Africa BulkSMS for customer messaging. Whichever system you evaluate, check that the specific package your accountant uses is on the list rather than assuming a general claim of integration covers it.',
                    ],
                ],
                [
                    'heading' => 'Support, setup and the first two weeks',
                    'paragraphs' => [
                        'The hardest period with any new system is the fortnight after you switch. Stock has to be loaded, staff have to be trained, and mistakes get made while everyone is still learning.',
                        'Ask what setup assistance is included rather than sold separately, whether staff training is provided, and what the support hours actually are. Around the clock support is meaningfully different from business hours support when your busiest trading happens on a Saturday evening.',
                        'SkelApp includes setup and staff training, with 24 hour support on the paid tiers and dedicated onboarding on custom plans. That is the standard worth measuring other quotes against.',
                    ],
                ],
                [
                    'heading' => 'Making the decision',
                    'paragraphs' => [
                        'Narrow the field to two or three options, then test rather than deliberate. Load fifty of your real products, process a normal day of real transactions, and ask an attendant who was not part of the decision to use it without much explanation. Their reaction after twenty minutes will tell you more than any feature comparison.',
                        'The best POS system in Tanzania is the one your staff use correctly when you are not standing behind them. Free plans and trials exist so you can establish that before committing. Use them.',
                    ],
                ],
            ],
        ],

        [
            'slug' => 'cloud-pos-tanzania',
            'title' => 'Cloud POS in Tanzania',
            'summary' => 'What a cloud POS is, how it differs from software installed on one counter machine, and why it matters when the power goes out or you are away from the shop.',
            'date' => '2026-05-08',
            'read_time' => '5:35',
            'categories' => ['Company', 'Operations'],
            'card_label' => 'Cloud POS',
            'card_colors' => ['#eef4ff', '#9ab6ff', '#5b6cff'],
            'sections' => [
                [
                    'heading' => null,
                    'paragraphs' => [
                        'The phrase cloud POS gets used so freely that it has almost stopped meaning anything. For a shop owner deciding how to spend money, though, the distinction is concrete and worth understanding, because it determines what happens to your business when a machine fails, when the power goes, and when you are two hundred kilometres from your own counter.',
                        'This article explains what cloud actually means in this context, what it replaced, and where its genuine limits are.',
                    ],
                ],
                [
                    'heading' => 'What the word actually means here',
                    'paragraphs' => [
                        'A cloud point of sale keeps your data on managed servers rather than on the computer sitting under your counter. The device in the shop, whether a phone, a tablet or a till, is a window onto that data rather than the place it lives.',
                        'That single architectural difference produces most of the practical benefits people associate with the term. There is nothing to install and no server for you to maintain. Updates arrive without a technician visiting. Any authorised device can open the same live view of the business. And if a device is dropped, stolen or simply dies, your records are unaffected, because they were never stored only on that device.',
                    ],
                ],
                [
                    'heading' => 'The old way, and why it caused trouble',
                    'paragraphs' => [
                        'Traditional POS software installed on one machine in the shop. That machine held the only copy of your sales history, your product catalogue and your customer records.',
                        'The consequences followed predictably. Backups depended on somebody remembering to make them, which meant they were frequently months old or absent. A hardware failure could erase years of trading history in an afternoon. Checking on your shop meant physically going to it. And a second branch meant a second installation with its own separate data, so consolidating the two became a manual exercise in spreadsheets.',
                        'None of these were exotic edge cases. They were the ordinary, recurring costs of the architecture, and they fell hardest on the smallest businesses, which were least able to absorb them.',
                    ],
                ],
                [
                    'heading' => 'The obvious objection: what about the internet?',
                    'paragraphs' => [
                        'This is the right question to ask, and any honest answer has to start by taking it seriously. If a cloud system requires a live connection to process a sale, then in Tanzanian conditions it has traded one fragility for another.',
                        'A properly built cloud POS handles this by continuing to work locally on the device and reconciling with the servers once connectivity returns. Sales continue during an outage. Nothing is lost, and nothing has to be re-keyed afterwards.',
                        'SkelApp is designed to keep selling through power cuts, and its hardware range is built offline ready for the same reason. When comparing any cloud system, ask precisely what happens to a transaction mid outage. The answer separates systems built with local conditions in mind from those adapted to them afterwards.',
                    ],
                ],
                [
                    'heading' => 'Seeing your shop when you are not in it',
                    'paragraphs' => [
                        'This is the benefit owners tend to notice first and value most. Because the data lives centrally, you can open real time reports and dashboards from a phone anywhere.',
                        'That changes the nature of being away. A supplier trip, a family obligation or a second business no longer means a day of blindness followed by an evening of reconstructing what happened. You can watch the day sales accumulate, check whether a fast moving line is running low, and notice an unusual pattern while there is still time to ask about it.',
                        'For owners with more than one location it is more significant still. Comparing branches becomes a matter of looking rather than of collecting reports from each manager and aligning them by hand.',
                    ],
                ],
                [
                    'heading' => 'What happens to your data',
                    'paragraphs' => [
                        'Two questions deserve clear answers from any cloud vendor, and you should ask both before committing.',
                        'The first is whether you can export your own records. Your sales history and customer list are business assets, and the ability to take them elsewhere is what keeps the relationship honest. The second is who can see what internally, which is a matter of role based access. An attendant needs to process sales; they do not need to see margin on every product or the full customer ledger.',
                        'Cloud systems generally handle both better than installed software did, because permissions are managed centrally rather than by whoever happens to be sitting at the machine. But the questions are still worth asking directly rather than assuming.',
                    ],
                ],
                [
                    'heading' => 'Multi branch without the spreadsheets',
                    'paragraphs' => [
                        'The clearest case for cloud appears the moment you operate more than one location. With installed software, each branch is an island with its own data, and head office visibility is assembled manually after the fact.',
                        'With a cloud system, all branches write to the same records. Stock transfers between locations become tracked events rather than informal arrangements. Consolidated reporting is simply the default view. Prices can be managed centrally rather than updated separately in each shop and drifting apart over time.',
                        'On SkelApp, multi location management arrives with the BiasharaPlus tier at TZS 15,000 per month billed annually, and the custom Build Your Own plan removes location and user limits entirely for larger multi branch operations.',
                    ],
                ],
                [
                    'heading' => 'Moving across from an installed system',
                    'paragraphs' => [
                        'If you already run POS software on a machine in the shop, the migration is usually less painful than owners expect, but it rewards a little planning.',
                        'Start by getting your product catalogue out of the old system in whatever export format it offers. That list, with names, prices and barcodes, is the bulk of the work and the part worth doing carefully. Customer records and outstanding balances come next, because those represent money owed and cannot simply be abandoned. Historical sales are the lowest priority; most shops keep the old machine readable for reference rather than importing years of transactions.',
                        'Run both systems in parallel for a few days rather than switching overnight. It costs a little duplicated effort and it removes almost all of the risk, because any gap in the new setup surfaces while the old one is still there to fall back on. Setup assistance and staff training are included with SkelApp, and larger operations moving from an existing platform can use the custom Build Your Own plan, which adds data migration and dedicated onboarding.',
                    ],
                ],
                [
                    'heading' => 'Where the trade offs actually sit',
                    'paragraphs' => [
                        'Cloud is not free of drawbacks and it is worth being straightforward about them. You are dependent on a vendor continuing to operate and to maintain the service. Subscription pricing means an ongoing cost rather than a one off purchase, which some owners dislike on principle. And you do need connectivity at least intermittently, even if individual sales do not require it.',
                        'Set against that: no server to buy or maintain, no installation fees, no backup routine to remember, no data loss when hardware fails, remote visibility, and the ability to start on a free plan and test the thing properly before spending anything. For most small and medium Tanzanian retailers, that exchange is a clearly favourable one.',
                    ],
                ],
            ],
        ],

        [
            'slug' => 'retail-point-of-sale-tanzania',
            'title' => 'Retail Point of Sale built for Tanzania',
            'summary' => 'Tanzanian retail has its own rhythms: mixed payments, credit customers, branch transfers. Here is what a point of sale genuinely built for that looks like in practice.',
            'date' => '2026-05-12',
            'read_time' => '5:40',
            'categories' => ['Growth', 'Product'],
            'card_label' => 'Retail POS',
            'card_colors' => ['#f0fff4', '#8fe0b0', '#2fbf71'],
            'sections' => [
                [
                    'heading' => null,
                    'paragraphs' => [
                        'A great deal of retail software is built somewhere else and translated afterwards. The menus get renamed, a currency symbol changes, and the result is sold as a local product. It usually works until it meets the parts of Tanzanian trade that the original designers never had to think about.',
                        'This article is about those parts, and about what a retail point of sale looks like when they are treated as the normal case rather than as exceptions to be worked around.',
                    ],
                ],
                [
                    'heading' => 'Retail here does not match the manual',
                    'paragraphs' => [
                        'In the textbook version of a retail transaction, a customer selects goods, pays the full amount by one method, receives a receipt and leaves. Stock decrements, the ledger balances, everyone is satisfied.',
                        'Actual trade in a Tanzanian shop diverges from that within the first hour of any given morning. A customer pays part in cash and the rest by mobile money. A regular takes goods now and settles at the end of the month. A sale is agreed at a negotiated price rather than the shelf price. Someone returns an item bought last week and wants an exchange rather than a refund. A branch runs out of a fast moving line and borrows from another branch.',
                        'None of these are unusual. They are the ordinary texture of the business. A point of sale that treats them as awkward exceptions will generate friction at the counter every single day, and staff will eventually route around it by keeping a parallel notebook, which defeats the entire purpose.',
                    ],
                ],
                [
                    'heading' => 'Mixed payments on a single sale',
                    'paragraphs' => [
                        'This is the most common gap in imported software and the most consequential. Mobile money is woven through Tanzanian commerce, and a single transaction settled partly in cash and partly by transfer is entirely routine.',
                        'A system that forces one payment method per sale leaves staff with two poor options. They can split the sale into two transactions, which corrupts your product and customer history. Or they can record the whole thing under one method, which corrupts your cash reconciliation. Either way the data degrades quietly, and you discover it much later when the numbers refuse to agree.',
                        'Flexible payment recording solves this properly by letting one sale carry multiple settlement methods. Payment account tracking then follows the money to its destination, so you can see what landed in cash and what arrived by transfer without reconstructing it from memory.',
                    ],
                ],
                [
                    'heading' => 'Customers who pay later',
                    'paragraphs' => [
                        'Selling on credit to known customers is standard practice, and it is a genuine commercial tool rather than a failure of discipline. It builds loyalty and moves volume. The difficulty has never been the practice itself; it is keeping track of it.',
                        'Once outstanding balances live in customer records rather than in a notebook or somebody recollection, several things improve at once. You can see total exposure across all customers rather than guessing at it. You can see who is overdue without leafing through pages. Following up stops being socially awkward because it is backed by a clear record rather than an assertion.',
                        'The same structure applies on the supplier side, where your own outstanding bills sit. Purchase orders and bills recorded against supplier records give you the other half of the picture, which is what your business owes rather than what it is owed.',
                    ],
                ],
                [
                    'heading' => 'Stock that moves between branches',
                    'paragraphs' => [
                        'The moment a business operates a second location, an entire category of problem appears. Stock moves between branches informally, often on a phone call and a boda rider, and the records at both ends drift apart.',
                        'Transfers and transfer orders turn that movement into a recorded event with a sender, a receiver and a quantity. Both branches stay accurate. Consolidated stock across all locations becomes something you can look at rather than something you assemble.',
                        'Alongside transfers, the controls that keep inventory honest matter as much: batch and expiry tracking for anything with a shelf life, stock adjustments for the discrepancies that inevitably appear, returns handled as first class transactions, and low stock alerts so shortages announce themselves before a customer does.',
                    ],
                ],
                [
                    'heading' => 'Turning sales history into repeat business',
                    'paragraphs' => [
                        'This is where a point of sale stops being an operational tool and starts being a commercial one, and it is the part most shops never reach.',
                        'Every transaction you record adds to a picture of who buys what and how often. After a few months that picture supports questions worth asking. Which customers have not returned in ninety days? Which products do your highest value customers buy? Which lines sell together often enough to justify placing them side by side?',
                        'Acting on those answers requires a way to reach people, which is why messaging integration matters. SkelApp connects to WhatsApp and to Notify Africa BulkSMS, so a list identified in your reports can become an actual message rather than an intention. The distance between knowing something about your customers and doing something about it is where most of the value sits.',
                    ],
                ],
                [
                    'heading' => 'The reports that change decisions',
                    'paragraphs' => [
                        'Most retail reporting goes unread because it answers questions nobody asked. The useful set is small and specific.',
                        'Profit by product, because turnover and profit are different things and the highest selling line is frequently not the most valuable one. Cash flow, because a profitable shop can still run out of money. Outstanding balances, because credit extended is cash not yet received. Payment account performance, because knowing what arrived where is the foundation of every other number.',
                        'Scheduled reports matter more than they sound. A summary that arrives on its own each month gets read; a dashboard that requires someone to remember to open it does not. On SkelApp, customisable and scheduled reports arrive with BiasharaPlus, alongside custom views and custom fields for the details particular to your trade.',
                    ],
                ],
                [
                    'heading' => 'Built here, not translated',
                    'paragraphs' => [
                        'The difference between software built for a market and software adapted to it shows up in these small places rather than in headline features. Whether a split payment takes one step or three. Whether a credit sale is a normal path or a workaround. Whether a branch transfer is recorded or improvised.',
                        'SkelApp runs on iOS, Android and the web, works across branches, shops and warehouses, and supports online ordering alongside the counter. Setup and staff training are included, and support runs around the clock. The free WingaMode plan exists precisely so you can test all of the above against your own trading patterns before deciding anything.',
                    ],
                ],
            ],
        ],

        [
            'slug' => 'mobile-pos-tanzania',
            'title' => 'Mobile POS in Tanzania: Easy Enough to Learn in a Day',
            'summary' => 'A mobile POS turns a phone into a till. Here is how quickly staff pick it up, what to train on first, and what hardware you actually need, if any.',
            'date' => '2026-05-15',
            'read_time' => '5:25',
            'categories' => ['Product', 'Operations'],
            'card_label' => 'Mobile POS',
            'card_colors' => ['#fff0f6', '#ffa8cf', '#ff5fa2'],
            'sections' => [
                [
                    'heading' => null,
                    'paragraphs' => [
                        'The strongest argument for a mobile point of sale in Tanzania is not a feature. It is that the hardware requirement has already been met. Your staff carry capable smartphones, they use them fluently, and the gestures a POS depends on are ones they already perform hundreds of times a day.',
                        'That is why training time on a well built mobile POS is measured in hours rather than days. This article covers what that first day actually looks like, what to teach first, and when buying dedicated equipment starts to make sense.',
                    ],
                ],
                [
                    'heading' => 'Why the phone is the natural counter',
                    'paragraphs' => [
                        'Traditional POS training carried a hidden cost that nobody itemised: teaching people to use an unfamiliar machine. A dedicated till has its own keyboard layout, its own screen conventions and its own logic, none of which anyone had encountered before.',
                        'A phone removes that layer entirely. Tapping, scrolling, searching and confirming are already automatic. What remains to be taught is your business process, which is the part that actually needed teaching in the first place.',
                        'There is a second advantage that matters in a busy shop. A mobile terminal is not fixed to one spot. Staff can serve customers wherever they happen to be standing, take stock while walking the shelves, or process a sale at the door during a rush rather than routing everyone through a single point.',
                    ],
                ],
                [
                    'heading' => 'What easy to learn actually means',
                    'paragraphs' => [
                        'The phrase is claimed by everyone, so it needs a concrete test. A useful one: can an attendant who was not involved in choosing the system process a complete sale correctly within twenty minutes of picking it up, without a manual?',
                        'That is a demanding standard and it is the right one, because it reflects reality. Staff turnover happens. New people arrive during busy periods when nobody has an afternoon free for training. A system that only works for those who attended a formal session will degrade the moment your team changes.',
                        'Run this test during any trial, with a real member of your staff rather than yourself. You already know what the system is supposed to do, which makes you the worst possible judge of whether it is obvious.',
                    ],
                ],
                [
                    'heading' => 'The first day: what to teach, in order',
                    'paragraphs' => [
                        'Resist the temptation to demonstrate everything. A new user needs four things on day one, and adding more simply dilutes the four.',
                        'First, processing a straightforward sale from start to receipt. Second, handling a split payment, because part cash and part mobile money will come up within the first hour and staff need to know it is a normal path rather than a problem. Third, looking up a product by name when a barcode will not scan or does not exist. Fourth, recording a return or an exchange.',
                        'Those four cover the overwhelming majority of counter interactions. Reports, stock adjustments, customer records and purchase orders can wait until week two, by which point the basics are automatic and there is room to absorb them.',
                    ],
                ],
                [
                    'heading' => 'Roles, so staff see only what they should',
                    'paragraphs' => [
                        'A question that surfaces quickly once staff have their own logins is how much they can see. The answer should be under your control rather than fixed by the software.',
                        'An attendant needs to process sales, look up products and handle returns. They generally do not need to see the margin on every line, the full customer ledger, or the totals for the day across all branches. Role based access lets you draw that boundary deliberately.',
                        'This is also what makes staff accountability workable rather than adversarial. When every transaction carries the identity of whoever processed it, questions about a discrepancy become specific and answerable instead of a general suspicion falling on everyone equally.',
                    ],
                ],
                [
                    'heading' => 'Hardware, if and when you want it',
                    'paragraphs' => [
                        'Phones are sufficient to start and often sufficient permanently. But there are real reasons to add equipment as volume grows, and it is worth knowing what each piece solves.',
                        'A barcode scanner is the first upgrade most shops feel the need for, because scanning is meaningfully faster than searching once you carry more than a few hundred products. A cash drawer matters if you handle significant cash and want it secured and tracked. Receipt printing matters if your customers expect a printed slip, which many business buyers do.',
                        'SkelApp offers Skel Register, Skel Terminal, Skel Tab and Skel Phone across different counter styles, with tablet stands, charging docks, receipt paper, barcode scanners and cash drawers alongside them. All of it is plug and play, offline ready and locally supported. The sensible order is to run on phones first, notice where the friction actually is, then buy the specific thing that removes it.',
                    ],
                ],
                [
                    'heading' => 'Selling beyond the counter',
                    'paragraphs' => [
                        'A mobile terminal opens sales channels that a fixed till cannot serve. Deliveries can be recorded at the point of handover rather than reconstructed later. Market days and pop up stalls become straightforward. A salesperson visiting a customer can process the transaction on the spot.',
                        'Online orders belong in the same picture. SkelApp includes online store order handling on every tier, with ten orders a month on the free WingaMode plan and 500 on BiasharaPlus. Because those orders flow into the same system as counter sales, your stock and reporting stay unified rather than splitting into separate channels that have to be reconciled.',
                    ],
                ],
                [
                    'heading' => 'The practical details nobody mentions',
                    'paragraphs' => [
                        'Two mundane questions decide whether a mobile POS works over a full trading day, and neither appears in product brochures.',
                        'The first is battery. A phone used continuously as a terminal drains considerably faster than one used normally, and a device that dies at four in the afternoon is worse than no device at all. The fix is trivial once you have thought about it: keep a charging cable at the counter, or use a charging dock, and treat it as part of the setup rather than an afterthought.',
                        'The second is who owns the device. Staff phones are convenient to start with and become awkward later, because the terminal walks out of the shop at the end of every shift and leaves with the employee when they do. Most shops eventually move to a dedicated device for the counter and keep personal phones as backup for busy periods. Skel Phone and Skel Tab exist for exactly this transition, and role based access means a shared device still records who processed which sale.',
                    ],
                ],
                [
                    'heading' => 'Training the rest of the team',
                    'paragraphs' => [
                        'The most effective pattern is straightforward. Train one person properly, let them use the system for a few days until it is genuinely automatic, then have them teach the next person. Knowledge passed on by a colleague who has hit the same confusions tends to stick better than a formal session.',
                        'Setup and staff training are included with SkelApp, and support runs around the clock for the questions that arrive at inconvenient hours. Start on the free plan with your own products and your own staff. If an attendant can serve a customer confidently on their first afternoon, you have your answer about whether it is easy enough.',
                    ],
                ],
            ],
        ],

        [
            'slug' => 'pos-system-east-africa-2026',
            'title' => '2026 Leading POS System in East Africa',
            'summary' => 'A complete 2026 guide to POS systems in East Africa: what has changed in retail, what buyers now expect as standard, and how to choose a platform that grows with you.',
            'date' => '2026-05-17',
            'read_time' => '6:10',
            'categories' => ['Retail Guide', 'Growth'],
            'card_label' => 'Full Guide',
            'card_colors' => ['#f5f0ff', '#c0a8ff', '#8b5cf6'],
            'sections' => [
                [
                    'heading' => null,
                    'paragraphs' => [
                        'East African retail has changed faster over the past decade than the software serving it. Mobile money became the default rather than the alternative. Smartphones reached the counter. Cross border trade grew more routine, and the shops driving that growth are frequently small and medium businesses rather than large chains.',
                        'This guide sets out what has actually shifted, what buyers should now treat as standard rather than premium, and how to choose a point of sale that will still suit you in three years rather than one.',
                    ],
                ],
                [
                    'heading' => 'What changed in East African retail',
                    'paragraphs' => [
                        'The first shift is who is buying. Point of sale was historically a supermarket technology, priced and packaged accordingly. The growth now sits with independent shops, boutiques, minimarts, hardware stores, pharmacies and specialist retailers, most of which have between one and five staff.',
                        'The second shift is the device. For years the assumption was a dedicated terminal at a fixed counter. The phone displaced that assumption almost entirely, which removed the largest single cost of adoption and changed what training looks like.',
                        'The third shift is expectation. Owners who manage their banking, their communications and their logistics from a phone reasonably expect to manage their shop the same way. Software that requires being physically present now reads as dated rather than as normal.',
                    ],
                ],
                [
                    'heading' => 'Mobile money rewrote the checkout',
                    'paragraphs' => [
                        'This is the change with the deepest consequences for software design, and the one imported systems handle worst.',
                        'When a meaningful share of transactions settle by mobile transfer, and when a single sale is routinely split across cash and transfer, the payment model at the heart of a POS has to accommodate that natively. Systems built around one payment method per transaction were designed for a different market, and adapting them after the fact produces the workarounds that quietly ruin your reconciliation.',
                        'What buyers should now expect as standard is flexible payment recording on a single sale, and payment account tracking that shows where money actually landed. These are not advanced features in this market. They are the baseline.',
                    ],
                ],
                [
                    'heading' => 'Cloud became the default',
                    'paragraphs' => [
                        'A decade ago, keeping business data on a machine in the shop was ordinary. Today it is a liability without a compensating benefit.',
                        'Cloud delivery removed installation, removed server maintenance, removed the backup routine that was rarely performed, and removed the risk that a hardware failure erases years of history. It added remote visibility, automatic updates, and the ability for multiple locations to share one live set of records.',
                        'The legitimate objection has always been connectivity, and it remains the question to press vendors on. A cloud POS suited to this region must keep selling through outages and reconcile automatically afterwards. SkelApp is built to keep trading through power cuts for exactly this reason, and its hardware range is offline ready by design.',
                    ],
                ],
                [
                    'heading' => 'What buyers should expect as standard in 2026',
                    'paragraphs' => [
                        'The bar has moved, and several things that were sold as premium additions a few years ago should now be treated as table stakes.',
                        'Offline capable selling. Mobile and web access rather than a single fixed terminal. Real time reports available remotely. Inventory control that includes batch and expiry tracking, adjustments, returns and low stock alerts. Customer and supplier records carrying outstanding balances. Role based access so staff permissions are deliberate. And a genuine free tier or trial, because asking a small business to pay before testing is no longer defensible.',
                        'If a quote in 2026 treats any of these as an upgrade, that tells you something useful about how current the product is.',
                    ],
                ],
                [
                    'heading' => 'Growing across branches and borders',
                    'paragraphs' => [
                        'Ambitious retailers in the region increasingly operate several locations, and some trade across national borders. The point of sale decision made at one shop tends to constrain what is possible at five.',
                        'Multi location management is the capability to check. Specifically: whether all branches write to one consolidated set of records, whether stock transfers between locations are tracked, whether prices can be managed centrally, and whether reporting rolls up without manual assembly.',
                        'On SkelApp, multi location management arrives with BiasharaPlus at TZS 15,000 per month billed annually, covering three users with unlimited POS transactions. The custom Build Your Own tier removes user and location limits altogether and adds custom integrations, data migration, dedicated onboarding and priority support for larger operations.',
                    ],
                ],
                [
                    'heading' => 'Accounting integration stopped being optional',
                    'paragraphs' => [
                        'As businesses formalise, the cost of manual re-entry between the shop and the books becomes difficult to justify. It consumes hours every month and introduces errors at exactly the point where errors are most expensive.',
                        'Direct integration with the accounting package your bookkeeper already uses removes that entirely. SkelApp connects to Zoho Books, Xero, QuickBooks and Sage, which covers the packages most commonly encountered in the region.',
                        'Customer messaging deserves the same treatment. Integration with WhatsApp and Notify Africa BulkSMS turns a customer list identified in your reports into an actual campaign, rather than a good intention that requires someone to copy numbers into a phone.',
                    ],
                ],
                [
                    'heading' => 'Choosing for the next three years',
                    'paragraphs' => [
                        'Most POS regret comes from buying for the shop you have rather than the one you intend to build. The system that suits one location and two staff can become the obstacle at four locations and twelve.',
                        'Three questions cover most of it. What happens when you add a second branch, and does the pricing model punish that? What happens when transaction volume triples, and are there caps you will hit? Can you export your own data if you decide to leave, which is the question that keeps a vendor honest about everything else?',
                        'A platform with a clear upgrade path answers all three comfortably. One that requires migrating to a different product as you grow is a decision you will make twice.',
                    ],
                ],
                [
                    'heading' => 'Where to start',
                    'paragraphs' => [
                        'Start free and start small. SkelApp WingaMode costs nothing and covers one user with ten POS transactions a month, alongside inventory, customer, sales and purchase management and real time reports. That is enough to load a real product catalogue, trade for a few days and form a genuine judgement.',
                        'If it fits, BiasharaPlus at TZS 15,000 per month billed annually lifts the transaction ceiling and adds the multi location, batch tracking, scheduled reporting and automation capabilities that growing businesses reach for next. Setup and staff training are included, support runs around the clock, and the platform works across iOS, Android and the web.',
                        'The best test remains the simplest one. Load your own products, let your own staff use it for a week, and see whether anyone reaches for the notebook.',
                    ],
                ],
            ],
        ],

    ],

];
