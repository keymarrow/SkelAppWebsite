<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const OLD_IMAGE = 'assets/Mobilehomeview.png';
    private const NEW_IMAGE = 'assets/onlinestoreshopper.png';

    public function up(): void
    {
        $page = DB::table('pages')->where('slug', 'pos')->first();

        if (! $page) {
            return;
        }

        $updates = [
            'draft_content' => $this->replaceImage($page->draft_content),
            'updated_at' => now(),
        ];

        if ($page->published_content) {
            $updates['published_content'] = $this->replaceImage($page->published_content);
        }

        DB::table('pages')->where('id', $page->id)->update($updates);

        Cache::forget('page:pos');
    }

    public function down(): void
    {
        // Do not replace newer CMS edits during a rollback.
    }

    private function replaceImage(?string $content): ?string
    {
        return $content === null ? null : str_replace(self::OLD_IMAGE, self::NEW_IMAGE, $content);
    }
};
