<?php

namespace App\Support;

use Illuminate\Support\Collection;

class LegacyNews
{
    public static function articles(): Collection
    {
        return collect(config('news.articles', []))
            ->map(function (array $article): array {
                $article['source'] = 'legacy';

                return $article;
            });
    }

    public static function toMarkdown(array $article): string
    {
        return collect($article['sections'] ?? [])
            ->map(function (array $section): string {
                $heading = filled($section['heading'] ?? null)
                    ? '## '.$section['heading']
                    : null;

                $paragraphs = collect($section['paragraphs'] ?? [])
                    ->filter()
                    ->implode("\n\n");

                return collect([$heading, $paragraphs])
                    ->filter()
                    ->implode("\n\n");
            })
            ->filter()
            ->implode("\n\n");
    }
}
