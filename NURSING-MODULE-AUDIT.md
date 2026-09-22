# Nursing Module - Codebase Audit Report

**Date:** 2026-09-22
**System:** Dunco School Management System
**Framework:** Laravel 12 + Inertia.js + Vue 3 + TypeScript + Tailwind CSS

---

## 1. EXISTING FUNCTIONALITY THAT CAN BE REUSED

| Infrastructure | Status | Location | Reuse Strategy |
|---|---|---|---|
| **Student Model** | EXISTS | `Modules/Academic/Models/Student.php` → `academic_students` | Direct reuse via FK relationships |
| **Staff Model** | EXISTS | `Modules/HR/Models/Staff.php` → `staff` | Direct reuse for clinical instructors |
| **User Model** | EXISTS | `app/Models/User.php` → `users` | Auth, roles, permissions |
| **Role/Permission System** | EXISTS | Custom RBAC with school scoping | Add nursing-specific roles/permissions |
| **Department Model** | EXISTS | `Modules/HR/Models/Department.php` → `departments` | Extend for clinical departments |
| **Attendance System** | EXISTS | `Modules/Academic/Models/AttendanceRecord.php` → `academic_attendance_records` | Extend with clinical attendance type |
| **Exam/Assessment System** | EXISTS | `Modules/Examination/` (Exam, ExamResult, Question, etc.) | Extend for nursing assessments |
| **Notification System** | EXISTS | `Modules/Notification/` + `Modules/Communication/` | Use for clinical notifications |
| **Document/File Upload** | EXISTS | `Modules/Document/Models/Document.php` → `documents` | Use for certificates, evidence uploads |
| **Audit Log** | EXISTS | `app/Models/AuditLog.php` → `audit_logs` | Use for clinical audit trail |
| **Inertia.js + Vue 3** | EXISTS | `resources/js/` | Build nursing UI with Vue 3 + TypeScript |
| **Blade Layouts** | EXISTS | `resources/views/layouts/app.blade.php` | Extend for nursing module layout |
| **Existing `nurse` role** | EXISTS | `database/seeders/CoreSeeder.php` line 90 | Already seeded |
| **Payment/Invoice System** | EXISTS | `Modules/Finance/` | For CPD fee tracking if needed |
| **Library System** | EXISTS | `Modules/Library/` | Reference for module structure pattern |

---

## 2. EXISTING TABLES THAT CAN BE EXTENDED

| Table | Current Use | Extension for Nursing |
|---|---|---|
| `academic_students` | Student records | Add `nursing_program`, `clinical_year` fields |
| `staff` | Staff records | Add `is_clinical_instructor`, `instructor_credentials` |
| `departments` | HR departments | Add `type` value for clinical departments |
| `academic_attendance_records` | Academic attendance | Add `type` = 'clinical' for clinical attendance |
| `users` | User accounts | No changes needed |
| `roles` / `permissions` | RBAC | Add nursing-specific roles and permissions |
| `documents` | File uploads | Use for clinical evidence, certificates |
| `audit_logs` | Audit trail | Use for clinical audit events |
| `academic_exams` | Academic exams | Extend for nursing clinical assessments |

---

## 3. EXISTING MODELS THAT CAN BE REUSED

| Model | Namespace | Table | Nursing Use |
|---|---|---|---|
| `Student` | `Modules\Academic\Models\Student` | `academic_students` | Clinical students |
| `Staff` | `Modules\HR\Models\Staff` | `staff` | Clinical instructors |
| `User` | `App\Models\User` | `users` | Authentication |
| `Department` | `Modules\HR\Models\Department` | `departments` | Clinical departments |
| `AttendanceRecord` | `Modules\Academic\Models\AttendanceRecord` | `academic_attendance_records` | Clinical attendance |
| `Document` | `Modules\Document\Models\Document` | `documents` | Evidence uploads |
| `AuditLog` | `App\Models\AuditLog` | `audit_logs` | Clinical audit |
| `Role` | `App\Models\Role` | `roles` | Nursing roles |
| `Permission` | `App\Models\Permission` | `permissions` | Nursing permissions |

---

## 4. EXISTING COMPONENTS THAT CAN BE REUSED

