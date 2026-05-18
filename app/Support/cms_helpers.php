<?php

use App\Models\Page;

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

        return Page::getValue($slug, $path, $default);
    }
}

if (! function_exists('content_list')) {
    /**
     * Same as content() but always returns an array (handy for @foreach loops with a fallback).
     */
    function content_list(string $key, array $default = []): array
    {
        $value = content($key, $default);

        return is_array($value) ? $value : $default;
    }
}
