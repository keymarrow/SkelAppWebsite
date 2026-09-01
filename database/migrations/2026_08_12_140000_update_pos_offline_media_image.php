<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Point the POS offline band at the blackout photograph.
 *
 * "Keep selling when the power or network drops" was illustrated with
 * CASHFLOW.png, a dashboard screenshot that says nothing about a blackout. The
 * replacement is a shopkeeper working by tablet in an unlit shop, which is what
 * the band and its caption ("Selling straight through a blackout.") describe.
 *
 * The upload arrived as a 1.9 MB PNG; it ships as WebP at 88 KB, same pixels.
 *
 * PagesSeeder only backfills missing keys and this key is already set, so the
 * seeder will not update it; hence a migration.
 */
return new class extends Migration
{
    private const MEDIA_IMAGE = 'assets/electric-off.webp';

    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'pos')->first();

        if (! $page) {
            return;
        }

        $updates = [
            'draft_content' => $this->withMediaImage($page->draft_content),
            'updated_at' => now(),
        ];

        if ($page->published_content) {
            $updates['published_content'] = $this->withMediaImage($page->published_content);
        }

        DB::table('pages')->where('id', $page->id)->update($updates);

        Cache::forget('page:pos');
    }

    public function down(): void
    {
        // The previous artwork is still on disk, but restoring the path here
        // would fight whatever the CMS holds by then. Re-point it in the admin.
    }

    /**
     * Rewrite only `offline.media_image`, leaving the online band and every
     * other key on the page untouched.
     */
    private function withMediaImage(?string $json): ?string
    {
        if (! $json) {
            return $json;
        }

        $content = json_decode($json, true);

        if (! is_array($content) || ! isset($content['offline']) || ! is_array($content['offline'])) {
            return $json;
        }

        $content['offline']['media_image'] = self::MEDIA_IMAGE;

        return json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
};
