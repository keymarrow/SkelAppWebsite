<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Update the About / Why page hero headline.
 *
 * The About hero was rebuilt on the home hero markup (full-bleed .hero). Its
 * headline changes from "Transforming Tanzania's small businesses…" to a short
 * two-line title. PagesSeeder only backfills MISSING keys, so on an already-
 * seeded environment it would leave the old headline in place — this migration
 * updates the stored value directly.
 *
 * It edits ONLY content.hero.title inside the existing draft/published payloads
 * (rather than replacing the whole page), so no other why-page content is
 * touched.
 */
return new class extends Migration
{
    private string $title = 'About SkelApp<br>and why we built';

    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'why')->first();

        if (! $page) {
            return;
        }

        DB::table('pages')->where('id', $page->id)->update([
            'draft_content' => $this->withTitle($page->draft_content),
            'published_content' => $this->withTitle($page->published_content),
            'updated_at' => now(),
        ]);

        Cache::forget('page:why');
    }

    public function down(): void
    {
        // Content mirror; no meaningful rollback.
    }

    /**
     * Return the JSON payload with content.hero.title set, leaving everything
     * else untouched. Passes through unchanged when the payload has no hero.
     */
    private function withTitle(?string $json): ?string
    {
        if ($json === null || $json === '') {
            return $json;
        }

        $data = json_decode($json, true);

        if (! is_array($data) || ! isset($data['hero']) || ! is_array($data['hero'])) {
            return $json;
        }

        $data['hero']['title'] = $this->title;

        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
};
