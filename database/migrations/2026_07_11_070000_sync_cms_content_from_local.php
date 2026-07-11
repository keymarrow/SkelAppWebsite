<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Full CMS content sync: mirror the local `pages` content onto this
 * environment. The authoritative snapshot lives as reviewable JSON in
 * database/cms-content/*.json (one file per page).
 *
 * Local is treated as the source of truth, so each page's draft_content and
 * published_content are replaced with the snapshot. PagesSeeder runs after
 * migrations on deploy and only backfills MISSING default keys, so it will
 * not clobber this content.
 */
return new class extends Migration
{
    public function up(): void
    {
        $dir = database_path('cms-content');

        if (! is_dir($dir)) {
            return;
        }

        foreach (glob($dir.'/*.json') as $file) {
            $payload = json_decode((string) file_get_contents($file), true);

            if (! is_array($payload) || empty($payload['slug']) || ! is_array($payload['content'] ?? null)) {
                continue;
            }

            $slug = (string) $payload['slug'];
            $title = $payload['title'] ?? $slug;
            $content = $this->encode($payload['content']);

            $page = DB::table('pages')->where('slug', $slug)->first();

            if ($page) {
                DB::table('pages')
                    ->where('id', $page->id)
                    ->update([
                        'title' => $title,
                        'draft_content' => $content,
                        'published_content' => $content,
                        'published_at' => $page->published_at ?: now(),
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('pages')->insert([
                    'slug' => $slug,
                    'title' => $title,
                    'draft_content' => $content,
                    'published_content' => $content,
                    'published_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Cache::forget("page:{$slug}");
        }
    }

    public function down(): void
    {
        // Intentionally left blank: this migration mirrors CMS copy in-place
        // and cannot meaningfully restore the previous per-environment content.
    }

    private function encode(array $content): string
    {
        return json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
};
