<?php

use Database\Seeders\PagesSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->updatePage('home', $this->homeSource());
    }

    public function down(): void
    {
        // Intentionally left blank: homepage CMS copy is rewritten in place.
    }

    private function updatePage(string $slug, array $source): void
    {
        $page = DB::table('pages')->where('slug', $slug)->first();

        if (! $page) {
            return;
        }

        $draft = $this->decode($page->draft_content) ?: $this->decode($page->published_content);
        $published = $this->decode($page->published_content);

        DB::table('pages')
            ->where('id', $page->id)
            ->update([
                'draft_content' => $this->encode($this->merge($draft, $source)),
                'published_content' => $this->encode($this->merge($published, $source)),
                'published_at' => $page->published_at ?: now(),
                'updated_at' => now(),
            ]);

        Cache::forget("page:{$slug}");
    }

    private function decode(?string $json): array
    {
        if (! is_string($json) || trim($json) === '') {
            return [];
        }

        $decoded = json_decode($json, true);

        return is_array($decoded) ? $decoded : [];
    }

    private function encode(array $content): string
    {
        return json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function merge(mixed $current, mixed $source): mixed
    {
        if (is_array($source)) {
            if (array_is_list($source)) {
                $result = [];

                foreach ($source as $index => $item) {
                    $existing = is_array($current) && array_key_exists($index, $current) ? $current[$index] : null;
                    $result[$index] = $this->merge($existing, $item);
                }

                return $result;
            }

            $result = is_array($current) && ! array_is_list($current) ? $current : [];

            foreach ($source as $key => $value) {
                $existing = $result[$key] ?? null;
                $result[$key] = $this->merge($existing, $value);
            }

            return $result;
        }

        if (is_array($current) && array_key_exists('text', $current)) {
            $current['text'] = $source;

            return $current;
        }

        return $source;
    }

    private function homeSource(): array
    {
        return \Closure::bind(
            fn () => $this->homeContent(),
            new PagesSeeder(),
            PagesSeeder::class
        )();
    }
};
