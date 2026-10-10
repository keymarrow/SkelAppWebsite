<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Trim the POS "the sale is just the start" band to three cards.
 *
 * The band shipped as four dense cards and now renders as three padded editorial
 * panels — a large rounded photo, then the copy — so the fourth card
 * ("Your team, accountable.") is dropped to leave the row at three across.
 *
 * PagesSeeder only backfills missing keys, and `sync.cards` already exists with
 * four entries, so existing environments need a direct page-content rewrite.
 */
return new class extends Migration
{
    private const KEEP = 3;

    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'pos')->first();

        if (! $page) {
            return;
        }

        $updates = [
            'draft_content' => $this->withThreeSyncCards($page->draft_content),
            'updated_at' => now(),
        ];

        if ($page->published_content) {
            $updates['published_content'] = $this->withThreeSyncCards($page->published_content);
        }

        DB::table('pages')->where('id', $page->id)->update($updates);

        Cache::forget('page:pos');
    }

    public function down(): void
    {
        // The dropped card's copy is in PagesSeeder if it is ever wanted back, but
        // restoring it here would fight whatever the CMS holds by then.
    }

    /**
     * Keep the first three sync cards as they are, dropping any beyond them.
     */
    private function withThreeSyncCards(?string $json): ?string
    {
        if (! $json) {
            return $json;
        }

        $content = json_decode($json, true);

        if (! is_array($content) || ! isset($content['sync']['cards']) || ! is_array($content['sync']['cards'])) {
            return $json;
        }

        $cards = array_values($content['sync']['cards']);

        if (count($cards) <= self::KEEP) {
            return $json;
        }

        $content['sync']['cards'] = array_slice($cards, 0, self::KEEP);

        return json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
};