| Component | Location | Nursing Use |
|---|---|---|
| `AuthenticatedLayout.vue` | `resources/js/Layouts/AuthenticatedLayout.vue` | Main layout wrapper |
| `Modal.vue` | `resources/js/Components/Modal.vue` | Dialog modals |
| `TextInput.vue` | `resources/js/Components/TextInput.vue` | Form inputs |
| `InputLabel.vue` | `resources/js/Components/InputLabel.vue` | Form labels |
| `InputError.vue` | `resources/js/Components/InputError.vue` | Validation errors |
| `PrimaryButton.vue` | `resources/js/Components/PrimaryButton.vue` | Action buttons |
| `SecondaryButton.vue` | `resources/js/Components/SecondaryButton.vue` | Secondary actions |
| `DangerButton.vue` | `resources/js/Components/DangerButton.vue` | Danger actions |
| `Checkbox.vue` | `resources/js/Components/Checkbox.vue` | Toggle inputs |
| `Dropdown.vue` | `resources/js/Components/Dropdown.vue` | Dropdown menus |
| `NavLink.vue` | `resources/js/Components/NavLink.vue` | Navigation links |
| `Blade Sidebar` | `resources/views/components/sidebar.blade.php` | Add nursing menu items |
| `Blade Navbar` | `resources/views/components/navbar.blade.php` | Top navigation |

---

## 5. EXISTING PERMISSIONS

The system has 200+ permissions across 18 modules. For nursing, we need to add:

### New Permissions to Create

```
nursing.dashboard.view
nursing.placements.view
nursing.placements.create
nursing.placements.edit
nursing.placements.delete
nursing.logbooks.view
nursing.logbooks.create
nursing.logbooks.review
nursing.logbooks.approve
nursing.skills.view
nursing.skills.manage
nursing.skills.assess
nursing.competency.award
nursing.attendance.view
nursing.attendance.mark
nursing.hours.view
nursing.hours.approve
nursing.instructors.manage
nursing.reference.view
nursing.reference.create
nursing.reference.approve
nursing.scenarios.view
nursing.scenarios.create
nursing.assessments.view
nursing.assessments.create
nursing.assessments.grade
nursing.cpd.view
nursing.cpd.manage
nursing.reports.view
nursing.reports.export
nursing.settings.manage
nursing.calculators.view
nursing.study.view
nursing.study.manage
```

---

## 6. EXISTING ATTENDANCE INFRASTRUCTURE

| Component | Table | Notes |
|---|---|---|
| Student Attendance | `academic_attendance_records` | Has `school_id`, `student_id`, `class_id`, `date`, `status`, `remarks`, `marked_by` |
| Staff Attendance | `staff_attendance_records` | Separate table for staff |
| Attendance Settings | `attendance_settings` | Configurable settings |
| Attendance Sessions | `attendance_sessions` | Session management |
| QR/Face/Biometric | Various log tables | Advanced attendance methods |

**Strategy:** Do NOT create a new attendance engine. Extend `academic_attendance_records` with a `type` field (academic/clinical) or create a thin `clinical_attendance` table that references the same student model.

---

## 7. EXISTING ASSESSMENT INFRASTRUCTURE

| Component | Table | Notes |
|---|---|---|
| Academic Exams | `academic_exams` | Name, type, dates, marks |
| Exam Results | `academic_exam_results` | Student scores per exam |
| Examination Module | `exams`, `exam_attempts`, `exam_answers` | Online/proctored exams |
| Questions | `academic_questions`, `questions` | Question banks |
| Grading | `grades`, `grading_scales` | Grade definitions |

**Strategy:** Reuse the examination module for nursing theory assessments. Create a new `clinical_assessments` table for practical/clinical skill assessments (which have different fields than academic exams).

---

## 8. EXISTING NOTIFICATION INFRASTRUCTURE

| Component | Notes |
|---|---|
| Laravel Notifications | Standard `notifications` table |
| Notification Templates | `notification_templates` with variable placeholders |
| Notification Logs | `notification_logs` for tracking |
| User Preferences | `users.notification_preferences` JSON |
| Communication Module | Messages, broadcasts, announcements |

**Strategy:** Create nursing-specific notification templates. Use existing `Notification` class pattern. Create a `NursingNotificationService` to handle clinical-specific notifications.

---

## 9. POTENTIAL CONFLICTS

| Conflict | Description | Mitigation |
|---|---|---|
| Dual permission systems | Custom HasPermissions + Spatie-style tables | Stick with the custom system used by policies |
| Dual assessment systems | Academic module vs Examination module | Use Examination module for theory, new table for clinical |
| No AcademicYear model | `academic_year` is a plain string | Reference as string in nursing tables |
| No Semester model | `term` is an enum on exams | Reference as string in nursing tables |
| CSS inconsistency | Tailwind (Vue) vs Bootstrap (Blade) | Use Tailwind for nursing Vue pages |
| Module providers disabled | Service providers not auto-registered | Routes/views still work via auto-discovery |

---

## 10. REQUIRED MIGRATIONS

### New Tables (18 tables)

