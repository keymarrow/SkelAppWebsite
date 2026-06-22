<?php

namespace App\Support;

use Illuminate\Support\Str;

final class CmsCatalogs
{
    public static function integrationItems(): array
    {
        $items = config('integrations.items', []);

        return collect($items)->map(function (array $item, string $slug): array {
            return [
                'slug' => $slug,
                'name' => $item['name'] ?? Str::headline(str_replace('-', ' ', $slug)),
                'category' => $item['category'] ?? 'Integration',
                'logo' => $item['logo'] ?? '',
                'color' => $item['color'] ?? '#4AAD77',
                'summary' => $item['summary'] ?? '',
                'detail_title' => $item['detail_title'] ?? '',
                'detail_copy' => $item['detail_copy'] ?? '',
                'website_url' => $item['website_url'] ?? '',
                'hero_image' => $item['hero_image'] ?? '',
                'features_text' => self::titleBodyLines($item['features'] ?? []),
                'faq_text' => self::questionAnswerLines($item['faq'] ?? []),
            ];
        })->values()->all();
    }

    public static function retailerPages(): array
    {
        $retailers = config('retailers.retailers', []);
        $defaultWhyItems = self::retailerWhyDefaults();

        return collect($retailers)->map(function (array $retailer, string $slug) use ($defaultWhyItems): array {
            $detailFeatures = array_values($retailer['detail_features'] ?? []);
            $why = $retailer['why'] ?? [];
            $whyItems = $why['items'] ?? $defaultWhyItems;
            $faqItems = $retailer['faq']['items'] ?? [];
            $spotlight = $retailer['spotlight'] ?? [];
            $cta = $retailer['cta'] ?? [];

            $row = [
                'slug' => $slug,
                'name' => $retailer['name'] ?? Str::headline(str_replace('-', ' ', $slug)),
                'meta_title' => ($retailer['name'] ?? Str::headline(str_replace('-', ' ', $slug))).' POS – SkelApp',
                'meta_description' => $retailer['hero']['subtitle'] ?? '',
                'hero_eyebrow' => $retailer['hero']['eyebrow'] ?? '',
                'hero_title' => $retailer['hero']['title'] ?? '',
                'hero_subtitle' => $retailer['hero']['subtitle'] ?? '',
                'hero_image' => $retailer['hero']['image'] ?? '',
                'hero_primary_label' => $retailer['hero']['primary_label'] ?? 'Get a demo',
                'hero_secondary_label' => $retailer['hero']['secondary_label'] ?? 'See pricing',
                'detail_title' => $retailer['detail_header']['title'] ?? '',
                'detail_subtitle' => $retailer['detail_header']['subtitle'] ?? '',
                'why_title' => $why['title'] ?? 'Made to be switched on and forgotten about',
                'why_subtitle' => $why['subtitle'] ?? 'Reliable, locally supported hardware that just works — set it up once and get on with selling.',
                'why_items_text' => self::titleBodyLines($whyItems),
                'spotlight_headline' => $spotlight['headline'] ?? '',
                'spotlight_eyebrow' => $spotlight['eyebrow'] ?? '',
                'spotlight_name' => $spotlight['name'] ?? '',
                'spotlight_copy' => $spotlight['copy'] ?? '',
                'spotlight_image' => $spotlight['image'] ?? '',
                'spotlight_points_text' => self::lines($spotlight['points'] ?? []),
                'spotlight_product' => $spotlight['product'] ?? '',
                'spotlight_button_label' => 'Explore',
                'faq_title' => $retailer['faq']['title'] ?? '',
                'faq_title_accent' => $retailer['faq']['title_accent'] ?? '',
                'faq_items_text' => self::questionAnswerLines($faqItems),
                'cta_title' => $cta['title'] ?? '',
                'cta_note' => $cta['note'] ?? '',
                'cta_label' => $cta['label'] ?? 'Start for free',
                'cta_image' => $cta['image'] ?? '',
            ];

            foreach (range(1, 4) as $index) {
                $feature = $detailFeatures[$index - 1] ?? [];
                $row["feature_{$index}_title"] = $feature['name'] ?? '';
                $row["feature_{$index}_body"] = $feature['body'] ?? '';
                $row["feature_{$index}_image"] = $feature['image'] ?? '';
            }

            return $row;
        })->values()->all();
    }

