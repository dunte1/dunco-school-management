<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransportModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsAdmin(): void
    {
        $role = Role::create(['name' => 'admin', 'display_name' => 'Admin']);
        $user = User::factory()->create();
        $user->roles()->attach($role->id);

        $this->actingAs($user);
    }

    public function test_transport_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/transport');
        $response->assertStatus(200);
    }
}
