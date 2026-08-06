<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Finish removing the repeated "SkelApp" suffix from page titles.
 *
 * An earlier migration covered the nine top-level pages, but the child pages
 * kept theirs and were still rendering "Sage integration - SkelApp",
 * "Grocery Store POS - SkelApp" and so on in search results. Nineteen pages in
 * total: six integrations, seven retailer types, four hardware products, and
 * the affiliate, application, terms and privacy pages.
 *
 * The six integration pages all build their title from one shared suffix key,
 * so a single value covers them.
 *
 * `/why-skelapp` and the news index keep their names on purpose: both are brand
 * pages where dropping "SkelApp" would read strangely.
 *
 * PagesSeeder only backfills missing keys, so these values need replacing here.
 * Individual keys are patched rather than whole content blobs being swapped, so
 * edits made through the admin CMS survive. List entries are matched on their
 * `slug` rather than position, so a reordered list cannot be mis-patched.
 */
return new class extends Migration
{
    /** page slug => [key path => value], for simple top-level values. */
    private const SCALAR_PATCHES = [
        'integration' => ['meta' => ['title_suffix' => 'POS Integration']],
        'affiliate' => ['meta' => ['title' => 'Affiliate Program']],
        'affiliate-apply' => ['meta' => ['title' => 'Affiliate Application']],
        'terms' => ['meta' => ['title' => 'Terms of Service']],
        'privacy' => ['meta' => ['title' => 'Privacy Policy']],
    ];

    /** page slug => [list key, [item slug => new meta_title]] */
    private const LIST_PATCHES = [
        'retailers' => ['pages', [
            'boutique' => 'Boutique POS',
            'cosmetics' => 'Cosmetics Store POS',
            'grocery' => 'Grocery Store POS',
            'hardware-shop' => 'Hardware Shop POS',
            'kitchenware' => 'Kitchenware Store POS',
            'autospare' => 'AutoSpare Shop POS',
            'tech-shop' => 'Tech Shop POS',
        ]],
        'hardware' => ['products', [
            'skel-register' => 'Skel Register POS Hardware',
            'skel-terminal' => 'Skel Terminal POS Hardware',
            'skel-tab' => 'Skel Tab POS Hardware',
            'skel-phone' => 'Skel Phone POS Hardware',
        ]],
    ];

    public function up(): void
    {
        $slugs = array_unique(array_merge(
            array_keys(self::SCALAR_PATCHES),
            array_keys(self::LIST_PATCHES),
        ));

        foreach ($slugs as $slug) {
            $page = DB::table('pages')->where('slug', $slug)->first();

            if (! $page) {
                continue;
            }

            $updates = [
                'draft_content' => $this->patch($page->draft_content, $slug),
                'updated_at' => now(),
            ];

            if ($page->published_content) {
                $updates['published_content'] = $this->patch($page->published_content, $slug);
            }

            DB::table('pages')->where('id', $page->id)->update($updates);

            Cache::forget("page:{$slug}");
        }
    }

    public function down(): void
    {
        // Cosmetic; re-adding the suffix would undo the intent.
    }

    private function patch(?string $json, string $slug): ?string
    {
        if (! $json) {
            return $json;
        }

        $content = json_decode($json, true);

        if (! is_array($content)) {
            return $json;
        }

        foreach (self::SCALAR_PATCHES[$slug] ?? [] as $group => $pairs) {
            foreach ($pairs as $key => $value) {
                // Only replace a key that already exists; never invent one.
                if (isset($content[$group]) && is_array($content[$group]) && array_key_exists($key, $content[$group])) {
                    $content[$group][$key] = $value;
                }
            }
        }

        if (isset(self::LIST_PATCHES[$slug])) {
            [$listKey, $titles] = self::LIST_PATCHES[$slug];

            if (isset($content[$listKey]) && is_array($content[$listKey])) {
                foreach ($content[$listKey] as $i => $item) {
                    if (! is_array($item) || ! isset($item['slug'], $titles[$item['slug']])) {
                        continue;
                    }

                    if (array_key_exists('meta_title', $item)) {
                        $content[$listKey][$i]['meta_title'] = $titles[$item['slug']];
                    }
                }
            }
        }

        return json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
};
