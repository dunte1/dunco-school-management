<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Modules\Examination\Models\{Exam, ExamType, Question, QuestionCategory, ExamSchedule, ExamAttempt, ExamResult, ExamPayment, ExamAnswer, ProctoringLog};
use Modules\Nursing\Models\{Facility, FacilityDepartment, Ward, Placement, LogbookEntry, Skill, SkillCategory, StudentSkill, ClinicalHours, ReferenceArticle, ReferenceCategory, Scenario, ScenarioQuestion, CpdActivity};
use Modules\Academic\Models\Student;
use Modules\HR\Models\Staff;
use App\Models\{User, Role, School};

class ComprehensiveE2ETest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $teacher;
    protected User $student;
    protected User $nursingInstructor;
    protected School $school;
    protected ExamType $examType;
    protected Exam $exam;
    protected QuestionCategory $questionCategory;
    protected Question $question;
    protected Facility $facility;
    protected FacilityDepartment $department;
    protected Ward $ward;
    protected SkillCategory $skillCategory;
    protected Skill $skill;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'SystemPermissionsSeeder']);
        $this->artisan('db:seed', ['--class' => 'CoreSeeder']);

        $this->school = School::create(['name' => 'Test School', 'slug' => 'test-school', 'code' => 'TST']);

        // Admin
        $this->admin = User::factory()->create(['school_id' => $this->school->id]);
        $this->admin->roles()->attach(Role::where('name', 'admin')->first());

        // Teacher
        $this->teacher = User::factory()->create(['school_id' => $this->school->id]);
        $this->teacher->roles()->attach(Role::where('name', 'teacher')->first());

        // Student
        $this->student = User::factory()->create(['school_id' => $this->school->id]);
        $this->student->roles()->attach(Role::where('name', 'student')->first());
        $studentRecord = Student::create([
            'user_id' => $this->student->id,
            'school_id' => $this->school->id,
            'student_id' => 'STU001',
            'name' => 'Test Student',
            'admission_number' => 'ADM001',
            'admission_date' => now()->toDateString(),
            'date_of_birth' => '2000-01-01',
            'gender' => 'male',
            'enrollment_status' => 'enrolled',
            'is_active' => true,
        ]);

        // Nursing Instructor
        $this->nursingInstructor = User::factory()->create(['school_id' => $this->school->id]);
        $this->nursingInstructor->roles()->attach(Role::where('name', 'nursing_instructor')->first());
        Staff::create([
            'user_id' => $this->nursingInstructor->id,
            'school_id' => $this->school->id,
            'staff_id' => 'INS001',
            'first_name' => 'Test',
            'last_name' => 'Instructor',
            'email' => $this->nursingInstructor->email,
            'gender' => 'male',
            'status' => 'active',
        ]);

        // Exam setup
        $this->examType = ExamType::create(['name' => 'Midterm', 'code' => 'MID', 'is_active' => true]);
        $this->exam = Exam::create([
            'name' => 'Mathematics Midterm',
            'code' => 'MATH001',
            'exam_type_id' => $this->examType->id,
            'academic_year' => '2026',
            'term' => 'first',
            'start_date' => now(),
            'end_date' => now()->addDays(7),
            'duration_minutes' => 60,
            'total_marks' => 100,
            'passing_marks' => 50,
            'status' => 'published',
            'is_online' => false,
        ]);

        $this->questionCategory = QuestionCategory::create(['name' => 'Algebra', 'code' => 'ALG', 'is_active' => true]);
        $this->question = Question::create([
            'question_text' => 'What is 2+2?',
            'type' => 'mcq',
            'category_id' => $this->questionCategory->id,
            'options' => ['3', '4', '5', '6'],
            'correct_answers' => ['4'],
            'marks' => 5,
            'difficulty' => 'easy',
        ]);

        // Nursing setup
        $this->facility = Facility::create([
            'school_id' => $this->school->id,
            'name' => 'City Hospital',
            'type' => 'hospital',
            'is_active' => true,
        ]);
        $this->department = FacilityDepartment::create([
            'facility_id' => $this->facility->id,
            'name' => 'Emergency',
            'is_active' => true,
        ]);
        $this->ward = Ward::create([
            'facility_id' => $this->facility->id,
            'department_id' => $this->department->id,
            'name' => 'Emergency Ward A',
            'is_active' => true,
        ]);

        $this->skillCategory = SkillCategory::create(['name' => 'Basic Nursing', 'code' => 'BN', 'sort_order' => 1]);
        $this->skill = Skill::create([
            'category_id' => $this->skillCategory->id,
            'name' => 'Hand Hygiene',
            'description' => 'WHO 5 Moments hand hygiene',
            'status' => 'published',
            'is_active' => true,
        ]);
    }

    // ======================================================================
    // SECTION 1: EXAMINATION MODULE
    // ======================================================================

    public function test_01_admin_can_manage_exam_types()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('examination.exam-types.index'));
        $response->assertStatus(200);

        $response = $this->post(route('examination.exam-types.store'), [
            'name' => 'Final', 'code' => 'FIN', 'is_active' => true,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('exam_types', ['code' => 'FIN']);
    }

    public function test_02_admin_can_manage_exams()
    {
        $this->actingAs($this->admin);

        // Index
        $response = $this->get(route('examination.exams.index'));
        $response->assertStatus(200);

        // Create
        $response = $this->get(route('examination.exams.create'));
        $response->assertStatus(200);

        // Store
        $response = $this->post(route('examination.exams.store'), [
            'name' => 'Science Final', 'code' => 'SCI001', 'exam_type_id' => $this->examType->id,
            'academic_year' => '2026', 'term' => 'first',
            'start_date' => now()->toDateString(), 'end_date' => now()->addDays(7)->toDateString(),
            'duration_minutes' => 120, 'total_marks' => 100, 'passing_marks' => 50,
            'status' => 'draft', 'is_online' => false,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('exams', ['code' => 'SCI001']);

        // Show
        $response = $this->get(route('examination.exams.show', $this->exam));
        $response->assertStatus(200);

        // Edit
        $response = $this->get(route('examination.exams.edit', $this->exam));
        $response->assertStatus(200);

        // Update
        $response = $this->put(route('examination.exams.update', $this->exam), [
            'name' => 'Math Midterm Updated', 'code' => $this->exam->code,
            'exam_type_id' => $this->examType->id, 'academic_year' => '2026', 'term' => 'first',
            'start_date' => now()->toDateString(), 'end_date' => now()->addDays(7)->toDateString(),
            'duration_minutes' => 60, 'total_marks' => 100, 'passing_marks' => 50, 'status' => 'published',
        ]);
        $response->assertRedirect();

        // Publish
        $response = $this->post(route('examination.exams.publish', $this->exam));
        $response->assertRedirect();
    }

    public function test_03_admin_can_manage_question_categories()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('examination.categories.index'));
        $response->assertStatus(200);

        $response = $this->post(route('examination.categories.store'), [
            'name' => 'Geometry', 'code' => 'GEO', 'difficulty' => 'medium', 'is_active' => true,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('question_categories', ['code' => 'GEO']);

        $cat = QuestionCategory::where('code', 'GEO')->first();
        $response = $this->get(route('examination.categories.edit', $cat));
        $response->assertStatus(200);

        $response = $this->put(route('examination.categories.update', $cat), [
            'name' => 'Advanced Geometry', 'code' => $cat->code, 'difficulty' => 'hard', 'is_active' => true,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('question_categories', ['name' => 'Advanced Geometry']);

        $response = $this->delete(route('examination.categories.destroy', $cat));
        $response->assertRedirect();
        $this->assertDatabaseMissing('question_categories', ['id' => $cat->id]);
    }

    public function test_04_admin_can_manage_questions()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('examination.questions.index'));
        $response->assertStatus(200);

        $response = $this->post(route('examination.questions.store'), [
            'question_text' => 'What is 3x3?', 'type' => 'mcq',
            'category_id' => $this->questionCategory->id,
            'options' => ['6', '9', '12', '15'], 'correct_answers' => ['9'],
            'marks' => 5, 'difficulty' => 'easy',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('questions', ['question_text' => 'What is 3x3?']);

        $q = Question::where('question_text', 'What is 3x3?')->first();
        $response = $this->get(route('examination.questions.show', $q));
        $response->assertStatus(200);

        $response = $this->get(route('examination.questions.edit', $q));
        $response->assertStatus(200);

        $response = $this->put(route('examination.questions.update', $q), [
            'question_text' => 'What is 3x3?', 'type' => 'mcq',
            'category_id' => $this->questionCategory->id,
            'options' => ['6', '9', '12', '15'], 'correct_answers' => ['9'],
            'marks' => 10, 'difficulty' => 'medium',
        ]);
        $response->assertRedirect();
    }

    public function test_05_admin_can_manage_schedules()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('examination.schedules.index'));
        $response->assertStatus(200);

        $response = $this->post(route('examination.schedules.store'), [
            'exam_id' => $this->exam->id, 'class_name' => 'Form 4A',
            'subject' => 'Mathematics', 'exam_date' => now()->toDateString(),
            'start_time' => '09:00', 'end_time' => '11:00',
            'room_number' => 'A101', 'is_active' => true,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('exam_schedules', ['class_name' => 'Form 4A']);

        $response = $this->get(route('examination.schedules.timetable'));
        $response->assertStatus(200);
    }

    public function test_06_admin_can_manage_results()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('examination.results.index'));
        $response->assertStatus(200);

        $response = $this->get(route('examination.results.analytics'));
        $response->assertStatus(200);

        $response = $this->get(route('examination.exams.results', $this->exam));
        $response->assertStatus(200);
    }

    public function test_07_admin_can_access_proctoring()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('examination.proctoring.index'));
        $response->assertStatus(200);

        $response = $this->get(route('examination.proctoring.dashboard'));
        $response->assertStatus(200);

        $response = $this->get(route('examination.proctoring.analytics'));
        $response->assertStatus(200);
    }

    public function test_08_admin_can_access_admin_panel()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('examination.admin.dashboard'));
        $response->assertStatus(200);

        $response = $this->get(route('examination.admin.settings'));
        $response->assertStatus(200);

        $response = $this->get(route('examination.admin.reports'));
        $response->assertStatus(200);
    }

    public function test_09_student_can_access_exam_routes()
    {
        $this->actingAs($this->student);

        $response = $this->get(route('examination.student.exams'));
        $response->assertStatus(200);

        $response = $this->get(route('examination.student.results'));
        $response->assertStatus(200);

        $response = $this->get(route('examination.student.history'));
        $response->assertStatus(200);
    }

    public function test_10_student_can_take_physical_exam_with_payment()
    {
        $this->actingAs($this->student);
        $studentRecord = Student::where('user_id', $this->student->id)->first();

        // Enable fee
        $this->exam->update(['fee_required' => true, 'fee_amount' => 500, 'currency' => 'KES']);

        // Try to start without payment - should redirect to payment
        $response = $this->post(route('examination.exams.start', $this->exam));
        $response->assertRedirect();

        // Pay
        $payment = ExamPayment::create([
            'exam_id' => $this->exam->id, 'student_id' => $studentRecord->id,
            'school_id' => $this->school->id, 'amount' => 500, 'currency' => 'KES',
            'method' => 'cash', 'status' => 'completed', 'paid_at' => now(),
            'reference' => 'PAY-TEST-001',
        ]);

        // Now can start
        $response = $this->post(route('examination.exams.start', $this->exam));
        $response->assertRedirect();

        // Check payment index
        $response = $this->get(route('examination.payments.index'));
        $response->assertStatus(200);

        // Check payment show
        $response = $this->get(route('examination.payments.show', $payment));
        $response->assertStatus(200);

        // Check receipt
        $response = $this->get(route('examination.payments.receipt', $payment));
        $response->assertStatus(200);
    }

    public function test_11_student_can_use_payment_methods()
    {
        $this->actingAs($this->student);
        $this->exam->update(['fee_required' => true, 'fee_amount' => 500]);

        // Select method page should load
        $response = $this->get(route('examination.payments.select-method', $this->exam));
        $response->assertStatus(200);

        // Process M-Pesa payment
        $response = $this->post(route('examination.payments.process', $this->exam), ['method' => 'mpesa']);
        $response->assertStatus(200);
    }

    public function test_12_teacher_can_access_exam_routes()
    {
        $this->actingAs($this->teacher);

        $response = $this->get(route('examination.teacher.exams'));
        $response->assertStatus(200);

        $response = $this->get(route('examination.teacher.grade'));
        $response->assertStatus(200);

        $response = $this->get(route('examination.teacher.analytics'));
        $response->assertStatus(200);
    }

    public function test_13_student_cannot_access_admin_routes()
    {
        $this->actingAs($this->student);

        $response = $this->get(route('examination.admin.settings'));
        $response->assertStatus(403);

        $response = $this->get(route('examination.admin.reports'));
        $response->assertStatus(403);
    }

    // ======================================================================
    // SECTION 2: NURSING MODULE
    // ======================================================================

    public function test_14_admin_can_manage_facilities()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('nursing.facilities.index'));
        $response->assertStatus(200);

        $response = $this->get(route('nursing.facilities.create'));
        $response->assertStatus(200);

        $response = $this->post(route('nursing.facilities.store'), [
            'name' => 'General Hospital', 'type' => 'hospital', 'is_active' => true,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('nursing_facilities', ['name' => 'General Hospital']);

        $facility = Facility::where('name', 'General Hospital')->first();
        $response = $this->get(route('nursing.facilities.show', $facility));
        $response->assertStatus(200);

        $response = $this->get(route('nursing.facilities.edit', $facility));
        $response->assertStatus(200);

        $response = $this->put(route('nursing.facilities.update', $facility), [
            'name' => 'General Hospital Updated', 'type' => 'hospital', 'is_active' => true,
        ]);
        $response->assertRedirect();
    }

    public function test_15_admin_can_manage_placements()
    {
        $this->actingAs($this->admin);
        $studentRecord = Student::where('user_id', $this->student->id)->first();

        $response = $this->get(route('nursing.placements.index'));
        $response->assertStatus(200);

        $response = $this->post(route('nursing.placements.store'), [
            'student_id' => $studentRecord->id, 'facility_id' => $this->facility->id,
            'department_id' => $this->department->id, 'ward_id' => $this->ward->id,
            'start_date' => now()->toDateString(), 'end_date' => now()->addDays(30)->toDateString(),
            'required_hours' => 200,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('nursing_placements', ['student_id' => $studentRecord->id]);

        $placement = Placement::where('student_id', $studentRecord->id)->first();
        $response = $this->get(route('nursing.placements.show', $placement));
        $response->assertStatus(200);
    }

    public function test_16_student_can_manage_logbook()
    {
        $this->actingAs($this->student);
        $studentRecord = Student::where('user_id', $this->student->id)->first();

        $placement = Placement::create([
            'school_id' => $this->school->id, 'student_id' => $studentRecord->id,
            'facility_id' => $this->facility->id, 'department_id' => $this->department->id,
            'start_date' => now(), 'end_date' => now()->addDays(30),
            'required_hours' => 200, 'status' => 'active',
        ]);

        $response = $this->get(route('nursing.logbook.index'));
        $response->assertStatus(200);

        $response = $this->get(route('nursing.logbook.create'));
        $response->assertStatus(200);

        $response = $this->post(route('nursing.logbook.store'), [
            'placement_id' => $placement->id, 'date' => now()->toDateString(),
            'hours' => 8, 'activity' => 'Observed patient assessment',
            'reflection' => 'Learned a lot about patient assessment techniques.',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('nursing_logbooks', ['student_id' => $studentRecord->id]);

        $logbook = LogbookEntry::where('student_id', $studentRecord->id)->first();
        $response = $this->get(route('nursing.logbook.show', $logbook));
        $response->assertStatus(200);

        // Submit for review
        $response = $this->post(route('nursing.logbook.submit', $logbook));
        $response->assertRedirect();
        $this->assertDatabaseHas('nursing_logbooks', ['id' => $logbook->id, 'status' => 'submitted']);
    }

    public function test_17_instructor_can_review_logbook()
    {
        $this->actingAs($this->nursingInstructor);
        $studentRecord = Student::where('user_id', $this->student->id)->first();

        $placement = Placement::create([
            'school_id' => $this->school->id, 'student_id' => $studentRecord->id,
            'facility_id' => $this->facility->id, 'department_id' => $this->department->id,
            'instructor_id' => Staff::where('user_id', $this->nursingInstructor->id)->first()->id,
            'start_date' => now(), 'end_date' => now()->addDays(30),
            'required_hours' => 200, 'status' => 'active',
        ]);

        $logbook = LogbookEntry::create([
            'school_id' => $this->school->id, 'student_id' => $studentRecord->id,
            'placement_id' => $placement->id, 'date' => now()->toDateString(),
            'hours' => 8, 'status' => 'submitted',
        ]);

        // Pending reviews
        $response = $this->get(route('nursing.logbook.pending-reviews'));
        $response->assertStatus(200);

        // Approve
        $response = $this->post(route('nursing.logbook.approve', $logbook));
        $response->assertRedirect();
        $this->assertDatabaseHas('nursing_logbooks', ['id' => $logbook->id, 'status' => 'approved']);
    }

    public function test_18_student_can_view_skills()
    {
        $this->actingAs($this->student);

        $response = $this->get(route('nursing.skills.index'));
        $response->assertStatus(200);

        $response = $this->get(route('nursing.skills.show', $this->skill));
        $response->assertStatus(200);

        $response = $this->get(route('nursing.skills.my-progress'));
        $response->assertStatus(200);
    }

    public function test_19_student_can_use_calculators()
    {
        $this->actingAs($this->student);

        $response = $this->get(route('nursing.calculators.index'));
        $response->assertStatus(200);

        $response = $this->post(route('nursing.calculators.calculate'), [
            'type' => 'bmi', 'values' => ['weight' => 70, 'height' => 175],
        ]);
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertArrayHasKey('result', $data);
    }

    public function test_20_student_can_view_reference()
    {
        $this->actingAs($this->student);

        $category = ReferenceCategory::create(['name' => 'Anatomy', 'slug' => 'anatomy', 'is_active' => true]);
        $article = ReferenceArticle::create([
            'category_id' => $category->id, 'title' => 'Test Article',
            'slug' => 'test-article', 'content' => '<p>Test content</p>',
            'status' => 'published', 'author' => 'Test Author',
        ]);

        $response = $this->get(route('nursing.reference.index'));
        $response->assertStatus(200);

        $response = $this->get(route('nursing.reference.show', $article));
        $response->assertStatus(200);
    }

    public function test_21_student_can_use_scenarios()
    {
        $this->actingAs($this->student);

        $scenario = Scenario::create([
            'title' => 'Emergency Scenario', 'difficulty' => 'intermediate',
            'category' => 'emergency', 'status' => 'published',
            'description' => 'A patient presents with chest pain.',
        ]);
        ScenarioQuestion::create([
            'scenario_id' => $scenario->id, 'order' => 1,
            'question' => 'What is the first step?', 'choices' => ['A', 'B', 'C'],
            'correct_choice_index' => 0, 'explanation' => 'Assess ABCs first.',
        ]);

        $response = $this->get(route('nursing.scenarios.index'));
        $response->assertStatus(200);

        $response = $this->get(route('nursing.scenarios.show', $scenario));
        $response->assertStatus(200);

        $response = $this->get(route('nursing.scenarios.attempt', $scenario));
        $response->assertStatus(200);
    }

    public function test_22_admin_can_manage_cpd()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('nursing.cpd.index'));
        $response->assertStatus(200);

        $response = $this->get(route('nursing.cpd.create'));
        $response->assertStatus(200);
    }

    public function test_23_admin_can_manage_settings()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('nursing.settings.index'));
        $response->assertStatus(200);

        $response = $this->put(route('nursing.settings.update'), [
            'clinical_hours_per_placement' => 200,
            'clinical_hours_per_semester' => 400,
            'max_hours_per_day' => 12,
            'attendance_passing' => 80,
            'cpd_target_hours' => 40,
            'logbook_backdating_days' => 7,
            'max_upload_size' => 10240,
        ]);
        $response->assertRedirect();
    }

    public function test_24_admin_can_view_reports()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('nursing.reports.index'));
        $response->assertStatus(200);

        $response = $this->get(route('nursing.reports.clinical-hours'));
        $response->assertStatus(200);

        $response = $this->get(route('nursing.reports.skills-competency'));
        $response->assertStatus(200);

        $response = $this->get(route('nursing.reports.attendance'));
        $response->assertStatus(200);
    }

    public function test_25_student_can_access_study_center()
    {
        $this->actingAs($this->student);

        $response = $this->get(route('nursing.study.index'));
        $response->assertStatus(200);

        $response = $this->get(route('nursing.study.flashcards'));
        $response->assertStatus(200);

        $response = $this->get(route('nursing.study.quizzes'));
        $response->assertStatus(200);
    }

    public function test_26_student_can_access_study_assistant()
    {
        $this->actingAs($this->student);

        $response = $this->get(route('nursing.study-assistant.index'));
        $response->assertStatus(200);
    }

    public function test_27_student_can_access_instructor_portal()
    {
        $this->actingAs($this->nursingInstructor);

        $response = $this->get(route('nursing.instructor.dashboard'));
        $response->assertStatus(200);

        $response = $this->get(route('nursing.instructor.students'));
        $response->assertStatus(200);
    }

    // ======================================================================
    // SECTION 3: CROSS-MODULE INTEGRATION
    // ======================================================================

    public function test_28_dashboard_loads_for_all_roles()
    {
        $this->actingAs($this->admin);
        $response = $this->get(route('nursing.dashboard'));
        $response->assertStatus(200);

        $this->actingAs($this->student);
        $response = $this->get(route('nursing.dashboard'));
        $response->assertStatus(200);

        $this->actingAs($this->teacher);
        $response = $this->get(route('examination.dashboard'));
        $response->assertStatus(200);
    }

    public function test_29_module_management_works()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.modules.index'));
        $response->assertStatus(200);

        $response = $this->post(route('admin.modules.toggle'), ['module' => 'Nursing']);
        $response->assertRedirect();

        // Toggle back
        $response = $this->post(route('admin.modules.toggle'), ['module' => 'Nursing']);
        $response->assertRedirect();
    }

    public function test_30_api_routes_work()
    {
        $this->actingAs($this->student);

        $response = $this->getJson(route('api.nursing.dashboard'));
        $response->assertStatus(200);

        $response = $this->getJson(route('api.nursing.skills'));
        $response->assertStatus(200);

        $response = $this->getJson(route('api.nursing.placements'));
        $response->assertStatus(200);
    }
}
