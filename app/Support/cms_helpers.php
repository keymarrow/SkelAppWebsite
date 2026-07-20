<?php

use App\Models\Page;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;

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

if (! function_exists('cms_url')) {
    /**
     * Resolve CMS-managed URLs, including auth shortcuts editors can type as
     * short relative paths from any URL field.
     */
    function cms_url(mixed $value, ?string $fallback = null): string
    {
        $url = is_string($value) ? trim($value) : '';

        if ($url === '') {
            $url = $fallback ?? '';
        }

        if ($url === '') {
            return '';
        }

        if (preg_match('~^/login(?P<suffix>(?:[/?#].*)?)$~i', $url, $matches)) {
            return 'https://web.skelapp.tz/login'.($matches['suffix'] ?? '');
        }

        if (preg_match('~^/signup(?P<suffix>(?:[/?#].*)?)$~i', $url, $matches)) {
            return 'https://web.skelapp.tz/register'.($matches['suffix'] ?? '');
        }

        return $url;
    }
}

if (! function_exists('cms_key_uses_url_rules')) {
    function cms_key_uses_url_rules(string $path): bool
    {
        if ($path === '') {
            return false;
        }

        $leaf = strtolower((string) preg_replace('/^.*\./', '', $path));

        return $leaf === 'url' || str_ends_with($leaf, '_url');
    }
}

if (! function_exists('cms_normalize_content_value')) {
    /**
     * Recursively normalize CMS values so nested repeater URL fields get the
     * same handling as standalone URL inputs.
     */
    function cms_normalize_content_value(mixed $value, string $path = ''): mixed
    {
        if (is_array($value)) {
            $normalized = [];

            foreach ($value as $key => $item) {
                $childPath = is_string($key)
                    ? ($path === '' ? $key : $path.'.'.$key)
                    : $path;

                $normalized[$key] = cms_normalize_content_value($item, $childPath);
            }

            return $normalized;
        }

        if (is_string($value) && cms_key_uses_url_rules($path)) {
            return cms_url($value);
        }

        return $value;
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
            return cms_normalize_content_value(Arr::get($previewContent, $path, $default), $path);
        }

        return cms_normalize_content_value(Page::getValue($slug, $path, $default), $path);
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

if (! function_exists('content_text')) {
    /**
     * Read a text value. Supports both plain strings and typography-wrapped
     * objects like {"text": "...", "typography": {...}}. The fallback may also
     * be either form — handy when iterating a repeater row whose value is now
     * an object after typography was enabled for that field.
     */
    function content_text(string $key, mixed $fallback = null): string
    {
        $normalize = function ($value): ?string {
            if (is_array($value)) {
                $text = $value['text'] ?? null;
                return is_string($text) && $text !== '' ? $text : null;
            }
            return is_string($value) && $value !== '' ? $value : null;
        };

        $raw = content($key, null);
        $fromKey = $normalize($raw);
        if ($fromKey !== null) {
            return $fromKey;
        }
        $fromFallback = $normalize($fallback);
        return $fromFallback ?? '';
    }
}

if (! function_exists('cms_text_html')) {
    /**
     * Escape arbitrary text, but allow admins to force line breaks with
     * literal <br> tags or actual newline characters.
     */
    function cms_text_html(mixed $value): HtmlString
    {
        if (is_array($value)) {
            $value = $value['text'] ?? '';
        }

        $text = is_scalar($value) ? (string) $value : '';
        $html = e($text);
        $html = preg_replace('/&lt;br\s*\/?&gt;/i', '<br>', $html) ?? $html;
        $html = nl2br($html);

        return new HtmlString($html);
    }
}

if (! function_exists('content_text_html')) {
    /**
     * Same as content_text(), but returns safe HTML with support for <br> and
     * newline characters in CMS-managed text.
     */
    function content_text_html(string $key, mixed $fallback = null): HtmlString
    {
        return cms_text_html(content_text($key, $fallback));
    }
}

if (! function_exists('content_typography_vars')) {
    /**
     * Build the CSS-variable string from a typography config object stored at $key.
     * Returns "" when no overrides are set.
     */
    function content_typography_vars(string $key): string
    {
        $raw = content($key, null);
        if (! is_array($raw) || empty($raw['typography']) || ! is_array($raw['typography'])) {
            return '';
        }
        $map = [
            'font_size' => 'fs',
            'font_family' => 'ff',
            'font_weight' => 'fw',
            'font_style' => 'fst',
            'line_height' => 'lh',
            'letter_spacing' => 'ls',
            'text_align' => 'ta',
            'color' => 'color',
        ];
        $vars = [];
        foreach (['desktop', 'tablet', 'mobile'] as $viewport) {
            $bucket = $raw['typography'][$viewport] ?? null;
            if (! is_array($bucket)) {
                continue;
            }
            foreach ($map as $field => $short) {
                $value = $bucket[$field] ?? null;
                if ($value === null || $value === '') {
                    continue;
                }
                if ($field === 'font_size' && is_numeric($value)) {
                    $value = $value.'px';
                }
                $vars[] = '--cms-'.$short.'-'.$viewport.':'.$value;
            }
        }
        return implode(';', $vars);
    }
}

if (! function_exists('content_typography_class')) {
    /**
     * Returns "cms-typo" when typography overrides exist at $key, otherwise empty.
     * Drop it into existing class lists in Blade: class="hero-title {{ content_typography_class(...) }}"
     */
    function content_typography_class(string $key): string
    {
        return content_typography_vars($key) === '' ? '' : 'cms-typo';
    }
}

if (! function_exists('content_typography_html')) {
    /**
     * Emits the full `class="..."` (optionally) `style="..."` attribute pair for
     * a CMS-styled element, with proper escaping. Returns "" when neither base
     * class nor typography overrides exist. Use with `{!! ... !!}`:
     *
     *   <h1 {!! content_typography_html('home.hero.title', 'hero-title') !!}>...</h1>
     *
     * Avoids the empty `style=""` attributes that confuse IDE CSS analyzers when
     * typography isn't set.
     */
    function content_typography_html(string $key, string $baseClass = ''): string
    {
        $typoClass = content_typography_class($key);
        $vars = content_typography_vars($key);
        $classList = trim($baseClass.' '.$typoClass);

        $parts = [];
        if ($classList !== '') {
            $parts[] = 'class="'.htmlspecialchars($classList, ENT_QUOTES).'"';
        }
        if ($vars !== '') {
            $parts[] = 'style="'.htmlspecialchars($vars, ENT_QUOTES).'"';
        }

        return implode(' ', $parts);
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
