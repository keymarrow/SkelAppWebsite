<?php

namespace App\Http\Controllers;

use App\Services\NewsRepository;
use App\Support\CmsCatalogs;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class SitemapController extends Controller
{
    public function __construct(
        private readonly NewsRepository $newsRepository,
    ) {
    }

    public function __invoke(): Response
    {
        $today = now()->toDateString();

        // ── Core marketing pages, ordered by SEO importance ──────────────
        // priority: 0.0–1.0 (relative weight vs. our own pages)
        // changefreq: crawler hint for how often the page changes
        $staticUrls = collect([
            // Homepage + the primary "Point of Sale in Tanzania" landing page
            ['loc' => url('/'),                       'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => route('pos.show'),              'priority' => '1.0', 'changefreq' => 'weekly'],

            // High-intent conversion pages
            ['loc' => route('features.show'),         'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => route('pricing.show'),          'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => route('hardware.show'),         'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => route('integrations.index'),    'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => route('retailers.index'),       'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => route('why.show'),              'priority' => '0.7', 'changefreq' => 'monthly'],

            // Content + trust pages
            ['loc' => route('news.index'),            'priority' => '0.7', 'changefreq' => 'daily'],
            ['loc' => route('faq.show'),              'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => route('contact.show'),          'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => route('affiliate.show'),        'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => route('affiliate.apply.show'),  'priority' => '0.4', 'changefreq' => 'monthly'],
            ['loc' => route('terms.show'),            'priority' => '0.3', 'changefreq' => 'yearly'],
            ['loc' => route('privacy.show'),          'priority' => '0.3', 'changefreq' => 'yearly'],
        ])->map(fn (array $url) => $url + ['lastmod' => $today]);

        // ── Dynamic catalog pages (CMS-driven) ───────────────────────────
        $integrationUrls = $this->catalog('integrations.items', CmsCatalogs::integrationItems())
            ->map(fn (array $item) => [
                'loc' => route('integrations.show', $item['slug']),
                'lastmod' => $today,
                'priority' => '0.7',
                'changefreq' => 'monthly',
            ]);

        $retailerUrls = $this->catalog('retailers.pages', CmsCatalogs::retailerPages())
            ->map(fn (array $item) => [
                'loc' => route('retailers.show', $item['slug']),
                'lastmod' => $today,
                'priority' => '0.7',
                'changefreq' => 'monthly',
            ]);

        $hardwareUrls = $this->catalog('hardware.products', CmsCatalogs::hardwareProducts())
            ->map(fn (array $item) => [
                'loc' => route('hardware.product', $item['slug']),
                'lastmod' => $today,
                'priority' => '0.6',
                'changefreq' => 'monthly',
            ]);

        // ── News articles ────────────────────────────────────────────────
        $newsUrls = $this->newsRepository->publicArticles()
            ->map(fn (array $article) => [
                'loc' => route('news.show', $article['slug']),
                'lastmod' => $article['date'] ?? $today,
                'priority' => '0.6',
                'changefreq' => 'monthly',
            ]);

        $urls = $staticUrls
            ->concat($integrationUrls)
            ->concat($retailerUrls)
            ->concat($hardwareUrls)
            ->concat($newsUrls)
            ->values();

        $xml = view('sitemap', ['urls' => $urls])->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    /**
     * Resolve a CMS-driven catalog to items that have a usable slug.
     *
     * @param  array<int, array<string, mixed>>  $default
     * @return Collection<int, array<string, mixed>>
     */
    private function catalog(string $key, array $default): Collection
    {
        return collect(content_list($key, $default))
            ->filter(fn ($item) => is_array($item) && filled($item['slug'] ?? null))
            ->values();
    }
}
