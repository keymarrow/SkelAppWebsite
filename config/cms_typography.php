<?php

/**
 * Typography presets for the CMS text_typography field.
 *
 * Each preset maps a role (e.g. "h1-hero", "body") to per-viewport defaults.
 * The admin form pre-fills these values when the typography panel is empty
 * so the admin sees the CURRENT site values right away and can tweak from
 * there. Values are taken from the current skel.css rules (rounded).
 *
 * Units:
 *   font_size       — px (string with px ok too)
 *   font_weight     — numeric string (100 … 900)
 *   font_style      — normal | italic
 *   line_height     — unitless number (preferred) or px/em
 *   letter_spacing  — em or px
 *   text_align      — left | center | right | justify
 *   color           — any valid CSS color
 */
return [
    // Hero H1 — the largest headline on each page.
    'h1-hero' => [
        'desktop' => [
            'font_size' => '56',
            'font_weight' => '800',
            'line_height' => '1.05',
            'letter_spacing' => '-0.02em',
        ],
        'tablet' => [
            'font_size' => '44',
        ],
        'mobile' => [
            'font_size' => '32',
        ],
    ],

    // Display H1 — Pricing "One price. Every feature." / FAQ page title.
    'h1-display' => [
        'desktop' => [
            'font_size' => '70',
            'font_weight' => '900',
            'line_height' => '1.06',
            'letter_spacing' => '-0.02em',
        ],
        'tablet' => [
            'font_size' => '52',
        ],
        'mobile' => [
            'font_size' => '36',
        ],
    ],

    // Section H2 — large title above a section.
    'h2-section' => [
        'desktop' => [
            'font_size' => '42',
            'font_weight' => '700',
            'line_height' => '1.1',
            'letter_spacing' => '-0.01em',
        ],
        'tablet' => [
            'font_size' => '34',
        ],
        'mobile' => [
            'font_size' => '24',
        ],
    ],

    // Feature card title (smaller, fits inside a card).
    'h3-card' => [
        'desktop' => [
            'font_size' => '24',
            'font_weight' => '600',
            'line_height' => '1.2',
        ],
        'mobile' => [
            'font_size' => '20',
        ],
    ],

    // Lead paragraph — slightly larger body copy under a hero/section.
    'body-lead' => [
        'desktop' => [
            'font_size' => '18',
            'font_weight' => '400',
            'line_height' => '1.55',
        ],
        'mobile' => [
            'font_size' => '16',
        ],
    ],

    // Standard body paragraph.
    'body' => [
        'desktop' => [
            'font_size' => '16',
            'font_weight' => '400',
            'line_height' => '1.55',
        ],
    ],

    // Small label / eyebrow.
    'label' => [
        'desktop' => [
            'font_size' => '13',
            'font_weight' => '600',
            'letter_spacing' => '0.06em',
        ],
    ],
];
