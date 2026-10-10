<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Point the App Store badge at the live SkelApp Point of Sale listing.
 *
 * `app_badges.apple_url` has shipped as a placeholder ("https://apps.apple.com/app/your-app-id",
 * then "#") since the global page was first seeded. PagesSeeder only backfills
 * missing keys and this key is already set, so the seeder will not update it;
 * hence a migration, same as the POS image/card fixes above.
 */
return new class extends Migration
{
    private const APPLE_URL = 'https://apps.apple.com/app/skelapp-point-of-sale/id6783924476';

    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'global')->first();

        if (! $page) {
            return;
        }

        $updates = [
            'draft_content' => $this->withAppleUrl($page->draft_content),
            'updated_at' => now(),
        ];

        if ($page->published_content) {
            $updates['published_content'] = $this->withAppleUrl($page->published_content);
        }

        DB::table('pages')->where('id', $page->id)->update($updates);

        Cache::forget('page:global');
    }

    public function down(): void
    {
        // The previous value was a placeholder, so there is nothing worth
        // restoring here.
    }

    /**
     * Rewrite only `app_badges.apple_url`, leaving every other key untouched.
     */
    private function withAppleUrl(?string $json): ?string
    {
        if (! $json) {
            return $json;
        }

        $content = json_decode($json, true);

        if (! is_array($content) || ! isset($content['app_badges']) || ! is_array($content['app_badges'])) {
            return $json;
        }

        $content['app_badges']['apple_url'] = self::APPLE_URL;

        return json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
};
