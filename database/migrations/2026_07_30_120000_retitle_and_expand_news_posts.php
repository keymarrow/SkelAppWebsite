<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Retitle, re-slug and expand the six legacy news posts.
 *
 * The original posts were near-duplicate superlative pages ("best", "most
 * trusted", "most user-friendly", "number one lead generating") all targeting
 * the same "POS in Tanzania" phrase, at 350-450 words each. That combination
 * reads as a doorway-page cluster and competes with itself for one keyword.
 * Each post now targets a distinct keyword at 1000+ words.
 *
 * This cannot be handled by `news:import-legacy`, which deploy.sh does not run
 * anyway: that importer matches on slug, so against a database still holding the
 * old slugs it would CREATE six duplicates rather than update the originals.
 *
 * Content is read from config/news.php, which is the importer's source of truth
 * and ships in the same commit as this migration.
 */
return new class extends Migration
{
    /**
     * Old public slug => new public slug. The old slug is preserved as a 301
     * redirect so existing links and any indexed URLs keep resolving.
     */
    private const SLUG_MAP = [
        'skelapp-best-pos-in-tanzania' => 'small-business-pos-tanzania',
        'best-point-of-sale-tanzania-skelapp' => 'best-pos-system-tanzania',
        'most-trusted-point-of-sale-tanzania-skelapp' => 'cloud-pos-tanzania',
        'number-one-lead-generating-pos-tanzania-skelapp' => 'retail-point-of-sale-tanzania',
        'most-user-friendly-pos-tanzania-skelapp' => 'mobile-pos-tanzania',
        'skelapp-pos-tanzania-complete-guide-2026' => 'pos-system-east-africa-2026',
    ];

    public function up(): void
    {
        $articles = collect(config('news.articles', []))->keyBy('slug');

        if ($articles->isEmpty()) {
            return;
        }

        foreach (self::SLUG_MAP as $oldSlug => $newSlug) {
            $article = $articles->get($newSlug);

            if (! is_array($article)) {
                continue;
            }

            // Match the old slug first; fall back to the new one so this is safe
            // on an environment that has already been updated by hand.
            $post = DB::table('news_posts')->where('slug', $oldSlug)->first()
                ?? DB::table('news_posts')->where('slug', $newSlug)->first();

            if (! $post) {
                continue;
            }

            // news/show.blade.php only appends the site suffix when the brand is
            // absent, so carrying it here keeps titles free of "| SkelApp | SkelApp News".
            $metaTitle = str_contains($article['title'], 'SkelApp')
                ? $article['title']
                : $article['title'].' | SkelApp';

            DB::table('news_posts')->where('id', $post->id)->update([
                'title' => $article['title'],
                'slug' => $newSlug,
                'summary' => $article['summary'],
                'body_markdown' => $this->toMarkdown($article),
                'categories' => json_encode($article['categories'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'card_label' => $article['card_label'],
                'card_color_start' => $article['card_colors'][0],
                'card_color_mid' => $article['card_colors'][1],
                'card_color_end' => $article['card_colors'][2],
                'meta_title' => $metaTitle,
                'meta_description' => $article['summary'],
                'read_time_override' => $article['read_time'],
                'updated_at' => now(),
            ]);

            // A redirect row whose slug equals a live post slug would shadow the
            // post itself, so clear that before recording the old slug.
            DB::table('news_post_slug_redirects')->where('slug', $newSlug)->delete();

            if ($oldSlug !== $newSlug) {
                $exists = DB::table('news_post_slug_redirects')
                    ->where('slug', $oldSlug)
                    ->exists();

                if (! $exists) {
                    DB::table('news_post_slug_redirects')->insert([
                        'news_post_id' => $post->id,
                        'slug' => $oldSlug,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        // Restoring the old posts would mean re-creating the duplicate-content
        // cluster this migration exists to remove, and the previous 350-word
        // bodies are not retained anywhere. Slug redirects are left in place so
        // the old URLs keep resolving either way.
    }

    /**
     * Mirrors App\Support\LegacyNews::toMarkdown, inlined so the migration does
     * not depend on application code that may change after it has shipped.
     */
    private function toMarkdown(array $article): string
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
};
