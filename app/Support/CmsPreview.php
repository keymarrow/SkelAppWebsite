<?php

namespace App\Support;

final class CmsPreview
{
    private const SCREENS = [
        'home' => ['label' => 'Home page', 'view' => 'welcome'],
        'features' => ['label' => 'Features page', 'view' => 'features'],
        'pos' => ['label' => 'Point of Sale page', 'view' => 'point-of-sale'],
        'hardware' => ['label' => 'Hardware page', 'view' => 'hardware'],
        'hardware-product' => ['label' => 'Hardware product page', 'view' => 'hardware-product'],
        'retailers' => ['label' => 'Retailers page', 'view' => 'retailers-index'],
        'retailer' => ['label' => 'Retailer detail page', 'view' => 'retailer'],
        'integrations' => ['label' => 'Integrations page', 'view' => 'integrations'],
        'integration' => ['label' => 'Integration detail page', 'view' => 'integration'],
        'pricing' => ['label' => 'Pricing page', 'view' => 'pricing'],
        'affiliate' => ['label' => 'Affiliate page', 'view' => 'affiliate'],
        'affiliate-apply' => ['label' => 'Affiliate form page', 'view' => 'affiliate-apply'],
        'faq' => ['label' => 'FAQ page', 'view' => 'faq'],
        'contact' => ['label' => 'Contact page', 'view' => 'contact'],
        'why' => ['label' => 'Why SkelApp page', 'view' => 'why'],
        'terms' => ['label' => 'Terms page', 'view' => 'terms'],
        'privacy' => ['label' => 'Privacy page', 'view' => 'privacy'],
    ];

    private const TARGETS = [
        'home' => ['home'],
        'features' => ['features'],
        'pos' => ['pos'],
        'hardware' => ['hardware', 'hardware-product'],
        'retailers' => ['retailers', 'retailer'],
        'integrations' => ['integrations'],
        'integration' => ['integration'],
        'pricing' => ['pricing'],
        'affiliate' => ['affiliate'],
        'affiliate-apply' => ['affiliate-apply'],
        'faq' => ['faq', 'home'],
        'contact' => ['contact'],
        'why' => ['why'],
        'terms' => ['terms'],
        'privacy' => ['privacy'],
        'global' => ['home', 'features', 'pos', 'hardware', 'hardware-product', 'retailers', 'retailer', 'integrations', 'integration', 'pricing', 'affiliate', 'affiliate-apply', 'faq', 'contact', 'why', 'terms', 'privacy'],
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