```
1.  nursing_facilities          - Clinical facilities (hospitals, health centres)
2.  nursing_facility_departments - Departments within facilities
3.  nursing_wards               - Wards within departments
4.  nursing_placements          - Student placement records
5.  nursing_logbooks            - Logbook entries
6.  nursing_logbook_corrections - Correction audit trail for logbooks
7.  nursing_skills              - Skill library
8.  nursing_skill_categories    - Skill categories
9.  nursing_student_skills      - Student skill progress tracking
10. nursing_skill_assessments   - Instructor skill assessments
11. nursing_skill_rubrics       - Assessment rubric criteria
12. nursing_clinical_hours      - Clinical hours tracking
13. nursing_reference_articles  - Reference center articles
14. nursing_reference_categories - Reference categories
15. nursing_scenarios           - Clinical case scenarios
16. nursing_scenario_questions  - Scenario questions
17. nursing_scenario_attempts   - Student scenario attempts
18. nursing_cpd_activities      - CPD activities
19. nursing_settings            - Module configuration
20. nursing_audit_logs          - Clinical audit trail
```

### No Changes Needed to Existing Tables

The existing `academic_students`, `staff`, `users`, `departments`, `roles`, `permissions` tables are sufficient. We reference them via foreign keys.

---

## 11. REQUIRED NEW MODELS

| Model | Table | Namespace |
|---|---|---|
| `Facility` | `nursing_facilities` | `Modules\Nursing\Models\Facility` |
| `FacilityDepartment` | `nursing_facility_departments` | `Modules\Nursing\Models\FacilityDepartment` |
| `Ward` | `nursing_wards` | `Modules\Nursing\Models\Ward` |
| `Placement` | `nursing_placements` | `Modules\Nursing\Models\Placement` |
| `LogbookEntry` | `nursing_logbooks` | `Modules\Nursing\Models\LogbookEntry` |
| `LogbookCorrection` | `nursing_logbook_corrections` | `Modules\Nursing\Models\LogbookCorrection` |
| `SkillCategory` | `nursing_skill_categories` | `Modules\Nursing\Models\SkillCategory` |
| `Skill` | `nursing_skills` | `Modules\Nursing\Models\Skill` |
| `StudentSkill` | `nursing_student_skills` | `Modules\Nursing\Models\StudentSkill` |
| `SkillAssessment` | `nursing_skill_assessments` | `Modules\Nursing\Models\SkillAssessment` |
| `SkillRubric` | `nursing_skill_rubrics` | `Modules\Nursing\Models\SkillRubric` |
| `ClinicalHours` | `nursing_clinical_hours` | `Modules\Nursing\Models\ClinicalHours` |
| `ReferenceCategory` | `nursing_reference_categories` | `Modules\Nursing\Models\ReferenceCategory` |
| `ReferenceArticle` | `nursing_reference_articles` | `Modules\Nursing\Models\ReferenceArticle` |
| `Scenario` | `nursing_scenarios` | `Modules\Nursing\Models\Scenario` |
| `ScenarioQuestion` | `nursing_scenario_questions` | `Modules\Nursing\Models\ScenarioQuestion` |
| `ScenarioAttempt` | `nursing_scenario_attempts` | `Modules\Nursing\Models\ScenarioAttempt` |
| `CpdActivity` | `nursing_cpd_activities` | `Modules\Nursing\Models\CpdActivity` |
| `NursingSetting` | `nursing_settings` | `Modules\Nursing\Models\NursingSetting` |
| `NursingAuditLog` | `nursing_audit_logs` | `Modules\Nursing\Models\NursingAuditLog` |

---

## 12. REQUIRED NEW PAGES

### Vue/Inertia Pages (under `Modules/Nursing/resources/js/Pages/`)

