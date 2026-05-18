<?php

namespace App\Support;

final class CmsPreview
{
    private const SCREENS = [
        'home' => ['label' => 'Home page', 'view' => 'welcome'],
        'pricing' => ['label' => 'Pricing page', 'view' => 'pricing'],
        'faq' => ['label' => 'FAQ page', 'view' => 'faq'],
        'contact' => ['label' => 'Contact page', 'view' => 'contact'],
        'terms' => ['label' => 'Terms page', 'view' => 'terms'],
    ];

    private const TARGETS = [
        'home' => ['home'],
        'pricing' => ['pricing'],
        'faq' => ['faq', 'home'],
        'contact' => ['contact'],
        'terms' => ['terms'],
        'global' => ['home', 'pricing', 'faq', 'contact', 'terms'],
    ];

    public static function targetsFor(string $editedSlug): array
    {
        $targets = [];

        foreach (self::TARGETS[$editedSlug] ?? ['home'] as $target) {
            if (isset(self::SCREENS[$target])) {
                $targets[$target] = self::SCREENS[$target];
            }
        }

        return $targets;
    }

    public static function defaultTarget(string $editedSlug): string
    {
        return array_key_first(self::targetsFor($editedSlug)) ?? 'home';
    }

    public static function normalizeTarget(string $editedSlug, ?string $requestedTarget): string
    {
        $targets = self::targetsFor($editedSlug);

        if ($requestedTarget && array_key_exists($requestedTarget, $targets)) {
            return $requestedTarget;
        }

        return array_key_first($targets) ?? 'home';
    }

    public static function viewForTarget(string $target): string
    {
        return self::SCREENS[$target]['view'] ?? self::SCREENS['home']['view'];
    }

    public static function sessionKey(string $editedSlug): string
    {
        return "cms_preview.pages.{$editedSlug}";
    }
}
