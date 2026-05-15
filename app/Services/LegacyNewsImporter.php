<?php

namespace App\Services;

use App\Models\NewsPost;
use App\Support\LegacyNews;

class LegacyNewsImporter
{
    public function import(bool $overwrite = false): int
    {
        $count = 0;

        LegacyNews::articles()->each(function (array $article) use ($overwrite, &$count): void {
            $post = NewsPost::query()->firstWhere('slug', $article['slug']);

            if ($post && ! $overwrite) {
                return;
            }

            $post ??= new NewsPost();

            $post->fill([
                'title' => $article['title'],
                'slug' => $article['slug'],
                'summary' => $article['summary'],
                'body_markdown' => LegacyNews::toMarkdown($article),
                'categories' => $article['categories'],
                'card_label' => $article['card_label'],
                'card_color_start' => $article['card_colors'][0],
                'card_color_mid' => $article['card_colors'][1],
                'card_color_end' => $article['card_colors'][2],
                'meta_title' => $article['title'],
                'meta_description' => $article['summary'],
                'read_time_override' => $article['read_time'],
                'is_published' => true,
                'published_at' => "{$article['date']} 09:00:00",
            ]);

            $post->save();
            $count++;
        });

        return $count;
    }
}