| Page | Path | Role |
|---|---|---|
| **Dashboard** | `Dashboard.vue` | Student/Instructor/Admin |
| **Placements** | | |
| - Facility List | `Placements/Facilities/Index.vue` | Admin |
| - Facility Create | `Placements/Facilities/Create.vue` | Admin |
| - Facility Edit | `Placements/Facilities/Edit.vue` | Admin |
| - Placement List | `Placements/Index.vue` | Student/Instructor/Admin |
| - Placement Create | `Placements/Create.vue` | Admin |
| - Placement Detail | `Placements/Show.vue` | Student/Instructor/Admin |
| **Logbook** | | |
| - Logbook List | `Logbook/Index.vue` | Student |
| - Logbook Create | `Logbook/Create.vue` | Student |
| - Logbook Detail | `Logbook/Show.vue` | Student/Instructor |
| - Logbook Review | `Logbook/Review.vue` | Instructor |
| **Skills** | | |
| - Skill Library | `Skills/Index.vue` | Student/Instructor |
| - Skill Detail | `Skills/Show.vue` | Student/Instructor |
| - My Progress | `Skills/MyProgress.vue` | Student |
| - Assess Student | `Skills/Assess.vue` | Instructor |
| **Clinical Hours** | `Hours/Index.vue` | Student/Instructor/Admin |
| **Attendance** | `Attendance/Index.vue` | Student/Instructor/Admin |
| **Instructor Portal** | `Instructor/Dashboard.vue` | Instructor |
| - My Students | `Instructor/Students/Index.vue` | Instructor |
| - Student Detail | `Instructor/Students/Show.vue` | Instructor |
| **Study Center** | `Study/Index.vue` | Student |
| - Flashcards | `Study/Flashcards.vue` | Student |
| - Quizzes | `Study/Quizzes.vue` | Student |
| **Skills Library** | `Reference/Skills/Index.vue` | Student/Instructor |
| - Skill Article | `Reference/Skills/Show.vue` | Student/Instructor |
| **Case Simulator** | `Simulator/Index.vue` | Student |
| - Scenario | `Simulator/Scenario.vue` | Student |
| **Assessments** | `Assessments/Index.vue` | Student/Instructor/Admin |
| **CPD** | `CPD/Index.vue` | Instructor/Admin |
| **Reference Center** | `Reference/Index.vue` | Student/Instructor |
| - Article | `Reference/Article.vue` | Student/Instructor |
| **Calculators** | `Calculators/Index.vue` | Student |
| **Study Assistant** | `StudyAssistant/Index.vue` | Student |
| **Reports** | `Reports/Index.vue` | Instructor/Admin |
| **Settings** | `Settings/Index.vue` | Admin |

---

## 13. REQUIRED APIs

### Web Routes (Inertia)

All routes under `nursing/` prefix with `auth` middleware.

### API Routes (for mobile/offline sync)

| Endpoint | Method | Purpose |
|---|---|---|
| `api/nursing/logbook` | GET/POST | List/create logbook entries |
| `api/nursing/logbook/{id}` | GET/PUT | Get/update logbook entry |
| `api/nursing/placements` | GET | List placements |
| `api/nursing/skills` | GET | List skills |
| `api/nursing/hours` | GET | Get clinical hours |
| `api/nursing/attendance` | GET/POST | List/mark attendance |

---

## 14. SECURITY CONSIDERATIONS

| Risk | Mitigation |
|---|---|
| IDOR on student data | Server-side authorization in every controller method |
| Unauthorized competency award | Only instructor+ roles can award competency |
| Logbook tampering | Approved entries locked; corrections via audit trail |
| Patient data exposure | No patient-identifiable data in logbook (use initials only) |
| Role escalation | Server-side role checks, never trust frontend |
| CSRF | Laravel CSRF protection on all web routes |
| Mass assignment | `$fillable` on all models |
| SQL injection | Eloquent ORM throughout |
| XSS | Blade `{{ }}` escaping, Vue template escaping |
| File upload | Validate file types, sizes; scan for malware |

---

## 15. IMPLEMENTATION PLAN

### Phase 1: Foundation (Core Infrastructure)
1. Create module directory structure
2. Create service providers, config, module.json
3. Create all database migrations
4. Create all Eloquent models with relationships
5. Register module in modules_statuses.json
6. Add nursing permissions to SystemPermissionsSeeder
7. Add nursing roles to CoreSeeder

### Phase 2: Backend (Controllers, Policies, Routes)
1. Create clinical facility/department/ward CRUD controllers
2. Create placement management controller
3. Create logbook controller with workflow
4. Create skills & competency controllers
5. Create clinical hours controller
6. Create clinical attendance controller
7. Create instructor portal controller
8. Create reference center controllers
9. Create scenario simulator controller
10. Create assessments controller
11. Create CPD controller
12. Create calculators controller
13. Create reports controller
14. Create settings controller
15. Create nursing notification service
16. Create nursing audit service
17. Create policies for all nursing models
18. Define all routes (web + API)

### Phase 3: Frontend (Vue/TypeScript)
1. Create nursing layout component
2. Create TypeScript type definitions
3. Create shared components (StatCard, DataTable, StatusBadge, etc.)
4. Create dashboard pages (Student, Instructor, Admin)
5. Create placement management pages
6. Create logbook pages with workflow UI
7. Create skills & competency pages
8. Create clinical hours pages
9. Create attendance pages
10. Create instructor portal pages
11. Create study center pages
12. Create reference center pages
13. Create simulator pages
14. Create assessment pages
15. Create CPD pages
16. Create calculators pages
17. Create reports pages
18. Create settings pages

### Phase 4: Integration & Testing
1. Integrate with existing sidebar navigation
2. Test all authorization rules
3. Test all CRUD operations
4. Test all workflows (logbook, competency, etc.)
5. Test mobile responsiveness
6. Test edge cases and error handling
7. Run linting and type checking
8. Performance testing (N+1 queries)

### Phase 5: Documentation
1. Create docs/nursing-module.md
2. Document all API endpoints
3. Document all permissions
4. Document all workflows
