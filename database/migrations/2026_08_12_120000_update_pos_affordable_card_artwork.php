<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Point the POS "do it all" cards at the new app screenshots.
 *
 * Each card now shows the screen its copy is about:
 *
 *   "Every shilling in, every shilling out." -> payment-account.webp
 *   "However they pay, it's on record."      -> customer.webp
 *   "Profit and cashflow, at a glance."      -> mobile-dashboard.webp
 *
 * The middle card also moves off the `photo` variant, which renders its image as
 * a background under a dark gradient: these are cut-out phone renders, not
 * photographs, so it takes a tint surface instead. The third card goes the other
 * way, to `light`, so the row reads light / tint / light.
 *
 * The uploads arrived as PNGs, two of them near 40 megapixels (4547x8756 and
 * 4468x8612). File size understates the problem: a browser decodes those to
 * hundreds of MB of bitmap, which stalls phones. They ship as 560px-wide WebP
 * totalling 118 KB rather than 7.4 MB.
 *
 * PagesSeeder only backfills missing keys and these cards already carry an
 * `image` and a `variant`, so a migration applies it.
 */
return new class extends Migration
{
    /** Card index => [image path, variant or null to leave alone]. */
    private const CARDS = [
        0 => ['assets/payment-account.webp', null],
        1 => ['assets/customer.webp', 'tint'],
        2 => ['assets/mobile-dashboard.webp', 'light'],
    ];

    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'pos')->first();

        if (! $page) {
            return;
        }

        $updates = [
            'draft_content' => $this->withCardArtwork($page->draft_content),
            'updated_at' => now(),
        ];

        if ($page->published_content) {
            $updates['published_content'] = $this->withCardArtwork($page->published_content);
        }

        DB::table('pages')->where('id', $page->id)->update($updates);

        Cache::forget('page:pos');
    }

    public function down(): void
    {
        // The previous artwork is still on disk, but restoring paths here would
        // fight whatever the CMS holds by then. Re-point the cards in the admin.
    }

    /**
     * Rewrite only the `image` (and where given, `variant`) of each affordable
     * card, leaving titles, copy and links untouched.
     */
    private function withCardArtwork(?string $json): ?string
    {
        if (! $json) {
            return $json;
        }

        $content = json_decode($json, true);

        if (! is_array($content) || ! isset($content['affordable']['cards']) || ! is_array($content['affordable']['cards'])) {
            return $json;
        }

        foreach (self::CARDS as $index => [$image, $variant]) {
            if (! isset($content['affordable']['cards'][$index]) || ! is_array($content['affordable']['cards'][$index])) {
                continue;
            }

            $content['affordable']['cards'][$index]['image'] = $image;

            if ($variant !== null) {
                $content['affordable']['cards'][$index]['variant'] = $variant;
            }
        }

        return json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
};
