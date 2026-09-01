<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Point the home "SkelApp Terminal" product card at the handheld terminal shot.
 *
 * The card sold "the whole counter, in your pocket" with Mobilehomeview.png — a
 * phone home screen, which is the same device story the hero already tells. The
 * new artwork is the handheld POS with a receipt coming out of it, which is what
 * the copy is actually about.
 *
 * PagesSeeder only backfills missing keys and this card already carries an
 * `image`, so the seeder will not update it; hence a migration.
 */
return new class extends Migration
{
    /** Index of the Terminal card in home.products.cards. */
    private const CARD_INDEX = 1;

    private const CARD_IMAGE = 'assets/Pos System 04.png';

    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'home')->first();

        if (! $page) {
            return;
        }

        $updates = [
            'draft_content' => $this->withCardImage($page->draft_content),
            'updated_at' => now(),
        ];

        if ($page->published_content) {
            $updates['published_content'] = $this->withCardImage($page->published_content);
        }

        DB::table('pages')->where('id', $page->id)->update($updates);

        Cache::forget('page:home');
    }

    public function down(): void
    {
        // The previous artwork is still on disk, but restoring the path here
        // would fight whatever the CMS holds by then. Re-point it in the admin.
    }

    /**
     * Rewrite only the `image` key of the Terminal card, leaving its eyebrow,
     * title, copy, link and every other card untouched.
     */
    private function withCardImage(?string $json): ?string
    {
        if (! $json) {
            return $json;
        }

        $content = json_decode($json, true);

        if (! is_array($content) || ! isset($content['products']['cards'][self::CARD_INDEX]) || ! is_array($content['products']['cards'][self::CARD_INDEX])) {
            return $json;
        }

        $content['products']['cards'][self::CARD_INDEX]['image'] = self::CARD_IMAGE;

        return json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
};
