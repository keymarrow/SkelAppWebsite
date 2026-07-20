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
    $pageId      = $canonical.'#webpage';
    $pageType    = trim((string) ($seoPageType ?? 'WebPage')) ?: 'WebPage';
    $pageName    = trim((string) ($seoPageName ?? $title)) ?: $title;

    $normalizeSchemaImage = function (mixed $value) use ($image): array {
        $images = collect(is_array($value) ? $value : [$value])
            ->filter(fn ($item) => is_string($item) && trim($item) !== '')
            ->map(function (string $item) {
                $item = trim($item);

                return \Illuminate\Support\Str::startsWith($item, ['http://', 'https://'])
                    ? $item
                    : url($item);
            })
            ->values()
            ->all();

        return $images !== [] ? $images : [$image];
    };

    $normalizeThing = function (mixed $value): ?array {
        if (is_string($value) && trim($value) !== '') {
            return ['@type' => 'Thing', 'name' => trim($value)];
        }

        if (! is_array($value)) {
            return null;
        }

        if (! empty($value['@id']) || ! empty($value['@type'])) {
            return $value;
        }

        $name = trim((string) ($value['name'] ?? $value['title'] ?? ''));

        if ($name === '') {
            return null;
        }

        return ['@type' => 'Thing', 'name' => $name];
    };

    $normalizeAuthor = function (mixed $value) use ($siteName): array {
        if (is_string($value) && trim($value) !== '') {
            return ['@type' => 'Person', 'name' => trim($value)];
        }

        if (is_array($value)) {
            if (! empty($value['@id']) || ! empty($value['@type'])) {
                return $value;
            }

            $name = trim((string) ($value['name'] ?? $value['title'] ?? ''));
            $type = trim((string) ($value['type'] ?? 'Person')) ?: 'Person';

            if ($name !== '') {
                return ['@type' => $type, 'name' => $name];
            }
        }

        return ['@type' => 'Organization', 'name' => $siteName];
    };

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
        'knowsAbout'  => ['Point of sale', 'POS system', 'Inventory management', 'Retail software', 'Small business software', 'Payment tracking', 'Multi-location retail management'],
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
    $pageMainEntityId = null;

    $page = [
        '@type'              => $pageType,
        '@id'                => $pageId,
        'url'                => $canonical,
        'name'               => $pageName,
        'description'        => $description,
        'isPartOf'           => ['@id' => $siteId],
        'primaryImageOfPage' => ['@type' => 'ImageObject', 'url' => $image],
        'inLanguage'         => 'en-TZ',
    ];

    if (! empty($seoAbout)) {
        $about = collect(is_array($seoAbout) ? $seoAbout : [$seoAbout])
            ->map($normalizeThing)
            ->filter()
            ->values()
            ->all();

        if ($about) {
            $page['about'] = count($about) === 1 ? $about[0] : $about;
        }
    }

    // SoftwareApplication — the node that anchors "Point of Sale in Tanzania"
    if (($seoSoftware ?? false) === true) {
        $softwareDescription = trim((string) ($seoSoftwareDescription ?? content(
            'global.seo.software_description',
            'SkelApp is the business management platform for retailers and small businesses in Tanzania, covering sales, purchases, expenses, stock, payment tracking and reporting.'
        )));

        $software = [
            '@type'                 => 'SoftwareApplication',
            '@id'                   => url('/') . '#software',
            'name'                  => $siteName,
            'alternateName'         => $siteName . ' POS',
            'applicationCategory'   => 'BusinessApplication',
            'applicationSubCategory' => 'Point of Sale (POS) Software',
            'operatingSystem'       => 'Android, iOS, Web',
            'url'                   => $seoSoftwareUrl ?? route('pos.show'),
            'description'           => $softwareDescription,
            'inLanguage'            => 'en',
            'publisher'             => ['@id' => $orgId],
            'areaServed'            => ['@type' => 'Country', 'name' => 'Tanzania'],
            'image'                 => $normalizeSchemaImage($seoSoftwareImage ?? $image),
            'featureList'           => [
                'Works on iOS, Android and the web',
                'Multi-location management',
                'Sales, purchases, expenses and order management',
                'Payment method and payment account tracking',
                'Customer and supplier management',
                'Advanced inventory management',
                'Online order management',
                'Accounting and communication integrations',
                'Advanced business reports',
                'Attendant and staff management',
                'Offline-ready selling',
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
        $pageMainEntityId = $software['@id'];
    }

    if (! empty($seoProduct) && is_array($seoProduct)) {
        $productName = trim((string) ($seoProduct['name'] ?? $pageName));

        if ($productName !== '') {
            $product = [
                '@type'        => 'Product',
                '@id'          => $canonical.'#product',
                'name'         => $productName,
                'description'  => trim((string) ($seoProduct['description'] ?? $description)) ?: $description,
                'url'          => $canonical,
                'image'        => $normalizeSchemaImage($seoProduct['image'] ?? $image),
                'brand'        => ['@type' => 'Brand', 'name' => trim((string) ($seoProduct['brand'] ?? $siteName)) ?: $siteName],
                'manufacturer' => ['@id' => $orgId],
                'category'     => trim((string) ($seoProduct['category'] ?? 'Point of Sale Hardware')) ?: 'Point of Sale Hardware',
            ];

            $attributes = collect($seoProduct['attributes'] ?? [])
                ->map(function ($attribute) {
                    if (! is_array($attribute)) {
                        return null;
                    }

                    $name = trim((string) ($attribute['name'] ?? $attribute['label'] ?? ''));
                    $value = trim((string) ($attribute['value'] ?? $attribute['text'] ?? ''));

                    if ($name === '' || $value === '') {
                        return null;
                    }

                    return [
                        '@type' => 'PropertyValue',
                        'name' => $name,
                        'value' => $value,
                    ];
                })
                ->filter()
                ->values()
                ->all();

            if ($attributes) {
                $product['additionalProperty'] = $attributes;
            }

            if (! empty($seoProduct['offers']) && is_array($seoProduct['offers'])) {
                $price = $seoProduct['offers']['price'] ?? null;

                if ($price !== null && $price !== '') {
                    $product['offers'] = [
                        '@type' => 'Offer',
                        'price' => (string) $price,
                        'priceCurrency' => trim((string) ($seoProduct['offers']['priceCurrency'] ?? 'TZS')) ?: 'TZS',
                        'availability' => trim((string) ($seoProduct['offers']['availability'] ?? 'https://schema.org/InStock')) ?: 'https://schema.org/InStock',
                        'url' => $canonical,
                    ];
                }
            }

            $graph[] = $product;
            $pageMainEntityId = $product['@id'];
        }
    }

    if (! empty($seoArticle) && is_array($seoArticle)) {
        $headline = trim((string) ($seoArticle['headline'] ?? $pageName));

        if ($headline !== '') {
            $article = [
                '@type' => trim((string) ($seoArticle['type'] ?? 'NewsArticle')) ?: 'NewsArticle',
                '@id' => $canonical.'#article',
                'headline' => $headline,
                'description' => trim((string) ($seoArticle['description'] ?? $description)) ?: $description,
                'url' => $canonical,
                'mainEntityOfPage' => ['@id' => $pageId],
                'author' => $normalizeAuthor($seoArticle['author'] ?? ['type' => 'Organization', 'name' => $siteName]),
                'publisher' => ['@id' => $orgId],
                'image' => $normalizeSchemaImage($seoArticle['image'] ?? $image),
            ];

            if ($datePublished = trim((string) ($seoArticle['datePublished'] ?? ''))) {
                $article['datePublished'] = $datePublished;
            }

            $dateModified = trim((string) ($seoArticle['dateModified'] ?? ($seoArticle['datePublished'] ?? '')));
            if ($dateModified !== '') {
                $article['dateModified'] = $dateModified;
            }

            $graph[] = $article;
            $pageMainEntityId = $article['@id'];
        }
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
        $breadcrumbId = $pageId.'#breadcrumb';
        $items = collect($seoBreadcrumbs)
            ->values()
            ->map(fn ($crumb, $i) => [
                '@type'    => 'ListItem',
                'position' => $i + 1,
                'name'     => $crumb['name'] ?? '',
                'item'     => $crumb['url'] ?? null,
            ])
            ->all();
        $graph[] = ['@type' => 'BreadcrumbList', '@id' => $breadcrumbId, 'itemListElement' => $items];
        $page['breadcrumb'] = ['@id' => $breadcrumbId];
    }

    if ($pageMainEntityId) {
        $page['mainEntity'] = ['@id' => $pageMainEntityId];
    }

    $graph[] = $page;

    $jsonLd = json_encode(
        ['@context' => 'https://schema.org', '@graph' => $graph],
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    );
@endphp
<link rel="icon" href="{{ asset('favicon.ico') }}" />
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
