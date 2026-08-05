<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Point the home "affordable" cards at the new artwork.
 *
 * The uploads arrived as PNGs with spaces and brackets in their filenames, two
 * of them at ~60 megapixels (7668x7688 and 8514x7809). File size understated
 * the problem: a browser decodes those to hundreds of MB of bitmap, which
 * stalls phones. They are now resized WebP totalling 232 KB instead of 3.5 MB.
 *
 * Images are matched to the card whose copy they illustrate, and the one real
 * photograph goes on the `photo` variant, which renders it as a background
 * under a dark gradient — a UI screenshot there would be unreadable.
 *
 * PagesSeeder only backfills missing keys, so these cards already having an
 * `image` value means the seeder will not update them; hence a migration.
 */
return new class extends Migration
{
    /** Card index => new image path, keyed to the order in home.affordable.cards. */
    private const CARD_IMAGES = [
        // "Know your profit, not just your sales."  (light — plain <img>)
        0 => 'assets/pos-profit-summary.webp',
        // "Get your debts out of the notebook."     (photo — background + gradient)
        1 => 'assets/happy-customer.webp',
        // "However they pay, it counts."            (tint  — plain <img>)
        2 => 'assets/payment-methods.webp',
    ];

    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'home')->first();

        if (! $page) {
            return;
        }

        $updates = [
            'draft_content' => $this->withCardImages($page->draft_content),
            'updated_at' => now(),
        ];

        if ($page->published_content) {
            $updates['published_content'] = $this->withCardImages($page->published_content);
        }

        DB::table('pages')->where('id', $page->id)->update($updates);

        Cache::forget('page:home');
    }

    public function down(): void
    {
        // The previous artwork is still on disk, but restoring paths here would
        // fight whatever the CMS holds by then. Re-point the cards in the admin.
    }

    /**
     * Rewrite only the `image` key of each affordable card, leaving titles,
     * copy, links and every other key untouched.
     */
    private function withCardImages(?string $json): ?string
    {
        if (! $json) {
            return $json;
        }

        $content = json_decode($json, true);

        if (! is_array($content) || ! isset($content['affordable']['cards']) || ! is_array($content['affordable']['cards'])) {
            return $json;
        }

        foreach (self::CARD_IMAGES as $index => $path) {
            if (isset($content['affordable']['cards'][$index]) && is_array($content['affordable']['cards'][$index])) {
                $content['affordable']['cards'][$index]['image'] = $path;
            }
        }

        return json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
};
