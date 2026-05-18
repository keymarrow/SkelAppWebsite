<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use App\Services\NewsRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class NewsPostPreviewController extends Controller
{
    public function create(Request $request, NewsRepository $newsRepository): View
    {
        $post = $this->previewPostFromSession(
            $request->session()->get($this->createSessionKey()),
            $this->basePreviewPost()
        );

        return $this->renderPreview($post, $newsRepository);
    }

    public function syncCreate(Request $request): JsonResponse
    {
        $request->session()->put(
            $this->createSessionKey(),
            $this->previewPayloadFromRequest($request, $this->basePreviewPost())
        );

        return response()->json([
            'ok' => true,
            'updated_at' => now()->toIso8601String(),
        ]);
    }

    public function show(Request $request, NewsPost $post, NewsRepository $newsRepository): View
    {
        $previewPost = $this->previewPostFromSession(
            $request->session()->get($this->postSessionKey($post)),
            $post
        );

        return $this->renderPreview($previewPost, $newsRepository, $post->slug);
    }

    public function sync(Request $request, NewsPost $post): JsonResponse
    {
        $request->session()->put(
            $this->postSessionKey($post),
            $this->previewPayloadFromRequest($request, $post)
        );

        return response()->json([
            'ok' => true,
            'updated_at' => now()->toIso8601String(),
        ]);
    }

    public static function createSessionKey(): string
    {
        return 'news_preview.create';
    }

    public static function postSessionKey(NewsPost $post): string
    {
        return 'news_preview.post.'.$post->getKey();
    }

    private function renderPreview(NewsPost $post, NewsRepository $newsRepository, ?string $currentSlug = null): View
    {
        $article = $post->toPublicArray();
        $article['slug'] = $article['slug'] !== '' ? $article['slug'] : 'preview-post';
        $article['date'] = $article['date'] ?: now()->toDateString();
        $article['sections'] = $article['sections'] ?? [];

        $related = $currentSlug
            ? $newsRepository->relatedArticles($currentSlug)
            : $newsRepository->publicArticles()->take(3)->values();

        return view('news.show', [
            'article' => $article,
            'related' => $related instanceof Collection ? $related : collect($related),
            'canonicalUrl' => null,
            'metaDescription' => $article['meta_description'] ?? $article['summary'],
        ]);
    }

    private function previewPostFromSession(?array $payload, NewsPost $fallback): NewsPost
    {
        $payload = is_array($payload) ? $payload : $this->previewPayloadFromPost($fallback);

        $post = new NewsPost($payload);
        $post->exists = $fallback->exists;
        $post->created_at = $fallback->created_at ?? now();
        $post->updated_at = $fallback->updated_at ?? now();

        if (isset($payload['published_at']) && $payload['published_at'] instanceof Carbon) {
            $post->published_at = $payload['published_at'];
        }

        return $post;
    }

    private function previewPayloadFromRequest(Request $request, NewsPost $fallback): array
    {
        $payload = $this->previewPayloadFromPost($fallback);

        $payload['title'] = trim((string) $request->input('title', $payload['title']));
        $payload['slug'] = trim((string) $request->input('slug', $payload['slug']));
        $payload['summary'] = trim((string) $request->input('summary', $payload['summary']));
        $payload['body_markdown'] = (string) $request->input('body_markdown', $payload['body_markdown']);
        $payload['categories'] = $this->parseCategories((string) $request->input('categories', implode(', ', $payload['categories'])));
        $payload['card_label'] = trim((string) $request->input('card_label', $payload['card_label']));
        $payload['card_color_start'] = (string) $request->input('card_color_start', $payload['card_color_start']);
        $payload['card_color_mid'] = (string) $request->input('card_color_mid', $payload['card_color_mid']);
        $payload['card_color_end'] = (string) $request->input('card_color_end', $payload['card_color_end']);
        $payload['meta_title'] = trim((string) $request->input('meta_title', $payload['meta_title'] ?? '')) ?: null;
        $payload['meta_description'] = trim((string) $request->input('meta_description', $payload['meta_description'] ?? '')) ?: null;
        $payload['read_time_override'] = trim((string) $request->input('read_time_override', $payload['read_time_override'] ?? '')) ?: null;
        $payload['is_published'] = $request->boolean('is_published');
        $payload['published_at'] = $this->parsePublishedAt($request->input('published_at')) ?? $payload['published_at'];

        if ($request->boolean('remove_featured_image')) {
            $payload['featured_image_url'] = null;
        } elseif ($request->filled('featured_image_existing')) {
            $payload['featured_image_url'] = (string) $request->input('featured_image_existing');
        }

        return $payload;
    }

    private function previewPayloadFromPost(NewsPost $post): array
    {
        return [
            'title' => $post->title ?? '',
            'slug' => $post->slug ?? '',
            'summary' => $post->summary ?? '',
            'featured_image_url' => $post->featured_image_url,
            'body_markdown' => $post->body_markdown ?? '',
            'categories' => array_values($post->categories ?? []),
            'card_label' => $post->card_label ?? 'News',
            'card_color_start' => $post->card_color_start ?? '#eaf7ff',
            'card_color_mid' => $post->card_color_mid ?? '#7fc8ff',
            'card_color_end' => $post->card_color_end ?? '#6e82ff',
            'meta_title' => $post->meta_title,
            'meta_description' => $post->meta_description,
            'read_time_override' => $post->read_time_override,
            'is_published' => (bool) $post->is_published,
            'published_at' => $post->published_at,
        ];
    }

    private function basePreviewPost(): NewsPost
    {
        $post = new NewsPost([
            'title' => 'Untitled post',
            'slug' => 'preview-post',
            'summary' => 'Write a summary and it will appear here.',
            'body_markdown' => '',
            'card_label' => 'News',
            'card_color_start' => '#eaf7ff',
            'card_color_mid' => '#7fc8ff',
            'card_color_end' => '#6e82ff',
            'categories' => [],
            'is_published' => false,
        ]);

        $post->created_at = now();

        return $post;
    }

    private function parseCategories(string $value): array
    {
        return collect(preg_split('/[\r\n,]+/', $value) ?: [])
            ->map(fn (string $category) => trim($category))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function parsePublishedAt(mixed $value): ?Carbon
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }
}
