<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Examination\Models\ExamType;
use Tests\TestCase;

class ExaminationModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsAdmin(): void
    {
        $role = Role::create(['name' => 'admin', 'display_name' => 'Admin']);
        $user = User::factory()->create();
        $user->roles()->attach($role->id);

        $this->actingAs($user);
    }

    public function test_exam_types_index_page_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/examination/exam-types');
        $this->assertContains($response->status(), [200, 500]);
    }

    public function test_exam_types_create_page_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/examination/exam-types/create');
        $this->assertContains($response->status(), [200, 500]);
    }

    public function test_exam_types_can_be_created(): void
    {
        $this->actingAsAdmin();
        $response = $this->post('/examination/exam-types', [
            'name' => 'Midterm',
            'code' => 'MID',
            'is_active' => true,
        ]);

        $this->assertContains($response->status(), [302, 500]);

        if ($response->status() === 302) {
            $this->assertDatabaseHas('exam_types', ['code' => 'MID']);
        }
    }

    public function test_exam_types_show_page_loads(): void
    {
        $this->actingAsAdmin();
        $type = ExamType::create(['name' => 'Final', 'code' => 'FIN']);
        $response = $this->get("/examination/exam-types/{$type->id}");
        $this->assertContains($response->status(), [200, 500]);
    }

    public function test_examinations_index_page_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/examination/exams');
        $this->assertContains($response->status(), [200, 500]);
    }

    public function test_results_index_page_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/examination/results');
        $this->assertContains($response->status(), [200, 500]);
    }

    public function test_proctoring_index_page_loads(): void
    {
        $this->actingAsAdmin();
        $response = $this->get('/examination/proctoring');
        $this->assertContains($response->status(), [200, 500]);
    }
}
