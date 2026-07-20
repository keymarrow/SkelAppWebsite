<?php

namespace Tests\Feature;

use App\Models\Page;
use Database\Seeders\PagesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsUrlRewriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_cms_auth_shortcuts_render_to_the_web_app(): void
    {
        $this->seed(PagesSeeder::class);

        $page = Page::query()->where('slug', 'home')->firstOrFail();
        $content = $page->published_content ?? [];

        data_set($content, 'hero.cta_url', '/login');
        data_set($content, 'hero.cta2_url', '/signup');
        data_set($content, 'affordable.cards.0.link_url', '/signup?source=card');

        $page->update([
            'draft_content' => $content,
            'published_content' => $content,
        ]);
        $page->forgetCache();

        $response = $this->get('/')
            ->assertOk();

        $response->assertSee('href="https://web.skelapp.tz/login"', false);
        $response->assertSee('href="https://web.skelapp.tz/register"', false);
        $response->assertSee('href="https://web.skelapp.tz/register?source=card"', false);

        $this->assertStringNotContainsString('href="/login"', $response->getContent());
        $this->assertStringNotContainsString('href="/signup"', $response->getContent());
        $this->assertStringNotContainsString('href="/signup?source=card"', $response->getContent());
    }
}
