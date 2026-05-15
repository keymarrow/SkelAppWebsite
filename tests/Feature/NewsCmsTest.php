<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\NewsPost;
use App\Models\NewsPostSlugRedirect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

    public function test_database_backed_articles_render_featured_and_inline_markdown_images(): void
    {
        $featuredImageUrl = 'https://images.example.com/news/whatsapp-cover.jpg';
        $inlineImageUrl = 'https://images.example.com/news/whatsapp-inline.jpg';

        $post = NewsPost::factory()->create([
            'slug' => 'image-supported-post',
            'featured_image_url' => $featuredImageUrl,
            'body_markdown' => implode("\n\n", [
                'Intro paragraph for the article.',
                "![Inline campaign example]({$inlineImageUrl})",
                'Closing paragraph for the article.',
            ]),
        ]);

        $this->get('/news')
            ->assertOk()
            ->assertSee($featuredImageUrl, false);

        $this->get("/news/{$post->slug}")
            ->assertOk()
            ->assertDontSee($featuredImageUrl, false)
            ->assertSee($inlineImageUrl, false)
            ->assertSee('Inline campaign example', false);
    }

    public function test_admin_can_upload_cover_and_inline_content_images(): void
    {
        Storage::fake('public');

        $admin = Admin::query()->create([
            'name' => 'CMS Owner',
            'email' => 'owner@example.com',
            'password' => 'StrongPassword!2026',
        ]);

        $this->actingAs($admin, 'admin')
            ->post('/admin/posts', [
                'title' => 'Image Upload Post',
                'slug' => 'image-upload-post',
                'summary' => 'Summary for image upload post.',
                'featured_image' => UploadedFile::fake()->image('cover.jpg', 1600, 900),
                'body_markdown' => 'Body copy for upload coverage.',
                'categories' => 'Product, Growth',
                'card_label' => 'News',
                'card_color_start' => '#eaf7ff',
                'card_color_mid' => '#7fc8ff',
                'card_color_end' => '#6e82ff',
                'is_published' => '1',
                'published_at' => now()->toDateTimeString(),
            ])
            ->assertRedirect();

        $post = NewsPost::query()->firstOrFail();

        $this->assertNotNull($post->featured_image_url);
        $this->assertTrue(Str::startsWith($post->featured_image_url, '/storage/news/covers/'));
        Storage::disk('public')->assertExists(Str::after($post->featured_image_url, '/storage/'));

        $response = $this->actingAs($admin, 'admin')
            ->postJson('/admin/posts/content-images', [
                'image' => UploadedFile::fake()->image('inline.jpg', 1200, 800),
            ]);

        $response
            ->assertOk()
            ->assertJsonStructure(['url', 'alt']);

        Storage::disk('public')->assertExists(Str::after($response->json('url'), '/storage/'));
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
