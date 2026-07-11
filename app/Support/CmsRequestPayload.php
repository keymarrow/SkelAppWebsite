<?php

namespace App\Support;

use Illuminate\Http\Request;

final class CmsRequestPayload
{
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
