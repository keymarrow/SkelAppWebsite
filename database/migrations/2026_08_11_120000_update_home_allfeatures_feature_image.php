<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Point the home "all features" centre card at the handheld POS render.
 *
 * techshop.webp was a shop-interior photograph standing in for "Point of sale".
 * The replacement is a cut-out render of a hand holding the app, so the card now
 * carries the tint canvas the affordable cards use and shows the product itself.
 *
 * The upload arrived as a 1935x3246 PNG at 2.7 MB; it ships as a 940px-wide
 * WebP at 81 KB, which is the width the card needs on a 2x screen.
 *
 * PagesSeeder only backfills missing keys and this key is already set, so the
 * seeder will not update it; hence a migration.
 */
return new class extends Migration
{
    private const FEATURE_IMAGE = 'assets/handfree-pos.webp';

    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'home')->first();

        if (! $page) {
            return;
        }

        $updates = [
            'draft_content' => $this->withFeatureImage($page->draft_content),
            'updated_at' => now(),
        ];

        if ($page->published_content) {
            $updates['published_content'] = $this->withFeatureImage($page->published_content);
        }

        DB::table('pages')->where('id', $page->id)->update($updates);

        Cache::forget('page:home');
    }

    public function down(): void
    {
        // The previous photograph is still on disk, but restoring the path here
        // would fight whatever the CMS holds by then. Re-point it in the admin.
    }

    /**
     * Rewrite only `allfeatures.feature_image`, leaving the surrounding label,
     * tagline and side cards untouched.
     */
    private function withFeatureImage(?string $json): ?string
    {
        if (! $json) {
            return $json;
        }

        $content = json_decode($json, true);

        if (! is_array($content) || ! isset($content['allfeatures']) || ! is_array($content['allfeatures'])) {
            return $json;
        }

        $content['allfeatures']['feature_image'] = self::FEATURE_IMAGE;

        return json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
};
