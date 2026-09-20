<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsAdmin(): void
    {
        $role = Role::create(['name' => 'admin', 'display_name' => 'Admin']);
        $user = User::factory()->create();
        $user->roles()->attach($role->id);

        $this->actingAs($user);
    }

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
}
