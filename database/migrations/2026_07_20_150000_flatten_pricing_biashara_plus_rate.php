<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Flatten the pricing page's paid plan to a single annual rate.
 *
 * The monthly / yearly billing toggle was removed, so the BiasharaPlus tier now
 * shows one flat rate: TZS 15,000 /month, billed annually (previously TZS 12,500
 * yearly ↔ TZS 15,000 monthly behind the toggle). PagesSeeder is backfill-only
 * and won't rewrite an existing tier, so this migration updates the stored
 * values directly.
 *
 * It edits ONLY the BiasharaPlus tier's price + price_note and the pricing meta
 * description (which quoted the old rate) inside the existing draft/published
 * payloads; every other tier and field is left untouched.
 */
return new class extends Migration
{
    private string $price = 'TZS 15,000';
    private string $note = 'billed annually';
    private string $metaDescription = 'SkelApp pricing — start free, or TZS 15,000 per month billed annually. Every plan sells offline. No hidden costs.';

    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'pricing')->first();

        if (! $page) {
            return;
        }

        DB::table('pages')->where('id', $page->id)->update([
            'draft_content' => $this->applyRate($page->draft_content),
            'published_content' => $this->applyRate($page->published_content),
            'updated_at' => now(),
        ]);

        Cache::forget('page:pricing');
    }

    public function down(): void
    {
        // Content mirror; no meaningful rollback.
    }

    /**
     * Return the JSON payload with the BiasharaPlus tier's price + note and the
     * meta description set to the flat annual rate. Passes through unchanged
     * when the payload isn't a content object.
     */
    private function applyRate(?string $json): ?string
    {
        if ($json === null || $json === '') {
            return $json;
        }

        $data = json_decode($json, true);

        if (! is_array($data)) {
            return $json;
        }

        if (isset($data['tiers']) && is_array($data['tiers'])) {
            foreach ($data['tiers'] as &$tier) {
                if (is_array($tier) && ($tier['name'] ?? null) === 'BiasharaPlus') {
                    $tier['price'] = $this->price;
                    $tier['price_note'] = $this->note;
                }
            }
            unset($tier);
        }

        if (isset($data['meta']) && is_array($data['meta']) && array_key_exists('description', $data['meta'])) {
            $data['meta']['description'] = $this->metaDescription;
        }

        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
};