    public static function hardwareProducts(): array
    {
        $products = config('hardware_products.products', []);

        return collect($products)->map(function (array $product, string $slug): array {
            $features = array_values($product['features'] ?? []);
            $specs = array_values($product['specs'] ?? []);
            $name = $product['name'] ?? Str::headline(str_replace('-', ' ', $slug));

            $row = [
                'slug' => $slug,
                'name' => $name,
                'meta_title' => $name.' – SkelApp Hardware',
                'meta_description' => $product['hero']['subtitle'] ?? '',
                'hero_badge' => 'NEW',
                'hero_primary_label' => 'Get a demo',
                'hero_secondary_label' => 'Learn more',
                'hero_subtitle' => $product['hero']['subtitle'] ?? '',
                'hero_image' => $product['hero']['image'] ?? '',
                'intro_title' => $product['intro_title'] ?? '',
                'intro_title_accent' => $product['intro_title_accent'] ?? '',
                'detail_title_prefix' => $name.',',
                'detail_title_accent' => 'up close',
                'detail_subtitle' => $product['intro_subtitle'] ?? '',
                'specs_title_prefix' => $name,
                'specs_title_accent' => 'specifications',
                'specs_subtitle' => 'Every detail that matters — screen, power, payments and connectivity, all in one place.',
            ];

            foreach (range(1, 4) as $index) {
                $feature = $features[$index - 1] ?? [];
                $row["feature_{$index}_title"] = $feature['name'] ?? '';
                $row["feature_{$index}_body"] = $feature['body'] ?? '';
                $row["feature_{$index}_image"] = $feature['image'] ?? '';
            }

            foreach (range(1, 5) as $index) {
                $spec = $specs[$index - 1] ?? [];
                $row["spec_{$index}_label"] = $spec['label'] ?? '';
                $row["spec_{$index}_rows_text"] = self::keyValueLines($spec['rows'] ?? []);
            }

            return $row;
        })->values()->all();
    }

    public static function retailerWhyDefaults(): array
    {
        return [
            ['title' => 'Plug-and-play', 'body' => 'Open the box, sign in to SkelApp, and start selling in minutes — no IT person required.'],
            ['title' => 'Offline-ready', 'body' => 'Power cut or weak network? Keep ringing up sales. Everything syncs the moment you reconnect.'],
            ['title' => 'Built for here', 'body' => 'Chosen and tested for Tanzanian retail — dust, heat, voltage swings and long days.'],
            ['title' => 'Local support', 'body' => 'On-the-ground help in Dar es Salaam, WhatsApp support and a warranty on every device.'],
        ];
    }

    public static function lines(array $items): string
    {
        return implode("\n", array_values(array_filter(array_map(
            fn ($item) => trim((string) $item),
            $items
        ))));
    }

    public static function titleBodyLines(array $items, string $titleKey = 'title', string $bodyKey = 'body'): string
    {
        return implode("\n", array_values(array_filter(array_map(function (array $item) use ($titleKey, $bodyKey): string {
            $title = trim((string) ($item[$titleKey] ?? ''));
            $body = trim((string) ($item[$bodyKey] ?? ''));

            if ($title === '' && $body === '') {
                return '';
            }

            return $title.' | '.$body;
        }, $items))));
    }

    public static function questionAnswerLines(array $items): string
    {
        return self::titleBodyLines($items, 'q', 'a');
    }

    public static function keyValueLines(array $rows, string $keyField = 'k', string $valueField = 'v'): string
    {
        return implode("\n", array_values(array_filter(array_map(function (array $row) use ($keyField, $valueField): string {
            $key = trim((string) ($row[$keyField] ?? ''));
            $value = trim((string) ($row[$valueField] ?? ''));

            if ($key === '' && $value === '') {
                return '';
            }

            return $key.' | '.$value;
        }, $rows))));
    }

    public static function parseTitleBodyLines(?string $text, string $titleKey = 'title', string $bodyKey = 'body'): array
    {
        if (! is_string($text) || trim($text) === '') {
            return [];
        }

        return collect(preg_split('/\r?\n/', $text))
            ->map(function ($line) use ($titleKey, $bodyKey) {
                $line = trim((string) $line);
                if ($line === '') {
                    return null;
                }

                [$title, $body] = array_pad(array_map('trim', explode('|', $line, 2)), 2, '');

                if ($title === '' && $body === '') {
                    return null;
                }

                if ($body === '') {
                    return [$titleKey => '', $bodyKey => $title];
                }

                return [$titleKey => $title, $bodyKey => $body];
            })
            ->filter()
            ->values()
            ->all();
    }

    public static function parseQuestionAnswerLines(?string $text): array
    {
        return self::parseTitleBodyLines($text, 'q', 'a');
    }

    public static function parseKeyValueLines(?string $text, string $keyField = 'k', string $valueField = 'v'): array
    {
        if (! is_string($text) || trim($text) === '') {
            return [];
        }

        return collect(preg_split('/\r?\n/', $text))
            ->map(function ($line) use ($keyField, $valueField) {
                $line = trim((string) $line);
                if ($line === '') {
                    return null;
                }

                [$key, $value] = array_pad(array_map('trim', explode('|', $line, 2)), 2, '');

                if ($key === '' && $value === '') {
                    return null;
                }

                if ($value === '') {
                    return [$keyField => '', $valueField => $key];
                }

                return [$keyField => $key, $valueField => $value];
            })
            ->filter()
            ->values()
            ->all();
    }
}
