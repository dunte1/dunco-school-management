<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinanceModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsAdmin(): void
    {
        $role = Role::create(['name' => 'admin', 'display_name' => 'Admin']);
        $user = User::factory()->create();
        $user->roles()->attach($role->id);

        $this->actingAs($user);
    }

    public function test_finance_dashboard_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance');
        $response->assertStatus(200);
    }

    public function test_fees_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance/fees');
        $response->assertStatus(200);
    }

    public function test_payments_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance/payments');
        $response->assertStatus(200);
    }

    public function test_finance_settings_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance/settings');
        $response->assertStatus(200);
    }

    public function test_reports_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance/reports');
        $response->assertStatus(200);
    }
}
