<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class Page extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'draft_content',
        'published_content',
        'published_at',
        'published_by_id',
        'draft_updated_by_id',
    ];

    protected function casts(): array
    {
        return [
            'draft_content' => 'array',
            'published_content' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function publishedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'published_by_id');
    }

    public function draftUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'draft_updated_by_id');
    }

    public static function bySlug(string $slug): ?self
    {
        return Cache::remember(self::cacheKey($slug), 3600, function () use ($slug) {
            return static::query()->where('slug', $slug)->first();
        });
    }

    public static function publishedContent(string $slug): array
    {
        $page = static::bySlug($slug);

        return $page?->published_content ?? [];
    }

    public static function cacheKey(string $slug): string
    {
        return "page:{$slug}";
    }

    public function forgetCache(): void
    {
        Cache::forget(self::cacheKey($this->slug));
    }

    public function effectiveDraft(): array
    {
        return $this->draft_content ?? $this->published_content ?? [];
    }

    public function hasUnpublishedChanges(): bool
    {
        if (! $this->draft_content) {
            return false;
        }

        return $this->draft_content !== ($this->published_content ?? []);
    }

    public static function getValue(string $slug, string $path, mixed $default = null): mixed
    {
        $content = static::publishedContent($slug);

        return Arr::get($content, $path, $default);
    }
}
