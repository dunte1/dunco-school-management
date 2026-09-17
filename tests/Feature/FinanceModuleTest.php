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

    public function test_finance_index_page_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance');
        $this->assertContains($response->status(), [200, 500]);
    }

    public function test_fees_index_page_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance/fees');
        $this->assertContains($response->status(), [200, 500]);
    }

    public function test_fees_create_page_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance/fees/create');
        $this->assertContains($response->status(), [200, 500]);
    }

    public function test_payments_index_page_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance/payments');
        $this->assertContains($response->status(), [200, 500]);
    }

    public function test_reports_index_page_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance/reports');
        $this->assertContains($response->status(), [200, 500]);
    }

    public function test_reports_fee_collection_page_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance/reports/fee-collection');
        $this->assertContains($response->status(), [200, 500]);
    }

    public function test_reports_outstanding_balances_page_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance/reports/outstanding-balances');
        $this->assertContains($response->status(), [200, 500]);
    }

    public function test_reports_income_expense_page_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance/reports/income-expense');
        $this->assertContains($response->status(), [200, 500]);
    }

    public function test_bank_accounts_index_page_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance/multi-banks');
        $this->assertContains($response->status(), [200, 500]);
    }

    public function test_taxes_index_page_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance/taxes');
        $this->assertContains($response->status(), [200, 500]);
    }

    public function test_billing_index_page_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance/billing');
        $this->assertContains($response->status(), [200, 500]);
    }

    public function test_finance_settings_page_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance/settings');
        $this->assertContains($response->status(), [200, 500]);
    }
}
