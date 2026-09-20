<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FullSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsAdmin(): void
    {
        $role = Role::create(['name' => 'admin', 'display_name' => 'Admin']);
        $user = User::factory()->create();
        $user->roles()->attach($role->id);

        $this->actingAs($user);
    }

    protected function createNonAdminUser(): User
    {
        $role = Role::create(['name' => 'teacher', 'display_name' => 'Teacher']);
        $user = User::factory()->create();
        $user->roles()->attach($role->id);

        return $user;
    }

    protected function assertRouteLoadsWithoutError($response, string $route): void
    {
        $this->assertContains(
            $response->status(),
            [200, 302],
            "Route {$route} returned unexpected status {$response->status()}"
        );
    }

    // ─── Authentication Tests ───────────────────────────────────────

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertStatus(302);
        $response->assertRedirect();
    }

    public function test_login_page_loads(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_admin_can_login(): void
    {
        $role = Role::create(['name' => 'admin', 'display_name' => 'Admin']);
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);
        $user->roles()->attach($role->id);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
    }

    public function test_admin_can_logout(): void
    {
        $this->actingAsAdmin();

        $response = $this->post('/logout');
        $response->assertStatus(302);

        $response = $this->get('/dashboard');
        $response->assertStatus(302);
    }

    // ─── Authorization Tests ────────────────────────────────────────

    public function test_non_admin_cannot_access_finance_routes(): void
    {
        $user = $this->createNonAdminUser();
        $this->actingAs($user);

        foreach (['/finance', '/finance/fees', '/finance/reports', '/finance/settings'] as $route) {
            $response = $this->get($route);
            $response->assertStatus(403);
        }
    }

    public function test_non_admin_cannot_access_hr_routes(): void
    {
        $user = $this->createNonAdminUser();
        $this->actingAs($user);

        foreach (['/hr', '/hr/staff', '/hr/departments', '/hr/leave', '/hr/payroll'] as $route) {
            $response = $this->get($route);
            $response->assertStatus(403);
        }
    }

    public function test_non_admin_cannot_access_settings_routes(): void
    {
        $user = $this->createNonAdminUser();
        $this->actingAs($user);

        foreach (['/settings', '/settings-global'] as $route) {
            $response = $this->get($route);
            $response->assertStatus(403);
        }
    }

    public function test_non_admin_can_access_non_admin_routes(): void
    {
        $user = $this->createNonAdminUser();
        $this->actingAs($user);

        foreach (['/dashboard', '/profile', '/academic', '/academic/classes', '/academic/subjects', '/academic/students'] as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }

    // ─── Welcome Page ───────────────────────────────────────────────

    public function test_welcome_page_loads(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Laravel');
    }

    // ─── Dashboard & Profile ────────────────────────────────────────

    public function test_dashboard_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_profile_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/profile');
        $response->assertStatus(200);
    }

    // ─── Academic Module ────────────────────────────────────────────

    public function test_academic_dashboard_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/academic');
        $response->assertStatus(200);
    }

    public function test_academic_classes_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/academic/classes');
        $response->assertStatus(200);
    }

    public function test_academic_subjects_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/academic/subjects');
        $response->assertStatus(200);
    }

    public function test_academic_students_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/academic/students');
        $response->assertStatus(200);
    }

    public function test_academic_grading_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/academic/grading');
        $response->assertStatus(200);
    }

    public function test_academic_online_classes_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/academic/online-classes');
        $response->assertStatus(200);
    }

    // ─── Examination Module ─────────────────────────────────────────

    public function test_examination_questions_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/examination/questions');
        $response->assertStatus(200);
    }

    // ─── Finance Module ─────────────────────────────────────────────

    public function test_finance_dashboard_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance');
        $response->assertStatus(200);
    }

    public function test_finance_fees_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance/fees');
        $response->assertStatus(200);
    }

    public function test_finance_payments_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance/payments');
        $response->assertStatus(200);
    }

    public function test_finance_reports_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance/reports');
        $response->assertStatus(200);
    }

    public function test_finance_settings_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance/settings');
        $response->assertStatus(200);
    }

    // ─── HR Module ──────────────────────────────────────────────────

    public function test_hr_staff_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/hr/staff');
        $response->assertStatus(200);
    }

    public function test_hr_departments_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/hr/departments');
        $response->assertStatus(200);
    }

    public function test_hr_leave_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/hr/leave');
        $response->assertStatus(200);
    }

    public function test_hr_payroll_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/hr/payroll');
        $response->assertStatus(200);
    }

    // ─── Hostel Module ──────────────────────────────────────────────

    public function test_hostel_dashboard_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/hostel');
        $response->assertStatus(200);
    }

    // ─── Library Module ─────────────────────────────────────────────

    public function test_library_dashboard_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/library');
        $response->assertStatus(200);
    }

    public function test_library_books_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/library/books');
        $response->assertStatus(200);
    }

    public function test_library_members_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/library/members');
        $response->assertStatus(200);
    }

    public function test_library_borrows_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/library/borrows');
        $response->assertStatus(200);
    }

    // ─── Transport Module ───────────────────────────────────────────

    public function test_transport_dashboard_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/transport');
        $response->assertStatus(200);
    }

    public function test_transport_vehicles_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/transport/vehicles');
        $response->assertStatus(200);
    }

    public function test_transport_drivers_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/transport/drivers');
        $response->assertStatus(200);
    }

    public function test_transport_routes_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/transport/routes');
        $response->assertStatus(200);
    }

    public function test_transport_trips_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/transport/trips');
        $response->assertStatus(200);
    }

    // ─── Timetable Module ───────────────────────────────────────────

    public function test_timetables_dashboard_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/timetables/dashboard');
        $response->assertStatus(200);
    }

    // ─── Communication Module ───────────────────────────────────────

    public function test_communication_dashboard_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/communication');
        $response->assertStatus(200);
    }

    public function test_communication_inbox_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/communication/inbox');
        $response->assertStatus(200);
    }

    // ─── Settings Module ────────────────────────────────────────────

    public function test_settings_index_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/settings');
        $response->assertStatus(200);
    }

    public function test_settings_global_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/settings-global');
        $response->assertStatus(200);
    }

    // ─── API Auth Tests ─────────────────────────────────────────────

    public function test_chatbot_send_without_auth_is_rejected(): void
    {
        $response = $this->postJson('/chatbot/send', ['message' => 'Hello']);
        $this->assertNotSame(200, $response->status(), 'Unauthenticated chatbot access should not succeed');
    }

    public function test_mobile_profile_without_auth_returns_401(): void
    {
        $response = $this->getJson('/api/v1/mobile/profile');
        $this->assertNotSame(200, $response->status(), 'Unauthenticated mobile API access should not succeed');
    }

    // ─── Response Content Tests ─────────────────────────────────────

    public function test_dashboard_returns_not_empty_response(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        $this->assertNotEmpty($response->content());
    }

    public function test_academic_returns_not_empty_response(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/academic');
        $response->assertStatus(200);
        $this->assertNotEmpty($response->content());
    }

    public function test_finance_returns_not_empty_response(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/finance');
        $response->assertStatus(200);
        $this->assertNotEmpty($response->content());
    }

    public function test_library_returns_not_empty_response(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/library');
        $response->assertStatus(200);
        $this->assertNotEmpty($response->content());
    }

    // ─── Bulk Route Health Check ────────────────────────────────────

    public function test_all_admin_get_routes_are_accessible(): void
    {
        $this->actingAsAdmin();

        $routes = [
            '/dashboard',
            '/profile',
            '/academic',
            '/academic/classes',
            '/academic/subjects',
            '/academic/students',
            '/academic/grading',
            '/academic/online-classes',
            '/examination/questions',
            '/finance',
            '/finance/fees',
            '/finance/payments',
            '/finance/reports',
            '/finance/settings',
            '/hr/staff',
            '/hr/departments',
            '/hr/leave',
            '/hr/payroll',
            '/hostel',
            '/library',
            '/library/books',
            '/library/members',
            '/library/borrows',
            '/transport',
            '/transport/vehicles',
            '/transport/drivers',
            '/transport/routes',
            '/transport/trips',
            '/timetables/dashboard',
            '/communication',
            '/communication/inbox',
            '/settings',
            '/settings-global',
        ];

        $failures = [];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $status = $response->status();

            if ($status !== 200) {
                $failures[] = "{$route} => {$status}";
            }
        }

        $this->assertEmpty(
            $failures,
            "The following routes did not return 200:\n" . implode("\n", $failures)
        );
    }
}
