<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsPostRequest;
use App\Models\NewsPost;
use App\Models\NewsPostSlugRedirect;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        $payload['featured_image_url'] = $this->storeUploadedImage($request->file('featured_image'), 'news/covers');

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
        $nextFeaturedImageUrl = $post->featured_image_url;

        if ($request->boolean('remove_featured_image')) {
            $this->deleteManagedImage($post->featured_image_url);
            $nextFeaturedImageUrl = null;
        }

        if ($request->hasFile('featured_image')) {
            $this->deleteManagedImage($post->featured_image_url);
            $nextFeaturedImageUrl = $this->storeUploadedImage($request->file('featured_image'), 'news/covers');
        }

        $payload['featured_image_url'] = $nextFeaturedImageUrl;

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
        $this->deleteManagedImage($post->featured_image_url);
        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('status', 'Post deleted.');
    }

    public function uploadContentImage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ]);

        /** @var UploadedFile $image */
        $image = $validated['image'];
        $url = $this->storeUploadedImage($image, 'news/body');
        $alt = Str::of($image->getClientOriginalName())
            ->beforeLast('.')
            ->replace(['-', '_'], ' ')
            ->trim()
            ->title()
            ->toString();

        return response()->json([
            'url' => $url,
            'alt' => $alt !== '' ? $alt : 'News image',
        ]);
    }

    private function storeUploadedImage(?UploadedFile $image, string $directory): ?string
    {
        if (! $image) {
            return null;
        }

        $extension = strtolower($image->getClientOriginalExtension() ?: 'jpg');
        $filename = now()->format('YmdHis').'-'.Str::random(20).'.'.$extension;
        $path = $image->storeAs($directory, $filename, 'public');

        return '/storage/'.$path;
    }

    private function deleteManagedImage(?string $publicPath): void
    {
        if (blank($publicPath)) {
            return;
        }

        $normalizedPath = ltrim((string) $publicPath, '/');

        if (! Str::startsWith($normalizedPath, 'storage/news/')) {
            return;
        }

        Storage::disk('public')->delete(Str::after($normalizedPath, 'storage/'));
    }
}
