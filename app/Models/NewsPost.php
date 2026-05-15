<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class NewsPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'featured_image_url',
        'body_markdown',
        'categories',
        'card_label',
        'card_color_start',
        'card_color_mid',
        'card_color_end',
        'meta_title',
        'meta_description',
        'read_time_override',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'categories' => 'array',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function slugRedirects(): HasMany
    {
        return $this->hasMany(NewsPostSlugRedirect::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function bodyHtml(): string
    {
        return (string) Str::markdown($this->body_markdown ?? '', [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    public function readTimeDisplay(): string
    {
        if ($this->read_time_override) {
            return $this->read_time_override;
        }

        $plainText = trim(strip_tags($this->bodyHtml()));
        $wordCount = max(1, str_word_count($plainText));
        $seconds = (int) ceil(($wordCount / 215) * 60);
        $minutes = intdiv($seconds, 60);
        $remainingSeconds = $seconds % 60;

        return sprintf('%d:%02d', max(1, $minutes), $remainingSeconds);
    }

    public function toPublicArray(): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'summary' => $this->summary,
            'featured_image_url' => $this->featured_image_url,
            'date' => optional($this->published_at)->toDateString() ?? $this->created_at?->toDateString(),
            'read_time' => $this->readTimeDisplay(),
            'categories' => array_values($this->categories ?? []),
            'card_label' => $this->card_label,
            'card_colors' => [
                $this->card_color_start,
                $this->card_color_mid,
                $this->card_color_end,
            ],
            'body_html' => $this->bodyHtml(),
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'source' => 'database',
        ];
    }
}
