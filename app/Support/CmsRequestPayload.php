<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

final class CmsRequestPayload
{
    /**
     * Repeater rows submit as sequential dot-paths (foo.0.x, foo.1.x, …). When a
     * row is removed the admin JS re-indexes the survivors, so the payload always
     * holds a contiguous 0..N-1 set — but Arr::set only overwrites those indices
     * and leaves any higher, now-stale index from the previous draft in place
     * (so removing a row appears to do nothing). Drop every integer-keyed row
     * under a submitted repeater that was not present in the payload.
     *
     * @param  array<string, mixed>  $content       the merged content
     * @param  array<int, string>    $submittedPaths the dot-paths that were posted
     */
    public static function pruneRemovedRepeaterRows(array $content, array $submittedPaths): array
    {
        // parent dot-path => set of submitted integer indices
        $submitted = [];

        foreach ($submittedPaths as $path) {
            $segments = explode('.', (string) $path);
            $prefix = [];

            foreach ($segments as $segment) {
                if ($segment !== '' && ctype_digit($segment)) {
                    $submitted[implode('.', $prefix)][(int) $segment] = true;
                }
                $prefix[] = $segment;
            }
        }

        // Deepest parents first, so pruning an inner list can't disturb an outer one.
        uksort($submitted, fn ($a, $b) => substr_count($b, '.') <=> substr_count($a, '.'));

        foreach ($submitted as $parent => $keep) {
            $array = $parent === '' ? $content : Arr::get($content, $parent);

            if (! is_array($array)) {
                continue;
            }

            foreach (array_keys($array) as $key) {
                if ((is_int($key) || ctype_digit((string) $key)) && ! isset($keep[(int) $key])) {
                    unset($array[$key]);
                }
            }

            // Re-index only genuine (all-numeric) lists so gaps close to 0..N-1.
            $allNumeric = true;
            foreach (array_keys($array) as $key) {
                if (! is_int($key) && ! ctype_digit((string) $key)) {
                    $allNumeric = false;
                    break;
                }
            }
            if ($allNumeric) {
                $array = array_values($array);
            }

            if ($parent === '') {
                $content = $array;
            } else {
                Arr::set($content, $parent, $array);
            }
        }

        return $content;
    }

    public static function content(Request $request): array
    {
        $payload = $request->input('content_payload');

        if (is_string($payload) && trim($payload) !== '') {
            $decoded = json_decode($payload, true);

            if (is_array($decoded)) {
                return $decoded;
            }
        }

        $content = $request->input('content', []);

        return is_array($content) ? $content : [];
    }

    public static function removeImagePaths(Request $request): array
    {
        $payload = $request->input('remove_image_payload');

        if (is_string($payload) && trim($payload) !== '') {
            $decoded = json_decode($payload, true);

            if (is_array($decoded)) {
                return array_values(array_filter(array_map(function ($path) {
                    return is_scalar($path) ? trim((string) $path) : '';
                }, $decoded), fn ($path) => $path !== ''));
            }
        }

        return array_values(array_filter(array_map(function ($path) {
            return trim((string) $path);
        }, (array) $request->input('remove_image', [])), fn ($path) => $path !== ''));
    }
}
