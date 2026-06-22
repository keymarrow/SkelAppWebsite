<?php

namespace Tests\Feature;

use App\Models\Page;
use Database\Seeders\PagesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageCmsDynamicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_retailer_detail_page_uses_cms_content(): void
    {
        $this->seed(PagesSeeder::class);

        $page = Page::query()->where('slug', 'retailers')->firstOrFail();
        $content = $page->published_content;
        $content['pages'][0]['hero_title'] = "Boutique title from\nCMS";
        $content['pages'][0]['cta_note'] = 'Retail CTA from CMS.';
        $page->update([
            'published_content' => $content,
            'draft_content' => $content,
        ]);
        $page->forgetCache();

        $this->get('/retailers/boutique')
            ->assertOk()
            ->assertSee('Boutique title from', false)
            ->assertSee('Retail CTA from CMS.', false);
    }

    public function test_hardware_product_page_uses_cms_content(): void
    {
        $this->seed(PagesSeeder::class);

        $page = Page::query()->where('slug', 'hardware')->firstOrFail();
        $content = $page->published_content;
        $content['products'][0]['intro_title'] = 'Hardware intro from CMS';
        $content['products'][0]['detail_subtitle'] = 'Hardware detail subtitle from CMS.';
        $page->update([
            'published_content' => $content,
            'draft_content' => $content,
        ]);
        $page->forgetCache();

        $this->get('/hardware/skel-register')
            ->assertOk()
            ->assertSee('Hardware intro from CMS', false)
            ->assertSee('Hardware detail subtitle from CMS.', false);
    }

    public function test_integration_detail_page_uses_cms_content(): void
    {
        $this->seed(PagesSeeder::class);

        $page = Page::query()->where('slug', 'integrations')->firstOrFail();
        $content = $page->published_content;
        $content['items'][0]['detail_title'] = 'Integration detail title from CMS';
        $content['items'][0]['detail_copy'] = 'Integration detail copy from CMS.';
        $page->update([
            'published_content' => $content,
            'draft_content' => $content,
        ]);
        $page->forgetCache();

        $this->get('/integrations/zoho-books')
            ->assertOk()
            ->assertSee('Integration detail title from CMS', false)
            ->assertSee('Integration detail copy from CMS.', false);
    }

    public function test_download_modal_uses_global_cms_copy(): void
    {
        $this->seed(PagesSeeder::class);

        $page = Page::query()->where('slug', 'global')->firstOrFail();
        $content = $page->published_content;
        $content['download']['title'] = 'Download modal title from CMS';
        $content['download']['phone_placeholder'] = 'Phone from CMS';
        $page->update([
            'published_content' => $content,
            'draft_content' => $content,
        ]);
        $page->forgetCache();

        $this->get('/')
            ->assertOk()
            ->assertSee('Download modal title from CMS', false)
            ->assertSee('Phone from CMS', false);
    }
}
