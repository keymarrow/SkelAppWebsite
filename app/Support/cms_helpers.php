<?php

use App\Models\Page;
use Illuminate\Support\Arr;

if (! function_exists('cms_preview_content')) {
    function cms_preview_content(string $slug): ?array
    {
        if (! app()->bound('cms.preview.payloads')) {
            return null;
        }

        $payloads = app('cms.preview.payloads');

        if (! is_array($payloads) || ! array_key_exists($slug, $payloads) || ! is_array($payloads[$slug])) {
            return null;
        }

        return $payloads[$slug];
    }
}

if (! function_exists('content')) {
    /**
     * Get a published content value from the CMS.
     *
     * @param  string  $key  Dot-path: "<page>.<field>" e.g. "home.hero.title" or "pricing.plans.0.label".
     * @param  mixed  $default  Fallback returned when the value isn't set (or the page has never been published).
     */
    function content(string $key, mixed $default = null): mixed
    {
        if (! str_contains($key, '.')) {
            return $default;
        }

        [$slug, $path] = explode('.', $key, 2);

        $previewContent = cms_preview_content($slug);

        if ($previewContent !== null) {
            return Arr::get($previewContent, $path, $default);
        }

        return Page::getValue($slug, $path, $default);
    }
}

if (! function_exists('cms_image')) {
    /**
     * Resolve a stored CMS image value to a usable URL:
     *  - absolute (http/https) → returned as-is
     *  - leading slash (/storage/..., /assets/...) → returned as-is
     *  - bare filename or relative path → prefixed via asset() (e.g. "assets/HeroImage.webp")
     */
    function cms_image(mixed $value, ?string $fallback = null): string
    {
        $url = is_string($value) && $value !== '' ? $value : ($fallback ?? '');
        if ($url === '') {
            return '';
        }
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://') || str_starts_with($url, '//')) {
            return $url;
        }
        if (str_starts_with($url, '/')) {
            return $url;
        }
        // Bare filename (no slash) with an image extension → assume it lives in /assets/.
        // This handles legacy seeded values like "speed.svg" or "boutique.png".
        if (! str_contains($url, '/') && preg_match('/\.(png|jpe?g|webp|gif|svg)$/i', $url)) {
            return asset('assets/'.$url);
        }
        return asset($url);
    }
}

if (! function_exists('content_image')) {
    /**
     * Convenience: read a content key and resolve as an image URL in one call.
     */
    function content_image(string $key, ?string $fallback = null): string
    {
        return cms_image(content($key), $fallback);
    }
}

if (! function_exists('content_list')) {
    /**
     * Same as content() but always returns an array (handy for @foreach loops with a fallback).
     */
    function content_list(string $key, array $default = []): array
    {
        $value = content($key, $default);

        if (! is_array($value)) {
            return $default;
        }

        if ($value !== [] && array_is_list($value)) {
            $normalized = collect($value)->map(function ($item) {
                if (is_array($item) && count($item) === 1 && array_key_exists('value', $item)) {
                    return $item['value'];
                }

                return $item;
            })->all();

            return $normalized;
        }

        return $value;
    }
}
