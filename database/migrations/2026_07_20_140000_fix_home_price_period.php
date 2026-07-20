<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Restore the homepage pricing-summary "price period" line.
 *
 * home.pricing_summary.price_period was stored as an empty string (an earlier
 * content sync from the local snapshot blanked it), so the "two months free…"
 * line under the price rendered empty on the homepage. PagesSeeder is
 * backfill-only and won't overwrite a present-but-empty key, so this migration
 * sets the value directly.
 *
 * It edits ONLY content.pricing_summary.price_period inside the existing
 * draft/published payloads, leaving everything else on the home page untouched.
 */
return new class extends Migration
{
    private string $value = '— two months free · or TZS 15,000 month-to-month. Cancel anytime.';

    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'home')->first();

        if (! $page) {
            return;
        }

        DB::table('pages')->where('id', $page->id)->update([
            'draft_content' => $this->withValue($page->draft_content),
            'published_content' => $this->withValue($page->published_content),
            'updated_at' => now(),
        ]);

        Cache::forget('page:home');
    }

    public function down(): void
    {
        // Content mirror; no meaningful rollback.
    }

    /**
     * Return the JSON payload with content.pricing_summary.price_period set,
     * but only when it is currently missing or blank (so we never clobber an
     * edit an admin may have made). Passes through unchanged otherwise.
     */
    private function withValue(?string $json): ?string
    {
        if ($json === null || $json === '') {
            return $json;
        }

        $data = json_decode($json, true);

        if (! is_array($data) || ! isset($data['pricing_summary']) || ! is_array($data['pricing_summary'])) {
            return $json;
        }

        $current = $data['pricing_summary']['price_period'] ?? '';

        if (is_string($current) && $current !== '') {
            return $json;
        }

        $data['pricing_summary']['price_period'] = $this->value;

        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
};
