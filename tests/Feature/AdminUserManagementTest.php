<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    private function owner(): Admin
    {
        return Admin::query()->create([
            'name' => 'CMS Owner',
            'email' => 'owner@example.com',
            'password' => 'StrongPassword!2026',
        ]);
    }

    public function test_users_page_requires_admin_authentication(): void
    {
        $this->get('/admin/users')->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_the_team_page(): void
    {
        $admin = $this->owner();

        $this->actingAs($admin, 'admin')
            ->get('/admin/users')
            ->assertOk()
            ->assertSee('Team &amp; admins', false)
            ->assertSee('owner@example.com');
    }

    public function test_admin_can_create_a_new_admin_user_with_hashed_password(): void
    {
        $admin = $this->owner();

        $this->actingAs($admin, 'admin')
            ->post('/admin/users', [
                'name' => 'New Editor',
                'email' => 'editor@example.com',
                'password' => 'AnotherStrong2026',
                'password_confirmation' => 'AnotherStrong2026',
            ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('status');

        $created = Admin::query()->where('email', 'editor@example.com')->firstOrFail();
        $this->assertSame('New Editor', $created->name);
        $this->assertNotSame('AnotherStrong2026', $created->password);
        $this->assertTrue(Hash::check('AnotherStrong2026', $created->password));
    }

    public function test_creating_an_admin_validates_unique_email_and_password_confirmation(): void
    {
        $admin = $this->owner();

        $this->actingAs($admin, 'admin')
            ->post('/admin/users', [
                'name' => 'Dupe',
                'email' => 'owner@example.com',
                'password' => 'AnotherStrong2026',
                'password_confirmation' => 'mismatch',
            ])
            ->assertSessionHasErrors(['email', 'password']);

        $this->assertSame(1, Admin::query()->count());
    }

    public function test_admin_cannot_delete_their_own_account(): void
    {
        $admin = $this->owner();

        $this->actingAs($admin, 'admin')
            ->delete(route('admin.users.destroy', $admin))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('error');

        $this->assertNotNull($admin->fresh());
    }

    public function test_admin_can_delete_another_admin(): void
    {
        $admin = $this->owner();
        $other = Admin::query()->create([
            'name' => 'Removable',
            'email' => 'removable@example.com',
            'password' => 'RemoveMe2026!',
        ]);

        $this->actingAs($admin, 'admin')
            ->delete(route('admin.users.destroy', $other))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('status');

        $this->assertNull($other->fresh());
        $this->assertSame(1, Admin::query()->count());
    }
}
