<?php

namespace App\Http\Controllers;

use App\Services\NewsRepository;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __construct(
        private readonly NewsRepository $newsRepository,
    ) {
    }

    public function __invoke(): Response
    {
        $staticUrls = collect([
            ['loc' => url('/'), 'lastmod' => now()->toDateString()],
            ['loc' => route('news.index'), 'lastmod' => now()->toDateString()],
            ['loc' => route('faq.show'), 'lastmod' => now()->toDateString()],
            ['loc' => route('contact.show'), 'lastmod' => now()->toDateString()],
            ['loc' => route('terms.show'), 'lastmod' => now()->toDateString()],
        ]);

        $newsUrls = $this->newsRepository->publicArticles()
            ->map(fn (array $article) => [
                'loc' => route('news.show', $article['slug']),
                'lastmod' => $article['date'] ?? now()->toDateString(),
            ]);

        $xml = view('sitemap', [
            'urls' => $staticUrls->concat($newsUrls),
        ])->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}
