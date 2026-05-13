<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/terms-of-service', 'terms')->name('terms.show');
Route::view('/faq', 'faq')->name('faq.show');

Route::get('/contact', function () {
    return view('contact');
})->name('contact.show');

Route::post('/contact', function (Request $request) {
    $data = $request->validate([
        'first_name' => ['required', 'string', 'max:100'],
        'last_name'  => ['required', 'string', 'max:100'],
        'email'      => ['required', 'email', 'max:255'],
        'phone'      => ['required', 'string', 'max:30'],
        'company'    => ['required', 'string', 'max:200'],
    ]);

    $body = implode("\n", [
        "New demo request from the SkelApp website.",
        "",
        "Name:     {$data['first_name']} {$data['last_name']}",
        "Email:    {$data['email']}",
        "Phone:    {$data['phone']}",
        "Company:  {$data['company']}",
    ]);

    Mail::raw($body, function ($message) use ($data) {
        $message
            ->to('pos@skelapp.tz')
            ->replyTo($data['email'], "{$data['first_name']} {$data['last_name']}")
            ->subject("Demo Request – {$data['first_name']} {$data['last_name']} ({$data['company']})");
    });

    return redirect()->route('contact.show')
        ->with('success', "Thank you, {$data['first_name']}! We've received your request and will be in touch shortly.");
})->name('contact.send');

Route::get('/news', function (Request $request) {
    $articles = collect(config('news.articles', []));

    $availableCategories = $articles
        ->flatMap(fn (array $article) => $article['categories'])
        ->unique()
        ->values();

    $categoryOrder = collect([
        'Product',
        'Company',
        'Operations',
        'Growth',
        'Retail Guide',
    ]);

    $categories = $categoryOrder
        ->filter(fn (string $category) => $availableCategories->contains($category))
        ->merge(
            $availableCategories->reject(fn (string $category) => $categoryOrder->contains($category))
        )
        ->push('All')
        ->values()
        ->all();

    $years = $articles
        ->map(fn (array $article) => substr($article['date'], 0, 4))
        ->unique()
        ->sortDesc()
        ->values()
        ->all();

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
            fn (array $article) => str_starts_with($article['date'], $selectedYear)
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
    ]);
})->name('news.index');

Route::get('/news/{slug}', function (string $slug) {
    $articles = collect(config('news.articles', []));
    $article = $articles->firstWhere('slug', $slug);

    abort_unless($article, 404);

    $related = $articles
        ->reject(fn (array $item) => $item['slug'] === $slug)
        ->sortByDesc('date')
        ->take(3)
        ->values();

    return view('news.show', [
        'article' => $article,
        'related' => $related,
    ]);
})->name('news.show');
