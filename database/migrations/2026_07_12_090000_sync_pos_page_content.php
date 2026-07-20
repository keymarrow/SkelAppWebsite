<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Refresh the Point of Sale page content from its local snapshot.
 *
 * The POS page was restructured — all-in-one POS cards, an offline-mode band,
 * a 6-tab feature showcase, a devices section (Skel Register + Skel Tablet), an
 * online-ordering band, a "fully in sync" section and a retailers carousel.
 *
 * PagesSeeder only backfills MISSING keys, so it cannot expand the already-seeded
 * `features` list (4 → 6) or add the reordered sections to environments that were
 * seeded before. This migration replaces the POS page's draft_content and
 * published_content with the reviewed snapshot in database/cms-content/pos.json.
 */
return new class extends Migration
{
    public function up(): void
    {
        $file = database_path('cms-content/pos.json');

        if (! is_file($file)) {
            return;
        }

        $payload = json_decode((string) file_get_contents($file), true);

        if (! is_array($payload) || ($payload['slug'] ?? null) !== 'pos' || ! is_array($payload['content'] ?? null)) {
            return;
        }

        $title = $payload['title'] ?? 'Point of Sale';
        $content = json_encode($payload['content'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $page = DB::table('pages')->where('slug', 'pos')->first();

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
                'slug' => 'pos',
                'title' => $title,
                'draft_content' => $content,
                'published_content' => $content,
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Cache::forget('page:pos');
    }

    public function down(): void
    {
        // Content mirror; no meaningful rollback.
    }
};
