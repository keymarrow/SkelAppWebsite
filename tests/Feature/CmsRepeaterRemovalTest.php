<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Page;
use Database\Seeders\PagesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Guards the repeater-removal fix: when the admin removes a repeater row, the
 * survivors re-index to a contiguous 0..N-1 set, so the merge must drop the
 * now-stale trailing index instead of leaving it behind.
 */
class CmsRepeaterRemovalTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): Admin
    {
        return Admin::query()->create([
            'name' => 'CMS Owner',
            'email' => 'owner@example.com',
            'password' => 'StrongPassword!2026',
        ]);
    }

    public function test_removing_a_repeater_row_shrinks_the_stored_list(): void
    {
        $this->seed(PagesSeeder::class);

        $page = Page::query()->where('slug', 'pos')->firstOrFail();
        $before = count(data_get($page->effectiveDraft(), 'features', []));
        $this->assertGreaterThan(5, $before, 'expected the POS page to seed more than 5 feature tabs');

        // Simulate the posted form after the editor removed one feature row:
        // the JS re-indexes survivors to features.0..features.4.
        $payload = [];
        foreach (range(0, 4) as $i) {
            $payload["features.{$i}.name"] = "Feature {$i}";
            $payload["features.{$i}.body"] = "Body {$i}";
        }

        $this->actingAs($this->admin(), 'admin')
            ->post('/admin/pages/pos?publish=1', ['content_payload' => json_encode($payload)])
            ->assertRedirect('/admin/pages/pos');

        $page->refresh();

        $this->assertCount(5, data_get($page->published_content, 'features'));
        $this->assertCount(5, data_get($page->draft_content, 'features'));
        $this->assertSame('Feature 0', data_get($page->published_content, 'features.0.name'));
        $this->assertSame('Feature 4', data_get($page->published_content, 'features.4.name'));
    }

    public function test_editing_non_repeater_fields_does_not_prune_anything(): void
    {
        $this->seed(PagesSeeder::class);

        $this->actingAs($this->admin(), 'admin')
            ->post('/admin/pages/pos?publish=1', [
                'content_payload' => json_encode(['hero.title' => 'Fresh hero title']),
            ])
            ->assertRedirect('/admin/pages/pos');

        $page = Page::query()->where('slug', 'pos')->firstOrFail();

        $this->assertSame('Fresh hero title', data_get($page->published_content, 'hero.title'));
        // The features repeater was not in this payload, so it must be untouched.
        $this->assertCount(6, data_get($page->published_content, 'features'));
    }
}
