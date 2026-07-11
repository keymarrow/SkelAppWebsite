{{--
  Reusable SEO head partial: canonical + Open Graph + Twitter + JSON-LD.
  Include inside a page <head> AFTER the <title>/<meta description>, e.g.

    @include('partials.seo', [
        'seoTitle'       => content('pos.meta.title', 'Point of Sale – SkelApp'),
        'seoDescription' => content('pos.meta.description', '...'),
        'seoType'        => 'website',          // website | article | product
        'seoImage'       => content_image('pos.hero.image', asset('assets/HeroImage.webp')),
        'seoSoftware'    => true,               // emit the SoftwareApplication (POS) node
        'seoFaqs'        => [['question' => '…', 'answer' => '…'], …], // optional FAQPage
        'seoBreadcrumbs' => [['name' => 'Home', 'url' => url('/')], …], // optional
    ])
--}}
@php
    $siteName    = content('global.brand.name', 'SkelApp');
    $canonical   = $seoCanonical ?? url()->current();
    $title       = $seoTitle ?? content('global.brand.name', 'SkelApp — Point of Sale for Tanzania');
    $description = $seoDescription ?? content(
        'home.meta.description',
        'SkelApp is the point of sale and inventory app built for small businesses in Tanzania.'
    );
    $image       = $seoImage ?? content_image('global.brand.og_image', asset('assets/HeroImage.webp'));
    $image       = \Illuminate\Support\Str::startsWith($image, ['http://', 'https://']) ? $image : url($image);
    $ogType      = $seoType ?? 'website';

    // ── JSON-LD @graph ───────────────────────────────────────────────────
    $orgId  = url('/') . '#organization';
    $siteId = url('/') . '#website';

    $organization = [
        '@type'       => 'Organization',
        '@id'         => $orgId,
        'name'        => $siteName,
        'url'         => url('/'),
        'logo'        => url(content_image('global.footer.logo', asset('assets/skel.svg'))),
        'description' => 'Point of sale, inventory and business management software for Tanzanian retailers and small businesses.',
        'slogan'      => content('global.footer.tagline', 'The point of sale built for how Tanzania sells.'),
        'areaServed'  => ['@type' => 'Country', 'name' => 'Tanzania'],
        'knowsAbout'  => ['Point of sale', 'POS system', 'Inventory management', 'Retail software', 'Small business software', 'Mobile money payments'],
        'address'     => ['@type' => 'PostalAddress', 'addressCountry' => 'TZ'],
    ];

    if ($email = content('global.footer.email')) {
        $organization['email'] = $email;
    }
    if ($phone = content('global.footer.phone_tel')) {
        $organization['contactPoint'] = [
            '@type'       => 'ContactPoint',
            'telephone'   => $phone,
            'contactType' => 'sales',
            'areaServed'  => 'TZ',
        ];
    }
    if ($sameAs = content_list('global.brand.social', [])) {
        $links = collect($sameAs)->pluck('url')->filter()->values()->all();
        if ($links) {
            $organization['sameAs'] = $links;
        }
    }

    $website = [
        '@type'     => 'WebSite',
        '@id'       => $siteId,
        'url'       => url('/'),
        'name'      => $siteName,
        'publisher' => ['@id' => $orgId],
    ];

    $graph = [$organization, $website];

    // SoftwareApplication — the node that anchors "Point of Sale in Tanzania"
    if (($seoSoftware ?? false) === true) {
        $software = [
            '@type'                 => 'SoftwareApplication',
            '@id'                   => url('/') . '#software',
            'name'                  => $siteName,
            'alternateName'         => $siteName . ' POS',
            'applicationCategory'   => 'BusinessApplication',
            'applicationSubCategory' => 'Point of Sale (POS) Software',
            'operatingSystem'       => 'Android, iOS, Web',
            'url'                   => route('pos.show'),
            'description'           => $description,
            'inLanguage'            => 'en',
            'publisher'             => ['@id' => $orgId],
            'areaServed'            => ['@type' => 'Country', 'name' => 'Tanzania'],
            'featureList'           => [
                'Fast checkout', 'Accepts M-Pesa, Airtel Money, Tigo Pesa and cards',
                'Works offline', 'Real-time inventory tracking', 'Sales and expense reports',
                'Thermal, SMS and email receipts',
            ],
        ];

        // Only emit an Offer when a real price is configured (avoid implying "free").
        if (($price = content('global.seo.price')) !== null && $price !== '') {
            $software['offers'] = [
                '@type'         => 'Offer',
                'price'         => (string) $price,
                'priceCurrency' => content('global.seo.currency', 'TZS'),
                'availability'  => 'https://schema.org/InStock',
            ];
        }

        // Only emit aggregateRating from REAL, configured review data.
        // Never fabricate ratings — Google penalises fake review markup.
        $ratingValue = content('global.seo.rating_value');
        $ratingCount = content('global.seo.rating_count');
        if ($ratingValue && $ratingCount) {
            $software['aggregateRating'] = [
                '@type'       => 'AggregateRating',
                'ratingValue' => (string) $ratingValue,
                'ratingCount' => (string) $ratingCount,
            ];
        }

        $graph[] = $software;
    }

    // Optional FAQPage
    if (!empty($seoFaqs) && is_array($seoFaqs)) {
        $entities = collect($seoFaqs)
            ->map(function ($faq) {
                $q = $faq['question'] ?? $faq['q'] ?? null;
                $a = $faq['answer'] ?? $faq['a'] ?? $faq['body'] ?? null;
                if (!$q || !$a) {
                    return null;
                }
                return [
                    '@type'          => 'Question',
                    'name'           => strip_tags((string) $q),
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags((string) $a)],
                ];
            })
            ->filter()
            ->values()
            ->all();

        if ($entities) {
            $graph[] = ['@type' => 'FAQPage', 'mainEntity' => $entities];
        }
    }

    // Optional BreadcrumbList
    if (!empty($seoBreadcrumbs) && is_array($seoBreadcrumbs)) {
        $items = collect($seoBreadcrumbs)
            ->values()
            ->map(fn ($crumb, $i) => [
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'name'     => $crumb['name'] ?? '',
                'item'     => $crumb['url'] ?? null,
            ])
            ->all();
        $graph[] = ['@type' => 'BreadcrumbList', 'itemListElement' => $items];
    }

    $jsonLd = json_encode(
        ['@context' => 'https://schema.org', '@graph' => $graph],
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    );
@endphp
<link rel="canonical" href="{{ $canonical }}" />
<meta property="og:type" content="{{ $ogType }}" />
<meta property="og:site_name" content="{{ $siteName }}" />
<meta property="og:title" content="{{ $title }}" />
<meta property="og:description" content="{{ $description }}" />
<meta property="og:url" content="{{ $canonical }}" />
<meta property="og:image" content="{{ $image }}" />
<meta property="og:locale" content="en_TZ" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $title }}" />
<meta name="twitter:description" content="{{ $description }}" />
<meta name="twitter:image" content="{{ $image }}" />
<script type="application/ld+json">{!! $jsonLd !!}</script>
