<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Refresh the Pricing page content from its local snapshot.
 *
 * The pricing page moved from a single flat rate (a features checklist beside a
 * billing-period radio picker) to three tiers — WingaMode / BiasharaPlus /
 * Build Your Own — and the hero was restructured around a benefit row.
 *
 * PagesSeeder only backfills MISSING keys, so on an already-seeded environment it
 * would leave the old hero headline in place and, worse, keep a page header that
 * reads "No tiers. No hidden fees." above three tiers. It also cannot drop the
 * now-unrendered `features` / `plans` / `cta` keys. This migration replaces the
 * pricing page's draft_content and published_content with the reviewed snapshot
 * in database/cms-content/pricing.json.
 */
return new class extends Migration
{
    public function up(): void
    {
        $file = database_path('cms-content/pricing.json');

        if (! is_file($file)) {
            return;
        }

        $payload = json_decode((string) file_get_contents($file), true);

        if (! is_array($payload) || ($payload['slug'] ?? null) !== 'pricing' || ! is_array($payload['content'] ?? null)) {
            return;
        }

        $title = $payload['title'] ?? 'Pricing';
        $content = json_encode($payload['content'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $page = DB::table('pages')->where('slug', 'pricing')->first();

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
                'slug' => 'pricing',
                'title' => $title,
                'draft_content' => $content,
                'published_content' => $content,
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Cache::forget('page:pricing');
    }

    public function down(): void
    {
        // Content mirror; no meaningful rollback.
    }
};
