<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_admin_settings(): void
    {
        $this->get('/settings-global')->assertRedirect('/login');
    }

    public function test_non_admin_users_cannot_access_admin_settings(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/settings-global')
            ->assertForbidden();
    }

    public function test_admin_users_can_access_admin_settings(): void
    {
        $role = Role::create(['name' => 'admin', 'display_name' => 'System Administrator']);

        $user = User::factory()->create();
        $user->roles()->attach($role->id);

        $this->actingAs($user)
            ->get('/settings-global')
            ->assertOk();
    }
}
