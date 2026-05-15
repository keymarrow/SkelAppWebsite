<?php

namespace Database\Factories;

use App\Models\NewsPost;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NewsPostFactory extends Factory
{
    protected $model = NewsPost::class;

    public function definition(): array
    {
        $title = fake()->sentence(6);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999),
            'summary' => fake()->paragraph(),
            'body_markdown' => "## Intro\n\n".fake()->paragraphs(4, true),
            'categories' => ['Product', 'Retail Guide'],
            'card_label' => 'News',
            'card_color_start' => '#eaf7ff',
            'card_color_mid' => '#7fc8ff',
            'card_color_end' => '#6e82ff',
            'meta_title' => $title,
            'meta_description' => fake()->sentence(18),
            'read_time_override' => null,
            'is_published' => true,
            'published_at' => now()->subDay(),
        ];
    }
}
