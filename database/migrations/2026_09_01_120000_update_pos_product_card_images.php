<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Point the POS device cards at the new register and tablet artwork.
 *
 * PagesSeeder only backfills missing keys and these two `products.cards.*.image`
 * values already exist, so existing environments need a direct page-content
 * rewrite rather than a reseed.
 */
return new class extends Migration
{
    private const CARD_IMAGES = [
        0 => 'assets/POS Register.png',
        1 => 'assets/Tablet POS.png',
    ];

    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'pos')->first();

        if (! $page) {
            return;
        }

        $updates = [
            'draft_content' => $this->withProductCardImages($page->draft_content),
            'updated_at' => now(),
        ];

        if ($page->published_content) {
            $updates['published_content'] = $this->withProductCardImages($page->published_content);
        }

        DB::table('pages')->where('id', $page->id)->update($updates);

        Cache::forget('page:pos');
    }

    public function down(): void
    {
        // Rollback would overwrite whatever the CMS holds later; re-point the
        // device cards in the admin if a different image is needed.
    }

    private function withProductCardImages(?string $json): ?string
    {
        if (! $json) {
            return $json;
        }

        $content = json_decode($json, true);

        if (! is_array($content) || ! isset($content['products']['cards']) || ! is_array($content['products']['cards'])) {
            return $json;
        }

        foreach (self::CARD_IMAGES as $index => $image) {
            if (! isset($content['products']['cards'][$index]) || ! is_array($content['products']['cards'][$index])) {
                continue;
            }

            $content['products']['cards'][$index]['image'] = $image;
        }

        return json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
};
