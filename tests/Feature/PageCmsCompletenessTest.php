<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\PageController;
use App\Models\Admin;
use App\Models\Page;
use Database\Seeders\PagesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Guards that every page wired into the CMS is actually seeded, renders on the
 * front end, is openable in the admin, and that no text a public view reads is
 * missing from the seeded content (which would leave it uneditable / drifting
 * back to a hardcoded fallback). Encodes the manual completeness audit.
 */
class PageCmsCompletenessTest extends TestCase
{
    use RefreshDatabase;

    /** Public routes for the pages added in the CMS expansion. */
    private const PUBLIC_ROUTES = [
        '/',
        '/pricing',
        '/point-of-sale',
        '/why-skelapp',
        '/hardware',
        '/hardware/skel-register',
        '/retailers',
        '/retailers/boutique',
        '/integrations',
        '/integrations/zoho-books',
        '/affiliate-program',
        '/affiliate-program/apply',
        '/privacy-policy',
    ];

    /** Views whose CMS keys must all resolve to seeded content. */
    private const AUDIT_VIEWS = [
        'welcome.blade.php',
        'pricing.blade.php',
        'point-of-sale.blade.php',
        'hardware.blade.php',
        'hardware-product.blade.php',
        'retailers-index.blade.php',
        'retailer.blade.php',
        'integrations.blade.php',
        'integration.blade.php',
        'affiliate.blade.php',
        'affiliate-apply.blade.php',
        'why.blade.php',
        'privacy.blade.php',
        'partials/nav-mega.blade.php',
        'partials/download-modal.blade.php',
        'partials/site-footer.blade.php',
        'partials/site-nav.blade.php',
    ];

    /** Keys that are intentionally blank by default (admin opts in via the form). */
    private const OPTIONAL_BLANK = [
        'global.download.qr_image', // falls back to the generated QR when unset
        'pricing.cta.url',          // blank → falls back to the Contact page route
    ];

    public function test_every_new_public_page_renders(): void
    {
        $this->seed(PagesSeeder::class);

        foreach (self::PUBLIC_ROUTES as $route) {
            $this->get($route)->assertOk();
        }
    }

    public function test_admin_can_open_every_page_form(): void
    {
        $this->seed(PagesSeeder::class);

        $admin = Admin::query()->create([
            'name' => 'CMS Owner',
            'email' => 'owner@example.com',
            'password' => 'StrongPassword!2026',
        ]);

        foreach (array_keys(PageController::PAGES) as $slug) {
            $this->actingAs($admin, 'admin')
                ->get('/admin/pages/'.$slug)
                ->assertOk();
        }
    }

    public function test_no_public_text_is_missing_from_the_cms(): void
    {
        $this->seed(PagesSeeder::class);

        $base = resource_path('views/');
        $missing = [];

        foreach (self::AUDIT_VIEWS as $rel) {
            $file = $base.$rel;
            $this->assertFileExists($file);
            $src = file_get_contents($file);

            preg_match_all(
                "/content(_text|_image|_list)?\\(\\s*'([a-z0-9_\\-]+\\.[a-z0-9_.\\-]+)'/i",
                $src,
                $matches,
                PREG_SET_ORDER
            );

            foreach ($matches as $m) {
                $kind = $m[1] ?: 'scalar';
                $key = $m[2];
                if (in_array($key, self::OPTIONAL_BLANK, true)) {
                    continue;
                }
                [$slug, $path] = explode('.', $key, 2);
                $value = Page::getValue($slug, $path, null);

                $empty = $value === null
                    || $value === ''
                    || ($kind === 'list' && (! is_array($value) || count($value) === 0));

                if ($empty) {
                    $missing[] = $key.'  ('.$rel.')';
                }
            }
        }

        $this->assertSame(
            [],
            array_values(array_unique($missing)),
            "These CMS keys are read by a public view but not seeded:\n".implode("\n", array_unique($missing))
        );
    }
}
