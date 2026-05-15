<?php

namespace App\Services;

use App\Models\NewsPost;
use App\Models\NewsPostSlugRedirect;
use App\Support\LegacyNews;
use Illuminate\Support\Collection;

class NewsRepository
{
    public function publicArticles(): Collection
    {
        $databaseArticles = NewsPost::query()
            ->published()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get()
            ->map(fn (NewsPost $post) => $post->toPublicArray());

        $databaseSlugs = $databaseArticles->pluck('slug')->all();

        $legacyArticles = LegacyNews::articles()
            ->reject(fn (array $article) => in_array($article['slug'], $databaseSlugs, true));

        return $databaseArticles
            ->concat($legacyArticles)
            ->sortByDesc('date')
            ->values();
    }

    public function findPublicArticle(string $slug): ?array
    {
        $post = NewsPost::query()
            ->published()
            ->where('slug', $slug)
            ->first();

        if ($post) {
            return $post->toPublicArray();
        }

        return LegacyNews::articles()->firstWhere('slug', $slug);
    }

    public function redirectSlugTarget(string $slug): ?string
    {
        $redirect = NewsPostSlugRedirect::query()
            ->where('slug', $slug)
            ->with(['post' => fn ($query) => $query->published()])
            ->first();

        return $redirect?->post?->slug;
    }

    public function relatedArticles(string $currentSlug, int $limit = 3): Collection
    {
        return $this->publicArticles()
            ->reject(fn (array $article) => $article['slug'] === $currentSlug)
            ->take($limit)
            ->values();
    }

    public function categories(Collection $articles): array
    {
        $availableCategories = $articles
            ->flatMap(fn (array $article) => $article['categories'] ?? [])
            ->unique()
            ->values();

        $categoryOrder = collect([
            'Product',
            'Company',
            'Operations',
            'Growth',
            'Retail Guide',
        ]);

        return $categoryOrder
            ->filter(fn (string $category) => $availableCategories->contains($category))
            ->merge(
                $availableCategories->reject(fn (string $category) => $categoryOrder->contains($category))
            )
            ->push('All')
            ->values()
            ->all();
    }

    public function years(Collection $articles): array
    {
        return $articles
            ->pluck('date')
            ->filter()
            ->map(fn (string $date) => substr($date, 0, 4))
            ->unique()
            ->sortDesc()
            ->values()
            ->all();
    }
}
