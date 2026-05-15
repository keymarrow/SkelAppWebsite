<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsPostRequest;
use App\Models\NewsPost;
use App\Models\NewsPostSlugRedirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NewsPostController extends Controller
{
    public function index(): View
    {
        return view('admin.news.index', [
            'posts' => NewsPost::query()
                ->latest('updated_at')
                ->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.news.create', [
            'post' => new NewsPost([
                'card_label' => 'News',
                'card_color_start' => '#eaf7ff',
                'card_color_mid' => '#7fc8ff',
                'card_color_end' => '#6e82ff',
                'categories' => [],
            ]),
            'categoriesText' => '',
        ]);
    }

    public function store(NewsPostRequest $request): RedirectResponse
    {
        $payload = $request->payload();

        if ($payload['is_published'] && blank($payload['published_at'])) {
            $payload['published_at'] = now();
        }

        $post = NewsPost::query()->create($payload);

        return redirect()
            ->route('admin.posts.edit', $post)
            ->with('status', 'Post created.');
    }

    public function edit(NewsPost $post): View
    {
        return view('admin.news.edit', [
            'post' => $post,
            'categoriesText' => implode(', ', $post->categories ?? []),
            'redirectSlugs' => $post->slugRedirects()->latest()->pluck('slug'),
        ]);
    }

    public function update(NewsPostRequest $request, NewsPost $post): RedirectResponse
    {
        $originalSlug = $post->slug;
        $payload = $request->payload();

        if ($payload['is_published'] && blank($payload['published_at'])) {
            $payload['published_at'] = now();
        }

        $post->update($payload);

        if ($originalSlug !== $post->slug) {
            NewsPostSlugRedirect::query()->firstOrCreate([
                'slug' => $originalSlug,
            ], [
                'news_post_id' => $post->id,
            ]);
        }

        NewsPostSlugRedirect::query()
            ->where('news_post_id', $post->id)
            ->where('slug', $post->slug)
            ->delete();

        return redirect()
            ->route('admin.posts.edit', $post)
            ->with('status', 'Post updated.');
    }

    public function destroy(NewsPost $post): RedirectResponse
    {
        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('status', 'Post deleted.');
    }
}
