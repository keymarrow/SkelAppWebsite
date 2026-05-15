<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\NewsPost;
use App\Models\NewsPostSlugRedirect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsCmsTest extends TestCase
{
    use RefreshDatabase;

    public function test_news_index_keeps_working_and_exposes_a_canonical_tag(): void
    {
        $response = $this->get('/news');

        $response
            ->assertOk()
            ->assertSee('SkelApp News', false)
            ->assertSee('<link rel="canonical" href="'.route('news.index').'" />', false);
    }

    public function test_news_slug_redirects_issue_a_301_to_the_current_post_url(): void
    {
        $post = NewsPost::factory()->create([
            'slug' => 'current-skelapp-post',
        ]);

        NewsPostSlugRedirect::query()->create([
            'news_post_id' => $post->id,
            'slug' => 'old-skelapp-post',
        ]);

        $this->get('/news/old-skelapp-post')
            ->assertRedirect(route('news.show', 'current-skelapp-post'));
    }

    public function test_sitemap_includes_public_news_posts(): void
    {
        $post = NewsPost::factory()->create([
            'slug' => 'sitemap-post',
        ]);

        $response = $this->get('/sitemap.xml');

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee(route('news.show', $post->slug), false);
    }

    public function test_admin_routes_are_private_and_admin_can_sign_in(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');

        $admin = Admin::query()->create([
            'name' => 'CMS Owner',
            'email' => 'owner@example.com',
            'password' => 'StrongPassword!2026',
        ]);

        $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'StrongPassword!2026',
        ])->assertRedirect(route('admin.dashboard'));

        $this->actingAs($admin, 'admin')
            ->get('/admin/posts')
            ->assertOk();
    }
}
