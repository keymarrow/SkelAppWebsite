<?php

namespace App\Http\Controllers;

use App\Services\NewsRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function __construct(
        private readonly NewsRepository $newsRepository,
    ) {
    }

    public function index(Request $request): View
    {
        $articles = $this->newsRepository->publicArticles();
        $categories = $this->newsRepository->categories($articles);
        $years = $this->newsRepository->years($articles);

        $selectedCategory = $request->string('category')->trim()->toString();
        if ($selectedCategory === '' || ! in_array($selectedCategory, $categories, true)) {
            $selectedCategory = 'All';
        }

        $selectedYear = $request->string('year')->trim()->toString();
        if ($selectedYear === '' || ! in_array($selectedYear, $years, true)) {
            $selectedYear = null;
        }

        if ($selectedCategory !== 'All') {
            $articles = $articles->filter(
                fn (array $article) => in_array($selectedCategory, $article['categories'], true)
            );
        }

        if ($selectedYear !== null) {
            $articles = $articles->filter(
                fn (array $article) => str_starts_with((string) $article['date'], $selectedYear)
            );
        }

        $sortOrder = $request->string('sort')->trim()->toString();
        if (! in_array($sortOrder, ['latest', 'oldest', 'title'], true)) {
            $sortOrder = 'latest';
        }

        $articles = (match ($sortOrder) {
            'oldest' => $articles->sortBy('date'),
            'title' => $articles->sortBy('title', SORT_NATURAL | SORT_FLAG_CASE),
            default => $articles->sortByDesc('date'),
        })->values();

        $viewMode = $request->string('view')->trim()->toString();
        if (! in_array($viewMode, ['grid', 'list'], true)) {
            $viewMode = 'grid';
        }

        return view('news.index', [
            'articles' => $articles,
            'categories' => $categories,
            'years' => $years,
            'selectedCategory' => $selectedCategory,
            'selectedYear' => $selectedYear,
            'sortOrder' => $sortOrder,
            'viewMode' => $viewMode,
            'canonicalUrl' => route('news.index'),
            'metaDescription' => 'Latest SkelApp news, product updates, retail guides, and company articles.',
        ]);
    }

    public function show(string $slug): View|RedirectResponse
    {
        $article = $this->newsRepository->findPublicArticle($slug);

        if (! $article) {
            $redirectSlug = $this->newsRepository->redirectSlugTarget($slug);

            if ($redirectSlug) {
                return redirect()->route('news.show', $redirectSlug, 301);
            }

            abort(404);
        }

        $related = $this->newsRepository->relatedArticles($article['slug']);

        return view('news.show', [
            'article' => $article,
            'related' => $related,
            'canonicalUrl' => route('news.show', $article['slug']),
            'metaDescription' => $article['meta_description'] ?? $article['summary'],
        ]);
    }
}
