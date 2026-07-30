<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Drop the repeated "SkelApp" suffix from page and news titles.
 *
 * Nine pages rendered as "Pricing – SkelApp", "Features – SkelApp" and so on,
 * which repeats the brand in the single highest-value SEO field on every page
 * while saying nothing about what the page offers. Google derives the site name
 * from the domain and Organization schema for search results regardless, so the
 * suffix was paying for itself twice over.
 *
 * The reclaimed space carries keywords instead. Page titles are patched key by
 * key rather than replacing draft_content/published_content wholesale, so any
 * edits made through the admin CMS since the last deploy survive.
 *
 * `/why-skelapp` and the news index are deliberately untouched: both are brand
 * pages where removing the name would read strangely.
 */
return new class extends Migration
{
    private const PAGE_TITLES = [
        'home' => 'Point of Sale System in Tanzania',
        'pos' => 'POS Software in Tanzania',
        'features' => 'Modern POS Features',
        'pricing' => 'POS System Pricing',
        'hardware' => 'POS Hardware & Accessories in Tanzania',
        'integrations' => 'Point Of Sale Integrations',
        'retailers' => 'Retail POS Solutions',
        'faq' => 'POS System Common Questions',
        'contact' => 'Contact Us',
    ];

    public function up(): void
    {
        foreach (self::PAGE_TITLES as $slug => $title) {
            $page = DB::table('pages')->where('slug', $slug)->first();

            if (! $page) {
                continue;
            }

            $updates = [
                'draft_content' => $this->withMetaTitle($page->draft_content, $title),
                'updated_at' => now(),
            ];

            // Only touch the published copy if one exists; publishing an unpublished
            // draft as a side effect of a title change would be a surprise.
            if ($page->published_content) {
                $updates['published_content'] = $this->withMetaTitle($page->published_content, $title);
            }

            DB::table('pages')->where('id', $page->id)->update($updates);

            Cache::forget("page:{$slug}");
        }

        // news/show.blade.php no longer appends a suffix, so meta_title is now
        // rendered verbatim and must not carry the brand itself.
        foreach (DB::table('news_posts')->get() as $post) {
            $title = $this->stripBrand($post->title);
            $metaTitle = $this->stripBrand($post->meta_title ?: $post->title);

            if ($title === $post->title && $metaTitle === $post->meta_title) {
                continue;
            }

            DB::table('news_posts')->where('id', $post->id)->update([
                'title' => $title,
                'meta_title' => $metaTitle,
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Cosmetic content change; the previous titles carried no information
        // worth restoring and re-adding the suffix would undo the intent.
    }

    /**
     * Replace content.meta.title inside a JSON column, leaving every other key
     * untouched. Returns the original value if it is not decodable.
     */
    private function withMetaTitle(?string $json, string $title): ?string
    {
        if (! $json) {
            return $json;
        }

        $content = json_decode($json, true);

        if (! is_array($content)) {
            return $json;
        }

        $content['meta'] = is_array($content['meta'] ?? null) ? $content['meta'] : [];
        $content['meta']['title'] = $title;

        return json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function stripBrand(?string $value): string
    {
        $value = (string) $value;

        // Matches " | SkelApp", " – SkelApp", " - SkelApp" and " — SkelApp"
        // only at the end of the string, so "Why SkelApp" style names survive.
        return trim(preg_replace('/\s*[|\x{2013}\x{2014}-]\s*SkelApp(?:\s+News)?\s*$/u', '', $value));
    }
};
