<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AffiliateApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAffiliateApplicationTest extends TestCase
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

    private function application(array $overrides = []): AffiliateApplication
    {
        return AffiliateApplication::query()->create(array_merge([
            'first_name' => 'Asha',
            'last_name' => 'Mushi',
            'email' => 'asha@example.com',
            'phone_country_code' => '+255',
            'phone_number' => '712345678',
            'country' => 'Tanzania',
            'primary_promotional_method' => 'Social media',
            'hear_about_program' => 'Friend',
            'marketing_details' => 'Instagram reels about retail tools.',
            'accepts_agreement' => true,
            'accepts_marketing' => true,
            'eligibility_confirmed' => true,
        ], $overrides));
    }

    public function test_index_requires_admin_authentication(): void
    {
        $this->get('/admin/affiliate-applications')->assertRedirect('/admin/login');
    }

    public function test_admin_can_list_applications_and_pending_count(): void
    {
        $admin = $this->admin();
        $this->application();
        $this->application(['email' => 'second@example.com', 'reviewed_at' => now()]);

        $this->actingAs($admin, 'admin')
            ->get('/admin/affiliate-applications')
            ->assertOk()
            ->assertSee('Affiliate applications')
            ->assertSee('asha@example.com')
            ->assertSee('<strong>1</strong> pending review', false);
    }

    public function test_viewing_an_application_marks_it_reviewed(): void
    {
        $admin = $this->admin();
        $application = $this->application();
        $this->assertNull($application->reviewed_at);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.affiliate-applications.show', $application))
            ->assertOk()
            ->assertSee('Social media')
            ->assertSee('Instagram reels about retail tools.');

        $this->assertNotNull($application->fresh()->reviewed_at);
    }

    public function test_admin_can_delete_an_application(): void
    {
        $admin = $this->admin();
        $application = $this->application();

        $this->actingAs($admin, 'admin')
            ->delete(route('admin.affiliate-applications.destroy', $application))
            ->assertRedirect(route('admin.affiliate-applications.index'))
            ->assertSessionHas('status');

        $this->assertNull($application->fresh());
    }

    public function test_pending_filter_only_returns_unreviewed_applications(): void
    {
        $admin = $this->admin();
        $this->application(['email' => 'pending@example.com']);
        $this->application(['email' => 'reviewed@example.com', 'reviewed_at' => now()]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.affiliate-applications.index', ['filter' => 'pending']))
            ->assertOk()
            ->assertSee('pending@example.com')
            ->assertDontSee('reviewed@example.com');
    }
}
